<?php

namespace MiniShop3\Processors\Config;

use MODX\Revolution\Processors\Processor;

class Read extends Processor
{
    /**
     * @return array|string
     *
     * @throws
     */
    public function process()
    {
        $file = MODX_CORE_PATH . 'components/minishop3/config/mgr/settings/vendor/window.json';
        if (!file_exists($file)) {
            $this->modx->log(1, 'file not found: ' . $file);
            return $this->success();
        }

        $fileData = file_get_contents($file);
        if (empty($fileData)) {
            return $this->success();
        }
        $fileJSON = json_decode($fileData, true);
        if (!is_array($fileJSON)) {
            $this->modx->log(1, 'not array');
            return $this->success();
        }

        $createLayout = $this->getCreateLayout($fileJSON);
        $updateLayout = $this->getUpdateLayout($fileJSON);

        return $this->success('', compact('createLayout', 'updateLayout'));
    }

    private function getCreateLayout($fileJSON)
    {
        $idPrefix = 'ms3-window-vendor-create';
        $layout = [];

        foreach ($fileJSON as $row) {
            if (isset($row['name'])) {
                if (empty($row['id'])) {
                    $row['id'] = $idPrefix . '-' . $row['name'];
                }

                if (empty($row['fieldLabel'])) {
                    $row['fieldLabel'] = $this->modx->lexicon('ms3_' . $row['name']);
                }

                if (empty($row['anchor'])) {
                    $row['anchor'] = '99%';
                }
            }

            if (isset($row['layout']) && !empty($row['items'])) {
                foreach ($row['items'] as $key => $item) {
                    if (empty($item['items'])) {
                        continue;
                    }

                    foreach ($item['items'] as $k => $item_2) {
                        if (empty($item_2['id'])) {
                            $row['items'][$key]['items'][$k]['id'] = $idPrefix . '-' . $item_2['name'];
                        }

                        if (empty($item_2['fieldLabel'])) {
                            $row['items'][$key]['items'][$k]['fieldLabel'] = $this->modx->lexicon(
                                'ms3_' . $item_2['name']
                            );
                        }

                        if (empty($item_2['anchor'])) {
                            $row['items'][$key]['items'][$k]['anchor'] = '99%';
                        }
                    }
                }
            }

            $layout[] = $row;
        }

        return $layout;
    }

    private function getUpdateLayout($fileJSON)
    {
        $idPrefix = 'ms3-window-vendor-update';
        $layout = [];

        $layout[] = [
            'name' => 'id',
            'xtype' => 'hidden'
        ];

        foreach ($fileJSON as $row) {
            if (isset($row['name'])) {
                if (empty($row['id'])) {
                    $row['id'] = $idPrefix . '-' . $row['name'];
                }

                if (empty($row['fieldLabel'])) {
                    $row['fieldLabel'] = $this->modx->lexicon('ms3_' . $row['name']);
                }

                if (empty($row['anchor'])) {
                    $row['anchor'] = '99%';
                }
            }

            if (isset($row['layout']) && !empty($row['items'])) {
                foreach ($row['items'] as $key => $item) {
                    if (empty($item['items'])) {
                        continue;
                    }

                    foreach ($item['items'] as $k => $item_2) {
                        if (empty($item_2['id'])) {
                            $row['items'][$key]['items'][$k]['id'] = $idPrefix . '-' . $item_2['name'];
                        }

                        if (empty($item_2['fieldLabel'])) {
                            $row['items'][$key]['items'][$k]['fieldLabel'] = $this->modx->lexicon(
                                'ms3_' . $item_2['name']
                            );
                        }

                        if (empty($item_2['anchor'])) {
                            $row['items'][$key]['items'][$k]['anchor'] = '99%';
                        }
                    }
                }
            }

            $layout[] = $row;
        }

        return $layout;
    }
}
