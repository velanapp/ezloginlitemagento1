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
class Velanapps_Ezloginlite_Block_Rewrite extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	Function for Facebook url re-write.
	Input  : Being called, no specific input given.
	Output : Returns Facebook url re-write method.
	*/
	public function facebookUrlRewrite()
	{
		return Mage::getStoreConfig('ezfacebook/facebook/url');
	}
	
	/**
	Function for Magento core url re-write.
	Input  : Being called, no specific input given.
	Output : Writes custom url configured in backend to Core url re-write, no return output.
	*/
	public function ezloginCoreUrlRewrite()
	{
		$ezLoginLiteHelperRewrite = Mage::helper("ezloginlite/rewrite");
		
		//Custom Facebook Url Re-write
		if($this->facebookUrlRewrite())
		{
			$facebookCustomUrl = $this->facebookUrlRewrite(); 
			
			$originalUrl 	  = 'ezloginlite/social/connect/account/facebook';	
			
			$filteredUrl = 	$ezLoginLiteHelperRewrite->ezLoginUrlFilter($facebookCustomUrl);
			
			//Checks if the newly configured custom url is already present, if not insert core url re-write table
			if(empty($filteredUrl))
			{		
				$ezLoginLiteHelperRewrite->ezLoginUrlRewrite($facebookCustomUrl, $originalUrl);
			}
		}	
		return;
	}
	
}
