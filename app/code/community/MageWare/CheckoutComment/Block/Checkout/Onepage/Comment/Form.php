<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Checkout_Onepage_Comment_Form
    extends Mage_Core_Block_Template
{
    public function getComment()
    {
        return Mage::getSingleton('checkout/session')->getQuote()->getComment();
    }
}
