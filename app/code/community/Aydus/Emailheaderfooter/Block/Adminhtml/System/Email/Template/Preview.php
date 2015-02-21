<?php

/**
 * Include data from last order
 *
 * @category    Aydus
 * @package     Aydus_Emailheaderfooter
 * @author      Aydus <davidt@aydus.com>
 */

class Aydus_Emailheaderfooter_Block_Adminhtml_System_Email_Template_Preview extends Mage_Adminhtml_Block_System_Email_Template_Preview
{
    /**
     * Prepare html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var $template Mage_Core_Model_Email_Template */
        $template = Mage::getModel('core/email_template');
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            $template->load($id);
        } else {
            $template->setTemplateType($this->getRequest()->getParam('type'));
            $template->setTemplateText($this->getRequest()->getParam('text'));
            $template->setTemplateStyles($this->getRequest()->getParam('styles'));
        }

        /* @var $filter Mage_Core_Model_Input_Filter_MaliciousCode */
        $filter = Mage::getSingleton('core/input_filter_maliciousCode');

        $template->setTemplateText(
            $filter->filter($template->getTemplateText())
        );

        Varien_Profiler::start("email_template_proccessing");
        $vars = array();
        $orders = Mage::getModel('sales/order')->getCollection()
        ->setOrder('increment_id','DESC')
        ->setPageSize(1)
        ->setCurPage(1);
        $order = $orders->getFirstItem();
        $storeId = $order->getStoreId();
        $store = Mage::getSingleton('core/store')->load($storeId);
        $quote = Mage::getModel('sales/quote')->setStore($store);
        $quoteId = $order->getQuoteId();
        $quote->load($quoteId);
        $vars['order'] = $order;
        $vars['quote'] = $quote;
        $vars['name'] = trim($order->getCustomerFirstname().' '.$order->getCustomerLastname());
        
        $templateProcessed = $template->getProcessedTemplate($vars, true);

        if ($template->isPlain()) {
            $templateProcessed = "<pre>" . htmlspecialchars($templateProcessed) . "</pre>";
        }

        Varien_Profiler::stop("email_template_proccessing");

        return $templateProcessed;
    }
}
