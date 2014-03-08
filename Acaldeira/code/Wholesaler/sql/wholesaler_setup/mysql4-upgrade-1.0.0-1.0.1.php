<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;


/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	
$installer->startSetup();


// Add ean to prduct attribute set
	$codigo = 'storecode';
	$config = array(
			'position' => 1,
			'required'=> 1,
			'label' => 'Storecode',
			'type' => 'varchar',
			'input'=>'text',
			'apply_to'=>'simple,bundle,grouped,configurable',
			'note'=>'Code of store'
	);
	
$setup->addAttribute('catalog_product', $codigo , $config);
 

$installer->endSetup();
