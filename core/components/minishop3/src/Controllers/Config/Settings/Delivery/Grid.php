<?php

namespace MiniShop3\Controllers\Config\Settings\Delivery;

use MODX\Revolution\modX;

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
    public function getColumns(): array
    {
        $fileJSON = $this->getConfigFromJSON();
        return $this->buildColumns($fileJSON);
    }

    public function getFields(): array
    {
        $fileJSON = $this->getConfigFromJSON();

        $output = [];
        foreach ($fileJSON as $row) {
            $output[] = $row['dataIndex'];
        }
        return $output;
    }

    protected function getConfigFromJSON()
    {
        $file = MODX_CORE_PATH . 'components/minishop3/config/mgr/settings/delivery/grid.json';
        if (!file_exists($file)) {
            $this->modx->log(1, 'file not found: ' . $file);
            return [];
        }

        $fileData = file_get_contents($file);
        if (empty($fileData)) {
            return [];
        }
        $fileJSON = json_decode($fileData, true);
        if (!is_array($fileJSON)) {
            $this->modx->log(1, 'not array');
            return [];
        }

        return $fileJSON;
    }

    private function buildColumns($data): array
    {
        $output = [];
        foreach ($data as $row) {
            if (empty($row['header'])) {
                $row['header'] = $this->modx->lexicon('ms3_' . $row['dataIndex']);
            } else {
                $row['header'] = $this->modx->lexicon($row['header']);
            }
            if (!isset($row['sortable'])) {
                $row['sortable'] = false;
            }
            if (!isset($row['width'])) {
                $row['width'] = 100;
            }
            $output[] = $row;
        }
        return $output;
    }
}
