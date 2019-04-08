<?php
/*
Plugin Name: Appointment Hour Booking
Plugin URI: https://apphourbooking.dwbooster.com
Description: Appointment Hour Booking is a plugin for creating booking forms for appointments with a start time and a defined duration.
Version: 1.1.20
Author: CodePeople
Author URI: https://apphourbooking.dwbooster.com
License: GPL
Text Domain: appointment-hour-booking
*/

define('CP_APPBOOK_DEFER_SCRIPTS_LOADING', (get_option('CP_APPB_LOAD_SCRIPTS',"1") == "1"?true:false));

define('CP_APPBOOK_DEFAULT_form_structure', '[[{"form_identifier":"","name":"fieldname1","shortlabel":"","index":0,"ftype":"fapp","userhelp":"","userhelpTooltip":false,"csslayout":"","title":"Appointment","services":[{"name":"Service 1","price":1,"duration":60}],"openhours":[{"name":"Default","openhours":[{"type":"all","d":"","h1":8,"m1":0,"h2":17,"m2":0}]}],"allOH":[{"name":"Default","openhours":[{"type":"all","d":"","h1":8,"m1":0,"h2":17,"m2":0}]}],"dateFormat":"mm/dd/yy","showDropdown":false,"dropdownRange":"-10:+10","working_dates":[true,true,true,true,true,true,true],"numberOfMonths":1,"firstDay":0,"minDate":"0","maxDate":"","defaultDate":"","invalidDates":"","required":true,"fBuild":{}},{"form_identifier":"","name":"email","shortlabel":"","index":1,"ftype":"femail","userhelp":"","userhelpTooltip":false,"csslayout":"","title":"Email","predefined":"","predefinedClick":false,"required":true,"size":"medium","equalTo":"","fBuild":{}}],[{"title":"","description":"","formlayout":"top_aligned","formtemplate":"","evalequations":1,"autocomplete":1}]]');


define('CP_APPBOOK_DEFAULT_track_IP', true);

define('CP_APPBOOK_DEFAULT_fp_subject', 'Notification to administrator: Booking request received...');
define('CP_APPBOOK_DEFAULT_fp_inc_additional_info', 'true');
define('CP_APPBOOK_DEFAULT_fp_return_page', get_site_url());
define('CP_APPBOOK_DEFAULT_fp_message', "The following contact message has been sent:\n\n<%INFO%>\n\n");

define('CP_APPBOOK_DEFAULT_cu_enable_copy_to_user', 'true');
define('CP_APPBOOK_DEFAULT_cu_user_email_field', 'email');
define('CP_APPBOOK_DEFAULT_cu_subject', 'Confirmation: Your booking has been received...');
define('CP_APPBOOK_DEFAULT_cu_message', "Thank you for your message. We will reply you as soon as possible.\n\nThis is a copy of the data sent:\n\n<%INFO%>\n\nBest Regards.");
define('CP_APPBOOK_DEFAULT_email_format','text');

define('CP_APPBOOK_DEFAULT_vs_use_validation', 'true');

