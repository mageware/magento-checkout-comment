<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State
    extends Mage_Checkout_Model_Type_Multishipping_State
{
    const STEP_COMMENT = 'multishipping_comment';

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            parent::__construct();
        } else {
            $this->_steps = array(
                self::STEP_SELECT_ADDRESSES => new Varien_Object(array(
                    'label' => Mage::helper('checkout')->__('Select Addresses')
                )),
                self::STEP_SHIPPING => new Varien_Object(array(
                    'label' => Mage::helper('checkout')->__('Shipping Information')
                )),
                self::STEP_COMMENT => new Varien_Object(array(
                    'label' => Mage::helper('mageware_checkoutcomment')->__('Additional Comment')
                )),
                self::STEP_BILLING => new Varien_Object(array(
                    'label' => Mage::helper('checkout')->__('Billing Information')
                )),
                self::STEP_OVERVIEW => new Varien_Object(array(
                    'label' => Mage::helper('checkout')->__('Place Order')
                )),
                self::STEP_SUCCESS => new Varien_Object(array(
                    'label' => Mage::helper('checkout')->__('Order Success')
                )),
            );

            foreach ($this->_steps as $step) {
                $step->setIsComplete(false);
            }

            $this->_checkout = Mage::getSingleton('checkout/type_multishipping');
            $this->_steps[$this->getActiveStep()]->setIsActive(true);
        }
    }
}
