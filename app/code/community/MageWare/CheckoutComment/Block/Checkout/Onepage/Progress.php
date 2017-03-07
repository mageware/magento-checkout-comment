<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Checkout_Onepage_Progress
    extends Mage_Checkout_Block_Onepage_Progress
{
    protected function _getStepCodes()
    {
        return array('login', 'billing', 'shipping', 'shipping_method', 'comment', 'payment', 'review');
    }
}
