<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;


/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	
$installer->startSetup();


// Add ean to prduct attribute set
	$codigo = 'psku';
	$config = array(
			'position' => 1,
			'required'=> 1,
			'label' => 'Parent Sku',
			'type' => 'varchar',
			'input'=>'text',
			'apply_to'=>'simple,bundle,grouped,configurable',
			'note'=>'Parent Relationship'
	);
	
$setup->addAttribute('catalog_product', $codigo , $config);
 

$installer->endSetup();
