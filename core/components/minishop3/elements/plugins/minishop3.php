<?php

/** @var \MODX\Revolution\modX $modx */

switch ($modx->event->name) {
    case 'OnMODXInit':
        // Load extensions
        /** @var \MiniShop3\MiniShop3 $ms3 */
        $ms3 = $modx->services->get('ms3');
        if ($ms3) {
            $ms3->loadMap();
        }
        break;

    case 'OnHandleRequest':
        // Handle ajax requests
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if (empty($_REQUEST['ms3_action']) || !$isAjax) {
            return;
        }
        /** @var \MiniShop3\MiniShop3 $ms3 */
        $ms3 = $modx->services->get('ms3');
        if ($ms3) {
            $response = $ms3->handleRequest($_REQUEST['ms3_action'], @$_POST);
            if (is_array($response)) {
                $response = json_encode($response, JSON_UNESCAPED_UNICODE);
            }
            echo $response;
            exit();
        }
        break;

    case 'OnManagerPageBeforeRender':
        /** @var \MiniShop3\MiniShop3 $ms3 */
        if ($ms3 = $modx->services->get('ms3')) {
            $modx->controller->addLexiconTopic('minishop3:default');
            $modx->regClientStartupScript($ms3->config['jsUrl'] . 'mgr/misc/ms3.manager.js');
        }
        break;

    case 'OnLoadWebDocument':
        /** @var \MiniShop3\MiniShop3 $ms3 */
        $ms3 = $modx->services->get('ms3');
        if ($ms3) {
            $ms3->initialize();
            $ms3->registerFrontend();
        }
        // Handle non-ajax requests
        if (!empty($_REQUEST['ms3_action'])) {
            if ($ms3) {
                $ms3->handleRequest($_REQUEST['ms3_action'], @$_POST);
            }
        }
        // Set product fields as [[*resource]] tags
        if ($modx->resource->get('class_key') == MiniShop3\Model\msProduct::class) {
            if ($dataMeta = $modx->getFieldMeta(MiniShop3\Model\msProductData::class)) {
                unset($dataMeta['id']);
                $modx->resource->_fieldMeta = array_merge(
                    $modx->resource->_fieldMeta,
                    $dataMeta
                );
            }
        }
        break;
}
