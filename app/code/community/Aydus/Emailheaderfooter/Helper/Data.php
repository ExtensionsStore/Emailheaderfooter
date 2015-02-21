<?php

/**
 * Emailheaderfooter helper
 *
 * @category    Aydus
 * @package     Aydus_Emailheaderfooter
 * @author      Aydus <davidt@aydus.com>
 */

class Aydus_Emailheaderfooter_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getDesignChange()
	{
		$websites = Mage::app()->getWebsites();
		$defaultWebsite = $websites[1];
	
		$defaultGroup = $defaultWebsite->getDefaultGroup();
		$defaultStoreId = $defaultGroup->getDefaultStoreId();
	
		$designChange = Mage::getSingleton('core/design')
		->loadChange($defaultStoreId);
	
		return $designChange;
	}
	
	public function getPackageName()
	{
		$designChange = $this->getDesignChange();
		$packageName = $designChange->getPackage();
		if (!$packageName){
			$packageName = Mage::getStoreConfig('design/package/name',1);
		}
	
		return $packageName;
	}
	
	public function getTheme()
	{
		$designChange = $this->getDesignChange();
		$theme = $designChange->getTheme();
	
		if (!$theme){
			$theme = Mage::getStoreConfig('design/theme/default',1);
		}
	
		return $theme;
	}
		
}