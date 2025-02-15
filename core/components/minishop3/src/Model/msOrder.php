<?php

namespace MiniShop3\Model;

use MODX\Revolution\modSystemEvent;
use MODX\Revolution\modX;
use xPDO\Om\xPDOSimpleObject;

/**
 * Class msOrder
 *
 * @property integer $user_id
 * @property integer $customer_id
 * @property string $token
 * @property string $createdon
 * @property string $updatedon
 * @property string $num
 * @property float $cost
 * @property float $cart_cost
 * @property float $delivery_cost
 * @property float $weight
 * @property integer $status_id
 * @property integer $delivery_id
 * @property integer $payment_id
 * @property string $context
 * @property string $order_comment
 * @property array $properties
 *
 * @property msOrderAddress $Address
 * @property msOrderProduct[] $Products
 * @property msOrderLog[] $Log
 *
 * @package MiniShop3\Model
 */
class msOrder extends xPDOSimpleObject
{
    /**
     * @return bool
     */
    public function updateProducts()
    {
        $delivery_cost = $this->get('delivery_cost');
        $cart_cost = $cost = $weight = 0;

        $products = $this->getMany('Products');
        /** @var msOrderProduct $product */
        foreach ($products as $product) {
            $count = $product->get('count');
            $cart_cost += $product->get('price') * $count;
            $weight += $product->get('weight') * $count;
        }

        $this->fromArray([
            'cost' => $cart_cost + $delivery_cost,
            'cart_cost' => $cart_cost,
            'weight' => $weight,
            'update_products' => true
        ]);

        return $this->save();
    }

    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('msOnBeforeSaveOrder', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'object' => $this,
                'msOrder' => $this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent:: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('msOnSaveOrder', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'object' => $this,
                'msOrder' => $this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }

    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('msOnBeforeRemoveOrder', [
                'id' => parent::get('id'),
                'object' => $this,
                'msOrder' => $this,
                'ancestors' => $ancestors,
            ]);
        }

        $removed = parent::remove($ancestors);

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('msOnRemoveOrder', [
                'id' => parent::get('id'),
                'object' => $this,
                'msOrder' => $this,
                'ancestors' => $ancestors,
            ]);
        }

        return $removed;
    }
}
