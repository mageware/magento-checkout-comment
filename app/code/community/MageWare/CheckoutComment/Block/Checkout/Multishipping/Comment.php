<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Checkout_Multishipping_Comment
    extends Mage_Core_Block_Template
{
    public function getPostActionUrl()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::getPostActionUrl();
        }

        return $this->getUrl('*/*/commentPost');
    }

    public function getBackUrl()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::getBackUrl();
        }

        return $this->getUrl('*/*/backtoshipping');
    }

    public function getComment()
    {
        return Mage::getSingleton('checkout/type_multishipping')->getQuote()->getComment();
    }
}
