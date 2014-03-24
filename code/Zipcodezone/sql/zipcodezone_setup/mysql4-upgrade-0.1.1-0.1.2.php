<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 

ALTER TABLE  `wholesaler` ADD  `zipcodezone` VARCHAR( 100 ) NOT NULL;
 

");
 
$installer->endSetup();

//FOREIGN KEY fk_admin_user (`user_id`) REFERENCES admin_user(`user_id`)