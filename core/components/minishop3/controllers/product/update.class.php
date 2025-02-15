<?php

use MiniShop3\Model\msProduct;
use MiniShop3\Model\msProductData;
use MiniShop3\Controllers\Config\Product\Layout;

if (!class_exists('msResourceUpdateController')) {
    require_once dirname(__FILE__, 2) . '/resource_update.class.php';
}

class msProductUpdateManagerController extends msResourceUpdateController
{
    /** @var msProduct $resource */
    public $resource;

    /**
     * Returns language topics
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['resource', 'minishop3:default', 'minishop3:product', 'minishop3:manager'];
    }

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('edit_document');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs()
    {
        $layoutController = new Layout($this->modx);
        $layout = $layoutController->getLayout();

        $mgrUrl = $this->getOption('manager_url', null, MODX_MANAGER_URL);
        $assetsUrl = $this->ms3->config['assetsUrl'];

        $this->addCss($assetsUrl . 'css/mgr/bootstrap.buttons.css');
        $this->addCss($assetsUrl . 'css/mgr/main.css');
        $this->addJavascript($mgrUrl . 'assets/modext/util/datetime.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl . 'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl . 'assets/modext/sections/resource/update.js');
        $this->addJavascript($assetsUrl . 'js/mgr/minishop3.js');
        $this->addJavascript($assetsUrl . 'js/mgr/misc/sortable/sortable.min.js');
        $this->addJavascript($assetsUrl . 'js/mgr/misc/ms3.combo.js');
        $this->addJavascript($assetsUrl . 'js/mgr/misc/strftime-min-1.3.js');
        $this->addJavascript($assetsUrl . 'js/mgr/misc/ms3.utils.js');
        $this->addJavascript($assetsUrl . 'js/mgr/misc/default.grid.js');
        $this->addJavascript($assetsUrl . 'js/mgr/misc/default.window.js');
        $this->addLastJavascript($assetsUrl . 'js/mgr/product/category.tree.js');
        $this->addLastJavascript($assetsUrl . 'js/mgr/product/links.grid.js');
        $this->addLastJavascript($assetsUrl . 'js/mgr/product/links.window.js');
        $this->addLastJavascript($assetsUrl . 'js/mgr/product/product.common.js');
        $this->addLastJavascript($assetsUrl . 'js/mgr/product/update.js');

        $show_gallery = $this->getOption('ms3_product_tab_gallery', null, true);
        if ($show_gallery) {
            $this->addLastJavascript($assetsUrl . 'js/mgr/misc/plupload/plupload.full.min.js');
            $this->addLastJavascript($assetsUrl . 'js/mgr/misc/plupload/i18n.js');
            $this->addLastJavascript($assetsUrl . 'js/mgr/misc/ext.ddview.js');
            $this->addLastJavascript($assetsUrl . 'js/mgr/product/gallery/gallery.panel.js');
            $this->addLastJavascript($assetsUrl . 'js/mgr/product/gallery/gallery.toolbar.js');
            $this->addLastJavascript($assetsUrl . 'js/mgr/product/gallery/gallery.view.js');
            $this->addLastJavascript($assetsUrl . 'js/mgr/product/gallery/gallery.window.js');
        }

        // Customizable product fields feature
        $product_fields = array_merge($this->resource->getAllFieldsNames(), ['syncsite']);
        $product_data_fields = $this->resource->getDataFieldsNames();

        if (!$product_main_fields = $this->getOption('ms3_product_main_fields')) {
            $product_main_fields = 'pagetitle,longtitle,introtext,content,publishedon,pub_date,unpub_date,template,
                parent,alias,menutitle,searchable,cacheable,richtext,uri_override,uri,hidemenu,show_in_tree';
        }
        $product_main_fields = array_map('trim', explode(',', $product_main_fields));
        $product_main_fields = array_values(array_intersect($product_main_fields, $product_fields));

        if (!$product_extra_fields = $this->getOption('ms3_product_extra_fields')) {
            $product_extra_fields = 'price,old_price,article,weight,color,size,vendor,made_in,tags,new,popular,favorite';
        }
        $product_extra_fields = array_map('trim', explode(',', $product_extra_fields));
        $product_extra_fields = array_values(array_intersect($product_extra_fields, $product_fields));

        $product_option_keys = $this->resource->loadData()->getOptionKeys();
        $product_option_fields = $this->resource->loadData()->getOptionFields();

        $this->prepareFields();

        $neighborhood = [];
        if ($this->resource instanceof msProduct) {
            $neighborhood = $this->resource->getNeighborhood();
        }

        $config = [
            'assets_url' => $this->ms3->config['assetsUrl'],
            'connector_url' => $this->ms3->config['connectorUrl'],
            'show_gallery' => $show_gallery,
            'show_extra' => (bool)$this->getOption('ms3_product_tab_extra', null, true),
            'show_options' => (bool)$this->getOption('ms3_product_tab_options', null, true),
            'show_links' => (bool)$this->getOption('ms3_product_tab_links', null, true),
            'show_categories' => (bool)$this->getOption('ms3_product_tab_categories', null, true),
            'default_thumb' => $this->ms3->config['defaultThumb'],
            'main_fields' => $product_main_fields,
            'extra_fields' => $product_extra_fields,
            'option_keys' => $product_option_keys,
            'option_fields' => $product_option_fields,
            'data_fields' => $product_data_fields,
            'additional_fields' => [],
            'media_source' => $this->getSourceProperties(),
            'isHideContent' => $this->isHideContent(),
        ];

        $ready = [
            'xtype' => 'ms3-page-product-update',
            'resource' => $this->resource->get('id'),
            'record' => $this->resourceArray,
            'publish_document' => $this->canPublish,
            'preview_url' => $this->previewUrl,
            'locked' => $this->locked,
            'lockedText' => $this->lockedText,
            'canSave' => $this->canSave,
            'canEdit' => $this->canEdit,
            'canCreate' => $this->canCreate,
            'canDuplicate' => $this->canDuplicate,
            'canDelete' => $this->canDelete,
            'canPublish' => $this->canPublish,
            'show_tvs' => !empty($this->tvCounts),
            'next_page' => !empty($neighborhood['right'][0])
                ? $neighborhood['right'][0]
                : 0,
            'prev_page' => !empty($neighborhood['left'][0])
                ? $neighborhood['left'][0]
                : 0,
            'up_page' => $this->resource->parent,
            'mode' => 'update',
        ];

        $this->addHtml('
        <script>
        // <![CDATA[
        MODx.config.publish_document = "' . $this->canPublish . '";
        MODx.onDocFormRender = "' . $this->onDocFormRender . '";
        MODx.ctx = "' . $this->ctx . '";
        ms3.config = ' . json_encode($config) . ';
        ms3.config.layout = ' . json_encode($layout) . ';
        Ext.onReady(function() {
            MODx.load(' . json_encode($ready) . ');
        });
        MODx.perm.tree_show_resource_ids = ' . ($this->modx->hasPermission('tree_show_resource_ids') ? 1 : 0) . ';
        // ]]>
        </script>');

        // load RTE
        $this->loadRichTextEditor();
        $this->modx->invokeEvent('msOnManagerCustomCssJs', ['controller' => $this, 'page' => 'product_update']);
        $this->loadPlugins();
    }

    /**
     * Additional preparation of the resource fields
     */
    public function prepareFields()
    {
        $data = array_keys($this->modx->getFieldMeta(msProductData::class));
        foreach ($this->resourceArray as $k => $v) {
            if (is_array($v) && in_array($k, $data)) {
                $tmp = $this->resourceArray[$k];
                $this->resourceArray[$k] = [];
                foreach ($tmp as $v2) {
                    if (!empty($v2)) {
                        $this->resourceArray[$k][] = ['value' => $v2];
                    }
                }
            }
        }

        if (empty($this->resourceArray['vendor'])) {
            $this->resourceArray['vendor'] = '';
        }
    }

    /**
     * Loads additional scripts for product form from MiniShop3 plugins
     */
    public function loadPlugins()
    {
//        $plugins = $this->ms3->plugins->load();
//        foreach ($plugins as $plugin) {
//            if (!empty($plugin['manager']['msProductData'])) {
//                $this->addJavascript($plugin['manager']['msProductData']);
//            }
//        }
    }

    /**
     * Loads media source properties
     *
     * @return array
     */
    public function getSourceProperties()
    {
        $properties = [];
        /** @var $source modMediaSource */
        if ($source = $this->resource->initializeMediaSource()) {
            $tmp = $source->getProperties();
            $properties = [];
            foreach ($tmp as $v) {
                $properties[$v['name']] = $v['value'];
            }
        }

        return $properties;
    }
}
