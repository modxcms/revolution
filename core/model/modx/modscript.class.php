<?php
/**
 * @package modx
 */
/**
 * An element representing executable PHP script content.
 *
 * {@inheritdoc}
 *
 * @property int $id
 * @property string $name The name of the script
 * @property string $description The description of the script
 * @property int $editor_type Deprecated
 * @property int $category The Category this Script resides in
 * @abstract Implement a derivative class that defines a table for storage.
 * @package modx
 */
class modScript extends modElement {
    /**
     * The name of the script
     * @var string $_scriptName
     */
    public $_scriptName= null;
    /**
     * The cache key of the script
     * @var string $_scriptCacheKey
     */
    public $_scriptCacheKey= null;
    /**
     * @var string The filename of the script to include.
     */
    protected $_scriptFilename;

    /**
     * Override set to properly strip invalid tags from script code
     *
     * {@inheritdoc}
     */
    public function set($k, $v= null, $vType= '') {
        if (in_array($k, array('snippet', 'plugincode', 'content'))) {
            $v= trim($v);
            if (strncmp($v, '<?', 2) == 0) {
                $v= substr($v, 2);
                if (strncmp($v, 'php', 3) == 0) $v= substr($v, 3);
            }
            if (substr($v, -2, 2) == '?>') $v= substr($v, 0, -2);
            $v= trim($v, " \n\r\0\x0B");
        }
        $set= parent::set($k, $v, $vType);
        return $set;
    }

    /**
     * Process specifically script-related functionality for modScript objects.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_scriptFilename = $this->loadScript();
            $this->_result= $this->_scriptFilename !== false && is_readable($this->_scriptFilename);
            if ($this->_result) {
                if (empty($this->xpdo->event)) $this->xpdo->event = new stdClass();
                $modx =& $this->xpdo;
                $scriptProperties = $this->xpdo->event->params= $this->_properties; /* store params inside event object */
                ob_start();
                unset($properties, $content);
                extract($scriptProperties, EXTR_SKIP);
                $includeResult= include $this->_scriptFilename;
                $includeResult= ($includeResult === null ? '' : $includeResult);
                if (ob_get_length()) {
                    $this->_output = ob_get_contents() . $includeResult;
                } else {
                    $this->_output= $includeResult;
                }
                ob_end_clean();
                if ($this->_output && is_string($this->_output)) {
                    /* collect element tags in the evaluated content and process them */
                    $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                    $this->xpdo->parser->processElementTags(
                        $this->_tag,
                        $this->_output,
                        $this->xpdo->parser->isProcessingUncacheable(),
                        $this->xpdo->parser->isRemovingUnprocessed(),
                        '[[',
                        ']]',
                        array(),
                        $maxIterations
                    );
                }
                $this->filterOutput();
                unset ($this->xpdo->event->params);
                $this->cache();
            }
        }
        $this->_processed= true;
        $this->xpdo->parser->setProcessingElement(false);
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the name of the script source file, written to the cache file system
     *
     * @return string The filename containing the function generated from the
     * script element.
     */
    public function getScriptCacheKey() {
        if ($this->_scriptCacheKey === null) {
            $this->_scriptCacheKey= str_replace('_', '/', $this->getScriptName());
        }
        return $this->_scriptCacheKey;
    }

    /**
     * Get the name of the function the script has been given.
     *
     * @return string The function name representing this script element.
     */
    public function getScriptName() {
        if ($this->_scriptName === null) {
            $className= $this->_class;
            $this->_scriptName= 'elements_' . strtolower($className) . '_' . $this->get('id');
        }
        return $this->_scriptName;
    }

    /**
     * Get the include filename for the script, generating it if it does not exist.
     *
     * @return string|bool The include filename of the script or false.
     */
    public function loadScript() {
        $includeFilename = $this->xpdo->getCachePath() . 'includes/' . $this->getScriptCacheKey() . '.include.cache.php';
        $result = is_readable($includeFilename);
        $outdated = false;
        $sourceFile = $this->getSourceFile();
        if ($this->isStatic() && $result && !empty($sourceFile) && is_readable($sourceFile)) {
            $includeMTime = filemtime($includeFilename);
            $sourceMTime = filemtime($sourceFile);
            $outdated = $sourceMTime > $includeMTime;
        }
        if (!$result || $outdated) {
            $script= false;
            if (!$outdated) {
                $script= $this->xpdo->cacheManager->get($this->getScriptCacheKey(), array(
                    xPDO::OPT_CACHE_KEY => $this->xpdo->getOption('cache_scripts_key', null, 'scripts'),
                    xPDO::OPT_CACHE_HANDLER => $this->xpdo->getOption('cache_scripts_handler', null, $this->xpdo->getOption(xPDO::OPT_CACHE_HANDLER)),
                    xPDO::OPT_CACHE_FORMAT => (integer) $this->xpdo->getOption('cache_scripts_format', null, $this->xpdo->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
                ));
            }
            if (!$script) {
                $script= $this->xpdo->cacheManager->generateScript($this);
            }
            if (!empty($script)) {
                $options = array();
                $folderMode = $this->xpdo->getOption('new_cache_folder_permissions', null, false);
                if ($folderMode) $options['new_folder_permissions'] = $folderMode;
                $fileMode = $this->xpdo->getOption('new_cache_file_permissions', null, false);
                if ($fileMode) $options['new_file_permissions'] = $fileMode;
                $result = $this->xpdo->cacheManager->writeFile($includeFilename, "<?php\n" . $script, 'wb' , $options);
            }
        }
        if ($result !== false) {
            $result = $includeFilename;
        }
        return $result;
    }

    public function getFileContent(array $options = array()) {
        $content = parent::getFileContent($options);
        if (strncmp($content, '<?', 2) == 0) {
            $content= substr($content, 2);
            if (strncmp($content, 'php', 3) == 0) $content= substr($content, 3);
        }
        if (substr($content, -2, 2) == '?>') $content= substr($content, 0, -2);
        $content= trim($content, " \0\x0B");
        return $content;
    }

    public function setFileContent($content, array $options = array()) {
        $content = "<?php\n{$content}";
        return parent::setFileContent($content, $options);
    }
}
