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
class Velanapps_Ezloginlite_SocialController extends Mage_Core_Controller_Front_Action
{
	/**
	Function for All Social Account Authtication.
	Input  : Provider Name(facebook, twitter, etc..)
	Output : Connecting respective socail login page. 
	*/
    public function connectAction()
    {
        $socailAccount = $this->getRequest()->getParam('account');
			
		switch($socailAccount)
		{
			case 'facebook':
				$this->facebook();
			break;
		}
	}
	
	/**
	Function for Facebook Login.
	Input  : Being called, no specific input given.
	Output : User connected and redirected to facebook login page.
	*/
	public function facebook()
	{	
		// fetching block function for facebook login
		$facebookObject = $this->getLayout()->createBlock('ezloginlite/data');
		
		try
		{	
			//Loadin Facebook API from Block function
			$facebookData  = $facebookObject->facebookLogin();	
		}
		catch(Exception $error)
		{
			Mage::getSingleton('core/session')->addError($error->getMessage());
			return false;
		}
		
		return $facebookData;
	}
	
	/**
	Function for All Socail Account's Call Back Action.
	Input  : Social account user response data.
	Output : Creates Magento user and redirects to user dashboard.
	*/
	public function callBackAction()
	{     
		$ezLoginLiteHelper = Mage::helper("ezloginlite/data");
			
		$session = Mage::getSingleton('core/session'); 
		
		//Error Handling for User Reject the Application.
		try
		{
			//Facebook provider
			$facebookError = $this->getRequest()->getParam('error');
			
			if($facebookError)
			{
				throw new Exception('Dear Customer! Allow this application to complete the login process', 0, $error);
			}
		}
		catch(Exception $error)
		{
			$session->addError($error->getMessage());
			echo('<script>window.opener.location="'.Mage::helper('customer')->getDashboardUrl().'";close();</script>');
			return;
		}
		
		//Facebook provider
		$facebookProvider = $this->getRequest()->getParam('code');
			
		//If the call back is from Facebook
		if($facebookProvider)
		{	
			try
			{
				//Get facebook user details
				$socialUser = $this->facebook(); 
			} 
			catch(Exception $error)
			{
				$session->addError($error->getMessage());
				return false;
			}
				
			$socialUserId    = $socialUser['id'];
			$socialFirstName = $socialUser['first_name'];
			$socialLastName  = $socialUser['last_name'];
			$socialGender    = $socialUser['gender'];
			$socialEmail     = $socialUser['email'];
			
			$socialAccountName = 'Facebook';
			$isVerified = '1';
			
			//Facebook User Profile Image.
			$socialAccountImage = 'https://graph.facebook.com/'.$socialUserId.'/picture';
			$socialAccountId = $ezLoginLiteHelper->socialAccountId($socialAccountName); 
		}
					
		if($socialAccountId)
		{  	
			//Get the customer session
			$customerSession = Mage::getSingleton('customer/session');
			
			//Checking ezlogin lite customer table to checking data's
		    $ezLoginLiteCustomerData = $ezLoginLiteHelper->getCustomer($socialUserId, $socialAccountId);
			
			//Set the logged in customer Social Account
			$session->setSocialAccountId($socialAccountId);
			
		    if(isset($ezLoginLiteCustomerData['mage_customer_id']))
		    {	
				//Checking ezlogin customer table for profile image changes.
				$ezLoginLiteProfileImage = $ezLoginLiteHelper->profileImage($socialUserId, $socialAccountImage);
		
				//Updating Customer Profile image, once new image is occur from API.
				if(!$ezLoginLiteProfileImage)
				{
					$ezLoginLiteHelper->profileImageUpdate($ezLoginLiteCustomerData['customer_id'], $socialAccountImage);
				}
				
				//If social account is exist, customer will login with customer id.
				$customerSession->loginById($ezLoginLiteCustomerData['mage_customer_id']);
				
		    }
			else
			{   
				//Using Exception for Email Filter and is Response empty from Social Api.
				
				if($socialEmail)
				{
					//Get the store information
					$storeId   = Mage::app()->getStore()->getStoreId();
					$websiteId = Mage::getModel('core/store')->load(Mage::app()->getStore()->getStoreId())->getWebsiteId();
								 
					//Check the Facebook email with customer table
					$customerData = Mage::getModel('customer/customer')->getCollection()
									->addFieldToFilter('email', $socialEmail)
									->addFieldToFilter('store_id', $storeId)
									->addFieldToFilter('website_id', $websiteId)
									->getData();
					
					$getCustomerData = $customerData[0];
								
					$entityId = $getCustomerData['entity_id'];
					
					if($entityId) 
					{  
						//Insert customer details with ezlogin customer table
						$data = Mage::getModel('ezloginlite/ezloginlitecustomers')
								->setMageCustomerId($entityId)
								->setAccountId($socialAccountId)
								->setSocialId($socialUserId)
								->setProfileImage($socialAccountImage)
								->setIsVerified($isVerified)							
								->save();
						
						//Customer login by Magento customer entiry id.
						$customerSession->loginById($entityId);
					} 
					else 
					{ 	
						//If login customer not present in magento , creating new magento acoount.
						$customer = Mage::getModel('customer/customer')
									->setFirstname($socialFirstName)
									->setLastname($socialLastName)
									->setEmail($socialEmail)
									->setGender(
											Mage::getResourceModel('customer/customer')
											->getAttribute('gender')
											->getSource()
											->getOptionId($socialGender)
									)
									->setIsActive(1)
									->setConfirmation(null)
									->save();

						$mageCustomer   = $customer->getData();
						$mageCustomerId = $mageCustomer['entity_id'];
								
						$ezLoginLiteData = Mage::getModel('ezloginlite/ezloginlitecustomers')
										   ->setMageCustomerId($mageCustomerId)
									       ->setAccountId($socialAccountId)
									       ->setSocialId($socialUserId)
									       ->setProfileImage($socialAccountImage)
									       ->setIsVerified($isVerified)		
									       ->save();
						
						$customerSession->setCustomerAsLoggedIn($customer);
						$customerId = $customerSession->getCustomerId();
					}	
				}
				else
				{
					$session->addError('Dear Customer! Allow this application to complete the login process');
				}
			}				
		}
		
		//Redirecting to Magento account page.
		echo('<script>window.opener.location="'.Mage::helper('customer')->getDashboardUrl().'";close();</script>');
		return;
	}
}