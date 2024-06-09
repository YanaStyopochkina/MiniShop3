<?php

namespace MiniShop3\Controllers\Config\Product;

use MODX\Revolution\modX;

class DataTab
{
    private $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    /**
     * @return array
     */
    public function getLeftColumn(): array
    {
        $file = MODX_CORE_PATH . 'components/minishop3/config/mgr/product/data-tab-left.json';
        $fileJSON = $this->getConfigFromJSON($file);
        return $this->buildColumn($fileJSON);
    }

    /**
     * @return array
     */
    public function getRightColumn(): array
    {
        $file = MODX_CORE_PATH . 'components/minishop3/config/mgr/product/data-tab-right.json';
        $fileJSON = $this->getConfigFromJSON($file);
        return $this->buildColumn($fileJSON);
    }

    protected function getConfigFromJSON($file): array
    {
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

    private function buildColumn($data): array
    {
        $output = [];
        foreach ($data as $row) {
            if (empty($row['name'])) {
                $this->modx->log(1, 'not found field name in row: ' . print_r($row, 1));
                continue;
            }
            if (empty($row['xtype'])) {
                $row['xtype'] = 'textfield';
            }

            if (empty($row['fieldLabel'])) {
                $row['fieldLabel'] = $this->modx->lexicon('ms3_product_' . $row['name']);
            } else {
                $row['fieldLabel'] = $this->modx->lexicon($row['fieldLabel']);
            }

            if (empty($row['anchor'])) {
                $row['anchor'] = '100%';
            }

            if (empty($row['description'])) {
                $row['description'] = $this->modx->lexicon(
                    '<b>[[+article]]</b><br />' . $this->modx->lexicon('ms3_product_' . $row['name'] . '_help')
                );
            }

            if ($row['xtype'] == 'numberfield') {
                if (!isset($row['decimalPrecision'])) {
                    $row['decimalPrecision'] = 2;
                }
            }

            if ($row['xtype'] == 'xcheckbox') {
                $row['hideLabel'] = true;
                $row['boxLabel'] = $row['fieldLabel'];
                $row['checked'] = $row['value'] ? 1 : 0;
            }

            if ($row['allowBlank'] === false) {
                $row['fieldLabel'] = $row['fieldLabel'] . ' <span class="required red">*</span>';
            }

            if ($row['name'] === 'tags') {
               // $row['value'] = 'config.record.tags';
            }

            $output[] = $row;
        }
        return $output;
    }
}
