<?php
/**
 * Loads form customization set editing panel
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityFormsSetUpdateManagerController extends modManagerController {
    public $setArray = array();
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('customize_forms');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/fc/modx.fc.common.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/fc/modx.panel.fcset.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/fc/set/update.js');
        $this->addHtml('<script type="text/javascript">
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-fc-set-update"
                ,set: "'.$this->setArray['id'].'"
                ,record: '.$this->modx->toJSON($this->setArray).'
            });
        });
        // ]]>
        </script>');
    }

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = array()) {
        $placeholders = array();

        /* get profile */
        if (empty($scriptProperties['id'])) return $this->failure($this->modx->lexicon('set_err_ns'));
        $c = $this->modx->newQuery('modFormCustomizationSet');
        $c->leftJoin('modTemplate','Template');
        $c->select($this->modx->getSelectColumns('modFormCustomizationSet','modFormCustomizationSet'));
        $c->select(array(
            'Action.controller',
            'Template.templatename',
        ));
        $c->innerJoin('modAction','Action');
        $c->where(array(
            'id' => $scriptProperties['id'],
        ));
        /** @var modFormCustomizationSet $set */
        $set = $this->modx->getObject('modFormCustomizationSet',$c);
        if (empty($set)) return $this->failure($this->modx->lexicon('set_err_nfs',array('id' => $scriptProperties['id'])));
        
        $this->setArray = $set->toArray();
        $setData = $set->getData();

        /* format fields */
        $this->setArray['fields'] = array();
        if (!empty($setData['fields'])) {
            foreach ($setData['fields'] as $field) {
                $this->setArray['fields'][] = array(
                    $field['id'],
                    (int)$field['action'],
                    $field['name'],
                    $field['tab'],
                    (int)$field['tab_rank'],
                    $field['other'],
                    (int)$field['rank'],
                    (boolean)$field['visible'],
                    $field['label'],
                    $field['default_value'],
                );
            }
        }

        /* format tabs */
        $this->setArray['tabs'] = array();
        if (!empty($setData['tabs'])) {
            foreach ($setData['tabs'] as $tab) {
                $this->setArray['tabs'][] = array(
                    (int)$tab['id'],
                    (int)$tab['action'],
                    $tab['name'],
                    $tab['form'],
                    $tab['other'],
                    (int)$tab['rank'],
                    (boolean)$tab['visible'],
                    $tab['label'],
                    $tab['type'],
                    'core',
                );
            }
        }

        /* format tvs */
        $this->setArray['tvs'] = array();
        if (!empty($setData['tvs'])) {
            foreach ($setData['tvs'] as $tv) {
                $this->setArray['tvs'][] = array(
                    (int)$tv['id'],
                    $tv['name'],
                    $tv['tab'],
                    (int)$tv['rank'],
                    (boolean)$tv['visible'],
                    $tv['label'],
                    $tv['default_value'],
                    !empty($tv['category_name']) ? $tv['category_name'] : $this->modx->lexicon('none'),
                    htmlspecialchars($tv['default_text'],null,$this->modx->getOption('modx_charset',null,'UTF-8')),
                );
            }
        }

        if (empty($this->setArray['template'])) $this->setArray['template'] = 0;

        $placeholders['set'] = $this->setArray;

        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('form_customization');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/forms/set.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','formcustomization');
    }
}