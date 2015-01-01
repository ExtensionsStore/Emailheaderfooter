<?php

/**
 * Set the theme for transactional emails
 *
 * @category    Aydus
 * @package     Aydus_Emailheaderfooter
 * @author		Aydus <davidt@aydus.com>
 */
class Aydus_Emailheaderfooter_Block_Template extends Mage_Core_Block_Template 
{
	/**
	 * Set the package/theme
	 */
	public function _construct()
	{
		parent::_construct();

		$packageName = $this->helper('emailheaderfooter')->getPackageName();
		
		$theme = $this->helper('emailheaderfooter')->getTheme();
		
		Mage::getDesign()->setArea('frontend')
			->setPackageName($packageName)
			->setTheme($theme);
	}
	
}