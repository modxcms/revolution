<?php

namespace MODX;

/**
 * An abstract class meant to be used by TV renders. Do not extend this class directly; use its Input or Output
 * derivatives instead.
 *
 * @package modx
 */
abstract class modTemplateVarRender
{
    /** @var modTemplateVar $tv */
    public $tv;
    /** @var MODX $modx */
    public $modx;
    /** @var array $config */
    public $config = [];


    function __construct(modTemplateVar $tv, array $config = [])
    {
        $this->tv =& $tv;
        $this->modx =& $tv->xpdo;
        $this->config = array_merge($this->config, $config);
    }


    /**
     * Get any lexicon topics for your render. You may override this method in your render to provide an array of
     * lexicon topics to load.
     *
     * @return array
     */
    public function getLexiconTopics()
    {
        return ['tv_widget'];
    }


    /**
     * Render the TV render.
     *
     * @param string $value
     * @param array $params
     *
     * @return mixed
     */
    public function render($value, array $params = [])
    {
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                if ($v === 'true') {
                    $params[$k] = true;
                } elseif ($v === 'false') {
                    $params[$k] = false;
                } elseif (is_numeric($v) && ((int)$v == $v)) {
                    $params[$k] = intval($v);
                } elseif (is_numeric($v)) {
                    $params[$k] = (float)($v);
                }
            }
        }
        $this->_loadLexiconTopics();

        return $this->process($value, $params);
    }


    /**
     * Load any specified lexicon topics for the render
     */
    protected function _loadLexiconTopics()
    {
        $topics = $this->getLexiconTopics();
        if (!empty($topics) && is_array($topics)) {
            foreach ($topics as $topic) {
                $this->modx->lexicon->load($topic);
            }
        }
    }


    /**
     * @param string $value
     * @param array $params
     *
     * @return mixed
     */
    public function process($value, array $params = [])
    {
        return $value;
    }
}