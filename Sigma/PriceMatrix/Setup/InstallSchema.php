<?php

namespace Sigma\PriceMatrix\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tableName = $installer->getTable('sigma_price_matrix');

        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()->newTable(
                $tableName
            )->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Product ID'
            )->addColumn(
                'display_base_price_1',
                Table::TYPE_DECIMAL,
                '12,2',
                ['nullable' => false, 'default' => '0.00'], 
                'Display Base Price 1'
            )->addColumn(
                'display_base_price_2',
                Table::TYPE_DECIMAL,
                '12,2',
                ['nullable' => false, 'default' => '0.00'],
                'Display Base Price 2'
            )->addColumn(
                'display_base_price_3',
                Table::TYPE_DECIMAL,
                '12,2',
                ['nullable' => false, 'default' => '0.00'],
                'Display Base Price 3'
            )->addColumn(
                'display_base_price_4',
                Table::TYPE_DECIMAL,
                '12,2',
                ['nullable' => false, 'default' => '0.00'],
                'Display Base Price 4'
            )->addColumn(
                'display_base_price_5',
                Table::TYPE_DECIMAL,
                '12,2',
                ['nullable' => false, 'default' => '0.00'],
                'Display Base Price 5'
            )->addColumn(
                'display_qty_1',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Display Qty 1'
            )->addColumn(
                'display_qty_2',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Display Qty 2'
            )->addColumn(
                'display_qty_3',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Display Qty 3'
            )->addColumn(
                'display_qty_4',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Display Qty 4'
            )->addColumn(
                'display_qty_5',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Display Qty 5'
            )->setComment(
                'Sigma Price Matrix Product Table'
            );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
