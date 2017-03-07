<?php
/**
 * See LICENSE.txt for license details.
 */

require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'MultishippingController.php';

class MageWare_CheckoutComment_MultishippingController
    extends Mage_Checkout_MultishippingController
{
    public function shippingPostAction()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::shippingPostAction();
        }

        $shippingMethods = $this->getRequest()->getPost('shipping_method');
        try {
            Mage::dispatchEvent(
                'checkout_controller_multishipping_shipping_post',
                array('request'=>$this->getRequest(), 'quote'=>$this->_getCheckout()->getQuote())
            );
            $this->_getCheckout()->setShippingMethods($shippingMethods);
            $this->_getState()->setActiveStep(
                MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_COMMENT
            );
            $this->_getState()->setCompleteStep(
                MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_SHIPPING
            );
            $this->_redirect('*/*/comment');
        }
        catch (Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
            $this->_redirect('*/*/shipping');
        }
    }

    public function commentAction()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            $this->_redirect('*/*/');

            return;
        }

        if (!$this->_validateMinimumAmount()) {
            return;
        }

        if (!$this->_getState()->getCompleteStep(MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_SHIPPING)) {
            $this->_redirect('*/*/shipping');
            return $this;
        }

        $this->_getState()->setActiveStep(
            MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_COMMENT
        );

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        $this->renderLayout();
    }

    public function commentPostAction()
    {
        try {
            $this->_getCheckout()->getQuote()->setComment($this->getRequest()->getPost('comment'))->save();
            $this->_getState()->setActiveStep(
                MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_BILLING
            );
            $this->_getState()->setCompleteStep(
                MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_COMMENT
            );
            $this->_redirect('*/*/billing');
        }
        catch (Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
            $this->_redirect('*/*/comment');
        }
    }

    public function backToShippingAction()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::backToShippingAction();
        }

        $this->_getState()->setActiveStep(
            MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_SHIPPING
        );
        $this->_getState()->unsCompleteStep(
            MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_COMMENT
        );
        $this->_redirect('*/*/shipping');
    }

    public function backToCommentAction()
    {
        $this->_getState()->setActiveStep(
            MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_COMMENT
        );
        $this->_getState()->unsCompleteStep(
            MageWare_CheckoutComment_Model_Checkout_Type_Multishipping_State::STEP_BILLING
        );
        $this->_redirect('*/*/comment');
    }
}
