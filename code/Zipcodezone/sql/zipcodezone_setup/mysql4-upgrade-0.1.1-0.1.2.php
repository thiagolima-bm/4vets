<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 

ALTER TABLE  `wholesaler` ADD  `zipcodezone` INT( 10 ) NOT NULL;
 

");
 
$installer->endSetup();

//FOREIGN KEY fk_admin_user (`user_id`) REFERENCES admin_user(`user_id`)