<?php
/**
 * @package modx
 */
/**
 * Handles backwards compatibility with pre-Revo 2.2 controllers
 *
 * @package modx
 */
class modManagerControllerDeprecated extends modManagerController {
    public $body = '';
    /**
     * Overrides modManagerController::process to provide custom backwards-compatible support for controllers that
     * were made prior to Revolution 2.2.x.
     *
     * @param array $scriptProperties
     * @see modManagerController::process()
     * @return string
     */
    public function process(array $scriptProperties = array()) {
        $modx =& $this->modx;
        $theme = $this->modx->getOption('manager_theme',null,'default');

        /* get template path */
        $templatePath = $this->modx->getOption('manager_path') . 'templates/'.$theme.'/';
        if (!file_exists($templatePath)) {
            $templatePath = $this->modx->getOption('manager_path') . 'templates/default/';
            $this->modx->config['manager_theme'] = 'default';
            $this->setPlaceholder('_config',$this->modx->config);
        }

        /* assign custom action topics to smarty, so can load custom topics for each page */
        $this->modx->lexicon->load('action');
        $topics = explode(',',$this->config['lang_topics']);
        foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
        $this->setPlaceholder('_lang_topics',$this->config['lang_topics']);
        $this->setPlaceholder('_lang',$this->modx->lexicon->fetch());
        $this->setPlaceholder('_ctx',$this->modx->context->get('key'));

        $this->registerBaseScripts(!empty($this->config['haslayout']) ? true : false);

        $this->body = '';

        $f = $this->prepareNamespacePath($this->config['controller'],$theme);
        $f = $this->getControllerFilename($f);

        if (file_exists($f)) {
            $this->modx->invokeEvent('OnBeforeManagerPageInit',array(
                'action' => $this->config,
                'filename' => $f,
            ));
            if (!empty($this->config['namespace']) && $this->config['namespace'] != 'core') {
                $this->modx->smarty->setTemplatePath($this->config['namespace_path']);
            }

            $cbody = include $f;
        } else {
            if (!empty($this->config['namespace_path'])) {
                $f = str_replace($this->config['namespace_path'], '', $f);
            }
            $cbody = 'Could not find action file at: '.$f;
        }

        if (!empty($this->ruleOutput)) {
            $this->addHtml($this->ruleOutput);
        }
        $this->registerCssJs();

        /* assign later to allow for css/js registering */
        if (is_array($cbody)) {
            $this->setPlaceholder('_e', $cbody);
            $cbody = $this->fetchTemplate('error.tpl');
        }
        $this->body .= $cbody;


        return $this->body;
    }

    /**
     * Check whether the active user has access to view this page
     * @return bool True if the user passes permission checks
     */
    public function checkPermissions() { return true; }
    /**
     * The page title for this controller
     * @return string The string title of the page
     */
    public function getPageTitle() { return ''; }
    /**
     * Loads any page-specific CSS/JS for the controller
     * @return void
     */
    public function loadCustomCssJs() { return; }
    /**
     * Load the appropriate language topics for this page
     *
     * @return array
     */
    public function getLanguageTopics() { return array(); }
    /**
     * Specify the location of the template file
     * @return string The absolute path to the template file
     */
    public function getTemplateFile() { return ''; }

    /**
     * Prepares the Namespace Path for usage
     *
     * @access protected
     * @param string $controller
     * @param string $theme
     * @return string The formatted Namespace path
     */
    protected function prepareNamespacePath($controller,$theme = 'default') {
        /* set context url and path */
        $this->modx->config['namespace_path'] = $controller;

        /* find context path */
        if (isset($this->config['namespace']) && $this->config['namespace'] != 'core') {
            /* if a custom 3rd party path */
            $f = $this->config['namespace_path'].$controller;

        } else {
            $f = $this->config['namespace_path'].'controllers/'.$theme.'/'.$controller;
            /* if custom theme doesnt have controller, go to default theme */
            if (!file_exists($f.'.php')) {
                $f = $this->config['namespace_path'].'controllers/default/'.$controller;
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
        $this->setPlaceholder('cssjs',$this->modx->sjscripts);
        if (!empty($this->head['js']) || !empty($this->head['lastjs']) || !empty($this->head['css']) ||
            !empty($this->head['html'])
        ) {
            // Some plugin probably registered additional assets (ie. OnBeforeManagerPageInit), let's load them
            parent::registerCssJs();
        }
    }

    /**
     * Adds a lexicon topic to this page's language topics to load. Will load
     * the topic as well.
     *
     * @param string $topic The topic to load, in standard namespace:topic format
     * @return boolean True if successful
     */
    public function addLangTopic($topic) {
        return $this->addLexiconTopic($topic);
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
        return $this->setPlaceholder('_lang_topics',$topics);
    }

}
