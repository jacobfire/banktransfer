<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @package Hello\Bank\Setup
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
        $locationType = $installer->getConnection()->newTable(
            $installer->getTable('hello_bank_requisites')
        )->addColumn(
            'row_id',
            Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Settings ID'
        )->addColumn(
            'hello_account_number',
            Table::TYPE_TEXT,
            255,
            ['unsigned' => true],
            'Account Number'
        )->addColumn(
            'hello_account_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Account Name'
        )->addColumn(
            'bank_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Bank Name'
        )->addColumn(
            'payee_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Payee Name'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Store Id'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Is Active'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Update Time'
        )->addIndex(
            $installer->getIdxName(
                'hello_bank_requisites',
                ['store_id', 'is_active'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['store_id', 'is_active'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
        )->setComment('Bank Requisites');

        $installer->getConnection()->createTable($locationType);

        $installer->endSetup();
    }
}
