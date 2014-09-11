<?php
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$this->getTable('webhooks_application')}`;
CREATE TABLE `{$this->getTable('webhooks_application')}` (
  `application_id` int(11) unsigned NOT NULL auto_increment,
  `domain` text NOT NULL,
  `description` text NOT NULL,
  `title` text NOT NULL,
  `logo` text NOT NULL,
  `key` text NOT NULL,
  `scripts` text NOT NULL,
  `events` text NOT NULL,
  `enable` Bool NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();

?>
