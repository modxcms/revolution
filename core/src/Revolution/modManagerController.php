<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;

use BrowserManagerController;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\System\Registry\Register\Read;
use MODX\Revolution\Sources\modMediaSource;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Abstract class for manager controllers. Not to be initialized directly; must be extended by the implementing
 * controller.
 *
 * @package MODX\Revolution
 */
abstract class modManagerController
{
    /** @var modX A reference to the modX object */
    public $modx;
    /** @var array A configuration array of options related to this controller's action object. */
    public $config = [];
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = true;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = true;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    public $loadBaseJavascript = true;
    /** @var array An array of possible paths to this controller's templates directory. */
    public $templatesPaths = [];
    /** @var array An array of possible paths to this controller's directory. */
    public $controllersPaths;
    /** @var modContext The current working context. */
    public $workingContext;
    /** @var modMediaSource The default media source for the user */
    public $defaultSource;
    /** @var string The current output content */
    public $content = '';
    /** @var array An array of request parameters sent to the controller */
    public $scriptProperties = [];
    /** @var array An array of css/js/html to load into the HEAD of the page */
    public $head = ['css' => [], 'js' => [], 'html' => [], 'lastjs' => []];
    /** @var array An array of placeholders that are being set to the page */
    public $placeholders = [];

    /** @var string Any Form Customization rule output that was created. */
    protected $ruleOutput = [];
    /** @var string The current manager theme. */
    protected $theme = 'default';
    /** @var string The pagetitle for this controller. */
    protected $title = '';
    /** @var bool Whether or not a failure message was sent by this controller. */
    protected $isFailure = false;
    /** @var string The failure message, if existent, for this controller. */
    protected $failureMessage = '';

    /**
     * The constructor for the modManagerController class.
     *
     * @param modX  $modx   A reference to the modX object.
     * @param array $config A configuration array of options related to this controller's action object.
     */
    public function __construct(modX $modx, $config = [])
    {
        $this->modx =& $modx;
        $this->config = !empty($config) && is_array($config) ? $config : [];
    }

    /**
     * Can be used to provide custom methods prior to processing
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Return the proper instance of the derived class. This can be used to override how the manager loads a controller
     * class; for example, when handling derivative classes with class_key settings.
     *
     * @static
     *
     * @param modX   $modx      A reference to the modX object.
     * @param string $className The name of the class that is being requested.
     * @param array  $config    A configuration array of options related to this controller's action object.
     *
     * @return modManagerController The class specified by $className
     */
    public static function getInstance(modX $modx, $className, array $config = [])
    {
        /** @var modManagerController $controller */
        $controller = new $className($modx, $config);

        return $controller;
    }

    /**
     * Sets the properties array for this controller
     *
     * @param array $properties
     * @param bool $merge Indicates if properties should be merged with existing ones
     *
     * @return void
     */
    public function setProperties(array $properties, $merge = false)
    {
        $this->scriptProperties = $merge ? array_merge($this->scriptProperties, $properties) : $properties;
    }

    /**
     * Set a property for this controller
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setProperty($key, $value)
    {
        $this->scriptProperties[$key] = $value;
    }

    /**
     * Prepares the language placeholders
     */
    public function prepareLanguage()
    {
        $this->modx->lexicon->load('action');
        $languageTopics = $this->getLanguageTopics();
        foreach ($languageTopics as $topic) {
            $this->modx->lexicon->load($topic);
        }
        $this->setPlaceholder('_lang_topics', implode(',', $languageTopics));
        $this->setPlaceholder('_lang', $this->modx->lexicon->fetch());
    }

