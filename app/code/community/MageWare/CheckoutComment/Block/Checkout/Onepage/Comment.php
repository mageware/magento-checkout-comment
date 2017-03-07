<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Checkout_Onepage_Comment
    extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('comment', array(
            'label'   => Mage::helper('mageware_checkoutcomment')->__('Additional Comment'),
            'is_show' => $this->isShow()
        ));

        parent::_construct();
    }
}
