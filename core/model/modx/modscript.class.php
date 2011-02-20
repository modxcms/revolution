<?php
/**
 * An element representing executable PHP script content.
 *
 * {@inheritdoc}
 *
 * @abstract Implement a derivative class that defines a table for storage.
 * @package modx
 */
class modScript extends modElement {
    public $_scriptName= null;
    public $_scriptCacheKey= null;

    /**
     * Override set to properly strip invalid tags from script code
     *
     * {@inheritdoc}
     */
    public function set($k, $v= null, $vType= '') {
        if (in_array($k,array('snippet','plugincode'))) {
            $v= trim($v);
            if (strncmp($v, '<?', 2) == 0) {
                $v= substr($v, 2);
                if (strncmp($v, 'php', 3) == 0) $v= substr($v, 3);
            }
            if (substr($v, -2, 2) == '?>') $v= substr($v, 0, -2);
            $v= trim($v, " \n\r\0\x0B");
        }
        $set= parent :: set($k, $v, $vType);
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
            $scriptName= $this->getScriptName();
            $this->_result= function_exists($scriptName);
            if (!$this->_result) {
                $this->_result= $this->loadScript();
            }
            if ($this->_result) {
                $this->xpdo->event->params= $this->_properties; /* store params inside event object */
                ob_start();
                $this->_output= $scriptName($this->_properties);
                $this->_output= ob_get_contents() . $this->_output;
                ob_end_clean();
                if ($this->_output && is_string($this->_output)) {
                    /* collect element tags in the evaluated content and process them */
                    $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                    $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
                }
                $this->filterOutput();
                unset ($this->xpdo->event->params);
                $this->cache();
            }
        }
        $this->_processed= true;

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
     * Loads and evaluates the script, returning the result.
     *
     * @return boolean True if the result of the script is not false.
     */
    public function loadScript() {
        $includeFilename = $this->xpdo->getCachePath() . 'includes/' . $this->getScriptCacheKey() . '.include.cache.php';
        $result = file_exists($includeFilename);
        if (!$result) {
            $script= $this->xpdo->cacheManager->get($this->getScriptCacheKey(), array(
                xPDO::OPT_CACHE_KEY => $this->xpdo->getOption('cache_scripts_key', null, 'scripts'),
                xPDO::OPT_CACHE_HANDLER => $this->xpdo->getOption('cache_scripts_handler', null, $this->xpdo->getOption(xPDO::OPT_CACHE_HANDLER)),
                xPDO::OPT_CACHE_FORMAT => (integer) $this->xpdo->getOption('cache_scripts_format', null, $this->xpdo->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
            ));
            if (!$script) {
                $script= $this->xpdo->cacheManager->generateScript($this);
            }
            if (!empty($script)) {
                $result = $this->xpdo->cacheManager->writeFile($includeFilename, "<?php\n" . $script);
            }
        }
        if ($result) {
            $result = include($includeFilename);
            if ($result) {
                $result = function_exists($this->getScriptName());
            }
        }
        return ($result !== false);
    }
}
