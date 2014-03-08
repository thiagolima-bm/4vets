<?php
 
$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 

CREATE TABLE IF NOT EXISTS `wholesaler` (
  `wholesaler_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `servicos` text NOT NULL,
  `mp` tinyint(4) DEFAULT '0',
  `aviso_recebimento` tinyint(4) DEFAULT '0',
  `valor_declarado` tinyint(4) DEFAULT '0',
  `taxa_postagem` float DEFAULT NULL,
  `prazo_extra` int(2) DEFAULT NULL,
  `cep` int(8) NOT NULL,
  `codigo_correios` varchar(40) DEFAULT NULL,
  `senha_correios` varchar(40) DEFAULT NULL,
  `storecode` varchar(5) NOT NULL,
  `sitemap` varchar(2000) NOT NULL,
  `free_shipping` varchar(5) DEFAULT NULL,
  `min_value_free_shipping` varchar(5) DEFAULT NULL,
  `fee` float DEFAULT NULL,
  PRIMARY KEY (`wholesaler_id`),
  KEY `fk_admin_user` (`user_id`)
);
 

CREATE TABLE IF NOT EXISTS `wholesaler_company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `wholesaler_id` int(11) unsigned NOT NULL,
  `email` varchar(100) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `cnpj` varchar(100) NOT NULL,
  `inscription_municipal` varchar(255) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `tel` varchar(40) NOT NULL,
  `tel_alternative` varchar(45) DEFAULT NULL,
  `cpf` varchar(15) NOT NULL,
  `rg` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `district` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(2) NOT NULL,
  `corporate_name` varchar(100) DEFAULT NULL,
  `cep` varchar(15) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `wholesaler_id` (`wholesaler_id`),
  UNIQUE KEY `cnpj` (`cnpj`),
  UNIQUE KEY `cnpj_2` (`cnpj`),
  KEY `fk_admin_wholesaler_id_company` (`wholesaler_id`)
);

CREATE TABLE IF NOT EXISTS `wholesaler_ship` (
  `wholesaler_ship_id` int(11) NOT NULL AUTO_INCREMENT,
  `wholesaler_id` int(11) NOT NULL,
  `method` varchar(120) NOT NULL,
  `weight` float NOT NULL,
  `order_id` int(11) NOT NULL,
  `value` float NOT NULL,
  PRIMARY KEY (`wholesaler_ship_id`),
  KEY `fk_wholesaler` (`wholesaler_id`)
);
");
 
$installer->endSetup();

//FOREIGN KEY fk_admin_user (`user_id`) REFERENCES admin_user(`user_id`)