define('CP_APPBOOK_DEFAULT_vs_text_is_required', 'This field is required.');
define('CP_APPBOOK_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('CP_APPBOOK_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with this format(mm/dd/yyyy)');
define('CP_APPBOOK_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with this format(dd/mm/yyyy)');
define('CP_APPBOOK_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('CP_APPBOOK_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('CP_APPBOOK_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('CP_APPBOOK_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');
define('CP_APPBOOK_DEFAULT_vs_text_maxapp', 'Please select a max of  {0} appointments per customer.');

define('CP_APPBOOK_DEFAULT_cv_enable_captcha', 'true');
define('CP_APPBOOK_DEFAULT_cv_width', '180');
define('CP_APPBOOK_DEFAULT_cv_height', '60');
define('CP_APPBOOK_DEFAULT_cv_chars', '5');
define('CP_APPBOOK_DEFAULT_cv_font', 'font-1.ttf');
define('CP_APPBOOK_DEFAULT_cv_min_font_size', '25');
define('CP_APPBOOK_DEFAULT_cv_max_font_size', '35');
define('CP_APPBOOK_DEFAULT_cv_noise', '200');
define('CP_APPBOOK_DEFAULT_cv_noise_length', '4');
define('CP_APPBOOK_DEFAULT_cv_background', 'ffffff');
define('CP_APPBOOK_DEFAULT_cv_border', '000000');
define('CP_APPBOOK_DEFAULT_cv_text_enter_valid_captcha', 'Please enter a valid captcha code.');

define('CP_APPBOOK_REP_ARR', '[+arr1237]');


// loading add-ons
// -----------------------------------------
global $cpappb_addons_active_list, // List of addon IDs
	   $cpappb_addons_objs_list; // List of addon objects
	   
$cpappb_addons_active_list = array();
$cpappb_addons_objs_list	 = array();
	
function cpappb_loading_add_ons()
{
	global $cpappb_addons_active_list, // List of addon IDs
		   $cpappb_addons_objs_list; // List of addon objects
	
    // Get the list of active addons
	$cpappb_addons_active_list = get_option( 'cpappb_addons_active_list', array() );
	if( !empty( $cpappb_addons_active_list ) 
        || ( isset( $_GET["page"] ) && $_GET["page"] == "cp_apphourbooking" )  
        || ( isset( $_GET["page"] ) && $_GET["page"] == "cp_apphourbooking_addons" )
      )
	{	
		$path = dirname( __FILE__ ).'/addons';
		if( file_exists( $path ) )
		{
			$addons = dir( $path );
			while( false !== ( $entry = $addons->read() ) ) 
			{    
				if( strlen( $entry ) > 3 && strtolower( pathinfo( $entry, PATHINFO_EXTENSION) ) == 'php' )
				{
					require_once $addons->path.'/'.$entry;
				}			
			}
		} 
	}	
}
cpappb_loading_add_ons();



/* initialization / install */

include_once dirname( __FILE__ ) . '/classes/cp-base-class.inc.php';
include_once dirname( __FILE__ ) . '/cp-main-class.inc.php';


$cp_appb_plugin = new CP_AppBookingPlugin;

register_activation_hook(__FILE__, array($cp_appb_plugin,'install') ); 
add_action( 'media_buttons', array($cp_appb_plugin, 'insert_button'), 11);
add_action( 'init', array($cp_appb_plugin, 'data_management'));
add_action( 'wp_loaded', array($cp_appb_plugin, 'data_management_loaded'));



function cpappb_plugin_init() {
   load_plugin_textdomain( 'appointment-hour-booking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
   $ao_options = get_option('autoptimize_js_exclude',"seal.js, js/jquery/jquery.js");
   if (!strpos($ao_options,'stringify.js'))
      update_option('autoptimize_js_exclude',"jQuery.stringify.js,jquery.validate.js,".$ao_options);
}
add_action('init', 'cpappb_plugin_init');

//START: activation redirection 
function cpappb_activation_redirect( $plugin ) {
    if(
        $plugin == plugin_basename( __FILE__ ) &&
        (!isset($_POST["action"]) || $_POST["action"] != 'activate-selected') &&
        (!isset($_POST["action2"]) || $_POST["action2"] != 'activate-selected') 
      )
    {
        exit( wp_redirect( admin_url( 'admin.php?page=cp_apphourbooking' ) ) );
    }
}
add_action( 'activated_plugin', 'cpappb_activation_redirect' );
//END: activation redirection 

if ( is_admin() ) {  
    
    add_action('admin_enqueue_scripts', array($cp_appb_plugin,'insert_adminScripts'), 1);    
    add_filter("plugin_action_links_".plugin_basename(__FILE__), array($cp_appb_plugin,'plugin_page_links'));   
    add_action('admin_menu', array($cp_appb_plugin,'admin_menu') );
    add_action('enqueue_block_editor_assets', array($cp_appb_plugin,'gutenberg_block'));
    
    
} else {    
    add_shortcode( $cp_appb_plugin->shorttag, array($cp_appb_plugin, 'filter_content') );   
    add_shortcode( 'CP_APP_HOUR_BOOKING_LIST', array($cp_appb_plugin, 'filter_list') );    
}  

// register gutemberg block
if (function_exists('register_block_type'))
{
    register_block_type('cpapphourbk/form-rendering', array(
                        'attributes'      => array(
                                'formId'    => array(
                                    'type'      => 'string'
                                ),
                                'instanceId'    => array(
                                    'type'      => 'string'
                                ),
                            ),
                        'render_callback' => array($cp_appb_plugin, 'render_form_admin')
                    )); 
}

// banner             
$codepeople_promote_banner_plugins[ 'appointment-hour-booking' ] = array( 
                      'plugin_name' => 'Appointment Hour Booking', 
                      'plugin_url'  => 'https://wordpress.org/support/plugin/appointment-hour-booking/reviews/#new-post'
);
require_once 'banner.php';

// optional opt-in deactivation feedback
require_once 'cp-feedback.php';

// code for compatibility with third party scripts
add_filter('option_sbp_settings', 'apphourbk_sbp_fix_conflict' );
function apphourbk_sbp_fix_conflict($option)
{
    if(!is_admin())
    {
       if(is_array($option) && isset($option['jquery_to_footer'])) 
           unset($option['jquery_to_footer']);
    }
    return $option;
}

// elementor integration
include_once dirname( __FILE__ ) . '/controllers/elementor/cp-elementor-widget.inc.php';

?>