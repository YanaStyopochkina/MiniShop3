<?php

namespace MiniShop3\Model\mysql;

class msCustomerAddress extends \MiniShop3\Model\msCustomerAddress
{
    public static $metaMap = [
        'package' => 'MiniShop3\\Model',
        'version' => '3.0',
        'table' => 'ms3_customer_addresses',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' =>
            [
                'engine' => 'InnoDB',
            ],
        'fields' =>
            [
                'customer_id' => 0,
                'country' => null,
                'index' => null,
                'region' => null,
                'city' => null,
                'metro' => null,
                'street' => null,
                'building' => null,
                'entrance' => null,
                'floor' => null,
                'room' => null,
                'comment' => null,
            ],
        'fieldMeta' =>
            [
                'customer_id' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'attributes' => 'unsigned',
                        'null' => false,
                        'default' => 0,
                    ],
                'country' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'index' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'region' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'city' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'metro' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'street' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'building' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '10',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'entrance' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '10',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'floor' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '10',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'room' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '10',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'comment' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => true,
                    ],
            ],
        'indexes' =>
            [
                'customer_id' =>
                    [
                        'alias' => 'customer_id',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'customer_id' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'Customer' =>
                    [
                        'class' => 'MiniShop3\\Model\\msCustomer',
                        'local' => 'customer_id',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
