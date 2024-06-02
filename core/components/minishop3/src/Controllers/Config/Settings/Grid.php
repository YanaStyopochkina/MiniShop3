<?php

namespace MiniShop3\Controllers\Config\Settings;

use MODX\Revolution\modX;
use MiniShop3\Controllers\Config\Settings\Vendor\Grid as VendorGrid;

class Grid
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
    public function getSettings(): array
    {
        $vendorGrid = new VendorGrid($this->modx);

        $output = [];
        $output['vendor']['grid']['columns'] = $vendorGrid->getColumns();
        $output['vendor']['grid']['fields'] = $vendorGrid->getFields();

        return $output;
    }
}
