<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Loads the policy template page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityAccessPolicyTemplateUpdateManagerController extends modManagerController {
    /** @var modAccessPolicyTemplate $template */
    public $template;
    /** @var array $templateArray */
    public $templateArray = array();

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('policy_template_edit');
    }

    /**
     * Get the current policy template
     * @return void
     */
    public function initialize() {
        if (!empty($this->scriptProperties['id']) && strlen($this->scriptProperties['id']) === strlen((integer)$this->scriptProperties['id'])) {
            $this->template = $this->modx->getObject('modAccessPolicyTemplate', array('id' => $this->scriptProperties['id']));
        }
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.access.policy.template.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/access/policy/template/update.js');
        $this->addHtml('
        <script>
        // <![CDATA[
        Ext.onReady(function() {
            MODx.load({
                xtype: "modx-page-access-policy-template"
                ,template: "'.$this->templateArray['id'].'"
                ,record: '.$this->modx->toJSON($this->templateArray).'
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
        if (empty($this->template)) return $this->failure($this->modx->lexicon('policy_template_err_nf'));

        $placeholders = array();

        /* get permissions */
        $this->templateArray = $this->template->toArray();
        $c = $this->modx->newQuery('modAccessPermission');
        $c->sortby('name','ASC');
        $permissions = $this->template->getMany('Permissions',$c);
        /** @var modAccessPermission $permission */
        foreach ($permissions as $permission) {
            $desc = $permission->get('description');
            if (!empty($this->templateArray['lexicon'])) {
                if (strpos($this->templateArray['lexicon'],':') !== false) {
                    $this->modx->lexicon->load($this->templateArray['lexicon']);
                } else {
                    $this->modx->lexicon->load('core:'.$this->templateArray['lexicon']);
                }
                $desc = $this->modx->lexicon($desc);
            }
            $this->templateArray['permissions'][] = array(
                $permission->get('name'),
                $permission->get('description'),
                $desc,
                $permission->get('value'),
            );
        }

        $placeholders['template'] = $this->templateArray;

        return $placeholders;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('policy_template').': '.$this->templateArray['name'];
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return '';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','access','policy','context');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'PolicyTemplates';
    }
}
