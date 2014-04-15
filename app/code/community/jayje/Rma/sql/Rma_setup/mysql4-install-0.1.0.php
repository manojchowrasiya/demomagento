<?php
/**
 * Jayje_Rma extension
 *  
 * @category   	Return Merchant Authorization Magento - wakensys
 * @package	Jayje_Rma
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 * @category	Jayje
 * @package	Jayje_Rma
 * @author        wakensys
 * @developper   s.ratheepan@gmail.com
 */


$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('rma')};
CREATE TABLE {$this->getTable('rma')} (
  `rma_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `customer_id` varchar(20) NOT NULL,
  `order_id` int(20) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `tracking_no` varchar(30) NOT NULL,
  `return_type` varchar(20) NOT NULL,
  `package` varchar(11) NOT NULL,
  `increment_id` int(30) NOT NULL,
  `adminstatus` varchar(30) NOT NULL,
  PRIMARY KEY (`rma_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

CREATE TABLE IF NOT EXISTS `rma_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rmaid` int(11) NOT NULL,
  `stype` varchar(30) NOT NULL,
  `comments` text NOT NULL,
  `date` datetime NOT NULL,
  `by` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `rma_products` (
  `rpid` int(11) NOT NULL AUTO_INCREMENT,
  `rmaid` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`rpid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('rma/rstatus')}` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `label` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `code` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2350 ;

INSERT INTO `rstatus` (`entity_id`, `type`, `label`, `status`, `code`, `created_at`, `updated_at`) VALUES
(2341, 'rma_returntype', 'Refund', 1, 'Refund', '2013-01-09 00:00:00', '2013-01-04 13:08:33'),
(2342, 'rma_status', 'Approved', 1, 'Approved', '2013-01-04 08:54:47', '2013-01-04 13:09:51'),
(2343, 'rma_returntype', 'Return', 1, 'return', '2013-01-04 08:59:27', '2013-01-07 06:49:31'),
(2344, 'rma_status', 'Failed', 1, 'failed', '2013-01-04 09:15:28', '2013-01-04 12:59:18'),
(2345, 'rma_status', 'Cancel', 1, 'Cancel', NULL, '2013-01-04 13:11:04'),
(2346, 'rma_status', 'Pending', 1, 'Pending', '2013-01-04 13:01:35', '2013-01-04 13:01:35'),
(2347, 'rma_status', 'Processing', 1, 'Processing', '2013-01-04 13:02:11', '2013-01-04 13:02:11'),
(2348, 'rma_returntype', 'Repair', 1, 'Repair', '2013-01-04 13:09:03', '2013-01-07 07:35:37'),
(2349, 'rma_returntype', 'Replacement', 1, 'Replacement', '2013-01-07 10:31:48', '2013-01-07 10:31:48');

    ");


$installer->endSetup(); 