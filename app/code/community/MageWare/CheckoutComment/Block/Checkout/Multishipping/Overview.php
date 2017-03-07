<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Checkout_Multishipping_Overview
    extends Mage_Checkout_Block_Multishipping_Overview
{
    public function getEditCommentUrl()
    {
        return $this->getUrl('*/*/backtocomment');
    }

    public function getComment()
    {
        return Mage::getSingleton('checkout/type_multishipping')->getQuote()->getComment();
    }
}
