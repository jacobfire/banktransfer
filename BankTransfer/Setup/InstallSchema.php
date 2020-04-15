<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Installs tables for bank transfer notifications
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\Setup\InstallSchemaInterface::install()
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create Location Type table
         */
        $notificationTable = $installer->getConnection()->newTable(
            $installer->getTable('hello_banktransfer_notification')
        )->addColumn(
            'row_id',
            Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true],
            'Order ID'
        )->addColumn(
            'email_reminder_sent',
            Table::TYPE_SMALLINT,
            1,
            [],
            'Email Notification Flag'
        )->addIndex(
            $installer->getIdxName(
                'hello_banktransfer_notification',
                ['order_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['order_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        )->addIndex(
            $installer->getIdxName(
                'email_reminder_sent',
                ['order_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['email_reminder_sent'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        )->addForeignKey(
            $installer->getFkName(
                'banktransfer_notification',
                'order_id',
                'sales_order',
                'entity_id'
            ),
            'order_id',
            $installer->getTable('sales_order'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment('Bank Transfer Notification');

        $installer->getConnection()->createTable($notificationTable);

        $installer->endSetup();
    }
}
