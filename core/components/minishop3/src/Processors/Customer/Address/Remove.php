<?php

namespace MiniShop3\Processors\Customer\Address;

use MiniShop3\Model\msCustomerAddress;
use MODX\Revolution\Processors\Model\RemoveProcessor;

class Remove extends RemoveProcessor
{
    public $classKey = msCustomerAddress::class;
    public $languageTopics = ['minishop3:default'];
    public $permission = 'msorder_remove';

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }
}
