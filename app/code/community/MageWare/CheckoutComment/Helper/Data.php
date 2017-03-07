<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED = 'mageware_checkoutcomment/general/enabled';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED);
    }
}