    /**
     * Render the controller.
     */
    public function render(): string
    {
        if (!$this->checkPermissions()) {
            $this->failure($this->modx->lexicon('access_denied'));
        }

        $this->modx->invokeEvent('OnBeforeManagerPageInit', $this->config);

        $this->theme = $this->modx->getOption('manager_theme', null, 'default', true);

        $this->prepareLanguage();
        $this->setPlaceholder('_ctx', $this->modx->context->get('key'));
        $this->loadControllersPath();
        $this->loadTemplatesPath();
        $content = '';

        $this->registerBaseScripts();

        $this->checkFormCustomizationRules();

        $this->setPlaceholder('_config', $this->modx->config);
        $this->setCssURLPlaceholders();
        /* help url */
        $helpUrl = $this->getHelpUrl();
        $this->addHtml('<script>MODx.helpUrl = "' . ($helpUrl) . '"</script>');

        $this->modx->invokeEvent('OnManagerPageBeforeRender', ['controller' => &$this]);

        $placeholders = !$this->isFailure ? $this->process($this->scriptProperties) : [];
        if (!$this->isFailure && !empty($placeholders) && is_array($placeholders)) {
            $this->setPlaceholders($placeholders);
        } elseif (!empty($placeholders)) {
            $content = $placeholders;
        }
        if (!$this->isFailure) {
            $this->loadCustomCssJs();
        }
        $this->firePreRenderEvents();

        /* handle FC rules */
        if (!empty($this->ruleOutput)) {
            $this->addHtml(implode("\n", $this->ruleOutput));
        }

        /* register CSS/JS */
        $this->registerCssJs();

        $this->setPlaceholder('_pagetitle', $this->getPageTitle());

        $this->content = '';
        if ($this->loadHeader) {
            $this->content .= $this->getHeader();
        }

        $tpl = $this->getTemplateFile();
        if ($this->isFailure) {
            $this->setPlaceholder('_e', $this->modx->error->failure($this->failureMessage));
            $content = $this->fetchTemplate('error.tpl');
        } else {
            if (!empty($tpl)) {
                $content = $this->fetchTemplate($tpl);
            }
        }

        $this->content .= $content;

        if ($this->loadFooter) {
            $this->content .= $this->getFooter();
        }

        $this->firePostRenderEvents();
        $this->modx->invokeEvent('OnManagerPageAfterRender', ['controller' => &$this]);

        return $this->content;
    }

    public function getHelpUrl()
    {
        return '';
    }

    /**
     * @return void
     */
    protected function assignPlaceholders()
    {
        foreach ($this->placeholders as $k => $v) {
            $this->modx->smarty->assign($k, $v);
        }
    }

    /**
     * Set a placeholder for this controller's template
     *
     * @param string $k The key of the placeholder
     * @param mixed  $v The value of the placeholder
     *
     * @return void
     */
    public function setPlaceholder($k, $v)
    {
        $this->placeholders[$k] = $v;
        $this->modx->smarty->assign($k, $v);
    }

    /**
     * Set an array of placeholders
     *
     * @param array $keys
     *
     * @return void
     */
    public function setPlaceholders($keys)
    {
        foreach ($keys as $k => $v) {
            $this->placeholders[$k] = $v;
            $this->modx->smarty->assign($k, $v);
        }
    }

    /**
     * Get all the set placeholders
     *
     * @return array
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    /**
     * Get a specific placeholder set
     *
     * @param string $k
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getPlaceholder($k, $default = null)
    {
        return isset($this->placeholders[$k]) ? $this->placeholders[$k] : $default;
    }

    /**
     * Fetch the template content
     *
     * @param string $tpl The path to the template
     *
     * @return string The output of the template
     */
    public function fetchTemplate($tpl)
    {
        $templatePath = '';
        if (is_array($this->templatesPaths)) {
            foreach ($this->templatesPaths as $path) {
                if (file_exists($path . $tpl)) {
                    $templatePath = $path;
                    break;
                }
            }
        }
        $this->modx->smarty->setTemplatePath($templatePath);

        return $this->modx->smarty->fetch($tpl);
    }

    /**
     * Load another manual controller file (such as header/footer)
     *
     * @param      $controller
     * @param bool $coreOnly
     *
     * @return mixed|string
     */
    public function loadController($controller, $coreOnly = false)
    {
        /** @var modX $modx */
        $modx =& $this->modx;
        $paths = $this->getControllersPaths($coreOnly);
        $o = '';
        foreach ($paths as $path) {
            if (file_exists($path . $controller)) {
                $o = include_once $path . $controller;
                break;
            }
        }

        return $o;
    }

