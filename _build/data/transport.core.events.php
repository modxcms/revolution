<?php
/**
 *
 * @package modx
 */

$events = array();


/* Plugin Events */
$events['OnPluginEventBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginEventBeforeSave']->fromArray(array (
    'name' => 'OnPluginEventBeforeSave',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginEventSave']->fromArray(array (
    'name' => 'OnPluginEventSave',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginEventBeforeRemove']->fromArray(array (
    'name' => 'OnPluginEventBeforeRemove',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginEventRemove']->fromArray(array (
    'name' => 'OnPluginEventRemove',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);


/* Resource Groups */
$events['OnResourceGroupSave']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceGroupSave']->fromArray(array (
    'name' => 'OnResourceGroupSave',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceGroupBeforeSave']->fromArray(array (
    'name' => 'OnResourceGroupBeforeSave',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceGroupRemove']->fromArray(array (
    'name' => 'OnResourceGroupRemove',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceGroupBeforeRemove']->fromArray(array (
    'name' => 'OnResourceGroupBeforeRemove',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);


/* Snippets */
$events['OnSnippetBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnSnippetBeforeSave']->fromArray(array (
    'name' => 'OnSnippetBeforeSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetSave']= $xpdo->newObject('MODX\modEvent');
$events['OnSnippetSave']->fromArray(array (
    'name' => 'OnSnippetSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnSnippetBeforeRemove']->fromArray(array (
    'name' => 'OnSnippetBeforeRemove',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnSnippetRemove']->fromArray(array (
    'name' => 'OnSnippetRemove',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnSnipFormPrerender']->fromArray(array (
    'name' => 'OnSnipFormPrerender',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnSnipFormRender']->fromArray(array (
    'name' => 'OnSnipFormRender',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnBeforeSnipFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeSnipFormSave']->fromArray(array (
    'name' => 'OnBeforeSnipFormSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnSnipFormSave']->fromArray(array (
    'name' => 'OnSnipFormSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnBeforeSnipFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeSnipFormDelete']->fromArray(array (
    'name' => 'OnBeforeSnipFormDelete',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnSnipFormDelete']->fromArray(array (
    'name' => 'OnSnipFormDelete',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);


/* Templates */
$events['OnTemplateBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateBeforeSave']->fromArray(array (
    'name' => 'OnTemplateBeforeSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateSave']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateSave']->fromArray(array (
    'name' => 'OnTemplateSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateBeforeRemove']->fromArray(array (
    'name' => 'OnTemplateBeforeRemove',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateRemove']->fromArray(array (
    'name' => 'OnTemplateRemove',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnTempFormPrerender']->fromArray(array (
    'name' => 'OnTempFormPrerender',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnTempFormRender']->fromArray(array (
    'name' => 'OnTempFormRender',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnBeforeTempFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeTempFormSave']->fromArray(array (
    'name' => 'OnBeforeTempFormSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnTempFormSave']->fromArray(array (
    'name' => 'OnTempFormSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnBeforeTempFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeTempFormDelete']->fromArray(array (
    'name' => 'OnBeforeTempFormDelete',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnTempFormDelete']->fromArray(array (
    'name' => 'OnTempFormDelete',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);


/* Template Variables */
$events['OnTemplateVarBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateVarBeforeSave']->fromArray(array (
    'name' => 'OnTemplateVarBeforeSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarSave']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateVarSave']->fromArray(array (
    'name' => 'OnTemplateVarSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateVarBeforeRemove']->fromArray(array (
    'name' => 'OnTemplateVarBeforeRemove',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnTemplateVarRemove']->fromArray(array (
    'name' => 'OnTemplateVarRemove',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnTVFormPrerender']->fromArray(array (
    'name' => 'OnTVFormPrerender',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnTVFormRender']->fromArray(array (
    'name' => 'OnTVFormRender',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnBeforeTVFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeTVFormSave']->fromArray(array (
    'name' => 'OnBeforeTVFormSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnTVFormSave']->fromArray(array (
    'name' => 'OnTVFormSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnBeforeTVFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeTVFormDelete']->fromArray(array (
    'name' => 'OnBeforeTVFormDelete',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnTVFormDelete']->fromArray(array (
    'name' => 'OnTVFormDelete',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);


/* TV Renders */
$events['OnTVInputRenderList']= $xpdo->newObject('MODX\modEvent');
$events['OnTVInputRenderList']->fromArray(array (
    'name' => 'OnTVInputRenderList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVInputPropertiesList']= $xpdo->newObject('MODX\modEvent');
$events['OnTVInputPropertiesList']->fromArray(array (
    'name' => 'OnTVInputPropertiesList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVOutputRenderList']= $xpdo->newObject('MODX\modEvent');
$events['OnTVOutputRenderList']->fromArray(array (
    'name' => 'OnTVOutputRenderList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVOutputRenderPropertiesList']= $xpdo->newObject('MODX\modEvent');
$events['OnTVOutputRenderPropertiesList']->fromArray(array (
    'name' => 'OnTVOutputRenderPropertiesList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);


/* User Groups */
$events['OnUserGroupBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupBeforeSave']->fromArray(array (
    'name' => 'OnUserGroupBeforeSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupSave']->fromArray(array (
    'name' => 'OnUserGroupSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupBeforeRemove']->fromArray(array (
    'name' => 'OnUserGroupBeforeRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupRemove']->fromArray(array (
    'name' => 'OnUserGroupRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupBeforeFormSave']->fromArray(array (
    'name' => 'OnBeforeUserGroupFormSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupFormSave']->fromArray(array (
    'name' => 'OnUserGroupFormSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeFormRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupBeforeFormRemove']->fromArray(array (
    'name' => 'OnBeforeUserGroupFormRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupFormRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserGroupFormRemove']->fromArray(array (
    'name' => 'OnBeforeUserGroupFormRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);

/* User Profiles */
$events['OnUserProfileBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserProfileBeforeSave']->fromArray(array (
    'name' => 'OnUserProfileBeforeSave',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);
$events['OnUserProfileSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserProfileSave']->fromArray(array (
    'name' => 'OnUserProfileSave',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);
$events['OnUserProfileBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserProfileBeforeRemove']->fromArray(array (
    'name' => 'OnUserProfileBeforeRemove',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);
$events['OnUserProfileRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserProfileRemove']->fromArray(array (
    'name' => 'OnUserProfileRemove',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);

/* Resources */
$events['OnDocFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnDocFormPrerender']->fromArray(array (
    'name' => 'OnDocFormPrerender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnDocFormRender']->fromArray(array (
    'name' => 'OnDocFormRender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeDocFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeDocFormSave']->fromArray(array (
    'name' => 'OnBeforeDocFormSave',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnDocFormSave']->fromArray(array (
    'name' => 'OnDocFormSave',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeDocFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeDocFormDelete']->fromArray(array (
    'name' => 'OnBeforeDocFormDelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnDocFormDelete']->fromArray(array (
    'name' => 'OnDocFormDelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocPublished']= $xpdo->newObject('MODX\modEvent');
$events['OnDocPublished']->fromArray(array (
    'name' => 'OnDocPublished',
    'service' => 5,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocUnPublished']= $xpdo->newObject('MODX\modEvent');
$events['OnDocUnPublished']->fromArray(array (
    'name' => 'OnDocUnPublished',
    'service' => 5,
    'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeEmptyTrash']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeEmptyTrash']->fromArray(array (
    'name' => 'OnBeforeEmptyTrash',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnEmptyTrash']= $xpdo->newObject('MODX\modEvent');
$events['OnEmptyTrash']->fromArray(array (
    'name' => 'OnEmptyTrash',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceTVFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceTVFormPrerender']->fromArray(array (
    'name' => 'OnResourceTVFormPrerender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceTVFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceTVFormRender']->fromArray(array (
    'name' => 'OnResourceTVFormRender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceAutoPublish']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceAutoPublish']->fromArray(array (
    'name' => 'OnResourceAutoPublish',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceDelete']->fromArray(array (
    'name' => 'OnResourceDelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceUndelete']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceUndelete']->fromArray(array (
    'name' => 'OnResourceUndelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceBeforeSort']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceBeforeSort']->fromArray(array (
    'name' => 'OnResourceBeforeSort',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceSort']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceSort']->fromArray(array (
    'name' => 'OnResourceSort',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceDuplicate']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceDuplicate']->fromArray(array (
    'name' => 'OnResourceDuplicate',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceToolbarLoad']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceToolbarLoad']->fromArray(array (
    'name' => 'OnResourceToolbarLoad',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceRemoveFromResourceGroup']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceRemoveFromResourceGroup']->fromArray(array (
    'name' => 'OnResourceRemoveFromResourceGroup',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceAddToResourceGroup']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceAddToResourceGroup']->fromArray(array (
    'name' => 'OnResourceAddToResourceGroup',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceCacheUpdate']= $xpdo->newObject('MODX\modEvent');
$events['OnResourceCacheUpdate']->fromArray(array (
    'name' => 'OnResourceCacheUpdate',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);


/* Richtext Editor */
$events['OnRichTextEditorRegister']= $xpdo->newObject('MODX\modEvent');
$events['OnRichTextEditorRegister']->fromArray(array (
    'name' => 'OnRichTextEditorRegister',
    'service' => 1,
    'groupname' => 'RichText Editor',
), '', true, true);
$events['OnRichTextEditorInit']= $xpdo->newObject('MODX\modEvent');
$events['OnRichTextEditorInit']->fromArray(array (
    'name' => 'OnRichTextEditorInit',
    'service' => 1,
    'groupname' => 'RichText Editor',
), '', true, true);
$events['OnRichTextBrowserInit']= $xpdo->newObject('MODX\modEvent');
$events['OnRichTextBrowserInit']->fromArray(array (
    'name' => 'OnRichTextBrowserInit',
    'service' => 1,
    'groupname' => 'RichText Editor',
), '', true, true);


/* Security */
$events['OnWebLogin']= $xpdo->newObject('MODX\modEvent');
$events['OnWebLogin']->fromArray(array (
    'name' => 'OnWebLogin',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeWebLogout']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeWebLogout']->fromArray(array (
    'name' => 'OnBeforeWebLogout',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnWebLogout']= $xpdo->newObject('MODX\modEvent');
$events['OnWebLogout']->fromArray(array (
    'name' => 'OnWebLogout',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLogin']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerLogin']->fromArray(array (
    'name' => 'OnManagerLogin',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeManagerLogout']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeManagerLogout']->fromArray(array (
    'name' => 'OnBeforeManagerLogout',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLogout']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerLogout']->fromArray(array (
    'name' => 'OnManagerLogout',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeWebLogin']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeWebLogin']->fromArray(array (
    'name' => 'OnBeforeWebLogin',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnWebAuthentication']= $xpdo->newObject('MODX\modEvent');
$events['OnWebAuthentication']->fromArray(array (
    'name' => 'OnWebAuthentication',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeManagerLogin']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeManagerLogin']->fromArray(array (
    'name' => 'OnBeforeManagerLogin',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerAuthentication']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerAuthentication']->fromArray(array (
    'name' => 'OnManagerAuthentication',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLoginFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerLoginFormRender']->fromArray(array (
    'name' => 'OnManagerLoginFormRender',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLoginFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerLoginFormPrerender']->fromArray(array (
    'name' => 'OnManagerLoginFormPrerender',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnPageUnauthorized']= $xpdo->newObject('MODX\modEvent');
$events['OnPageUnauthorized']->fromArray(array (
    'name' => 'OnPageUnauthorized',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);


/* Users */
$events['OnUserFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnUserFormPrerender']->fromArray(array (
    'name' => 'OnUserFormPrerender',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnUserFormRender']->fromArray(array (
    'name' => 'OnUserFormRender',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeUserFormSave']->fromArray(array (
    'name' => 'OnBeforeUserFormSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserFormSave']->fromArray(array (
    'name' => 'OnUserFormSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeUserFormDelete']->fromArray(array (
    'name' => 'OnBeforeUserFormDelete',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnUserFormDelete']->fromArray(array (
    'name' => 'OnUserFormDelete',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserNotFound']= $xpdo->newObject('MODX\modEvent');
$events['OnUserNotFound']->fromArray(array (
    'name' => 'OnUserNotFound',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserActivate']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeUserActivate']->fromArray(array (
    'name' => 'OnBeforeUserActivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserActivate']= $xpdo->newObject('MODX\modEvent');
$events['OnUserActivate']->fromArray(array (
    'name' => 'OnUserActivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserDeactivate']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeUserDeactivate']->fromArray(array (
    'name' => 'OnBeforeUserDeactivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserDeactivate']= $xpdo->newObject('MODX\modEvent');
$events['OnUserDeactivate']->fromArray(array (
    'name' => 'OnUserDeactivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserDuplicate']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeUserDuplicate']->fromArray(array (
    'name' => 'OnBeforeUserDuplicate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserDuplicate']= $xpdo->newObject('MODX\modEvent');
$events['OnUserDuplicate']->fromArray(array (
    'name' => 'OnUserDuplicate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserChangePassword']= $xpdo->newObject('MODX\modEvent');
$events['OnUserChangePassword']->fromArray(array (
    'name' => 'OnUserChangePassword',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserBeforeRemove']->fromArray(array (
    'name' => 'OnUserBeforeRemove',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserBeforeSave']->fromArray(array (
    'name' => 'OnUserBeforeSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserSave']= $xpdo->newObject('MODX\modEvent');
$events['OnUserSave']->fromArray(array (
    'name' => 'OnUserSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnUserRemove']->fromArray(array (
    'name' => 'OnUserRemove',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeAddToGroup']= $xpdo->newObject('MODX\modEvent');
$events['OnUserBeforeAddToGroup']->fromArray(array (
    'name' => 'OnUserBeforeAddToGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserAddToGroup']= $xpdo->newObject('MODX\modEvent');
$events['OnUserAddToGroup']->fromArray(array (
    'name' => 'OnUserAddToGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserBeforeRemoveFromGroup']= $xpdo->newObject('MODX\modEvent');
$events['OnUserBeforeRemoveFromGroup']->fromArray(array (
    'name' => 'OnUserBeforeRemoveFromGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserRemoveFromGroup']= $xpdo->newObject('MODX\modEvent');
$events['OnUserRemoveFromGroup']->fromArray(array (
    'name' => 'OnUserRemoveFromGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);


/* System */
$events['OnWebPagePrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnWebPagePrerender']->fromArray(array (
    'name' => 'OnWebPagePrerender',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnBeforeCacheUpdate']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeCacheUpdate']->fromArray(array (
    'name' => 'OnBeforeCacheUpdate',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnCacheUpdate']= $xpdo->newObject('MODX\modEvent');
$events['OnCacheUpdate']->fromArray(array (
    'name' => 'OnCacheUpdate',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnLoadWebPageCache']= $xpdo->newObject('MODX\modEvent');
$events['OnLoadWebPageCache']->fromArray(array (
    'name' => 'OnLoadWebPageCache',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnBeforeSaveWebPageCache']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeSaveWebPageCache']->fromArray(array (
    'name' => 'OnBeforeSaveWebPageCache',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnSiteRefresh']= $xpdo->newObject('MODX\modEvent');
$events['OnSiteRefresh']->fromArray(array (
    'name' => 'OnSiteRefresh',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerDirCreate']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerDirCreate']->fromArray(array (
    'name' => 'OnFileManagerDirCreate',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerDirRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerDirRemove']->fromArray(array (
    'name' => 'OnFileManagerDirRemove',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerDirRename']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerDirRename']->fromArray(array (
    'name' => 'OnFileManagerDirRename',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileRename']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerFileRename']->fromArray(array (
    'name' => 'OnFileManagerFileRename',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerFileRemove']->fromArray(array (
    'name' => 'OnFileManagerFileRemove',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileUpdate']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerFileUpdate']->fromArray(array (
    'name' => 'OnFileManagerFileUpdate',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileCreate']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerFileCreate']->fromArray(array (
    'name' => 'OnFileManagerFileCreate',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerBeforeUpload']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerBeforeUpload']->fromArray(array (
    'name' => 'OnFileManagerBeforeUpload',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerUpload']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerUpload']->fromArray(array (
    'name' => 'OnFileManagerUpload',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerMoveObject']= $xpdo->newObject('MODX\modEvent');
$events['OnFileManagerMoveObject']->fromArray(array (
    'name' => 'OnFileManagerMoveObject',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileCreateFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnFileCreateFormPrerender']->fromArray(array (
    'name' => 'OnFileCreateFormPrerender',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileEditFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnFileEditFormPrerender']->fromArray(array (
    'name' => 'OnFileEditFormPrerender',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnManagerPageInit']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerPageInit']->fromArray(array (
    'name' => 'OnManagerPageInit',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnManagerPageBeforeRender']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerPageBeforeRender']->fromArray(array (
    'name' => 'OnManagerPageBeforeRender',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnManagerPageAfterRender']= $xpdo->newObject('MODX\modEvent');
$events['OnManagerPageAfterRender']->fromArray(array (
    'name' => 'OnManagerPageAfterRender',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnWebPageInit']= $xpdo->newObject('MODX\modEvent');
$events['OnWebPageInit']->fromArray(array (
    'name' => 'OnWebPageInit',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnLoadWebDocument']= $xpdo->newObject('MODX\modEvent');
$events['OnLoadWebDocument']->fromArray(array (
    'name' => 'OnLoadWebDocument',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnParseDocument']= $xpdo->newObject('MODX\modEvent');
$events['OnParseDocument']->fromArray(array (
    'name' => 'OnParseDocument',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnWebPageComplete']= $xpdo->newObject('MODX\modEvent');
$events['OnWebPageComplete']->fromArray(array (
    'name' => 'OnWebPageComplete',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnBeforeManagerPageInit']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeManagerPageInit']->fromArray(array (
    'name' => 'OnBeforeManagerPageInit',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnPageNotFound']= $xpdo->newObject('MODX\modEvent');
$events['OnPageNotFound']->fromArray(array (
    'name' => 'OnPageNotFound',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnHandleRequest']= $xpdo->newObject('MODX\modEvent');
$events['OnHandleRequest']->fromArray(array (
    'name' => 'OnHandleRequest',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnMODXInit']= $xpdo->newObject('MODX\modEvent');
$events['OnMODXInit']->fromArray(array (
    'name' => 'OnMODXInit',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnElementNotFound']= $xpdo->newObject('MODX\modEvent');
$events['OnElementNotFound']->fromArray(array (
    'name' => 'OnElementNotFound',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);


/* Settings */
$events['OnSiteSettingsRender']= $xpdo->newObject('MODX\modEvent');
$events['OnSiteSettingsRender']->fromArray(array (
    'name' => 'OnSiteSettingsRender',
    'service' => 1,
    'groupname' => 'Settings',
), '', true, true);


/* Internationalization */
$events['OnInitCulture']= $xpdo->newObject('MODX\modEvent');
$events['OnInitCulture']->fromArray(array (
    'name' => 'OnInitCulture',
    'service' => 1,
    'groupname' => 'Internationalization',
), '', true, true);


/* Categories */
$events['OnCategorySave']= $xpdo->newObject('MODX\modEvent');
$events['OnCategorySave']->fromArray(array (
    'name' => 'OnCategorySave',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnCategoryBeforeSave']->fromArray(array (
    'name' => 'OnCategoryBeforeSave',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnCategoryRemove']->fromArray(array (
    'name' => 'OnCategoryRemove',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnCategoryBeforeRemove']->fromArray(array (
    'name' => 'OnCategoryBeforeRemove',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);


/* Chunks */
$events['OnChunkSave']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkSave']->fromArray(array (
    'name' => 'OnChunkSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkBeforeSave']->fromArray(array (
    'name' => 'OnChunkBeforeSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkRemove']->fromArray(array (
    'name' => 'OnChunkRemove',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkBeforeRemove']->fromArray(array (
    'name' => 'OnChunkBeforeRemove',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkFormPrerender']->fromArray(array (
    'name' => 'OnChunkFormPrerender',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkFormRender']->fromArray(array (
    'name' => 'OnChunkFormRender',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnBeforeChunkFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeChunkFormSave']->fromArray(array (
    'name' => 'OnBeforeChunkFormSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkFormSave']->fromArray(array (
    'name' => 'OnChunkFormSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnBeforeChunkFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforeChunkFormDelete']->fromArray(array (
    'name' => 'OnBeforeChunkFormDelete',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnChunkFormDelete']->fromArray(array (
    'name' => 'OnChunkFormDelete',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);


/* Contexts */
$events['OnContextSave']= $xpdo->newObject('MODX\modEvent');
$events['OnContextSave']->fromArray(array (
    'name' => 'OnContextSave',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnContextBeforeSave']->fromArray(array (
    'name' => 'OnContextBeforeSave',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnContextRemove']->fromArray(array (
    'name' => 'OnContextRemove',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnContextBeforeRemove']->fromArray(array (
    'name' => 'OnContextBeforeRemove',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnContextFormPrerender']->fromArray(array (
    'name' => 'OnContextFormPrerender',
    'service' => 2,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnContextFormRender']->fromArray(array (
    'name' => 'OnContextFormRender',
    'service' => 2,
    'groupname' => 'Contexts',
), '', true, true);


/* Plugins */
$events['OnPluginSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginSave']->fromArray(array (
    'name' => 'OnPluginSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginBeforeSave']->fromArray(array (
    'name' => 'OnPluginBeforeSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginRemove']->fromArray(array (
    'name' => 'OnPluginRemove',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginBeforeRemove']->fromArray(array (
    'name' => 'OnPluginBeforeRemove',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormPrerender']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginFormPrerender']->fromArray(array (
    'name' => 'OnPluginFormPrerender',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormRender']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginFormRender']->fromArray(array (
    'name' => 'OnPluginFormRender',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnBeforePluginFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforePluginFormSave']->fromArray(array (
    'name' => 'OnBeforePluginFormSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginFormSave']->fromArray(array (
    'name' => 'OnPluginFormSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnBeforePluginFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnBeforePluginFormDelete']->fromArray(array (
    'name' => 'OnBeforePluginFormDelete',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnPluginFormDelete']->fromArray(array (
    'name' => 'OnPluginFormDelete',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);


/* Property Sets */
$events['OnPropertySetSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPropertySetSave']->fromArray(array (
    'name' => 'OnPropertySetSave',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetBeforeSave']= $xpdo->newObject('MODX\modEvent');
$events['OnPropertySetBeforeSave']->fromArray(array (
    'name' => 'OnPropertySetBeforeSave',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPropertySetRemove']->fromArray(array (
    'name' => 'OnPropertySetRemove',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetBeforeRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPropertySetBeforeRemove']->fromArray(array (
    'name' => 'OnPropertySetBeforeRemove',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);


/* Media Source */
$events['OnMediaSourceBeforeFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnMediaSourceBeforeFormDelete']->fromArray(array (
    'name' => 'OnMediaSourceBeforeFormDelete',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceBeforeFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnMediaSourceBeforeFormSave']->fromArray(array (
    'name' => 'OnMediaSourceBeforeFormSave',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceGetProperties']= $xpdo->newObject('MODX\modEvent');
$events['OnMediaSourceGetProperties']->fromArray(array (
    'name' => 'OnMediaSourceGetProperties',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceFormDelete']= $xpdo->newObject('MODX\modEvent');
$events['OnMediaSourceFormDelete']->fromArray(array (
    'name' => 'OnMediaSourceFormDelete',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceFormSave']= $xpdo->newObject('MODX\modEvent');
$events['OnMediaSourceFormSave']->fromArray(array (
    'name' => 'OnMediaSourceFormSave',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceDuplicate']= $xpdo->newObject('MODX\modEvent');
$events['OnMediaSourceDuplicate']->fromArray(array (
    'name' => 'OnMediaSourceDuplicate',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);

/* Package Manager */
$events['OnPackageInstall']= $xpdo->newObject('MODX\modEvent');
$events['OnPackageInstall']->fromArray(array (
  'name' => 'OnPackageInstall',
  'service' => 2,
  'groupname' => 'Package Manager',
), '', true, true);
$events['OnPackageUninstall']= $xpdo->newObject('MODX\modEvent');
$events['OnPackageUninstall']->fromArray(array (
  'name' => 'OnPackageUninstall',
  'service' => 2,
  'groupname' => 'Package Manager',
), '', true, true);
$events['OnPackageRemove']= $xpdo->newObject('MODX\modEvent');
$events['OnPackageRemove']->fromArray(array (
  'name' => 'OnPackageRemove',
  'service' => 2,
  'groupname' => 'Package Manager',
), '', true, true);

return $events;
