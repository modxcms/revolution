<?php
/**
 * Create a new MODx Revolution repository.
 *
 * @package setup
 */

$results= array ();
$classes= array (
    'modAccessAction',
    'modAccessActionDom',
    'modAccessContext',
    'modAccessElement',
    'modAccessMenu',
    'modAccessPermission',
    'modAccessPolicy',
    'modAccessResource',
    'modAccessResourceGroup',
    'modAccessTemplateVar',
    'modAction',
    'modActionDom',
    'modActiveUser',
    'modCategory',
    'modChunk',
    'modContentType',
    'modContext',
    'modContextResource',
    'modContextSetting',
    'modElementPropertySet',
    'modEvent',
    'modEventLog',
    'modKeyword',
    'modLexiconEntry',
    'modManagerLog',
    'modMenu',
    'modMetatag',
    'modNamespace',
    'modPlugin',
    'modPluginEvent',
    'modPropertySet',
    'modResource',
    'modResourceGroup',
    'modResourceGroupResource',
    'modResourceKeyword',
    'modResourceMetatag',
    'modSession',
    'modSnippet',
    'modSystemSetting',
    'modTemplate',
    'modTemplateVar',
    'modTemplateVarResource',
    'modTemplateVarResourceGroup',
    'modTemplateVarTemplate',
    'modUser',
    'modUserProfile',
    'modUserGroup',
    'modUserGroupMember',
    'modUserGroupRole',
    'modUserMessage',
    'modUserRole',
    'modUserSetting',
    'modWorkspace',
    'registry.db.modDbRegisterMessage',
    'registry.db.modDbRegisterTopic',
    'registry.db.modDbRegisterQueue',
    'transport.modTransportPackage',
    'transport.modTransportProvider',
);

$this->xpdo->getManager();
$connected= $this->xpdo->connect();
$created= false;
if (!$connected) {
    $dsnArray= xPDO :: parseDSN($this->xpdo->getOption('dsn'));
    $containerOptions['charset']= $install->settings->get('database_charset', 'utf8');
    $containerOptions['collation']= $install->settings->get('database_collation', 'utf8_general_ci');
    $created= $this->xpdo->manager->createSourceContainer($dsnArray, $this->xpdo->config['username'], $this->xpdo->config['password'], $containerOptions);
    if (!$created) {
        $results[]= array ('class' => 'failed', 'msg' => '<p class="notok">'.$this->lexicon['db_err_create'].'</p>');
    }
    else {
        $connected= $this->xpdo->connect();
    }
    if ($connected) {
        $results[]= array ('class' => 'success', 'msg' => '<p class="ok">'.$this->lexicon['db_created'].'</p>');
    }
}
if ($connected) {
    ob_start();
    $this->xpdo->loadClass('modAccess');
    $this->xpdo->loadClass('modAccessibleObject');
    $this->xpdo->loadClass('modAccessibleSimpleObject');
    $this->xpdo->loadClass('modResource');
    $this->xpdo->loadClass('modElement');
    $this->xpdo->loadClass('modScript');
    $this->xpdo->loadClass('modPrincipal');
    $this->xpdo->loadClass('modUser');
    foreach ($classes as $class) {
        if (!$dbcreated= $this->xpdo->manager->createObjectContainer($class)) {
            $results[]= array ('class' => 'failed', 'msg' => '<p class="notok">' . sprintf($this->lexicon['table_err_create'],$class) . '</p>');
        } else {
            $results[]= array ('class' => 'success', 'msg' => '<p class="ok">' . sprintf($this->lexicon['table_created'],$class) . '</p>');
        }
    }
    ob_end_clean();
}
return $results;