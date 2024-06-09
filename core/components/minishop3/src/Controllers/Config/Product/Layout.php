<?php

namespace MiniShop3\Controllers\Config\Product;

use MODX\Revolution\modX;

class Layout
{
    private $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    /**
     * @return array
     *
     * @throws
     */
    public function getLayout(): array
    {
        $dataTab = new DataTab($this->modx);


        $output = [];
        $output['product']['data']['left'] = $dataTab->getLeftColumn();
        $output['product']['data']['right'] = $dataTab->getRightColumn();

        return $output;
    }
}
