<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Block_Sales_Order_Info
    extends Mage_Sales_Block_Order_Info
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('mageware/checkoutcomment/sales/order/info.phtml');
    }
}
