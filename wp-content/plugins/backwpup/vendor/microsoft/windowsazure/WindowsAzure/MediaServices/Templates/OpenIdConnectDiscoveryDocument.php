<?php

/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5
 *
 * @category  Microsoft
 * @package   WindowsAzure\MediaServices\Models
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */

namespace WindowsAzure\MediaServices\Templates;
use WindowsAzure\Common\Internal\Validate;
use WindowsAzure\Common\Internal\Utilities;

/**
 * Represents OpenIdConnectDiscoveryDocument object used in media services
 *
 * @category  Microsoft
 * @package   WindowsAzure\MediaServices\Templates
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version   Release: 0.4.2_2016-04
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */
class OpenIdConnectDiscoveryDocument
{
    /**
     * SymmetricTokenVerificationKey keyValue
     *
     * @var string
     */
    private $_openIdDiscoveryUri;

    /**
     * Create SymmetricTokenVerificationKey
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get "SymmetricTokenVerificationKey OpenIdDiscoveryUri"
     *
     * @return string OpenIdDiscoveryUri
     */
    public function getOpenIdDiscoveryUri()
    {
        return $this->_openIdDiscoveryUri;
    }

    /**
     * Set "SymmetricTokenVerificationKey OpenIdDiscoveryUri"
     *
     * @param string $value OpenIdDiscoveryUri
     *
     * @return void
     */
    public function setOpenIdDiscoveryUri($value)
    {
        $this->_openIdDiscoveryUri = $value;
    }
}


