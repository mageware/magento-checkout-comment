<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Checkout_Multishipping_Billing
    extends Mage_Checkout_Block_Multishipping_Billing
{
    public function getBackUrl()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::getBackUrl();
        }

        return $this->getUrl('*/*/backtocomment');
    }
}
