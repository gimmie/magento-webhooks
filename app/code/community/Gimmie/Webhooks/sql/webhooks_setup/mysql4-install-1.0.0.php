<?php
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$this->getTable('webhooks')}`;
CREATE TABLE `{$this->getTable('webhooks')}` (
    `webhook_id` int(11) unsigned NOT NULL auto_increment,
    `url`        text NOT NULL,
    `config`     text NOT NULL,
    PRIMARY KEY (`webhook_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();

?>
