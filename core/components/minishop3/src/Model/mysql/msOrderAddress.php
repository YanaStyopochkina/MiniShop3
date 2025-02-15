<?php

namespace MiniShop3\Model\mysql;

class msOrderAddress extends \MiniShop3\Model\msOrderAddress
{
    public static $metaMap = [
        'package' => 'MiniShop3\\Model',
        'version' => '3.0',
        'table' => 'ms3_order_addresses',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' =>
            [
                'engine' => 'InnoDB',
            ],
        'fields' =>
            [
                'order_id' => null,
                'createdon' => null,
                'updatedon' => null,
                'first_name' => null,
                'last_name' => null,
                'phone' => null,
                'email' => null,
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
                'text_address' => null,
                'properties' => null,
            ],
        'fieldMeta' =>
            [
                'order_id' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                    ],
                'createdon' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => true,
                    ],
                'updatedon' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => true,
                    ],
                'first_name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'last_name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'phone' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'email' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => true,
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
                'text_address' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'properties' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'json',
                        'null' => true,
                    ],
            ],
        'indexes' =>
            [
                'order_id' =>
                    [
                        'alias' => 'order_id',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'order_id' =>
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
                'Order' =>
                    [
                        'class' => 'MiniShop3\\Model\\msOrder',
                        'local' => 'order_id',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
