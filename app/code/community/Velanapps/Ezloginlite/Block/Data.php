<?php
/**
 * Velan Info Services India Pvt Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.velanapps.com/License.txt
 *
  /***************************************
 *         MAGENTO EDITION USAGE NOTICE *
 * *************************************** */
/* This package designed for Magento COMMUNITY edition
 * Velan Info Services does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Velan Info Services does not provide extension support in case of
 * incorrect edition usage.
  /***************************************
 *         DISCLAIMER   *
 * *************************************** */
/* Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future.
 * ****************************************************
 * @category   Velanapps
 * @package    EzLogin Lite
 * @author     Velan Team
 * @copyright  Copyright (c) 2012 - 2013 Velan Info Services India Pvt Ltd. (http://www.velanapps.com)
 * @license    http://store.velanapps.com/License.txt
 */
class Velanapps_Ezloginlite_Block_Data extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	Function for Loading Facebook login API.
	Input  : Being called, no specific input given.
	Output : Prompts user with Facebook login window and returns logged in user details to call back action.
	*/
	public function facebookLogin()
	{
		$ezLoginLiteHelper = Mage::helper("ezloginlite/data");
			
		//Include facebook OAuth files
		require_once $ezLoginLiteHelper->facebookUrl().'facebook.php'; 
			
		//Get facebook App id , App secret, Callback Url
		$appId 		 = $ezLoginLiteHelper->facebookAppId();
		$appSecret   = $ezLoginLiteHelper->facebookAppSecret();
		$callbackurl = $ezLoginLiteHelper->callBackUrl();
			
		//Get facebook login response code
		$code = $this->getRequest()->getParam('code');
			
		//If code is empty, then user has not logged into Facebook, and we are showing the prompt
		if(empty($code)) 
		{
			$dialogUrl = 'https://www.facebook.com/dialog/oauth?client_id='.$appId.'&redirect_uri='.urlencode($callbackurl).'&scope=email&display=popup';
			
			echo("<script>top.location.href='".$dialogUrl."'</script>");
		}
					
		//Get user access_token
		$tokenUrl = 'https://graph.facebook.com/oauth/access_token?client_id='.$appId.'&redirect_uri='.urlencode($callbackurl).'&client_secret='.$appSecret.'&code='.$code;
	
		//Facebook Access Token
		$accessToken = file_get_contents($tokenUrl);
			
		//Get logged in user details
		$fqlQuery = 'https://graph.facebook.com/v2.4/me?fields=id,email,gender,first_name,last_name&'.$accessToken;
		$fqlQueryResult = file_get_contents($fqlQuery);
		$me = json_decode($fqlQueryResult, true);
		
		return $me;
	}
}