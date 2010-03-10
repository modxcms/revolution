<?php
/**
 * A modScript derivative representing a MODx PHP code snippet.
 *
 * @package modx
 * @extends modScript
 */
class modSnippet extends modScript {
    /**
     * Overrides modElement::save to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        $success = parent::save($cacheFlag);

        if (!$success && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('snippet_err_create') : $this->xpdo->lexicon('snippet_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }
        return $success;
    }

    /**
     * Overrides modElement::remove to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        $success = parent :: remove($ancestors);

        if (!$success && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('snippet_err_remove').$this->toArray());
        }

        return $success;
    }

    /**
     * Get the source content of this snippet.
     *
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function setContent($content, array $options = array()) {
        return $this->set('snippet', $content);
    }
}