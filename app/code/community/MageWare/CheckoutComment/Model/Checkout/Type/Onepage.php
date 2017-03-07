<?php
/**
 * See LICENSE.txt for license details.
 */

class MageWare_CheckoutComment_Model_Checkout_Type_Onepage
    extends Mage_Checkout_Model_Type_Onepage
{
    /**
     * @inheritdoc
     */
    public function saveShippingMethod($shippingMethod)
    {
        if (empty($shippingMethod)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid shipping method.'));
        }
        $rate = $this->getQuote()->getShippingAddress()->getShippingRateByCode($shippingMethod);
        if (!$rate) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid shipping method.'));
        }
        $this->getQuote()->getShippingAddress()
            ->setShippingMethod($shippingMethod);
        $this->getQuote()->collectTotals()
            ->save();

        $this->getCheckout()
            ->setStepData('shipping_method', 'complete', true);

        if (Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            $this->getCheckout()->setStepData('comment', 'allow', true);
        } else {
            $this->getCheckout()->setStepData('payment', 'allow', true);
        }

        return array();
    }

    /**
     * @param string $comment
     * @return array
     */
    public function saveComment($comment)
    {
        $this->getQuote()
            ->setComment($comment)
            ->save();

        $this->getCheckout()
            ->setStepData('comment', 'complete', true)
            ->setStepData('payment', 'allow', true);

        return array();
    }
}
