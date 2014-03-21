<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;


/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
	
$installer->startSetup();

/* create the new attribute */
$setup->addAttribute('customer', 'zipcodezone', array(
		'type' => 'text',				/* input type */
		'label' => 'Zip Code Zone',	/* Label for the user to read */
		'input' => 'text',				/* input type */
		'visible' => TRUE,				/* users can see it */
		'required' => TRUE,			/* is it required, self-explanatory */
		'default_value' => 'default',	/* default value */
		'adminhtml_only' => '1',			/* use in admin html only */
));



// Add ean to prduct attribute set
	$codigo = 'zone';
	$config = array(
			'position' => 1,
			'required'=> 1,
			'label' => 'Zone',
			'type' => 'int',
			'input'=>'multiselect',
			'apply_to'=>'simple,bundle,grouped,configurable',
			'note'=>'Produc Zone'
	);
	
$setup->addAttribute('catalog_product', $codigo , $config);
/* save the setup */


$installer->endSetup();