    /**
     * Set a failure on this controller. This will return the error message.
     *
     * @param string $message
     *
     * @return void
     */
    public function failure($message)
    {
        $this->isFailure = true;
        $this->failureMessage .= $message;
    }

    /**
     * Load the path to this controller's template's directory. Only override this if you want to override default
     * behavior; otherwise, overriding getTemplatesPath is preferred.
     *
     * @return string
     */
    public function loadTemplatesPath()
    {
        if (empty($this->templatesPaths)) {
            $templatesPaths = $this->getTemplatesPaths();
            if (is_string($templatesPaths)) {
                $templatesPaths = [$templatesPaths];
            }
            $this->setTemplatePaths($templatesPaths);
        }

        return $this->templatesPaths;
    }

    /**
     * Set the possible template paths for this controller
     *
     * @param array $paths
     *
     * @return void
     */
    public function setTemplatePaths(array $paths)
    {
        $this->templatesPaths = $paths;
    }

    /**
     * Load an array of possible paths to this controller's directory. Only override this if you want to override
     * default behavior; otherwise, overriding getControllersPath is preferred.
     *
     * @return array
     */
    public function loadControllersPath()
    {
        if (empty($this->controllersPaths)) {
            $this->controllersPaths = $this->getControllersPaths();
        }

        return $this->controllersPaths;
    }


