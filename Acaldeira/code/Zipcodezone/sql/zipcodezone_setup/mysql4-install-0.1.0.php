<?php
 
$installer = $this;
 
$installer->startSetup();
try{
$installer->run("
 
-- DROP TABLE IF EXISTS zipcodezone;
CREATE TABLE zipcodezone (
  `zipcodezone_id` int(11) unsigned NOT NULL auto_increment,
  `zonename` varchar(255) NOT NULL ,
  `start` int(8) NOT NULL,
  `end` int(8) NOT NULL , 
  PRIMARY KEY (`zipcodezone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 
    ");
 
}catch (Exception $ex){
	echo $ex->getMessage();
}
$installer->endSetup();