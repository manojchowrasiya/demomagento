<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table group_discount(
id int not null auto_increment,
discount decimal(12,4),
group_id int(11),
primary key(id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 