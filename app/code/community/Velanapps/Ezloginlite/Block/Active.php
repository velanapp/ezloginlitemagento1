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
class Velanapps_Ezloginlite_Block_Active extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	Function for getting Facebook account enable/disable status.
	Input  : Being called, no specific input given.
	Output : Returns Facebook Enable/Disable status.
	*/
	public function facebookActive()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/active');
	}
	
	/**
	Function for getting Facebook Login Url in Login Page.
    Input  : Being called, no specific input given.
	Output : Returns Facebook Login Url in Login Page.
	*/
	public function facebookLoginUrl()
	{	
		$ezLoginLiteHelperRewrite = Mage::helper("ezloginlite/rewrite");
		
		//Re-write Block function loading 
		$ezLoginLiteBlockRewrite = $this->getLayout()->createBlock('ezloginlite/rewrite');
			
		$facebookRewriteUrl = $ezLoginLiteBlockRewrite->facebookUrlRewrite();
			
		$checkData = $ezLoginLiteHelperRewrite->ezLoginUrlFilter($facebookRewriteUrl);
			
		if(isset($checkData['id_path']))
		{
			$facebookLoginUrl = $checkData['id_path'];		
		}
		else
		{
			$facebookLoginUrl = 'login-with-facebook';		
		}
		
		return $facebookLoginUrl;
	}
	
	/**
	Function for getting Facebook Login Image.
    Input  : Being called, no specific input given.
	Output : Returns Facebook Login Image.
	*/
	public function facebookLoginImageUrl()
	{
		$ezLoginLiteHelperData = Mage::helper("ezloginlite/data");
		
		$loginImageUrl = $ezLoginLiteHelperData->loginImageUrl(); 
		
		$customFacebookImage = $ezLoginLiteHelperData->facebookImage();
		
		if($customFacebookImage)
		{
			$facebookLoginImage = $loginImageUrl.$customFacebookImage;
		}
		else
		{
			$facebookLoginImage = $loginImageUrl.'ezlogin_facebook.png';
		}
		
		return $facebookLoginImage;
	}
	
}
