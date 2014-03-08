<?php
 
class Acaldeira_Zipcodezone_Block_Adminhtml_Zipcodezone_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('zipcodezoneGrid');
        // This is the primary key of the database
        $this->setDefaultSort('zipcodezone_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('zipcodezone/zipcodezone')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('zipcodezone_id', array(
            'header'    => Mage::helper('zipcodezone')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'zipcodezone_id',
        ));
 
        $this->addColumn('zonename', array(
            'header'    => Mage::helper('zipcodezone')->__('Zone Name'),
            'align'     =>'left',
            'index'     => 'zonename',
        ));

       
        $this->addColumn('start', array(
            'header'    => Mage::helper('zipcodezone')->__('Start'),
            'align'     =>'left',
            'index'     => 'start',
        ));


        $this->addColumn('end', array(
            'header'    => Mage::helper('zipcodezone')->__('End'),
            'align'     =>'left',
            'index'     => 'end',
        ));
 
        /*
        $this->addColumn('content', array(
            'header'    => Mage::helper('zipcodezone')->__('Item Content'),
            'width'     => '150px',
            'index'     => 'content',
        ));
        */
 
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
}