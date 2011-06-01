<?php
/**
 * modManagerResponse
 *
 * @package modx
 */
require_once MODX_CORE_PATH . 'model/modx/modresponse.class.php';
/**
 * Encapsulates an HTTP response from the MODX manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerResponse extends modResponse {
    public $action = array();

    /**
     * Overrides modResponse::outputContent to provide mgr-context specific
     * response.
     *
     * {@inheritdoc}
     */
    public function outputContent(array $options = array()) {
        $modx= & $this->modx;
        $error= & $this->modx->error;

        $action = '';
        if (!isset($this->modx->request) || !isset($this->modx->request->action)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            $action =& intval($this->modx->request->action);
        }

        $theme = $this->modx->getOption('manager_theme',null,'default');

        $this->modx->lexicon->load('dashboard','topmenu','file');
        if ($action == 0) {
            $action = $this->modx->getObject('modAction',array(
                'namespace' => 'core',
                'controller' => 'welcome',
            ));
            $action = $action->get('id');
        }

        if ($this->modx->hasPermission('frames')) {
            if (isset($this->modx->actionMap[$action])) {
                $this->action = $this->modx->actionMap[$action];

                /* get template path */
                $templatePath = $this->modx->getOption('manager_path') . 'templates/'.$theme.'/';
                if (!file_exists($templatePath)) {
                    $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
                    $this->modx->config['manager_theme'] = 'default';
                    $this->modx->smarty->assign('_config',$this->modx->config);
                }

                /* assign custom action topics to smarty, so can load custom topics for each page */
                $this->modx->lexicon->load('action');
                $topics = explode(',',$this->action['lang_topics']);
                foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
                $this->modx->smarty->assign('_lang_topics',$this->action['lang_topics']);
                $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
                $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));

                $this->registerBaseScripts($this->action['haslayout'] ? true : false);

                $this->body = '';


                $f = $this->prepareNamespacePath($this->action['controller'],$theme);
                $f = $this->getControllerFilename($f);

                if (file_exists($f)) {
                    $this->modx->invokeEvent('OnBeforeManagerPageInit',array(
                        'action' => $this->action,
                        'filename' => $f,
                    ));
                    if (!empty($this->action['namespace']) && $this->action['namespace'] != 'core') {
                        $this->modx->smarty->setTemplatePath($this->action['namespace_path']);
                    }

                    $cbody = include $f;
                } else {
                    $cbody = 'Could not find action file at: '.$f;
                }

                if (!empty($this->ruleOutput)) {
                    $this->modx->regClientStartupHTMLBlock($this->ruleOutput);
                }
                $this->registerCssJs();

                /* reset path to core modx path for header/footer */
                $this->modx->smarty->setTemplatePath($templatePath);

                /* load header */
                $controllersPath = $this->modx->getOption('manager_path').'controllers/'.$theme.'/';
                if (!file_exists($controllersPath)) {
                    $controllersPath = $this->modx->getOption('manager_path').'controllers/default/';
                }
                if ($this->action['haslayout']) {
                    $this->body .= include_once $controllersPath.'header.php';
                }

                /* assign later to allow for css/js registering */
                if (is_array($cbody)) {
                    $this->modx->smarty->assign('_e', $cbody);
                    $cbody = $this->modx->smarty->fetch('error.tpl');
                }
                $this->body .= $cbody;

                /* load footer */
                if ($this->action['haslayout']) {
                    $this->body .= include_once $controllersPath.'footer.php';
                }


            } else {
                $this->body = $this->modx->error->failure($modx->lexicon('action_err_nfs',array(
                    'id' => $action,
                )));
            }
        } else {
            /* doesnt have permissions to view manager */
            $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
            $this->modx->smarty->assign('_ctx',$this->modx->context->get('key'));

            $this->body = include_once $this->modx->getOption('manager_path').'controllers/'.$theme.'/security/logout.php';

        }
        if (empty($this->body)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        }
        if (is_array($this->body)) {
            $this->modx->smarty->assign('_e', $this->body);
            echo $this->modx->smarty->fetch('error.tpl');
        } else {
            echo $this->body;
        }
        @session_write_close();
        exit();
    }

    /**
     * Checks Form Customization rules for an object.
     *
     * @param xPDOObject $obj If passed, will validate against for rules with constraints.
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
            'modActionDom.action' => $this->action['id'],
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

    /**
     * Registers the core and base JS scripts
     *
     * @access public
     */
    public function registerBaseScripts($loadLayout = true) {
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

        if ($loadLayout) {
            $siteId = $_SESSION["modx.{$this->modx->context->get('key')}.user.token"];
            $this->modx->regClientStartupScript($managerUrl.'assets/modext/core/modx.layout.js');
            $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">Ext.onReady(function() {
    MODx.load({xtype: "modx-layout",accordionPanels: MODx.accordionPanels || [],auth: "'.$siteId.'"});
});</script>');
        }
    }

    /**
     * Prepares the Namespace Path for usage
     *
     * @access protected
     * @return string The formatted Namespace path
     */
    protected function prepareNamespacePath($controller,$theme = 'default') {
        /* set context url and path */
        $this->modx->config['namespace_path'] = $controller;

        /* find context path */
        if (isset($this->action['namespace']) && $this->action['namespace'] != 'core') {
            /* if a custom 3rd party path */
            $f = $this->action['namespace_path'].$controller;

        } else {
            $f = $this->action['namespace_path'].'controllers/'.$theme.'/'.$controller;
            /* if custom theme doesnt have controller, go to default theme */
            if (!file_exists($f.'.php')) {
                $f = $this->action['namespace_path'].'controllers/default/'.$controller;
            }
        }
        return $f;
    }

    /**
     * Gets the parsed controller filename and checks for its existence.
     *
     * @access protected
     * @param string $f The filename to parse.
     * @return mixed The parsed filename, or boolean false if invalid.
     */
    protected function getControllerFilename($f = '') {
        if (empty($f)) return false;

        /* if action is a directory, load base index.php */
        if (substr($f,strlen($f)-1,1) == '/') { $f .= 'index'; }
        /* append .php */
        if (file_exists($f.'.php')) {
            $f = $f.'.php';
        /* for actions that don't have trailing / but reference index */
        } elseif (file_exists($f.'/index.php')) {
            $f = $f.'/index.php';
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not find action file at: '.$f);
            $f = $f.'.php';
        }
        return $f;
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
    public function registerCssJs() {
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
     * Adds a lexicon topic to this page's language topics to load. Will load
     * the topic as well.
     *
     * @param string $topic The topic to load, in standard namespace:topic format
     * @return boolean True if successful
     */
    public function addLangTopic($topic) {
        $this->modx->lexicon->load($topic);
        $topics = $this->getLangTopics();
        $topics[] = $topic;
        return $this->setLangTopics($topics);
    }
    /**
     * Adds a lexicon topic to this page's language topics to load
     *
     * @return boolean True if successful
     */
    public function getLangTopics() {
        $topics = $this->modx->smarty->get_template_vars('_lang_topics');
        return explode(',',$topics);
    }
    /**
     * Sets the language topics for this page
     *
     * @param array $topics The array of topics to set
     * @return boolean True if successful
     */
    public function setLangTopics(array $topics = array()) {
        if (!is_array($topics) || empty($topics)) return false;

        $topics = array_unique($topics);
        $topics = implode(',',$topics);
        return $this->modx->smarty->assign('_lang_topics',$topics);
    }
}
