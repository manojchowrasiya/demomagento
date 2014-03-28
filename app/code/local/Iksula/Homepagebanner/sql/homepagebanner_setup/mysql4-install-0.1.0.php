<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table IF NOT EXISTS homepagebanner (
id int not null auto_increment,
name varchar(100),
image varchar(4000),
sortorder int(10),
url varchar(2000),
primary key(id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 