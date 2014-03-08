<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Orders extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ordersGrid');
        // This is the primary key of the database
        $this->setDefaultSort('increment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {

    	$orders = array();
    	$collection = Mage::getModel('sales/order_item')->getCollection();
        $collection->getSelect()->where('sku '.Bm_Cmon::decSku());
        // $collection = Mage::getResourceModel('sales/order_item_collection');
    //     $collection->getSelect()->addExpressionAttributeToSelect('sku',Bm_Cmon::decSku(),'sku');
         $collection->getSelect()->group('order_id');
         //echo sizeof($collection);

         foreach($collection as $item){
            array_push($orders, $item->getOrder()->getRealOrderId());
            
         }


        $collection = Mage::getResourceModel('sales/order_collection');
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('increment_id', array(
    		'in' => $orders,
    	));

        $collection->join(
        	array('table_alias' => 'sales/order_item'),
        	'table_alias.order_id = main_table.entity_id',
        	'SUM(CASE WHEN table_alias.sku '.Bm_Cmon::decSku().' THEN table_alias.price ELSE 0 END) as saler_value'
        );
        $collection->getSelect()->group('order_id');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('wholesaler')->__('Order #'),
            'align'     =>'left',
            //'width'     => '50px',
            'index'     => 'increment_id',
        ));
         $this->addColumn('created_at', array(
            'header'    => Mage::helper('wholesaler')->__('Created'),
            'align'     =>'left',
           // 'width'     => '50px',
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));
         $this->addColumn('status', array(
            'header'    => Mage::helper('wholesaler')->__('Status'),
            'align'     =>'left',
           // 'width'     => '50px',
            'index'     => 'status',
            'type'  => 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
         	//'renderer'  => 'Bommercatto_Wholesaler_Block_Adminhtml_Wholesaler_Renderer_Orderstatus',
        ));

           $this->addColumn('saler_value', array(
            'header'    => Mage::helper('wholesaler')->__('Total'),
            'align'     =>'left',
           // 'width'     => '50px',
            'index'     => 'saler_value',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));
 
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/adminhtml_sales_order/view', array('order_id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/gridOrders', array('_current'=>true));
    }
 
}