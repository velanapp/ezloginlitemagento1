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
class Velanapps_Ezloginlite_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	Function for getting Facebook OAuth file url.
	Input  : Being called, no specific input given.
	Output : Returns Facebook OAuth file url.
	*/
	public function facebookUrl()
	{
		return Mage::getBaseDir("media") . DS . "ezloginlite" . DS. "facebook" . DS;
	}
	
	/**
	Function for getting Facebook Application Id.
	Input  : Being called, no specific input given.
	Output : Returns Facebook Application Id.
	*/
	public function facebookAppId()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/appid');
	}
	
	/**
	Function for getting Facebook Application Secret key.
	Input  : Being called, no specific input given.
	Output : Returns Facebook Application Secret key.
	*/
	public function facebookAppSecret()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/appsec');
	}
	
	/**
	Function for getting social Login Icon Width.
	Input  : Being called, no specific input given.
	Output : Returns Social Login Icon Width.
	*/
	public function loginIconWidth()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/iconwidth');
	}
	
	/**
	Function for getting social Login Icon Height.
	Input  : Being called, no specific input given.
	Output : Returns Social Login Icon Height.
	*/
	public function loginIconHeight()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/iconheight');
	}
	
	/**
	Function for all social accounts call back url.
	Input  : Being called, no specific input given.
	Output : Returns Magento call back action url.
	*/
	public function callBackUrl()
	{
		return Mage::getUrl('ezloginlite/social/callBack');
	}
	
	/**
	Function for getting Magento store base url.
	Input  : Being called, no specific input given.
	Output : Returns Magento store base url.
	*/
	public function getStoreUrl()
	{
		return Mage::getBaseUrl();
	}
	
	/**
	Function for getting Magento session object.
	Input  : Being called, no specific input given.
	Output : Returns Magento core session object.
	*/
	public function getSession()
	{	
		return Mage::getSingleton('core/session');
	}
	
	/**
	Function for getting Facebook login image.
	Input  : Being called, no specific input given.
	Output : Returns Facebook login image.
	*/
	public function facebookImage()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/iconimage');
	}
	
	/**
	Function for getting social image upload url.
	Input  : Being called, no specific input given.
	Output : Returns social image uploaded url.
	*/
	public function loginImageUrl()
	{	
		return "media/ezloginlite/images/";
	}
	
	/**
	Function for Getting Powered by Enable Disable.
	Input  : Being called, no specific input given.
	Output : Returns the Powered by Enable Disable Status.
	*/
	public function poweredBy()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/poweredby');
	}
	
	/**
	Function for getting EzLogin customer table customer details.
	Input  : Social account user unique id, socail account id from ezlogin account table.  
	Output : Returns EzLogin table customer data's.
	*/
	public function getCustomer($socialUserId, $socialAccountId)
	{
		$ezLoginCustomers = Mage::getModel('ezloginlite/ezloginlitecustomers')->getCollection()
						    ->addFieldToFilter('social_id', $socialUserId)
						    ->addFieldToFilter('account_id', $socialAccountId)
						    ->getData();
		
		return $ezLoginCustomers[0];
	}
	
	/**
	Function for getting social account Id.
	Input  : Being called, no specific input given.
	Output : Returns social account Id.
	*/
	public function socialAccountId($socialAccountId)
	{
		$data = Mage::getModel('ezloginlite/ezloginliteaccounts')->getCollection()
				->AddFieldToFilter('account_name', $socialAccountId)
				->getData();
		
		$ezLoginAccountId = $data[0]['account_id'];
	
		return $ezLoginAccountId;
	}
	
	/**
	Function for getting social customers profile image validating.
	Input  : Social Account unique Id, Profile Image Url.
	Output : Returns customer profile image count.
	*/
	public function profileImage($socialAccountId, $profileImage)
	{
		$checkCustomer = Mage::getModel('ezloginlite/ezloginlitecustomers')->getCollection()
						 ->AddFieldToFilter('social_id', $socialAccountId)
						 ->AddFieldToFilter('profile_image', $profileImage)
						 ->getData();
		
		$ezLoginCustomer = count($checkCustomer);
	
		return $ezLoginCustomer;
	}
	
	/**
	Function for updating customer profile image url in ezlogin customer table.
	Input  : Social account unique id, Social Customer Profile Image Url.
	Output : No Output return.
	*/
	public function profileImageUpdate($customerId, $profileImage)
	{
		$updateCustomer = Mage::getModel('ezloginlite/ezloginlitecustomers')->load($customerId)
						  ->setProfileImage($profileImage)
						  ->save();
		
		return;
	}
	
	/**
	Function for getting logged in customer profile image.
	Input  : Magento logged in customer id, Social Account Id.
	Output : Return user profile image.
	*/
	public function getUserProfileImage($customerId, $socialAccountId)
	{
		$customerImage = Mage::getModel('ezloginlite/ezloginlitecustomers')->getCollection()
						 ->AddFieldToFilter('mage_customer_id', $customerId)
						 ->AddFieldToFilter('account_id', $socialAccountId)
						 ->getData();
		
		return $customerImage[0];
	}
	
	/**
	Function for getting Store Url Without index.php.
	Input  : Being called, no specific input given.
	Output : Returns Store Url Without index.php.
	*/
	public function storeLoginImageUrl()
	{
		$baseUrl = Mage::getBaseUrl();
		
		if(strpos($baseUrl, 'index.php') !== false)
		{
			$url = explode('index.php', $baseUrl);
		
			$storeUrl =	$url[0];
		}
		else
		{
			$storeUrl =	$baseUrl;
		}
		
		return $storeUrl;
	}
	
}