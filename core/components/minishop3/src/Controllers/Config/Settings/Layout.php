<?php

namespace MiniShop3\Controllers\Config\Settings;

use MODX\Revolution\modX;
use MiniShop3\Controllers\Config\Settings\Vendor\Grid as VendorGrid;
use MiniShop3\Controllers\Config\Settings\Vendor\Window as VendorWindow;

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
        $vendorGrid = new VendorGrid($this->modx);
        $vendorWindow = new VendorWindow($this->modx);

        $output = [];
        $output['vendor']['grid']['columns'] = $vendorGrid->getColumns();
        $output['vendor']['grid']['fields'] = $vendorGrid->getFields();

        $output['vendor']['window']['create'] = $vendorWindow->getCreate();
        $output['vendor']['window']['update'] = $vendorWindow->getUpdate();

        return $output;
    }
}
