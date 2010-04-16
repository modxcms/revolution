<?php
/**
 * A modScript derivative representing a MODx PHP code snippet.
 *
 * @package modx
 * @extends modScript
 */
class modSnippet extends modScript {
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
        return $this->set('snippet', $content);
    }
}