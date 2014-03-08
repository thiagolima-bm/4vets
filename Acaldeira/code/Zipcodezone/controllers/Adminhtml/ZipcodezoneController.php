<?php
 
class Acaldeira_Zipcodezone_Adminhtml_zipcodezoneController extends Mage_Adminhtml_Controller_Action
{
 
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('zipcodezone/tables')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }   
   
    public function indexAction() {
     
        $this->_initAction();       
        // $this->_addContent($this->getLayout()->createBlock('zipcodezone/adminhtml_zipcodezone'));
        $this->renderLayout();
    }
 
    public function editAction()
    {
        
        $zipcodezoneId     = $this->getRequest()->getParam('id');
        $zipcodezoneModel  = Mage::getModel('zipcodezone/zipcodezone')->load($zipcodezoneId);
 
        if ($zipcodezoneModel->getId() || $zipcodezoneId == 0) {
 
            Mage::register('zipcodezone_data', $zipcodezoneModel);
 
            $this->loadLayout();
            $this->_setActiveMenu('zipcodezone/items');
           
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
           
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
           
            $this->_addContent($this->getLayout()->createBlock('zipcodezone/adminhtml_Zipcodezone_edit'))
                 ->_addLeft($this->getLayout()->createBlock('zipcodezone/adminhtml_Zipcodezone_edit_tabs'));
               
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zipcodezone')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
   
    public function newAction()
    {

        $this->_forward('edit');
    }
   
    public function saveAction()
    {
        if ( $this->getRequest()->getPost() ) {
            try {
                $postData = $this->getRequest()->getPost();
                $zipcodezoneModel = Mage::getModel('zipcodezone/zipcodezone');
               
                $zipcodezoneModel->setId($this->getRequest()->getParam('id'))
                    ->setZonename($postData['zonename'])
                    ->setStart($postData['start'])
                    ->setEnd($postData['end'])
                    ->save();
               
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setzipcodezoneData(false);
 
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setzipcodezoneData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
   
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $zipcodezoneModel = Mage::getModel('zipcodezone/zipcodezone');
               
                $zipcodezoneModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                   
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('zipcodezone/adminhtml_Zipcodezone_grid')->toHtml()
        );
    }
}