    /**
     * Get the path to this controller's directory. Override this to point to a custom directory.
     *
     * @param bool $coreOnly Ensure that it grabs the path from the core namespace only.
     *
     * @return array
     */
    public function getControllersPaths($coreOnly = false)
    {
        if (!empty($this->config['namespace']) && $this->config['namespace'] != 'core' && !$coreOnly) { /* for non-core controllers */
            $managerPath = $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH);
            $paths[] = $this->config['namespace_path'] . 'controllers/' . $this->theme . '/';
            $paths[] = $this->config['namespace_path'] . 'controllers/default/';
            $paths[] = $this->config['namespace_path'] . 'controllers/';
            $paths[] = $this->config['namespace_path'] . $this->theme . '/';
            $paths[] = $this->config['namespace_path'] . 'default/';
            $paths[] = $this->config['namespace_path'];
            $paths[] = $managerPath . 'controllers/' . $this->theme . '/';
            $paths[] = $managerPath . 'controllers/default/';

        } else { /* for core controllers only */
            $managerPath = $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH);
            $paths[] = $managerPath . 'controllers/' . $this->theme . '/';
            $paths[] = $managerPath . 'controllers/default/';
        }

        return $paths;
    }

    /**
     * Get an array of possible paths to this controller's template's directory.
     * Override this to point to a custom directory.
     *
     * @param bool $coreOnly Ensure that it grabs the path from the core namespace only.
     *
     * @return array|string
     */
    public function getTemplatesPaths($coreOnly = false)
    {
        /* extras */
        if (!empty($this->config['namespace']) && $this->config['namespace'] != 'core' && !$coreOnly) {
            $namespacePath = $this->config['namespace_path'];
            $paths[] = $namespacePath . 'templates/' . $this->theme . '/';
            $paths[] = $namespacePath . 'templates/default/';
            $paths[] = $namespacePath . 'templates/';
        }
        $managerPath = $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH);
        $paths[] = $managerPath . 'templates/' . $this->theme . '/';
        $paths[] = $managerPath . 'templates/default/';
        $paths = array_unique($paths);

        return $paths;
    }

    /**
     * Get an array of possible URLs to the template's directory.
     * Override this to point to a custom directory.
     *
     * @param bool $specificThemeOnly Return URL only to theme specified in system settings
     *
     * @return array
     */
    public function getTemplatesUrls($specificThemeOnly = false)
    {
        $urls = [];
        $managerUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);

        $urls[] = $managerUrl . 'templates/' . $this->theme . '/';

        if ($specificThemeOnly === false) {
            $urls[] = $managerUrl . 'templates/default/';
        }

        return $urls;
    }

    /**
     * Do permission checking in this method. Returning false will present a "permission denied" message.
     *
     * @abstract
     * @return bool
     */
    abstract public function checkPermissions();

    /**
     * Process the controller, returning an array of placeholders to set.
     *
     * @abstract
     *
     * @param array $scriptProperties A array of REQUEST parameters.
     *
     * @return mixed Either an error or output string, or an array of placeholders to set.
     */
    abstract public function process(array $scriptProperties = []);

    /**
     * Return a string to set as the controller's page title.
     *
     * @abstract
     * @return string
     */
    abstract public function getPageTitle();

    /**
     * Register any custom CSS or JS in this method.
     *
     * @abstract
     * @return void
     */
    abstract public function loadCustomCssJs();

    /**
     * Return the relative path to the template file to load
     *
     * @abstract
     * @return string
     */
    abstract public function getTemplateFile();

    /**
     * Specify an array of language topics to load for this controller
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return [];
    }

    /**
     * Can be used to fire events after all the CSS/JS is loaded for a page
     *
     * @return void
     */
    public function firePostRenderEvents()
    {
    }

    /**
     * Fire any pre-render events for the controller
     *
     * @return void
     */
    public function firePreRenderEvents()
    {
    }

    /**
     * Get the page header for the controller.
     *
     * @return string
     */
    public function getHeader()
    {
        $this->setPlaceholder('_authToken', $this->modx->user->getUserToken('mgr'));
        $this->loadController('header.php', true);
        $output = $this->fetchTemplate('header.tpl');
        $this->setPlaceholder('_authToken', '');
        return $output;
    }

    /**
     * Get the page footer for the controller.
     *
     * @return string
     */
    public function getFooter()
    {
        $this->loadController('footer.php', true);

        return $this->fetchTemplate('footer.tpl');
    }

    /**
     * Registers the core and base JS scripts
     *
     * @access public
     * @return void
     */
    public function registerBaseScripts()
    {
        $managerUrl = $this->modx->getOption('manager_url');
        $externals = [];

        if ($this->loadBaseJavascript) {
            $compressJs = (boolean)$this->modx->getOption('compress_js', null, true);
            $this->modx->setOption('compress_js', $compressJs);
            if ($compressJs) {
                $externals[] = $managerUrl . 'assets/modext/modx.jsgrps-min.js';
            } else {
                $externals[] = $managerUrl . 'assets/modext/core/modx.localization.js';
                $externals[] = $managerUrl . 'assets/modext/util/utilities.js';
                $externals[] = $managerUrl . 'assets/modext/util/datetime.js';
                $externals[] = $managerUrl . 'assets/modext/util/uploaddialog.js';
                $externals[] = $managerUrl . 'assets/modext/util/fileupload.js';
                $externals[] = $managerUrl . 'assets/modext/util/superboxselect.js';

                $externals[] = $managerUrl . 'assets/modext/core/modx.component.js';
                $externals[] = $managerUrl . 'assets/modext/core/modx.view.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.button.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.searchbar.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.panel.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.tabs.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.window.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.combo.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.grid.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.console.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.portal.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/windows.js';

                $externals[] = $managerUrl . 'assets/fileapi/FileAPI.js';
                $externals[] = $managerUrl . 'assets/modext/util/multiuploaddialog.js';

                $externals[] = $managerUrl . 'assets/modext/widgets/core/tree/modx.tree.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/tree/modx.tree.treeloader.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/modx.treedrop.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/core/modx.tree.asynctreenode.js';

                $externals[] = $managerUrl . 'assets/modext/widgets/resource/modx.tree.resource.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/resource/modx.window.resource.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/element/modx.tree.element.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/system/modx.tree.directory.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/system/modx.panel.filetree.js';
                $externals[] = $managerUrl . 'assets/modext/widgets/media/modx.browser.js';
                $externals[] = $managerUrl . 'assets/modext/core/modx.layout.js';
            }

            $this->loadLayout($externals);

            if ($this->modx->getOption('compress_css', null, true)) {
                $this->modx->setOption('compress_css', true);
            }

            $o = '';
            $suffix = '?mv=' . $this->_prepareVersionPostfix();
            // Add script tags for the required javascript
            foreach ($externals as $js) {
                $o .= '<script src="' . $js . $suffix . '"></script>' . "\n";
            }

            // Get the state and user token for adding to the init script
            $state = $this->getDefaultState();
            $state = !empty($state)
                ? 'MODx.defaultState = ' . json_encode($state) . ';'
                : '';
            $layout = '';
            if (!$this instanceof BrowserManagerController) {
                $data = [
                    'xtype' => 'modx-layout',
                    //'accordionPanels' => 'MODx.accordionPanels || []',
                    'auth' => $this->modx->user->getUserToken('mgr'),
                    'search' => (int)$this->modx->hasPermission('search'),
                ];
                $layout = 'MODx.load(' . json_encode($data) . ');';
            }
            $o .= '<script>Ext.onReady(function() {' . $state . $layout . '});</script>';
            $this->modx->smarty->assign('maincssjs', $o);
        }
    }

    /**
     * Load theme specific layout.js if found, fallback to default layout
     *
     * @param array $externals An array of assets to load
     *
     * @return void
     */
    public function loadLayout(array &$externals)
    {
        $templatesUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL) . 'templates/';
        $themePath = MODX_MANAGER_PATH . "templates/{$this->theme}";
        $layoutFile = '/js/layout.js';

        if (file_exists($themePath . $layoutFile)) {
            // Apply to both custom themes and "default" theme
            $externals[] = $templatesUrl . $this->theme . $layoutFile;
        } elseif ($this->theme !== 'default') {
            // Load default layout for custom themes without a custom layout.js
            $externals[] = $templatesUrl . 'default' . $layoutFile;
        }
    }

    /**
     * Get the default state for the UI
     *
     * @return array|mixed|string
     */
    public function getDefaultState()
    {
        /** @var ProcessorResponse $response */
        $response = $this->modx->runProcessor(Read::class, [
            'register' => 'state',
            'topic' => '/ys/user-' . $this->modx->user->get('id') . '/',
            'include_keys' => true,
            'poll_interval' => 1,
            'poll_limit' => 1,
            'remove_read' => false,
            'show_filename' => false,
            'time_limit' => 10,
        ]);
        $obj = $response->getMessage();
        if (!empty($obj)) {
            $obj = $this->modx->fromJSON($obj);
        } else {
            $obj = [];
        }

        return $obj;
    }

    /**
     * Grabs a stripped version of modx to prevent caching of JS after upgrades
     *
     * @access private
     * @return string The parsed version string
     */
    private function _prepareVersionPostfix()
    {
        $version = $this->modx->getVersionData();

        return str_replace(['.', '-'], '', $version['full_version']);
    }

    /**
     * Appends a version postfix to a script tag
     *
     * @access private
     *
     * @param string $str     The script tag to append the version to
     * @param string $version The version to append
     *
     * @return string The adjusted script tag
     */
    private function _postfixVersionToScript($str, $version)
    {
        $pos = strpos($str, '.js');
        $pos2 = strpos($str, 'src="'); /* only apply to externals */
        if ($pos && $pos2) {
            $s = substr($str, 0, strpos($str, '"></script>'));
            if (!empty($s) && substr($s, strlen($s) - 3, strlen($s)) == '.js') {
                $str = $s . '?v=' . $version . '"></script>';
            }
        }

        return $str;
    }

    /**
     * Registers CSS/JS to manager interface
     */
    public function registerCssJs()
    {
        $this->_prepareHead();
        $versionPostFix = $this->_prepareVersionPostfix();

        $jsToCompress = [];
        foreach ($this->head['js'] as $js) {
            $jsToCompress[] = $js;
        }
        $cssjs = [];
        if (!empty($jsToCompress)) {
            foreach ($jsToCompress as $scr) {
                $scr = strpos($scr, '?') === false ? $scr . '?mv=' . $versionPostFix : $scr . '&mv=' . $versionPostFix;
                $cssjs[] = '<script src="' . $scr . '"></script>';
            }
        }

        $cssToCompress = [];
        foreach ($this->head['css'] as $css) {
            $cssToCompress[] = $css;
        }
        if (!empty($cssToCompress)) {
            foreach ($cssToCompress as $scr) {
                $scr = strpos($scr, '?') === false ? $scr . '?mv=' . $versionPostFix : $scr . '&mv=' . $versionPostFix;
                $cssjs[] = '<link href="' . $scr . '" rel="stylesheet" type="text/css" />';
            }
        }

        foreach ($this->head['html'] as $html) {
            $cssjs[] = $html;
        }

        foreach ($this->modx->sjscripts as $scr) {
            $scr = $this->_postfixVersionToScript($scr, $versionPostFix);
            $cssjs[] = $scr;
        }


        $lastjs = [];
        foreach ($this->head['lastjs'] as $js) {
            $lastjs[] = $js;
        }
        if (!empty($lastjs)) {
            foreach ($lastjs as $scr) {
                $cssjs[] = '<script src="' . $scr . '"></script>';
            }
        }


        $this->modx->smarty->assign('cssjs', $cssjs);
    }

    /**
     * Prepare the set html/css/js to be added
     *
     * @return void
     */
    private function _prepareHead()
    {
        $this->head['js'] = array_unique($this->head['js']);
        $this->head['html'] = array_unique($this->head['html']);
        $this->head['css'] = array_unique($this->head['css']);
        $this->head['lastjs'] = array_unique($this->head['lastjs']);
    }

    /**
     * Add an external Javascript file to the head of the page
     *
     * @param string $script
     *
     * @return void
     */
    public function addJavascript($script)
    {
        $this->head['js'][] = $script;
    }

    /**
     * Add a block of HTML to the head of the page
     *
     * @param string $script
     *
     * @return void
     */
    public function addHtml($script)
    {
        $this->head['html'][] = $script;
    }

    /**
     * Add a external CSS file to the head of the page
     *
     * @param string $script
     *
     * @return void
     */
    public function addCss($script)
    {
        $this->head['css'][] = $script;
    }

    /**
     * Add an external Javascript file to the head of the page
     *
     * @param string $script
     *
     * @return void
     */
    public function addLastJavascript($script)
    {
        $this->head['lastjs'][] = $script;
    }


    /**
     * Checks Form Customization rules for an object.
     *
     * @param xPDOObject $obj       If passed, will validate against for rules with constraints.
     * @param bool       $forParent No longer used - filtering only happens by controller
     *
     * @return array
     */
    public function checkFormCustomizationRules(&$obj = null, $forParent = false)
    {
        $overridden = [];

        if ($this->modx->getOption('form_customization_use_all_groups', null, false)) {
            $userGroups = $this->modx->user->getUserGroups();
        } else {
            $primaryGroup = $this->modx->user->getPrimaryGroup();
            if ($primaryGroup) {
                $userGroups = [$primaryGroup->get('id')];
            }
        }
        $c = $this->modx->newQuery(modActionDom::class);
        $c->innerJoin(modFormCustomizationSet::class, 'FCSet');
        $c->innerJoin(modFormCustomizationProfile::class, 'Profile', 'FCSet.profile = Profile.id');
        $c->leftJoin(modFormCustomizationProfileUserGroup::class, 'ProfileUserGroup',
            'Profile.id = ProfileUserGroup.profile');
        $c->leftJoin(modFormCustomizationProfile::class, 'UGProfile', 'UGProfile.id = ProfileUserGroup.profile');

        // Filter on the controller (action).
        $controller = array_key_exists('controller', $this->config) ? $this->config['controller'] : '';
        if (strpos($controller, '/') !== false) {
            // For multi-level controllers (e.g. resource/create), we get the last part
            // of the controller name to also search for a wildcard (e.g. resource/*)
            $wildController = substr($controller, 0, strrpos($controller, '/')) . '/*';
            $c->where([
                'modActionDom.action:IN' => [$controller, $wildController],
            ]);
        } else {
            $c->where([
                'modActionDom.action' => array_key_exists('controller',
                    $this->config) ? $this->config['controller'] : '',
            ]);
        }

        $c->where([
            'FCSet.active' => true,
            'Profile.active' => true,
        ]);
        if (!empty($userGroups)) {
            $c->where([
                [
                    'ProfileUserGroup.usergroup:IN' => $userGroups,
                    [
                        'OR:ProfileUserGroup.usergroup:IS' => null,
                        'AND:UGProfile.active:=' => true,
                    ],
                ],
                'OR:ProfileUserGroup.usergroup:=' => null,
            ], xPDOQuery::SQL_AND, null, 2);
        }
        $c->select($this->modx->getSelectColumns(modActionDom::class, 'modActionDom'));
        $c->select($this->modx->getSelectColumns(modFormCustomizationSet::class, 'FCSet', '', [
            'constraint_class',
            'constraint_field',
            'constraint',
            'template',
        ]));
        $c->sortby('modActionDom.rank', 'ASC');
        $domRules = $this->modx->getCollection(modActionDom::class, $c);
        $rules = [];
        /** @var modActionDom $rule */
        foreach ($domRules as $rule) {
            $template = $rule->get('template');
            if (!empty($template) && $obj) {
                if ($template != $obj->get('template')) {
                    continue;
                }
            }
            $constraintClass = $rule->get('constraint_class');
            if (!empty($constraintClass)) {
                if (empty($obj) || !($obj instanceof $constraintClass)) {
                    continue;
                }
                $constraintField = $rule->get('constraint_field');
                $constraint = $rule->get('constraint');
                $constraintList = explode(',', $constraint);
                $constraintList = array_map('trim', $constraintList);
                if (($obj->get($constraintField) != $constraint) && (!in_array($obj->get($constraintField),
                        $constraintList))) {
                    continue;
                }
            }
            if ($rule->get('rule') == 'fieldDefault') {
                $field = $rule->get('name');
                if ($field == 'modx-resource-content') {
                    $field = 'content';
                }
                $overridden[$field] = $rule->get('value');
                if ($field == 'parent-cmb') {
                    $overridden['parent'] = (int)$rule->get('value');
                    $overridden['parent-cmb'] = (int)$rule->get('value');
                }
            }
            $r = $rule->apply();
            if (!empty($r)) {
                $rules[] = $r;
            }
        }
        if (!empty($rules)) {
            $this->ruleOutput[] = '<script>Ext.onReady(function() {' . implode(
                "\n",
                $rules
            ) . '});</script>';
        }

        return $overridden;
    }

    /**
     * Load the working context for this controller.
     *
     * @return modContext|string
     */
    public function loadWorkingContext()
    {
        $wctx = !empty($_GET['wctx']) ? $_GET['wctx'] : $this->modx->context->get('key');
        if (!empty($wctx)) {
            $this->workingContext = $this->modx->getContext($wctx);
            if (!$this->workingContext) {
                $this->failure($this->modx->lexicon('permission_denied'));
            }
        } else {
            $this->workingContext =& $this->modx->context;
        }

        return $this->workingContext;
    }

    /**
     * Adds a topic to the JS language array
     *
     * @param string $topic
     *
     * @return string
     */
    public function addLexiconTopic($topic)
    {
        $this->modx->lexicon->load($topic);
        $langTopics = $this->getPlaceholder('_lang_topics');
        $langTopics = explode(',', $langTopics);
        $langTopics[] = $topic;
        $langTopics = implode(',', $langTopics);
        $this->setPlaceholder('_lang_topics', $langTopics);

        return $langTopics;
    }

    public function setCssURLPlaceholders()
    {
        $managerUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        $managerPath = $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH);

        $index = false;
        $login = false;

        if ($this->theme != 'default') {
            if (file_exists($managerPath . 'templates/' . $this->theme . '/css/index.css')) {
                $this->setPlaceholder('indexCss', $managerUrl . 'templates/' . $this->theme . '/css/index.css');
                $index = true;
            }

            if (file_exists($managerPath . 'templates/' . $this->theme . '/css/login.css')) {
                $this->setPlaceholder('loginCss', $managerUrl . 'templates/' . $this->theme . '/css/login.css');
                $login = true;
            }
        }

        $versionToken = hash('adler32', $this->modx->getOption('settings_version') . $this->modx->uuid);
        $this->setPlaceholder('versionToken', $versionToken);

        if (!$index) {
            $this->setPlaceholder('indexCss', $managerUrl . 'templates/default/css/index.css');
        }

        if (!$login) {
            $this->setPlaceholder('loginCss', $managerUrl . 'templates/default/css/login.css');
        }
    }
}
