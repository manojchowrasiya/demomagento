<?php

$installer = $this;

$installer->startSetup();

$installer->run("

alter table {$this->getTable('giftvoucher')} add column `gift_cardno` varchar (256)   NULL  after 
`gift_code`, add column `gift_pincode` varchar (256)   NULL  after `gift_cardno` , ADD COLUMN `activated_on` DATETIME NULL AFTER `expired_at` ,  ADD COLUMN `activated_cid` INT(11) NULL AFTER `activated_on`,     ADD COLUMN `activated_email` VARCHAR(256) NULL AFTER `activated_cid`,     ADD COLUMN `activated_name` VARCHAR(256) NULL AFTER `activated_email`;

");

$installer->endSetup(); 
