<?php
abstract class modManagerController {
    public $modx;
    public $config = array();
    public $loadHeader = true;
    public $loadFooter = true;
    public $loadBaseJavascript = true;
    public $templatesPath;
    public $controllersPath;

    protected $ruleOutput = '';
    protected $theme = 'default';
    protected $title = '';

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(),$config);
    }

    public function render() {
        if (!$this->checkPermissions()) {
            return $this->modx->error->failure($this->modx->lexicon('access_denied'));
        }

        $this->theme = $this->modx->getOption('manager_theme',null,'default');

        $this->modx->smarty->assign('_pagetitle',$this->getPageTitle());

        $this->modx->lexicon->load('action');
        $languageTopics = $this->getLanguageTopics();
        foreach ($languageTopics as $topic) { $this->modx->lexicon->load($topic); }
        $this->modx->smarty->assign('_lang_topics',implode(',',$languageTopics));
        $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
        $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));


        $this->loadControllersPath();
        $this->loadTemplatesPath();
        $tpl = $this->getTemplateFile();
        $content = '';
        if (!empty($tpl)) {
            $content = $this->modx->smarty->fetch($tpl);
        }

        $this->registerBaseScripts();

        $this->checkFormCustomizationRules();

        $this->modx->invokeEvent('OnBeforeManagerPageInit',array(
            'action' => $this->config,
        ));
        $placeholders = $this->process();
        if (!empty($placeholders) && is_array($placeholders)) {
            foreach ($placeholders as $k => $v) {
                $this->modx->smarty->assign($k,$v);
            }
        } elseif (!empty($placeholders)) {
            $content = $placeholders;
        }
        $this->loadCustomCssJs();
        $this->loadCssJs();

        if (!empty($this->ruleOutput)) {
            $this->modx->regClientStartupHTMLBlock($this->ruleOutput);
        }

        $body = $this->getHeader();

        /* assign later to allow for css/js registering */
        if (is_array($content)) {
            $this->modx->smarty->assign('_e', $content);
            $content = $this->modx->smarty->fetch('error.tpl');
        }
        
        $body .= $content;

        $body .= $this->getFooter();

        return $body;
    }

    public function loadTemplatesPath() {
        if (empty($this->templatesPath)) {
            $this->templatesPath = $this->getTemplatesPath();
            if (!file_exists($this->templatesPath)) {
                $this->templatesPath = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH) . 'templates/'.$this->theme.'/';
                $this->modx->setOption('manager_theme','default');
                $this->modx->smarty->assign('_config',$this->modx->config);
            }
            $this->modx->smarty->setTemplatePath($this->templatesPath);
        }
        return $this->templatesPath;
    }
    public function loadControllersPath() {
        if (empty($this->controllersPath)) {
            $this->controllersPath = $this->getControllersPath();
            if (!file_exists($this->controllersPath)) {
                $this->controllersPath = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/'.$this->theme.'/';
            }
        }
        return $this->controllersPath;
    }


    public function getTemplatesPath($coreOnly = false) {
        $namespacePath = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH);
        if (!empty($this->config['namespace_path']) && !$coreOnly) {
            $namespacePath = $this->config['namespace_path'];
        }
        return $namespacePath . 'templates/'.($this->config['namespace'] == 'core' || $coreOnly ? $this->theme.'/' : '');
    }
    public function getControllersPath($coreOnly = false) {
        $namespacePath = $this->modx->getOption('manager_path',null,MODX_MANAGER_PATH);
        if (!empty($this->config['namespace_path']) && !$coreOnly) {
            $namespacePath = $this->config['namespace_path'];
        }
        return $namespacePath.'controllers/'.($this->config['namespace'] == 'core' || $coreOnly ? $this->theme.'/' : '');
    }

    abstract public function checkPermissions();
    abstract public function process();
    abstract public function getPageTitle();
    abstract public function loadCustomCssJs();


    public function getHeader() {
        $modx =& $this->modx;
        $this->modx->smarty->setTemplatePath($this->modx->getOption('manager_path',null,MODX_MANAGER_PATH) . 'templates/'.$this->theme.'/');
        $o = include_once $this->getControllersPath(true).'header.php';
        return $o;
    }
    public function getFooter() {
        $modx =& $this->modx;
        $this->modx->smarty->setTemplatePath($this->modx->getOption('manager_path',null,MODX_MANAGER_PATH) . 'templates/'.$this->theme.'/');
        $o = include_once $this->getControllersPath(true).'footer.php';
        return $o;
    }

    /**
     * Registers the core and base JS scripts
     *
     * @access public
     * @return void
     */
    public function registerBaseScripts() {
        $managerUrl = $this->modx->getOption('manager_url');
        if ($this->modx->getOption('concat_js',null,false)) {
            if ($this->modx->getOption('compress_js',null,false)) {
                $this->modx->regClientStartupScript($managerUrl.'assets/modext/modext-min.js');
            } else {
                $this->modx->regClientStartupScript($managerUrl.'assets/modext/modext.js');
            }
        } else {
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.localization.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/util/utilities.js');

            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.component.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.panel.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.tabs.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.window.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.tree.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.combo.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.grid.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.console.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/core/modx.portal.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/modx.treedrop.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/windows.js');

            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/resource/modx.tree.resource.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/element/modx.tree.element.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/widgets/system/modx.tree.directory.js');
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.view.js');
        }

        if ($this->loadBaseJavascript) {
            $siteId = $_SESSION["modx.{$this->modx->context->get('key')}.user.token"];
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.layout.js');
            $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({xtype: "modx-layout",accordionPanels: MODx.accordionPanels || [],auth: "'.$siteId.'"});
});</script>');
        }
    }
    /**
     * Grabs a stripped version of modx to prevent caching of JS after upgrades
     *
     * @access private
     * @return string The parsed version string
     */
    private function _prepareVersionPostfix() {
        $version = $this->modx->getVersionData();
        return str_replace(array('.','-'),'',$version['full_version']);
    }

    /**
     * Appends a version postfix to a script tag
     *
     * @access private
     * @param string $str The script tag to append the version to
     * @param string $version The version to append
     * @return string The adjusted script tag
     */
    private function _postfixVersionToScript($str,$version) {
        $pos = strpos($str,'.js');
        $pos2 = strpos($str,'src="'); /* only apply to externals */
        if ($pos && $pos2) {
            $s = substr($str,0,strpos($str,'"></script>'));
            if (!empty($s) && substr($s,strlen($s)-3,strlen($s)) == '.js') {
                $str = $s.'?v='.$version.'"></script>';
            }
        }
        return $str;
    }

    /**
     * Registers CSS/JS to manager interface
     */
    public function loadCssJs() {
        $versionPostFix = $this->_prepareVersionPostfix();
        /* if true, use compressed JS */
        if ($this->modx->getOption('compress_js',null,false)) {
            foreach ($this->modx->sjscripts as &$scr) {
                $pos = strpos($scr,'.js');
                if ($pos) {
                    $newUrl = substr($scr,0,$pos).'-min'.substr($scr,$pos,strlen($scr));
                } else { continue; }
                $pos = strpos($newUrl,'modext/');
                if ($pos) {
                    $pos = $pos+7;
                    $newUrl = substr($newUrl,0,$pos).'build/'.substr($newUrl,$pos,strlen($newUrl));
                }

                $path = str_replace(array(
                    $this->modx->getOption('manager_url').'assets/modext/',
                    '<script type="text/javascript" src="',
                    '"></script>',
                ),'',$newUrl);

                if (file_exists($this->modx->getOption('manager_path').'assets/modext/'.$path)) {
                    $scr = $newUrl;
                }
                /* append version string */
                $scr = $this->_postfixVersionToScript($scr,$versionPostFix);
            }
        } else {
            foreach ($this->modx->sjscripts as &$scr) {
                $scr = $this->_postfixVersionToScript($scr,$versionPostFix);
            }
        }
        /* assign css/js to header */
        $this->modx->smarty->assign('cssjs',$this->modx->sjscripts);
    }



    /**
     * Checks Form Customization rules for an object.
     *
     * @param xPDOObject $obj If passed, will validate against for rules with constraints.
     * @param bool $forParent
     * @return bool
     */
    public function checkFormCustomizationRules(&$obj = null,$forParent = false) {
        $overridden = array();

        $userGroups = $this->modx->user->getUserGroups();
        $c = $this->modx->newQuery('modActionDom');
        $c->innerJoin('modFormCustomizationSet','FCSet');
        $c->innerJoin('modFormCustomizationProfile','Profile','FCSet.profile = Profile.id');
        $c->leftJoin('modFormCustomizationProfileUserGroup','ProfileUserGroup','Profile.id = ProfileUserGroup.profile');
        $c->leftJoin('modFormCustomizationProfile','UGProfile','UGProfile.id = ProfileUserGroup.profile');
        $c->where(array(
            'modActionDom.action' => $this->config['id'],
            'modActionDom.for_parent' => $forParent,
            'FCSet.active' => true,
            'Profile.active' => true,
        ));
        $c->where(array(
            array(
                'ProfileUserGroup.usergroup:IN' => $userGroups,
                array(
                    'OR:ProfileUserGroup.usergroup:IS' => null,
                    'AND:UGProfile.active:=' => true,
                ),
            ),
            'OR:ProfileUserGroup.usergroup:=' => null,
        ),xPDOQuery::SQL_AND,null,2);
        $c->select($this->modx->getSelectColumns('modActionDom', 'modActionDom'));
        $c->select($this->modx->getSelectColumns('modFormCustomizationSet', 'FCSet', '', array(
            'constraint_class',
            'constraint_field',
            'constraint',
            'template'
        )));
        $c->sortby('modActionDom.rank','ASC');
        $domRules = $this->modx->getCollection('modActionDom',$c);
        $rules = array();
        foreach ($domRules as $rule) {
            $template = $rule->get('template');
            if (!empty($template)) {
                if ($template != $obj->get('template')) continue;
            }
            $constraintClass = $rule->get('constraint_class');
            if (!empty($constraintClass)) {
                if (empty($obj) || !($obj instanceof $constraintClass)) continue;
                $constraintField = $rule->get('constraint_field');
                $constraint = $rule->get('constraint');
                if ($obj->get($constraintField) != $constraint) {
                    continue;
                }
            }
            if ($rule->get('rule') == 'fieldDefault') {
                $field = $rule->get('name');
                if ($field == 'modx-resource-content') $field = 'content';
                $overridden[$field] = $rule->get('value');
            }
            $r = $rule->apply();
            if (!empty($r)) $rules[] = $r;
        }
        $this->ruleOutput = '';
        if (!empty($rules)) {
            $this->ruleOutput .= '<script type="text/javascript">Ext.onReady(function() {'.implode("\n",$rules).'});</script>';
        }
        return $overridden;
    }
}