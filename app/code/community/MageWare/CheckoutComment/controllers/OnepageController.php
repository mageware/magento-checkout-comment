<?php
/**
 * See LICENSE.txt for license details.
 */

require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'OnepageController.php';

class MageWare_CheckoutComment_OnepageController
    extends Mage_Checkout_OnepageController
{
    protected $_sectionUpdateFunctions = array(
        'payment-method'  => '_getPaymentMethodsHtml',
        'shipping-method' => '_getShippingMethodsHtml',
        'comment'         => '_getCommentHtml',
        'review'          => '_getReviewHtml',
    );

    protected function _getCommentHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_comment');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    public function commentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function saveBillingAction()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::saveBillingAction();
        }

        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'comment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    $result['goto_section'] = 'shipping_method';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function saveShippingMethodAction()
    {
        if (!Mage::helper('mageware_checkoutcomment')->isEnabled()) {
            return parent::saveShippingMethodAction();
        }

        if ($this->_expireAjax()) {
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);

            if(!$result) {
                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array(
                    'request' => $this->getRequest(), 'quote' => $this->getOnepage()->getQuote()
                ));

                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

                $result['goto_section'] = 'comment';
                $result['update_section'] = array(
                    'name' => 'comment',
                    'html' => $this->_getCommentHtml()
                );
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function saveCommentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        try {
            if ($this->getRequest()->isPost()) {
                $comment = $this->getRequest()->getPost('comment');
                $result = $this->getOnepage()->saveComment($comment);

                if (!isset($result['error'])) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                }
            }
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Comment Information.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
