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
 * Loads update user page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class SecurityUserUpdateManagerController extends modManagerController {
    /** @var string $onUserFormRender */
    public $onUserFormRender = '';
    /** @var array $extendedFields */
    public $extendedFields = array();
    /** @var array $remoteFields */
    public $remoteFields = array();
    /** @var modUser $user */
    public $user;

    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('edit_user');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addHtml('<script>
// <![CDATA[
MODx.onUserFormRender = "'.$this->onUserFormRender.'";
MODx.perm.set_sudo = '.($this->modx->hasPermission('set_sudo') ? 1 : 0).';
// ]]>
</script>');

        /* register JS scripts */
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.orm.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/core/modx.grid.settings.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.settings.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.grid.user.group.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/security/modx.panel.user.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/security/user/update.js');
        $this->addHtml('<script>
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-user-update"
        ,user: "'.$this->user->get('id').'"
        '.(!empty($this->remoteFields) ? ',remoteFields: '.$this->modx->toJSON($this->remoteFields) : '').'
        '.(!empty($this->extendedFields) ? ',extendedFields: '.$this->modx->toJSON($this->extendedFields) : '').'
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

        /* get user */
        if (empty($scriptProperties['id']) || strlen($scriptProperties['id']) !== strlen((integer)$scriptProperties['id'])) {
            return $this->failure($this->modx->lexicon('user_err_ns'));
        }
        $this->user = $this->modx->getObject('modUser', array('id' => $scriptProperties['id']));
        if ($this->user == null) return $this->failure($this->modx->lexicon('user_err_nf'));

        /* process remote data, if existent */
        $this->remoteFields = array();
        $remoteData = $this->user->get('remote_data');
        if (!empty($remoteData)) {
            $this->remoteFields = $this->_parseCustomData($remoteData);
        }

        /* parse extended data, if existent */
        $this->user->getOne('Profile');
        if ($this->user->Profile) {
            $this->extendedFields = array();
            $extendedData = $this->user->Profile->get('extended');
            if (!empty($extendedData)) {
                $this->extendedFields = $this->_parseCustomData($extendedData);
            }
        }

        /* invoke OnUserFormPrerender event */
        $onUserFormPrerender = $this->modx->invokeEvent('OnUserFormPrerender', array(
            'id' => $this->user->get('id'),
            'user' => &$this->user,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($onUserFormPrerender)) {
            $onUserFormPrerender = implode('',$onUserFormPrerender);
        }
        $placeholders['OnUserFormPrerender'] = $onUserFormPrerender;

        /* invoke OnUserFormRender event */
        $onUserFormRender = $this->modx->invokeEvent('OnUserFormRender', array(
            'id' => $this->user->get('id'),
            'user' => &$this->user,
            'mode' => modSystemEvent::MODE_UPD,
        ));
        if (is_array($onUserFormRender)) $onUserFormRender = implode('',$onUserFormRender);
        $this->onUserFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onUserFormRender);
        $placeholders['OnUserFormRender'] = $this->onUserFormRender;

        return $placeholders;
    }

    private function _parseCustomData(array $remoteData = array(),$path = '') {
        $usemb = function_exists('mb_strlen') && (boolean)$this->modx->getOption('use_multibyte',null,false);
        $encoding = $this->modx->getOption('modx_charset',null,'UTF-8');
        $fields = array();
        foreach ($remoteData as $key => $value) {
            $field = array(
                'name' => $key,
                'id' => (!empty($path) ? $path.'.' : '').$key,
            );
            if (is_array($value)) {
                $field['iconCls'] = 'icon-folder';
                $field['text'] = htmlentities($key,ENT_QUOTES,$encoding);
                $field['leaf'] = false;
                $field['children'] = $this->_parseCustomData($value,$key);
            } else {
                $v = $value;
                if ($usemb) {
                    if (mb_strlen($v, $encoding) > 30) {
                        $v = mb_substr($v,0,30,$encoding).'...';
                    }
                }
                elseif (strlen($v) > 30) {
                    $v = substr($v,0,30).'...';
                }
                $field['iconCls'] = 'icon-terminal';
                $field['text'] = htmlentities($key,ENT_QUOTES,$encoding).' - <i>'.htmlentities($v,ENT_QUOTES,$encoding).'</i>';
                $field['leaf'] = true;
                $field['value'] = $value;
            }
            $fields[] = $field;
        }
        return $fields;
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        if($this->user == null) {
                return $this->modx->lexicon('user_err_nf');
        } else {
                return $this->modx->lexicon('user').': '.$this->user->get('username');
        }
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/user/update.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return array('user','setting','access');
    }

    /**
     * Get the Help URL
     * @return string
     */
    public function getHelpUrl() {
        return 'Users';
    }
}
