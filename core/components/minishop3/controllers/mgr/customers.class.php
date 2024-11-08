<?php

use MODX\Revolution\modSystemSetting;

if (!class_exists('msManagerController')) {
    require_once dirname(__FILE__, 2) . '/manager.class.php';
}

class MiniShop3MgrCustomersManagerController extends msManagerController
{
    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('ms3_customers') . ' | MiniShop3';
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('minishop3:default', 'minishop3:customer', 'minishop3:manager');
    }


    /**
     *
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->ms3->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addCss($this->ms3->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/minishop3.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/misc/strftime-min-1.3.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/misc/ms3.utils.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/misc/ms3.combo.js');

        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/customers/customers.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/customers/customers.panel.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/customers/customers.grid.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/customers/customers.window.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/customers/customers.grid.addresses.js');
        $this->addJavascript($this->ms3->config['jsUrl'] . 'mgr/customers/customers.window.address.js');

        $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');

        $gridFields = array_map('trim', explode(',', $this->getOption(
            'ms3_customer_grid_fields',
            null,
            'id,first_name,last_name,email,phone',
            true
        )));
        $gridFields[] = 'actions';

        $windowFields = array_map('trim', explode(',', $this->getOption(
            'ms3_customer_window_fields',
            null,
            'id,first_name,last_name,email,phone',
            true
        )));

        $addressFields = array_map(
            'trim',
            explode(',', $this->getOption('ms3_customer_address_grid_fields', null, 'id,city,street,building', true))
        );
        $addressFields[] = 'actions';

        $config = $this->ms3->config;
        $config['customer_grid_fields'] = $gridFields;
        $config['customer_window_fields'] = $windowFields;
        $config['customer_address_grid_fields'] = $addressFields;

        $this->addHtml('
            <script>
                ms3.config = ' . json_encode($config) . ';

                MODx.perm.mssetting_list = ' . ($this->modx->hasPermission('mssetting_list') ? 1 : 0) . ';

                Ext.onReady(function() {
                    MODx.add({xtype: "ms3-panel-customers"});
                });
            </script>');

        $this->modx->invokeEvent('msOnManagerCustomCssJs', array(
            'controller' => $this,
            'page' => 'customers',
        ));
    }
}
