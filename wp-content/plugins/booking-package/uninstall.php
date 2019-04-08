<?php
	// if uninstall.php is not called by WordPress, die
	if(!defined('WP_UNINSTALL_PLUGIN')){
		die;
	}
	
	define("BOOKING_PACKAGE_EXTENSION_URL", "https://saasproject.net/api/1.6/");
	
	class BOOKING_PACKAGE_UNINSTALL {
		
		public $prefix = 'booking_package_';
		
		public $pluginName = 'booking-package';
		
		public function __construct() {
			
			require_once(plugin_dir_path( __FILE__ ).'lib/Setting.php');
			require_once(plugin_dir_path( __FILE__ ).'lib/Database.php');
			
			global $wpdb;
			if(function_exists('get_sites') && class_exists('WP_Site_Query')){
				
			    $sites = get_sites();
			    foreach($sites as $site){
			    	
			    	switch_to_blog($site->blog_id);
			    	global $wpdb;
			    	$this->delete();
			    	
			    }
			    
			    restore_current_blog();
			    
			}else{
				
				$this->delete();
				
			}
			
		}
		
		public function delete(){
			
			$prefix = $this->prefix;
			$pluginName = $this->pluginName;
			
			$memberRoleName = $prefix.'member';
			remove_role($memberRoleName);
			
			$setting = new setting($prefix, $pluginName);
			$list = $setting->getList();
			foreach($list as $key => $value){
				
				$deleteList = $value;
				foreach($deleteList as $key => $value){
					
					delete_option($key);
					
				}
				
			}
			
			$list = $setting->booking_sync;
			foreach($list as $key => $value){
				
				$deleteList = $value;
				foreach($deleteList as $key => $value){
					
					delete_option($key);
					
				}
				
			}
			
			$database = new Database($prefix, null);
			$database->uninstall(true);
			
			delete_option('booking_package_version');
			delete_option('booking_package_activation_id');
			delete_option('booking_package_db_version');
			delete_option('booking_package_active');
			delete_option('booking_package_id');
			delete_option('booking_package_path');
			delete_option('booking_package_script_path');
			delete_option('booking_package_home_path');
			delete_option('widget_booking_package_widget');
			
			// for site options in Multisite
			delete_site_option($option_name);
			
			$subscriptions = $setting->upgradePlan("get");
			$isExtensionsValid = $setting->extensionFunction(false);
			$isExtensionsValid = false;
			if ($isExtensionsValid == true) {
				
				$params = array('customer_id' => $subscriptions["customer_id_for_subscriptions"], 'subscriptions_id' => $subscriptions["id_for_subscriptions"]);
		        $response = null;
		        
		        $ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, BOOKING_PACKAGE_EXTENSION_URL."cancelSubscription/");
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
				curl_setopt($ch, CURLOPT_POST, 1);
				
				ob_start();
				$response = curl_exec($ch);
				$response = ob_get_contents();
				ob_end_clean();
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close ($ch);
				$setting->upgradePlan("delete");
				
			} else {
				
				$setting->upgradePlan("delete");
				
			}
			
		}
		
	}
	
	$uninstall = new BOOKING_PACKAGE_UNINSTALL();
	
?>