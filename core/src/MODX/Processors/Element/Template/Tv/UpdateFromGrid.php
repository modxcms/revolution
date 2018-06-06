<?php

namespace MODX\Processors\Element\Template\Tv;

use MODX\Processors\modProcessor;

/**
 * Assigns or unassigns TVs to a template. Passed as JSON data.
 *
 * @param integer $id The ID of the TV.
 * @param string $name (optional) The name of the TV.
 * @param string $description (optional) The description of the TV.
 * @param integer $template The ID of the template.
 * @param integer $rank The rank of the TV for the template.
 * @param boolean $access If true, give the TV access to the template. Else,
 * remove access.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
class UpdateFromGrid extends modProcessor
{
    public function process()
    {
        $this->modx->lexicon->load('tv', 'category');
        if (!$this->modx->hasPermission('save_template')) {
            return $this->modx->error->failure($this->modx->lexicon('permission_denied'));
        }

        $_DATA = json_decode($this->getProperty('data'), true);

        if (empty($_DATA['id'])) {
            return $this->modx->error->failure($this->modx->lexicon('tv_err_ns'));
        }
        $tv = $this->modx->getObject('modTemplateVar', $_DATA['id']);
        if ($tv == null) {
            return $this->modx->error->failure($this->modx->lexicon('tv_err_nf'));
        }

        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate', [
            'templateid' => $_DATA['template'],
            'tmplvarid' => $_DATA['id'],
        ]);

        /* if adding to the template */
        if (empty($templateVarTemplate) && !empty($_DATA['access'])) {
            $templateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
            $templateVarTemplate->set('templateid', $_DATA['template']);
            $templateVarTemplate->set('tmplvarid', $_DATA['id']);
            $templateVarTemplate->set('rank', $_DATA['rank']);
            if ($templateVarTemplate->save() === false) {
                return $this->modx->error->failure($this->modx->lexicon('tvt_err_save'));
            }

            /* if removing */
        } elseif (!empty($templateVarTemplate) && $_DATA['access'] == false) {
            if ($templateVarTemplate->remove() === false) {
                return $this->modx->error->failure($this->modx->lexicon('tvt_err_remove'));
            }
            /* if reordering */
        } elseif (!empty($templateVarTemplate)) {
            $templateVarTemplate->set('rank', $_DATA['rank']);
            if ($templateVarTemplate->save() === false) {
                return $this->modx->error->failure($this->modx->lexicon('tvt_err_save'));
            }
        }

        if (!empty($_DATA['name'])) $tv->set('name', $_DATA['name']);
        if (isset($_DATA['description'])) $tv->set('description', $_DATA['description']);
        $tv->save();

        return $this->modx->error->success();
    }
}