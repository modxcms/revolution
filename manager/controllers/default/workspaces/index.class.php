<?php
/**
 * @package modx
 * @subpackage manager.controllers
 */
/**
 * Loads the workspace manager
 *
 * @package modx
 * @subpackage manager.controllers
 */
class WorkspacesManagerController extends modManagerController {
    public $errors = array();
    /**
     * The template file for this controller
     * @var string $templateFile
     */
    public $templateFile = 'workspaces/index.tpl';
    /**
     * The ID of the default Provider
     * @var int $providerId
     */
    public $providerId = 1;
    /**
     * The name of the default Provider
     * @var string $providerName
     */
    public $providerName = 'modx.com';
    /**
     * Whether or not cURL is enabled on this server
     * @var boolean $curlEnabled
     */
    public $curlEnabled = true;
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('workspaces');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/core/modx.view.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package.browser.tree.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package.browser.panels.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/combos.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package.grid.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package.windows.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package.panels.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/package.containers.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/provider.grid.js');
        $this->addJavascript($mgrUrl.'assets/modext/workspace/workspace.panel.js');
        $this->addJavascript($mgrUrl.'assets/modext/util/lightbox.js');
        $this->addHtml("<script>
            Ext.onReady(function() {
                MODx.errors = ".$this->modx->toJSON($this->errors).";
                MODx.defaultProvider = '".$this->providerId."';MODx.provider = '".$this->providerId."';MODx.providerName = '".$this->providerName."';MODx.curlEnabled = ".(integer)$this->curlEnabled."; Ext.ux.Lightbox.register('a.lightbox');
                MODx.add('modx-page-workspace');
            });</script>");
        $this->addJavascript($mgrUrl.'assets/modext/workspace/index.js');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        /* ensure directories for Package Management are created */
        /** @var modCacheManager $cacheManager */
        $cacheManager = $this->modx->getCacheManager();
        $directoryOptions = array(
            'new_folder_permissions' => $this->modx->getOption('new_folder_permissions',null,0775),
        );
        $errors = array();

        /* create assets/ */
        $assetsPath = $this->modx->getOption('assets_path',null,MODX_ASSETS_PATH);
        if (!is_dir($assetsPath)) {
            $cacheManager->writeTree($assetsPath,$directoryOptions);
        }
        if (!is_dir($assetsPath) || !is_writable($assetsPath)) {
            $errors[] = $this->modx->lexicon('dir_err_assets',array('path' => $assetsPath));
        }
        unset($assetsPath);

        /* create assets/components/ */
        $assetsCompPath = $this->modx->getOption('assets_path',null,MODX_ASSETS_PATH).'components/';
        if (!is_dir($assetsCompPath)) {
            $cacheManager->writeTree($assetsCompPath,$directoryOptions);
        }
        if (!is_dir($assetsCompPath) || !is_writable($assetsCompPath)) {
            $errors[] = $this->modx->lexicon('dir_err_assets_comp',array('path' => $assetsCompPath));
        }
        unset($assetsCompPath);

        /* create core/components/ */
        $coreCompPath = $this->modx->getOption('core_path',null,MODX_CORE_PATH).'components/';
        if (!is_dir($coreCompPath)) {
            $cacheManager->writeTree($coreCompPath,$directoryOptions);
        }
        if (!is_dir($coreCompPath) || !is_writable($coreCompPath)) {
            $errors[] = $this->modx->lexicon('dir_err_core_comp',array('path' => $coreCompPath));
        }

        if (!function_exists('curl_init') || !in_array('curl',get_loaded_extensions())) {
            $errors[] = $this->modx->lexicon('curl_not_installed');
            $this->curlEnabled = false;
        }

        if (!empty($errors)) {
            $this->errors = $errors;
        }

        $this->getDefaultProvider();

        return true;
    }

    /**
     * Get the default Provider for Package Management
     *
     * @return modTransportProvider|void
     */
    public function getDefaultProvider() {
        $default = $this->modx->getOption('default_provider');
        $c = $this->modx->newQuery('transport.modTransportProvider');
        if ($default) {
            $c->where(array(
                'id' => $default,
            ));
        } else {
            $c->where(array(
                'name:=' => 'modxcms.com',
                'OR:name:=' => 'modx.com',
            ));
        }
        /** @var modTransportProvider $provider */
        $provider = $this->modx->getObject('transport.modTransportProvider',$c);
        if ($provider) {
            $this->providerId = $provider->get('id');
            $this->providerName = $provider->get('name');
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not find the main provider for some reason with a name of "modx.com". Did you delete it?');
        }
        return $provider;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('package_management');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return $this->templateFile;
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('workspace','namespace');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Package+Management';
    }
}
