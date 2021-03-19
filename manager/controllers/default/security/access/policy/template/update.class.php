<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modManagerController;
use xPDO\xPDOException;

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
    public $templateArray = [];

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
            $this->template = $this->modx->getObject(modAccessPolicyTemplate::class, ['id' => $this->scriptProperties['id']]);
        }
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     * @throws xPDOException
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.combo.access.policy.template.groups.js');
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
    public function process(array $scriptProperties = []) {
        if (empty($this->template)) return $this->failure($this->modx->lexicon('policy_template_err_nf'));

        $placeholders = [];

        /* get permissions */
        $this->templateArray = $this->template->toArray();
        $c = $this->modx->newQuery(modAccessPermission::class);
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
            $this->templateArray['permissions'][] = [
                $permission->get('name'),
                $permission->get('description'),
                $desc,
                $permission->get('value'),
            ];
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
        return ['user','access','policy','context'];
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'PolicyTemplates';
    }
}
