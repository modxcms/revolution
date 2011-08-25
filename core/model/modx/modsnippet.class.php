<?php
/**
 * @package modx
 */
/**
 * A modScript derivative representing a MODX PHP code snippet.
 *
 * @property boolean $cache_type Deprecated
 * @property string $snippet The PHP code of the Snippet
 * @property boolean $locked Whether or not this Snippet can only be edited by Administrators
 * @property array $properties An array of default properties for the Snippet
 * @property string $moduleguid Deprecated
 * @package modx
 * @extends modScript
 */
class modSnippet extends modScript {
    /**
     * Override xPDOObject::__construct() to alias the content field.
     * 
     * @param xPDO &$xpdo
     */
    public function __construct(xPDO &$xpdo) {
        parent::__construct($xpdo);
        $this->_fields['content'] =& $this->_fields['snippet'];
        if ($this->getOption(xPDO::OPT_HYDRATE_FIELDS)) {
            $this->content =& $this->_fields['content'];
        }
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'snippet' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'snippet' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('snippet_err_create') : $this->xpdo->lexicon('snippet_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }
        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetBeforeRemove',array(
                'snippet' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetRemove',array(
                'snippet' => &$this,
                'ancestors' => $ancestors,
            ));
        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('snippet_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Get the source content of this snippet.
     *
     * {@inheritDoc}
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } elseif ($this->isStatic()) {
                $this->_content = $this->getFileContent($options);
            } else {
                $this->_content = $this->get('snippet');
            }
        }
        return $this->_content;
    }

    /**
     * Set the source content of this snippet.
     *
     * {@inheritDoc}
     */
    public function setContent($content, array $options = array()) {
        $set = false;
        if ($this->isStatic()) {
            $sourceFile = $this->getSourceFile($options);
            if ($sourceFile) {
                $set = file_put_contents($sourceFile, $content);
            }
        } else {
            $set = $this->set('snippet', $content);
        }
        return $set;
    }
}