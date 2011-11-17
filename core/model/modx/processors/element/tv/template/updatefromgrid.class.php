<?php
/**
 * Assigns or unassigns a template to a TV. Passed in JSON data.
 *
 * @param integer $id The ID of the template
 * @param integer $tv The ID of the tv
 * @param integer $rank The rank of the tv-template relationship
 * @param boolean $access If true, the TV has access to the template.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
class modElementTvTemplateUpdateFromGrid extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('save_tv');
    }
    public function getLanguageTopics() {
        return array('tv');
    }
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        return true;
    }

    public function process() {
        $fields = $this->getProperties();

        if (isset($fields['rank']) && empty($fields['rank'])) $fields['rank'] = 0;

        if (!empty($fields['access'])) {
            $templateVarTemplate = $this->addAccess($fields);
        } else {
            $templateVarTemplate = $this->removeAccess($fields);
        }

        return is_object($templateVarTemplate) && $templateVarTemplate instanceof modTemplateVarTemplate
            ? $this->success('',$templateVarTemplate)
            : $this->failure($templateVarTemplate);
    }

    /**
     * For adding access or updating rank
     *
     * @param array $fields
     * @return modTemplateVarTemplate|string
     */
    public function addAccess(array $fields) {
        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate',array(
            'templateid' => $fields['id'],
            'tmplvarid' => $fields['tv'],
        ));
        /** @var modTemplateVarTemplate $templateVarTemplate */
        if (empty($templateVarTemplate)) {
            $templateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
        }
        $templateVarTemplate->set('templateid',$fields['id']);
        $templateVarTemplate->set('tmplvarid',$fields['tv']);

        if ($templateVarTemplate->save() == false) {
            return $this->failure($this->modx->lexicon('tvt_err_save'));
        }
        return $templateVarTemplate;
    }

    /**
     * For removing access
     *
     * @param array $fields
     * @return modTemplateVarTemplate|string
     */
    public function removeAccess(array $fields) {
        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate',array(
            'templateid' => $fields['id'],
            'tmplvarid' => $fields['tv'],
        ));
        if (empty($templateVarTemplate)) {
            return $this->modx->lexicon('tvt_err_nf');
        }

        if ($templateVarTemplate->remove() == false) {
            return $this->modx->lexicon('tvt_err_remove');
        }
        return $templateVarTemplate;
    }
}
return 'modElementTvTemplateUpdateFromGrid';