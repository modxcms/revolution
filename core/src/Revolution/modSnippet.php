<?php

namespace MODX\Revolution;

use xPDO\xPDO;

/**
 * A modScript derivative representing a MODX PHP code snippet.
 *
 * @property boolean                 $cache_type Deprecated
 * @property string                  $snippet    The PHP code of the Snippet
 * @property boolean                 $locked     Whether or not this Snippet can only be edited by Administrators
 * @property array                   $properties An array of default properties for the Snippet
 * @property string                  $moduleguid Deprecated
 * @property boolean                 $static
 * @property string                  $static_file
 *
 * @property modElementPropertySet[] $PropertySets
 *
 * @package MODX\Revolution
 */
class modSnippet extends modScript
{
    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'snippet' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        $saved = parent::save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'snippet' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        } else {
            if (!$saved && !empty($this->xpdo->lexicon)) {
                $msg = $isNew ? $this->xpdo->lexicon('snippet_err_create') : $this->xpdo->lexicon('snippet_err_save');
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $msg . print_r($this->toArray(), true));
            }
        }

        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetBeforeRemove', [
                'snippet' => &$this,
                'ancestors' => $ancestors,
            ]);
        }
        $removed = parent:: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnSnippetRemove', [
                'snippet' => &$this,
                'ancestors' => $ancestors,
            ]);
        } else {
            if (!$removed && !empty($this->xpdo->lexicon)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $this->xpdo->lexicon('snippet_err_remove') . print_r($this->toArray(), true));
            }
        }

        return $removed;
    }
}
