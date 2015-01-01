<?php

/**
 * Set transactional email templates
 * 
 * @category    Aydus
 * @package     Aydus_Emailheaderfooter
 * @author		Aydus <davidt@aydus.com>
 */
$installer = $this;
$installer->startSetup();
echo 'Emailheaderfooter setup started ...<br/>';

/*
 * Create transactional emails
 * @see Config xpath global/template/email
 */
//specify templates to create by code
$templatesToCreate = array(
	"customer_create_account_email_template" => array("path"=>"customer/create_account/email_template"),
	"customer_create_account_email_confirmation_template" => array("path"=>"customer/create_account/email_confirmation_template"),
	"customer_create_account_email_confirmed_template" => array("path"=>"customer/create_account/email_confirmed_template"),
	"customer_password_forgot_email_template" => array("path"=>"customer/password/forgot_email_template"),
	"customer_password_remind_email_template" => array("path"=>"customer/password/remind_email_template"),
	"customer_enterprise_customerbalance_email_template" => array("path"=>"customer/enterprise_customerbalance/email_template"),
	"sales_email_order_template" => array("path"=>"sales_email/order/template"),
	"sales_email_order_guest_template" => array("path"=>"sales_email/order/guest_template"),
	"sales_email_order_comment_template" => array("path"=>"sales_email/order_comment/template"),
	"sales_email_order_comment_guest_template" => array("path"=>"sales_email/order_comment/guest_template"),
	"sales_email_invoice_template" => array("path"=>"sales_email/invoice/template"),
	"sales_email_invoice_guest_template" => array("path"=>"sales_email/invoice/guest_template"),
	"sales_email_invoice_comment_template" => array("path"=>"sales_email/invoice_comment/template"),
	"sales_email_invoice_comment_guest_template" => array("path"=>"sales_email/invoice_comment/guest_template"),
	"sales_email_shipment_template" => array("path"=>"sales_email/shipment/template"),
	"sales_email_shipment_guest_template" => array("path"=>"sales_email/shipment/guest_template"),
	"sales_email_shipment_comment_template" => array("path"=>"sales_email/shipment_comment/template"),
	"sales_email_shipment_comment_guest_template" => array("path"=>"sales_email/shipment_comment/guest_template"),
	"sales_email_creditmemo_template" => array("path"=>"sales_email/creditmemo/template"),
	"sales_email_creditmemo_guest_template" => array("path"=>"sales_email/creditmemo/guest_template"),
	"sales_email_creditmemo_comment_template" => array("path"=>"sales_email/creditmemo_comment/template"),
	"sales_email_creditmemo_comment_guest_template" => array("path"=>"sales_email/creditmemo_comment/guest_template"),
	"sales_email_enterprise_rma_template" => array("path"=>"sales_email/enterprise_rma/template"),
	"sales_email_enterprise_rma_guest_template" => array("path"=>"sales_email/enterprise_rma/guest_template"),
	"sales_email_enterprise_rma_auth_template" => array("path"=>"sales_email/enterprise_rma_auth/template"),
	"sales_email_enterprise_rma_auth_guest_template" => array("path"=>"sales_email/enterprise_rma_auth/guest_template"),
	"sales_email_enterprise_rma_comment_template" => array("path"=>"sales_email/enterprise_rma_comment/template"),
	"sales_email_enterprise_rma_comment_guest_template" => array("path"=>"sales_email/enterprise_rma_comment/guest_template"),
	"sales_email_enterprise_rma_customer_comment_template" => array("path"=>"sales_email/enterprise_rma_customer_comment/template"),
);

//delete specified transactional emails to recreate
$emailTemplate = Mage::getModel('adminhtml/email_template');
$emailTemplates = $emailTemplate->getCollection();
if ($emailTemplates->getSize()){
	foreach ($emailTemplates as $emailTemplate){
		try {
			if (in_array($emailTemplate->getOrigTemplateCode(),array_keys($templatesToCreate))) {
				$emailTemplate->delete();
			}
		}catch(Exception $e){
			Mage::log($e->getMessage(),null,'emailheaderfooter.log');
		}
	}
}

$defaultTemplates = $emailTemplate::getDefaultTemplates();

//recreate specified transactional emails
foreach ($defaultTemplates as $templateId=>$defaultTemplate){

	try {
		if (in_array($templateId,array_keys($templatesToCreate))){
			$emailTemplate = Mage::getModel("adminhtml/email_template");
			$emailTemplate->loadDefault($templateId);
			$emailTemplate
				->setOrigTemplateCode($templateId)
				->setTemplateCode("EHF ".$defaultTemplate['label'])
				->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
				->setModifiedAt(Mage::getSingleton('core/date')->gmtDate());
			
			$templateText = $emailTemplate->getTemplateText();
			
			//template file missing
			if (!$templateText){
				continue;
			}
				
			//strip out template tags and plug in placeholders
			$matches = array();
			preg_match_all('/{{[^}]+}}/',$templateText, $matches);
									
			$matches = $matches[0];
			if (count($matches)>0){
				foreach ($matches as $i=>$match){
					$templateText = str_replace($match, "<!--INDEX-$i-->", $templateText);
				}
			}
				
			libxml_use_internal_errors(true);
			$doc = new DOMDocument();
			$doc->substituteEntities = false;
			$doc->loadHTML($templateText);
			//standard transactional email just contains body
			$bodys = $doc->getElementsByTagName("body");
			$body = $bodys->item(0);
			
			$tables = $body->getElementsByTagName("table");
			
			//if no tables, just keep going
			if (!$tables)
				continue;
			
			//get the nested table
			$table = $tables->item(1);
			if (!$table)
				continue;
			
			//get the first row, the logo
			$firstTr = $table->firstChild;
			//create the header
			$headerRow = $doc->createElement('tr');
			$headerData = $doc->createElement('td');
			$headerData->setAttribute("valign","top");
			$headerDataText = $doc->createTextNode('{{block type="emailheaderfooter/template" template="aydus/emailheaderfooter/header.phtml" }}');
			$headerData->appendChild($headerDataText);
			$headerRow->appendChild($headerData);
			$table->insertBefore($headerRow, $firstTr);
			$table->removeChild($firstTr);//remove the default logo
			//create the footer
			$footerRow = $doc->createElement('tr');
			$footerData = $doc->createElement('td');
			$footerData->setAttribute("valign","top");
			$footerDataText = $doc->createTextNode('{{block type="emailheaderfooter/template" template="aydus/emailheaderfooter/footer.phtml" }}');
			$footerData->appendChild($footerDataText);
			$footerRow->appendChild($footerData);
			$table->appendChild($footerRow);
			
			//set body as template text
			$templateText = $doc->saveHTML($body);
			//put back template tags
			if (count($matches)>0){
				foreach ($matches as $i=>$match){
					$templateText = str_replace(array("<!--INDEX-$i-->", "&lt;!--INDEX-$i--&gt;"), $match, $templateText);
				}
			}
			$emailTemplate->setTemplateText($templateText);
			$emailTemplate->setId(null);
			$emailTemplate->save();
			$templatesToCreate[$templateId]['value'] = $emailTemplate->getId();
		}
	}
	catch (Exception $e){

		Mage::log($e->getMessage(),null,'emailheaderfooter.log');
	}
}

//assign default templates to system config
$config = Mage::getModel("core/config");
foreach ($templatesToCreate as $templateId=>$templateConfig){
	$config->saveConfig($templateConfig['path'], $templateConfig['value'], 'default', 0);
}

echo 'Emailheaderfooter setup complete.';
$installer->endSetup();