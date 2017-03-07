<?php
/**
 * See LICENSE.txt for license details.
 */

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'comment', array(
        'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Comment',
        'length'  => '64k'
    ));

$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote'), 'comment', array(
        'type'    => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Comment',
        'length'  => '64k'
    ));

$installer->endSetup();
