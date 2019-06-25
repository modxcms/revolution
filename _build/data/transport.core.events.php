<?php
/**
 *
 * @package modx
 */

use MODX\Revolution\modEvent;

$events = array();


/* Plugin Events */
$events['OnPluginEventBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnPluginEventBeforeSave']->fromArray(array (
    'name' => 'OnPluginEventBeforeSave',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventSave']= $xpdo->newObject(modEvent::class);
$events['OnPluginEventSave']->fromArray(array (
    'name' => 'OnPluginEventSave',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnPluginEventBeforeRemove']->fromArray(array (
    'name' => 'OnPluginEventBeforeRemove',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventRemove']= $xpdo->newObject(modEvent::class);
$events['OnPluginEventRemove']->fromArray(array (
    'name' => 'OnPluginEventRemove',
    'service' => 1,
    'groupname' => 'Plugin Events',
), '', true, true);


/* Resource Groups */
$events['OnResourceGroupSave']= $xpdo->newObject(modEvent::class);
$events['OnResourceGroupSave']->fromArray(array (
    'name' => 'OnResourceGroupSave',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnResourceGroupBeforeSave']->fromArray(array (
    'name' => 'OnResourceGroupBeforeSave',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupRemove']= $xpdo->newObject(modEvent::class);
$events['OnResourceGroupRemove']->fromArray(array (
    'name' => 'OnResourceGroupRemove',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnResourceGroupBeforeRemove']->fromArray(array (
    'name' => 'OnResourceGroupBeforeRemove',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);


/* Snippets */
$events['OnSnippetBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnSnippetBeforeSave']->fromArray(array (
    'name' => 'OnSnippetBeforeSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetSave']= $xpdo->newObject(modEvent::class);
$events['OnSnippetSave']->fromArray(array (
    'name' => 'OnSnippetSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnSnippetBeforeRemove']->fromArray(array (
    'name' => 'OnSnippetBeforeRemove',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetRemove']= $xpdo->newObject(modEvent::class);
$events['OnSnippetRemove']->fromArray(array (
    'name' => 'OnSnippetRemove',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnSnipFormPrerender']->fromArray(array (
    'name' => 'OnSnipFormPrerender',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormRender']= $xpdo->newObject(modEvent::class);
$events['OnSnipFormRender']->fromArray(array (
    'name' => 'OnSnipFormRender',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnBeforeSnipFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforeSnipFormSave']->fromArray(array (
    'name' => 'OnBeforeSnipFormSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormSave']= $xpdo->newObject(modEvent::class);
$events['OnSnipFormSave']->fromArray(array (
    'name' => 'OnSnipFormSave',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnBeforeSnipFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforeSnipFormDelete']->fromArray(array (
    'name' => 'OnBeforeSnipFormDelete',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnSnipFormDelete']->fromArray(array (
    'name' => 'OnSnipFormDelete',
    'service' => 1,
    'groupname' => 'Snippets',
), '', true, true);


/* Templates */
$events['OnTemplateBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnTemplateBeforeSave']->fromArray(array (
    'name' => 'OnTemplateBeforeSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateSave']= $xpdo->newObject(modEvent::class);
$events['OnTemplateSave']->fromArray(array (
    'name' => 'OnTemplateSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnTemplateBeforeRemove']->fromArray(array (
    'name' => 'OnTemplateBeforeRemove',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateRemove']= $xpdo->newObject(modEvent::class);
$events['OnTemplateRemove']->fromArray(array (
    'name' => 'OnTemplateRemove',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnTempFormPrerender']->fromArray(array (
    'name' => 'OnTempFormPrerender',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormRender']= $xpdo->newObject(modEvent::class);
$events['OnTempFormRender']->fromArray(array (
    'name' => 'OnTempFormRender',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnBeforeTempFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforeTempFormSave']->fromArray(array (
    'name' => 'OnBeforeTempFormSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormSave']= $xpdo->newObject(modEvent::class);
$events['OnTempFormSave']->fromArray(array (
    'name' => 'OnTempFormSave',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnBeforeTempFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforeTempFormDelete']->fromArray(array (
    'name' => 'OnBeforeTempFormDelete',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnTempFormDelete']->fromArray(array (
    'name' => 'OnTempFormDelete',
    'service' => 1,
    'groupname' => 'Templates',
), '', true, true);


/* Template Variables */
$events['OnTemplateVarBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnTemplateVarBeforeSave']->fromArray(array (
    'name' => 'OnTemplateVarBeforeSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarSave']= $xpdo->newObject(modEvent::class);
$events['OnTemplateVarSave']->fromArray(array (
    'name' => 'OnTemplateVarSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnTemplateVarBeforeRemove']->fromArray(array (
    'name' => 'OnTemplateVarBeforeRemove',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarRemove']= $xpdo->newObject(modEvent::class);
$events['OnTemplateVarRemove']->fromArray(array (
    'name' => 'OnTemplateVarRemove',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnTVFormPrerender']->fromArray(array (
    'name' => 'OnTVFormPrerender',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormRender']= $xpdo->newObject(modEvent::class);
$events['OnTVFormRender']->fromArray(array (
    'name' => 'OnTVFormRender',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnBeforeTVFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforeTVFormSave']->fromArray(array (
    'name' => 'OnBeforeTVFormSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormSave']= $xpdo->newObject(modEvent::class);
$events['OnTVFormSave']->fromArray(array (
    'name' => 'OnTVFormSave',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnBeforeTVFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforeTVFormDelete']->fromArray(array (
    'name' => 'OnBeforeTVFormDelete',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnTVFormDelete']->fromArray(array (
    'name' => 'OnTVFormDelete',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);


/* TV Renders */
$events['OnTVInputRenderList']= $xpdo->newObject(modEvent::class);
$events['OnTVInputRenderList']->fromArray(array (
    'name' => 'OnTVInputRenderList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVInputPropertiesList']= $xpdo->newObject(modEvent::class);
$events['OnTVInputPropertiesList']->fromArray(array (
    'name' => 'OnTVInputPropertiesList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVOutputRenderList']= $xpdo->newObject(modEvent::class);
$events['OnTVOutputRenderList']->fromArray(array (
    'name' => 'OnTVOutputRenderList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVOutputRenderPropertiesList']= $xpdo->newObject(modEvent::class);
$events['OnTVOutputRenderPropertiesList']->fromArray(array (
    'name' => 'OnTVOutputRenderPropertiesList',
    'service' => 1,
    'groupname' => 'Template Variables',
), '', true, true);


/* User Groups */
$events['OnUserGroupBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupBeforeSave']->fromArray(array (
    'name' => 'OnUserGroupBeforeSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupSave']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupSave']->fromArray(array (
    'name' => 'OnUserGroupSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupBeforeRemove']->fromArray(array (
    'name' => 'OnUserGroupBeforeRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupRemove']->fromArray(array (
    'name' => 'OnUserGroupRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeFormSave']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupBeforeFormSave']->fromArray(array (
    'name' => 'OnBeforeUserGroupFormSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupFormSave']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupFormSave']->fromArray(array (
    'name' => 'OnUserGroupFormSave',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeFormRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupBeforeFormRemove']->fromArray(array (
    'name' => 'OnBeforeUserGroupFormRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupFormRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserGroupFormRemove']->fromArray(array (
    'name' => 'OnBeforeUserGroupFormRemove',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);

/* User Profiles */
$events['OnUserProfileBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnUserProfileBeforeSave']->fromArray(array (
    'name' => 'OnUserProfileBeforeSave',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);
$events['OnUserProfileSave']= $xpdo->newObject(modEvent::class);
$events['OnUserProfileSave']->fromArray(array (
    'name' => 'OnUserProfileSave',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);
$events['OnUserProfileBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserProfileBeforeRemove']->fromArray(array (
    'name' => 'OnUserProfileBeforeRemove',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);
$events['OnUserProfileRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserProfileRemove']->fromArray(array (
    'name' => 'OnUserProfileRemove',
    'service' => 1,
    'groupname' => 'User Profiles',
), '', true, true);

/* Resources */
$events['OnDocFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnDocFormPrerender']->fromArray(array (
    'name' => 'OnDocFormPrerender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormRender']= $xpdo->newObject(modEvent::class);
$events['OnDocFormRender']->fromArray(array (
    'name' => 'OnDocFormRender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeDocFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforeDocFormSave']->fromArray(array (
    'name' => 'OnBeforeDocFormSave',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormSave']= $xpdo->newObject(modEvent::class);
$events['OnDocFormSave']->fromArray(array (
    'name' => 'OnDocFormSave',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeDocFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforeDocFormDelete']->fromArray(array (
    'name' => 'OnBeforeDocFormDelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnDocFormDelete']->fromArray(array (
    'name' => 'OnDocFormDelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocPublished']= $xpdo->newObject(modEvent::class);
$events['OnDocPublished']->fromArray(array (
    'name' => 'OnDocPublished',
    'service' => 5,
    'groupname' => 'Resources',
), '', true, true);
$events['OnDocUnPublished']= $xpdo->newObject(modEvent::class);
$events['OnDocUnPublished']->fromArray(array (
    'name' => 'OnDocUnPublished',
    'service' => 5,
    'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeEmptyTrash']= $xpdo->newObject(modEvent::class);
$events['OnBeforeEmptyTrash']->fromArray(array (
    'name' => 'OnBeforeEmptyTrash',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnEmptyTrash']= $xpdo->newObject(modEvent::class);
$events['OnEmptyTrash']->fromArray(array (
    'name' => 'OnEmptyTrash',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceTVFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnResourceTVFormPrerender']->fromArray(array (
    'name' => 'OnResourceTVFormPrerender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceTVFormRender']= $xpdo->newObject(modEvent::class);
$events['OnResourceTVFormRender']->fromArray(array (
    'name' => 'OnResourceTVFormRender',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceAutoPublish']= $xpdo->newObject(modEvent::class);
$events['OnResourceAutoPublish']->fromArray(array (
    'name' => 'OnResourceAutoPublish',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceDelete']= $xpdo->newObject(modEvent::class);
$events['OnResourceDelete']->fromArray(array (
    'name' => 'OnResourceDelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceUndelete']= $xpdo->newObject(modEvent::class);
$events['OnResourceUndelete']->fromArray(array (
    'name' => 'OnResourceUndelete',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceBeforeSort']= $xpdo->newObject(modEvent::class);
$events['OnResourceBeforeSort']->fromArray(array (
    'name' => 'OnResourceBeforeSort',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceSort']= $xpdo->newObject(modEvent::class);
$events['OnResourceSort']->fromArray(array (
    'name' => 'OnResourceSort',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceDuplicate']= $xpdo->newObject(modEvent::class);
$events['OnResourceDuplicate']->fromArray(array (
    'name' => 'OnResourceDuplicate',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceToolbarLoad']= $xpdo->newObject(modEvent::class);
$events['OnResourceToolbarLoad']->fromArray(array (
    'name' => 'OnResourceToolbarLoad',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceRemoveFromResourceGroup']= $xpdo->newObject(modEvent::class);
$events['OnResourceRemoveFromResourceGroup']->fromArray(array (
    'name' => 'OnResourceRemoveFromResourceGroup',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceAddToResourceGroup']= $xpdo->newObject(modEvent::class);
$events['OnResourceAddToResourceGroup']->fromArray(array (
    'name' => 'OnResourceAddToResourceGroup',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);
$events['OnResourceCacheUpdate']= $xpdo->newObject(modEvent::class);
$events['OnResourceCacheUpdate']->fromArray(array (
    'name' => 'OnResourceCacheUpdate',
    'service' => 1,
    'groupname' => 'Resources',
), '', true, true);


/* Richtext Editor */
$events['OnRichTextEditorRegister']= $xpdo->newObject(modEvent::class);
$events['OnRichTextEditorRegister']->fromArray(array (
    'name' => 'OnRichTextEditorRegister',
    'service' => 1,
    'groupname' => 'RichText Editor',
), '', true, true);
$events['OnRichTextEditorInit']= $xpdo->newObject(modEvent::class);
$events['OnRichTextEditorInit']->fromArray(array (
    'name' => 'OnRichTextEditorInit',
    'service' => 1,
    'groupname' => 'RichText Editor',
), '', true, true);
$events['OnRichTextBrowserInit']= $xpdo->newObject(modEvent::class);
$events['OnRichTextBrowserInit']->fromArray(array (
    'name' => 'OnRichTextBrowserInit',
    'service' => 1,
    'groupname' => 'RichText Editor',
), '', true, true);


/* Security */
$events['OnWebLogin']= $xpdo->newObject(modEvent::class);
$events['OnWebLogin']->fromArray(array (
    'name' => 'OnWebLogin',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeWebLogout']= $xpdo->newObject(modEvent::class);
$events['OnBeforeWebLogout']->fromArray(array (
    'name' => 'OnBeforeWebLogout',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnWebLogout']= $xpdo->newObject(modEvent::class);
$events['OnWebLogout']->fromArray(array (
    'name' => 'OnWebLogout',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLogin']= $xpdo->newObject(modEvent::class);
$events['OnManagerLogin']->fromArray(array (
    'name' => 'OnManagerLogin',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeManagerLogout']= $xpdo->newObject(modEvent::class);
$events['OnBeforeManagerLogout']->fromArray(array (
    'name' => 'OnBeforeManagerLogout',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLogout']= $xpdo->newObject(modEvent::class);
$events['OnManagerLogout']->fromArray(array (
    'name' => 'OnManagerLogout',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeWebLogin']= $xpdo->newObject(modEvent::class);
$events['OnBeforeWebLogin']->fromArray(array (
    'name' => 'OnBeforeWebLogin',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnWebAuthentication']= $xpdo->newObject(modEvent::class);
$events['OnWebAuthentication']->fromArray(array (
    'name' => 'OnWebAuthentication',
    'service' => 3,
    'groupname' => 'Security',
), '', true, true);
$events['OnBeforeManagerLogin']= $xpdo->newObject(modEvent::class);
$events['OnBeforeManagerLogin']->fromArray(array (
    'name' => 'OnBeforeManagerLogin',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerAuthentication']= $xpdo->newObject(modEvent::class);
$events['OnManagerAuthentication']->fromArray(array (
    'name' => 'OnManagerAuthentication',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLoginFormRender']= $xpdo->newObject(modEvent::class);
$events['OnManagerLoginFormRender']->fromArray(array (
    'name' => 'OnManagerLoginFormRender',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnManagerLoginFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnManagerLoginFormPrerender']->fromArray(array (
    'name' => 'OnManagerLoginFormPrerender',
    'service' => 2,
    'groupname' => 'Security',
), '', true, true);
$events['OnPageUnauthorized']= $xpdo->newObject(modEvent::class);
$events['OnPageUnauthorized']->fromArray(array (
    'name' => 'OnPageUnauthorized',
    'service' => 1,
    'groupname' => 'Security',
), '', true, true);


/* Users */
$events['OnUserFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnUserFormPrerender']->fromArray(array (
    'name' => 'OnUserFormPrerender',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserFormRender']= $xpdo->newObject(modEvent::class);
$events['OnUserFormRender']->fromArray(array (
    'name' => 'OnUserFormRender',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforeUserFormSave']->fromArray(array (
    'name' => 'OnBeforeUserFormSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserFormSave']= $xpdo->newObject(modEvent::class);
$events['OnUserFormSave']->fromArray(array (
    'name' => 'OnUserFormSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforeUserFormDelete']->fromArray(array (
    'name' => 'OnBeforeUserFormDelete',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnUserFormDelete']->fromArray(array (
    'name' => 'OnUserFormDelete',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserNotFound']= $xpdo->newObject(modEvent::class);
$events['OnUserNotFound']->fromArray(array (
    'name' => 'OnUserNotFound',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserActivate']= $xpdo->newObject(modEvent::class);
$events['OnBeforeUserActivate']->fromArray(array (
    'name' => 'OnBeforeUserActivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserActivate']= $xpdo->newObject(modEvent::class);
$events['OnUserActivate']->fromArray(array (
    'name' => 'OnUserActivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserDeactivate']= $xpdo->newObject(modEvent::class);
$events['OnBeforeUserDeactivate']->fromArray(array (
    'name' => 'OnBeforeUserDeactivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserDeactivate']= $xpdo->newObject(modEvent::class);
$events['OnUserDeactivate']->fromArray(array (
    'name' => 'OnUserDeactivate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserDuplicate']= $xpdo->newObject(modEvent::class);
$events['OnBeforeUserDuplicate']->fromArray(array (
    'name' => 'OnBeforeUserDuplicate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserDuplicate']= $xpdo->newObject(modEvent::class);
$events['OnUserDuplicate']->fromArray(array (
    'name' => 'OnUserDuplicate',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserChangePassword']= $xpdo->newObject(modEvent::class);
$events['OnUserChangePassword']->fromArray(array (
    'name' => 'OnUserChangePassword',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserBeforeRemove']->fromArray(array (
    'name' => 'OnUserBeforeRemove',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnUserBeforeSave']->fromArray(array (
    'name' => 'OnUserBeforeSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserSave']= $xpdo->newObject(modEvent::class);
$events['OnUserSave']->fromArray(array (
    'name' => 'OnUserSave',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserRemove']= $xpdo->newObject(modEvent::class);
$events['OnUserRemove']->fromArray(array (
    'name' => 'OnUserRemove',
    'service' => 1,
    'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeAddToGroup']= $xpdo->newObject(modEvent::class);
$events['OnUserBeforeAddToGroup']->fromArray(array (
    'name' => 'OnUserBeforeAddToGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserAddToGroup']= $xpdo->newObject(modEvent::class);
$events['OnUserAddToGroup']->fromArray(array (
    'name' => 'OnUserAddToGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserBeforeRemoveFromGroup']= $xpdo->newObject(modEvent::class);
$events['OnUserBeforeRemoveFromGroup']->fromArray(array (
    'name' => 'OnUserBeforeRemoveFromGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);
$events['OnUserRemoveFromGroup']= $xpdo->newObject(modEvent::class);
$events['OnUserRemoveFromGroup']->fromArray(array (
    'name' => 'OnUserRemoveFromGroup',
    'service' => 1,
    'groupname' => 'User Groups',
), '', true, true);


/* System */
$events['OnBeforeRegisterClientScripts']= $xpdo->newObject(modEvent::class);
$events['OnBeforeRegisterClientScripts']->fromArray(array (
    'name' => 'OnBeforeRegisterClientScripts',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnWebPagePrerender']= $xpdo->newObject(modEvent::class);
$events['OnWebPagePrerender']->fromArray(array (
    'name' => 'OnWebPagePrerender',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnBeforeCacheUpdate']= $xpdo->newObject(modEvent::class);
$events['OnBeforeCacheUpdate']->fromArray(array (
    'name' => 'OnBeforeCacheUpdate',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnCacheUpdate']= $xpdo->newObject(modEvent::class);
$events['OnCacheUpdate']->fromArray(array (
    'name' => 'OnCacheUpdate',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnLoadWebPageCache']= $xpdo->newObject(modEvent::class);
$events['OnLoadWebPageCache']->fromArray(array (
    'name' => 'OnLoadWebPageCache',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnBeforeSaveWebPageCache']= $xpdo->newObject(modEvent::class);
$events['OnBeforeSaveWebPageCache']->fromArray(array (
    'name' => 'OnBeforeSaveWebPageCache',
    'service' => 4,
    'groupname' => 'System',
), '', true, true);
$events['OnSiteRefresh']= $xpdo->newObject(modEvent::class);
$events['OnSiteRefresh']->fromArray(array (
    'name' => 'OnSiteRefresh',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerDirCreate']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerDirCreate']->fromArray(array (
    'name' => 'OnFileManagerDirCreate',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerDirRemove']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerDirRemove']->fromArray(array (
    'name' => 'OnFileManagerDirRemove',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerDirRename']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerDirRename']->fromArray(array (
    'name' => 'OnFileManagerDirRename',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileRename']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerFileRename']->fromArray(array (
    'name' => 'OnFileManagerFileRename',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileRemove']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerFileRemove']->fromArray(array (
    'name' => 'OnFileManagerFileRemove',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileUpdate']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerFileUpdate']->fromArray(array (
    'name' => 'OnFileManagerFileUpdate',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerFileCreate']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerFileCreate']->fromArray(array (
    'name' => 'OnFileManagerFileCreate',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerBeforeUpload']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerBeforeUpload']->fromArray(array (
    'name' => 'OnFileManagerBeforeUpload',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerUpload']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerUpload']->fromArray(array (
    'name' => 'OnFileManagerUpload',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileManagerMoveObject']= $xpdo->newObject(modEvent::class);
$events['OnFileManagerMoveObject']->fromArray(array (
    'name' => 'OnFileManagerMoveObject',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileCreateFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnFileCreateFormPrerender']->fromArray(array (
    'name' => 'OnFileCreateFormPrerender',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnFileEditFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnFileEditFormPrerender']->fromArray(array (
    'name' => 'OnFileEditFormPrerender',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnManagerPageInit']= $xpdo->newObject(modEvent::class);
$events['OnManagerPageInit']->fromArray(array (
    'name' => 'OnManagerPageInit',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnManagerPageBeforeRender']= $xpdo->newObject(modEvent::class);
$events['OnManagerPageBeforeRender']->fromArray(array (
    'name' => 'OnManagerPageBeforeRender',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnManagerPageAfterRender']= $xpdo->newObject(modEvent::class);
$events['OnManagerPageAfterRender']->fromArray(array (
    'name' => 'OnManagerPageAfterRender',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnWebPageInit']= $xpdo->newObject(modEvent::class);
$events['OnWebPageInit']->fromArray(array (
    'name' => 'OnWebPageInit',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnLoadWebDocument']= $xpdo->newObject(modEvent::class);
$events['OnLoadWebDocument']->fromArray(array (
    'name' => 'OnLoadWebDocument',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnParseDocument']= $xpdo->newObject(modEvent::class);
$events['OnParseDocument']->fromArray(array (
    'name' => 'OnParseDocument',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnWebPageComplete']= $xpdo->newObject(modEvent::class);
$events['OnWebPageComplete']->fromArray(array (
    'name' => 'OnWebPageComplete',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnBeforeManagerPageInit']= $xpdo->newObject(modEvent::class);
$events['OnBeforeManagerPageInit']->fromArray(array (
    'name' => 'OnBeforeManagerPageInit',
    'service' => 2,
    'groupname' => 'System',
), '', true, true);
$events['OnPageNotFound']= $xpdo->newObject(modEvent::class);
$events['OnPageNotFound']->fromArray(array (
    'name' => 'OnPageNotFound',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);
$events['OnHandleRequest']= $xpdo->newObject(modEvent::class);
$events['OnHandleRequest']->fromArray(array (
    'name' => 'OnHandleRequest',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnMODXInit']= $xpdo->newObject(modEvent::class);
$events['OnMODXInit']->fromArray(array (
    'name' => 'OnMODXInit',
    'service' => 5,
    'groupname' => 'System',
), '', true, true);
$events['OnElementNotFound']= $xpdo->newObject(modEvent::class);
$events['OnElementNotFound']->fromArray(array (
    'name' => 'OnElementNotFound',
    'service' => 1,
    'groupname' => 'System',
), '', true, true);


/* Settings */
$events['OnSiteSettingsRender']= $xpdo->newObject(modEvent::class);
$events['OnSiteSettingsRender']->fromArray(array (
    'name' => 'OnSiteSettingsRender',
    'service' => 1,
    'groupname' => 'Settings',
), '', true, true);


/* Internationalization */
$events['OnInitCulture']= $xpdo->newObject(modEvent::class);
$events['OnInitCulture']->fromArray(array (
    'name' => 'OnInitCulture',
    'service' => 1,
    'groupname' => 'Internationalization',
), '', true, true);


/* Categories */
$events['OnCategorySave']= $xpdo->newObject(modEvent::class);
$events['OnCategorySave']->fromArray(array (
    'name' => 'OnCategorySave',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnCategoryBeforeSave']->fromArray(array (
    'name' => 'OnCategoryBeforeSave',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryRemove']= $xpdo->newObject(modEvent::class);
$events['OnCategoryRemove']->fromArray(array (
    'name' => 'OnCategoryRemove',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnCategoryBeforeRemove']->fromArray(array (
    'name' => 'OnCategoryBeforeRemove',
    'service' => 1,
    'groupname' => 'Categories',
), '', true, true);


/* Chunks */
$events['OnChunkSave']= $xpdo->newObject(modEvent::class);
$events['OnChunkSave']->fromArray(array (
    'name' => 'OnChunkSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnChunkBeforeSave']->fromArray(array (
    'name' => 'OnChunkBeforeSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkRemove']= $xpdo->newObject(modEvent::class);
$events['OnChunkRemove']->fromArray(array (
    'name' => 'OnChunkRemove',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnChunkBeforeRemove']->fromArray(array (
    'name' => 'OnChunkBeforeRemove',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnChunkFormPrerender']->fromArray(array (
    'name' => 'OnChunkFormPrerender',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormRender']= $xpdo->newObject(modEvent::class);
$events['OnChunkFormRender']->fromArray(array (
    'name' => 'OnChunkFormRender',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnBeforeChunkFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforeChunkFormSave']->fromArray(array (
    'name' => 'OnBeforeChunkFormSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormSave']= $xpdo->newObject(modEvent::class);
$events['OnChunkFormSave']->fromArray(array (
    'name' => 'OnChunkFormSave',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnBeforeChunkFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforeChunkFormDelete']->fromArray(array (
    'name' => 'OnBeforeChunkFormDelete',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnChunkFormDelete']->fromArray(array (
    'name' => 'OnChunkFormDelete',
    'service' => 1,
    'groupname' => 'Chunks',
), '', true, true);


/* Contexts */
$events['OnContextSave']= $xpdo->newObject(modEvent::class);
$events['OnContextSave']->fromArray(array (
    'name' => 'OnContextSave',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnContextBeforeSave']->fromArray(array (
    'name' => 'OnContextBeforeSave',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextRemove']= $xpdo->newObject(modEvent::class);
$events['OnContextRemove']->fromArray(array (
    'name' => 'OnContextRemove',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnContextBeforeRemove']->fromArray(array (
    'name' => 'OnContextBeforeRemove',
    'service' => 1,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnContextFormPrerender']->fromArray(array (
    'name' => 'OnContextFormPrerender',
    'service' => 2,
    'groupname' => 'Contexts',
), '', true, true);
$events['OnContextFormRender']= $xpdo->newObject(modEvent::class);
$events['OnContextFormRender']->fromArray(array (
    'name' => 'OnContextFormRender',
    'service' => 2,
    'groupname' => 'Contexts',
), '', true, true);


/* Plugins */
$events['OnPluginSave']= $xpdo->newObject(modEvent::class);
$events['OnPluginSave']->fromArray(array (
    'name' => 'OnPluginSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnPluginBeforeSave']->fromArray(array (
    'name' => 'OnPluginBeforeSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginRemove']= $xpdo->newObject(modEvent::class);
$events['OnPluginRemove']->fromArray(array (
    'name' => 'OnPluginRemove',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnPluginBeforeRemove']->fromArray(array (
    'name' => 'OnPluginBeforeRemove',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormPrerender']= $xpdo->newObject(modEvent::class);
$events['OnPluginFormPrerender']->fromArray(array (
    'name' => 'OnPluginFormPrerender',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormRender']= $xpdo->newObject(modEvent::class);
$events['OnPluginFormRender']->fromArray(array (
    'name' => 'OnPluginFormRender',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnBeforePluginFormSave']= $xpdo->newObject(modEvent::class);
$events['OnBeforePluginFormSave']->fromArray(array (
    'name' => 'OnBeforePluginFormSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormSave']= $xpdo->newObject(modEvent::class);
$events['OnPluginFormSave']->fromArray(array (
    'name' => 'OnPluginFormSave',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnBeforePluginFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnBeforePluginFormDelete']->fromArray(array (
    'name' => 'OnBeforePluginFormDelete',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnPluginFormDelete']->fromArray(array (
    'name' => 'OnPluginFormDelete',
    'service' => 1,
    'groupname' => 'Plugins',
), '', true, true);


/* Property Sets */
$events['OnPropertySetSave']= $xpdo->newObject(modEvent::class);
$events['OnPropertySetSave']->fromArray(array (
    'name' => 'OnPropertySetSave',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetBeforeSave']= $xpdo->newObject(modEvent::class);
$events['OnPropertySetBeforeSave']->fromArray(array (
    'name' => 'OnPropertySetBeforeSave',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetRemove']= $xpdo->newObject(modEvent::class);
$events['OnPropertySetRemove']->fromArray(array (
    'name' => 'OnPropertySetRemove',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetBeforeRemove']= $xpdo->newObject(modEvent::class);
$events['OnPropertySetBeforeRemove']->fromArray(array (
    'name' => 'OnPropertySetBeforeRemove',
    'service' => 1,
    'groupname' => 'Property Sets',
), '', true, true);


/* Media Source */
$events['OnMediaSourceBeforeFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnMediaSourceBeforeFormDelete']->fromArray(array (
    'name' => 'OnMediaSourceBeforeFormDelete',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceBeforeFormSave']= $xpdo->newObject(modEvent::class);
$events['OnMediaSourceBeforeFormSave']->fromArray(array (
    'name' => 'OnMediaSourceBeforeFormSave',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceGetProperties']= $xpdo->newObject(modEvent::class);
$events['OnMediaSourceGetProperties']->fromArray(array (
    'name' => 'OnMediaSourceGetProperties',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceFormDelete']= $xpdo->newObject(modEvent::class);
$events['OnMediaSourceFormDelete']->fromArray(array (
    'name' => 'OnMediaSourceFormDelete',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceFormSave']= $xpdo->newObject(modEvent::class);
$events['OnMediaSourceFormSave']->fromArray(array (
    'name' => 'OnMediaSourceFormSave',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);
$events['OnMediaSourceDuplicate']= $xpdo->newObject(modEvent::class);
$events['OnMediaSourceDuplicate']->fromArray(array (
    'name' => 'OnMediaSourceDuplicate',
    'service' => 1,
    'groupname' => 'Media Sources',
), '', true, true);

/* Package Manager */
$events['OnPackageInstall']= $xpdo->newObject(modEvent::class);
$events['OnPackageInstall']->fromArray(array (
  'name' => 'OnPackageInstall',
  'service' => 2,
  'groupname' => 'Package Manager',
), '', true, true);
$events['OnPackageUninstall']= $xpdo->newObject(modEvent::class);
$events['OnPackageUninstall']->fromArray(array (
  'name' => 'OnPackageUninstall',
  'service' => 2,
  'groupname' => 'Package Manager',
), '', true, true);
$events['OnPackageRemove']= $xpdo->newObject(modEvent::class);
$events['OnPackageRemove']->fromArray(array (
  'name' => 'OnPackageRemove',
  'service' => 2,
  'groupname' => 'Package Manager',
), '', true, true);

return $events;
