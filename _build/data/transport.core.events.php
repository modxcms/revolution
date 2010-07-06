<?php
/**
 *
 * @package modx
 */
$events = array();

/* Plugin Events */
$events['OnPluginEventBeforeSave']= $xpdo->newObject('modEvent');
$events['OnPluginEventBeforeSave']->fromArray(array (
  'name' => 'OnPluginEventBeforeSave',
  'service' => 1,
  'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventSave']= $xpdo->newObject('modEvent');
$events['OnPluginEventSave']->fromArray(array (
  'name' => 'OnPluginEventSave',
  'service' => 1,
  'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnPluginEventBeforeRemove']->fromArray(array (
  'name' => 'OnPluginEventBeforeRemove',
  'service' => 1,
  'groupname' => 'Plugin Events',
), '', true, true);
$events['OnPluginEventRemove']= $xpdo->newObject('modEvent');
$events['OnPluginEventRemove']->fromArray(array (
  'name' => 'OnPluginEventRemove',
  'service' => 1,
  'groupname' => 'Plugin Events',
), '', true, true);

/* Resource Groups */
$events['OnResourceGroupSave']= $xpdo->newObject('modEvent');
$events['OnResourceGroupSave']->fromArray(array (
  'name' => 'OnResourceGroupSave',
  'service' => 1,
  'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupBeforeSave']= $xpdo->newObject('modEvent');
$events['OnResourceGroupBeforeSave']->fromArray(array (
  'name' => 'OnResourceGroupBeforeSave',
  'service' => 1,
  'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupRemove']= $xpdo->newObject('modEvent');
$events['OnResourceGroupRemove']->fromArray(array (
  'name' => 'OnResourceGroupRemove',
  'service' => 1,
  'groupname' => 'Security',
), '', true, true);
$events['OnResourceGroupBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnResourceGroupBeforeRemove']->fromArray(array (
  'name' => 'OnResourceGroupBeforeRemove',
  'service' => 1,
  'groupname' => 'Security',
), '', true, true);

/* Snippets */
$events['OnSnippetBeforeSave']= $xpdo->newObject('modEvent');
$events['OnSnippetBeforeSave']->fromArray(array (
  'name' => 'OnSnippetBeforeSave',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetSave']= $xpdo->newObject('modEvent');
$events['OnSnippetSave']->fromArray(array (
  'name' => 'OnSnippetSave',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnSnippetBeforeRemove']->fromArray(array (
  'name' => 'OnSnippetBeforeRemove',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnippetRemove']= $xpdo->newObject('modEvent');
$events['OnSnippetRemove']->fromArray(array (
  'name' => 'OnSnippetRemove',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormPrerender']= $xpdo->newObject('modEvent');
$events['OnSnipFormPrerender']->fromArray(array (
  'name' => 'OnSnipFormPrerender',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormRender']= $xpdo->newObject('modEvent');
$events['OnSnipFormRender']->fromArray(array (
  'name' => 'OnSnipFormRender',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnBeforeSnipFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforeSnipFormSave']->fromArray(array (
  'name' => 'OnBeforeSnipFormSave',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormSave']= $xpdo->newObject('modEvent');
$events['OnSnipFormSave']->fromArray(array (
  'name' => 'OnSnipFormSave',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnBeforeSnipFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforeSnipFormDelete']->fromArray(array (
  'name' => 'OnBeforeSnipFormDelete',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$events['OnSnipFormDelete']= $xpdo->newObject('modEvent');
$events['OnSnipFormDelete']->fromArray(array (
  'name' => 'OnSnipFormDelete',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);


/* Templates */
$events['OnTemplateBeforeSave']= $xpdo->newObject('modEvent');
$events['OnTemplateBeforeSave']->fromArray(array (
  'name' => 'OnTemplateBeforeSave',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateSave']= $xpdo->newObject('modEvent');
$events['OnTemplateSave']->fromArray(array (
  'name' => 'OnTemplateSave',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnTemplateBeforeRemove']->fromArray(array (
  'name' => 'OnTemplateBeforeRemove',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTemplateRemove']= $xpdo->newObject('modEvent');
$events['OnTemplateRemove']->fromArray(array (
  'name' => 'OnTemplateRemove',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormPrerender']= $xpdo->newObject('modEvent');
$events['OnTempFormPrerender']->fromArray(array (
  'name' => 'OnTempFormPrerender',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormRender']= $xpdo->newObject('modEvent');
$events['OnTempFormRender']->fromArray(array (
  'name' => 'OnTempFormRender',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnBeforeTempFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforeTempFormSave']->fromArray(array (
  'name' => 'OnBeforeTempFormSave',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormSave']= $xpdo->newObject('modEvent');
$events['OnTempFormSave']->fromArray(array (
  'name' => 'OnTempFormSave',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnBeforeTempFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforeTempFormDelete']->fromArray(array (
  'name' => 'OnBeforeTempFormDelete',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$events['OnTempFormDelete']= $xpdo->newObject('modEvent');
$events['OnTempFormDelete']->fromArray(array (
  'name' => 'OnTempFormDelete',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);


/* Template Variables */
$events['OnTemplateVarBeforeSave']= $xpdo->newObject('modEvent');
$events['OnTemplateVarBeforeSave']->fromArray(array (
  'name' => 'OnTemplateVarBeforeSave',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarSave']= $xpdo->newObject('modEvent');
$events['OnTemplateVarSave']->fromArray(array (
  'name' => 'OnTemplateVarSave',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnTemplateVarBeforeRemove']->fromArray(array (
  'name' => 'OnTemplateVarBeforeRemove',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTemplateVarRemove']= $xpdo->newObject('modEvent');
$events['OnTemplateVarRemove']->fromArray(array (
  'name' => 'OnTemplateVarRemove',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormPrerender']= $xpdo->newObject('modEvent');
$events['OnTVFormPrerender']->fromArray(array (
  'name' => 'OnTVFormPrerender',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormRender']= $xpdo->newObject('modEvent');
$events['OnTVFormRender']->fromArray(array (
  'name' => 'OnTVFormRender',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnBeforeTVFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforeTVFormSave']->fromArray(array (
  'name' => 'OnBeforeTVFormSave',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormSave']= $xpdo->newObject('modEvent');
$events['OnTVFormSave']->fromArray(array (
  'name' => 'OnTVFormSave',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnBeforeTVFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforeTVFormDelete']->fromArray(array (
  'name' => 'OnBeforeTVFormDelete',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVFormDelete']= $xpdo->newObject('modEvent');
$events['OnTVFormDelete']->fromArray(array (
  'name' => 'OnTVFormDelete',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);

/* TV Renders */
$events['OnTVInputRenderList']= $xpdo->newObject('modEvent');
$events['OnTVInputRenderList']->fromArray(array (
  'name' => 'OnTVInputRenderList',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVOutputRenderList']= $xpdo->newObject('modEvent');
$events['OnTVOutputRenderList']->fromArray(array (
  'name' => 'OnTVOutputRenderList',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$events['OnTVOutputRenderPropertiesList']= $xpdo->newObject('modEvent');
$events['OnTVOutputRenderPropertiesList']->fromArray(array (
  'name' => 'OnTVOutputRenderPropertiesList',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);

/* User Groups */
$events['OnUserGroupBeforeSave']= $xpdo->newObject('modEvent');
$events['OnUserGroupBeforeSave']->fromArray(array (
  'name' => 'OnUserGroupBeforeSave',
  'service' => 1,
  'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupSave']= $xpdo->newObject('modEvent');
$events['OnUserGroupSave']->fromArray(array (
  'name' => 'OnUserGroupSave',
  'service' => 1,
  'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnUserGroupBeforeRemove']->fromArray(array (
  'name' => 'OnUserGroupBeforeRemove',
  'service' => 1,
  'groupname' => 'User Groups',
), '', true, true);
$events['OnUserGroupRemove']= $xpdo->newObject('modEvent');
$events['OnUserGroupRemove']->fromArray(array (
  'name' => 'OnUserGroupRemove',
  'service' => 1,
  'groupname' => 'User Groups',
), '', true, true);


/* Resources */
$events['OnDocFormPrerender']= $xpdo->newObject('modEvent');
$events['OnDocFormPrerender']->fromArray(array (
  'name' => 'OnDocFormPrerender',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormRender']= $xpdo->newObject('modEvent');
$events['OnDocFormRender']->fromArray(array (
  'name' => 'OnDocFormRender',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeDocFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforeDocFormSave']->fromArray(array (
  'name' => 'OnBeforeDocFormSave',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormSave']= $xpdo->newObject('modEvent');
$events['OnDocFormSave']->fromArray(array (
  'name' => 'OnDocFormSave',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeDocFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforeDocFormDelete']->fromArray(array (
  'name' => 'OnBeforeDocFormDelete',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnDocFormDelete']= $xpdo->newObject('modEvent');
$events['OnDocFormDelete']->fromArray(array (
  'name' => 'OnDocFormDelete',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnDocPublished']= $xpdo->newObject('modEvent');
$events['OnDocPublished']->fromArray(array (
  'name' => 'OnDocPublished',
  'service' => 5,
  'groupname' => 'Resources',
), '', true, true);
$events['OnDocUnPublished']= $xpdo->newObject('modEvent');
$events['OnDocUnPublished']->fromArray(array (
  'name' => 'OnDocUnPublished',
  'service' => 5,
  'groupname' => 'Resources',
), '', true, true);
$events['OnBeforeEmptyTrash']= $xpdo->newObject('modEvent');
$events['OnBeforeEmptyTrash']->fromArray(array (
  'name' => 'OnBeforeEmptyTrash',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnEmptyTrash']= $xpdo->newObject('modEvent');
$events['OnEmptyTrash']->fromArray(array (
  'name' => 'OnEmptyTrash',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);
$events['OnResourceTVFormRender']= $xpdo->newObject('modEvent');
$events['OnResourceTVFormRender']->fromArray(array (
  'name' => 'OnResourceTVFormRender',
  'service' => 1,
  'groupname' => 'Resources',
), '', true, true);


/* Richtext Editor */
$events['OnRichTextEditorRegister']= $xpdo->newObject('modEvent');
$events['OnRichTextEditorRegister']->fromArray(array (
  'name' => 'OnRichTextEditorRegister',
  'service' => 1,
  'groupname' => 'RichText Editor',
), '', true, true);
$events['OnRichTextEditorInit']= $xpdo->newObject('modEvent');
$events['OnRichTextEditorInit']->fromArray(array (
  'name' => 'OnRichTextEditorInit',
  'service' => 1,
  'groupname' => 'RichText Editor',
), '', true, true);
$events['OnRichTextBrowserInit']= $xpdo->newObject('modEvent');
$events['OnRichTextBrowserInit']->fromArray(array (
  'name' => 'OnRichTextBrowserInit',
  'service' => 1,
  'groupname' => 'RichText Editor',
), '', true, true);


/* Security */
$events['OnWebLogin']= $xpdo->newObject('modEvent');
$events['OnWebLogin']->fromArray(array (
  'name' => 'OnWebLogin',
  'service' => 3,
  'groupname' => 'Security',
), '', true, true);
$events['OnBeforeWebLogout']= $xpdo->newObject('modEvent');
$events['OnBeforeWebLogout']->fromArray(array (
  'name' => 'OnBeforeWebLogout',
  'service' => 3,
  'groupname' => 'Security',
), '', true, true);
$events['OnWebLogout']= $xpdo->newObject('modEvent');
$events['OnWebLogout']->fromArray(array (
  'name' => 'OnWebLogout',
  'service' => 3,
  'groupname' => 'Security',
), '', true, true);
$events['OnManagerLogin']= $xpdo->newObject('modEvent');
$events['OnManagerLogin']->fromArray(array (
  'name' => 'OnManagerLogin',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnBeforeManagerLogout']= $xpdo->newObject('modEvent');
$events['OnBeforeManagerLogout']->fromArray(array (
  'name' => 'OnBeforeManagerLogout',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnManagerLogout']= $xpdo->newObject('modEvent');
$events['OnManagerLogout']->fromArray(array (
  'name' => 'OnManagerLogout',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnBeforeWebLogin']= $xpdo->newObject('modEvent');
$events['OnBeforeWebLogin']->fromArray(array (
  'name' => 'OnBeforeWebLogin',
  'service' => 3,
  'groupname' => 'Security',
), '', true, true);
$events['OnWebAuthentication']= $xpdo->newObject('modEvent');
$events['OnWebAuthentication']->fromArray(array (
  'name' => 'OnWebAuthentication',
  'service' => 3,
  'groupname' => 'Security',
), '', true, true);
$events['OnBeforeManagerLogin']= $xpdo->newObject('modEvent');
$events['OnBeforeManagerLogin']->fromArray(array (
  'name' => 'OnBeforeManagerLogin',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnManagerAuthentication']= $xpdo->newObject('modEvent');
$events['OnManagerAuthentication']->fromArray(array (
  'name' => 'OnManagerAuthentication',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnManagerLoginFormRender']= $xpdo->newObject('modEvent');
$events['OnManagerLoginFormRender']->fromArray(array (
  'name' => 'OnManagerLoginFormRender',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnManagerLoginFormPrerender']= $xpdo->newObject('modEvent');
$events['OnManagerLoginFormPrerender']->fromArray(array (
  'name' => 'OnManagerLoginFormPrerender',
  'service' => 2,
  'groupname' => 'Security',
), '', true, true);
$events['OnPageUnauthorized']= $xpdo->newObject('modEvent');
$events['OnPageUnauthorized']->fromArray(array (
  'name' => 'OnPageUnauthorized',
  'service' => 1,
  'groupname' => 'Security',
), '', true, true);

/* Users */
$events['OnUserFormPrerender']= $xpdo->newObject('modEvent');
$events['OnUserFormPrerender']->fromArray(array (
  'name' => 'OnUserFormPrerender',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserFormRender']= $xpdo->newObject('modEvent');
$events['OnUserFormRender']->fromArray(array (
  'name' => 'OnUserFormRender',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforeUserFormSave']->fromArray(array (
  'name' => 'OnBeforeUserFormSave',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserFormSave']= $xpdo->newObject('modEvent');
$events['OnUserFormSave']->fromArray(array (
  'name' => 'OnUserFormSave',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforeUserFormDelete']->fromArray(array (
  'name' => 'OnBeforeUserFormDelete',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserFormDelete']= $xpdo->newObject('modEvent');
$events['OnUserFormDelete']->fromArray(array (
  'name' => 'OnUserFormDelete',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserNotFound']= $xpdo->newObject('modEvent');
$events['OnUserNotFound']->fromArray(array (
  'name' => 'OnUserNotFound',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnBeforeUserActivate']= $xpdo->newObject('modEvent');
$events['OnBeforeUserActivate']->fromArray(array (
  'name' => 'OnBeforeUserActivate',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserActivate']= $xpdo->newObject('modEvent');
$events['OnUserActivate']->fromArray(array (
  'name' => 'OnUserActivate',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserChangePassword']= $xpdo->newObject('modEvent');
$events['OnUserChangePassword']->fromArray(array (
  'name' => 'OnUserChangePassword',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnUserBeforeRemove']->fromArray(array (
  'name' => 'OnUserBeforeRemove',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserBeforeSave']= $xpdo->newObject('modEvent');
$events['OnUserBeforeSave']->fromArray(array (
  'name' => 'OnUserBeforeSave',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserSave']= $xpdo->newObject('modEvent');
$events['OnUserSave']->fromArray(array (
  'name' => 'OnUserSave',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$events['OnUserRemove']= $xpdo->newObject('modEvent');
$events['OnUserRemove']->fromArray(array (
  'name' => 'OnUserRemove',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);


/* System */
$events['OnWebPagePrerender']= $xpdo->newObject('modEvent');
$events['OnWebPagePrerender']->fromArray(array (
  'name' => 'OnWebPagePrerender',
  'service' => 5,
  'groupname' => 'System',
), '', true, true);
$events['OnBeforeCacheUpdate']= $xpdo->newObject('modEvent');
$events['OnBeforeCacheUpdate']->fromArray(array (
  'name' => 'OnBeforeCacheUpdate',
  'service' => 4,
  'groupname' => 'System',
), '', true, true);
$events['OnCacheUpdate']= $xpdo->newObject('modEvent');
$events['OnCacheUpdate']->fromArray(array (
  'name' => 'OnCacheUpdate',
  'service' => 4,
  'groupname' => 'System',
), '', true, true);
$events['OnLoadWebPageCache']= $xpdo->newObject('modEvent');
$events['OnLoadWebPageCache']->fromArray(array (
  'name' => 'OnLoadWebPageCache',
  'service' => 4,
  'groupname' => 'System',
), '', true, true);
$events['OnBeforeSaveWebPageCache']= $xpdo->newObject('modEvent');
$events['OnBeforeSaveWebPageCache']->fromArray(array (
  'name' => 'OnBeforeSaveWebPageCache',
  'service' => 4,
  'groupname' => 'System',
), '', true, true);
$events['OnSiteRefresh']= $xpdo->newObject('modEvent');
$events['OnSiteRefresh']->fromArray(array (
  'name' => 'OnSiteRefresh',
  'service' => 1,
  'groupname' => 'System',
), '', true, true);
$events['OnFileManagerUpload']= $xpdo->newObject('modEvent');
$events['OnFileManagerUpload']->fromArray(array (
  'name' => 'OnFileManagerUpload',
  'service' => 1,
  'groupname' => 'System',
), '', true, true);
$events['OnManagerPageInit']= $xpdo->newObject('modEvent');
$events['OnManagerPageInit']->fromArray(array (
  'name' => 'OnManagerPageInit',
  'service' => 2,
  'groupname' => 'System',
), '', true, true);
$events['OnWebPageInit']= $xpdo->newObject('modEvent');
$events['OnWebPageInit']->fromArray(array (
  'name' => 'OnWebPageInit',
  'service' => 5,
  'groupname' => 'System',
), '', true, true);
$events['OnLoadWebDocument']= $xpdo->newObject('modEvent');
$events['OnLoadWebDocument']->fromArray(array (
  'name' => 'OnLoadWebDocument',
  'service' => 5,
  'groupname' => 'System',
), '', true, true);
$events['OnParseDocument']= $xpdo->newObject('modEvent');
$events['OnParseDocument']->fromArray(array (
  'name' => 'OnParseDocument',
  'service' => 5,
  'groupname' => 'System',
), '', true, true);
$events['OnWebPageComplete']= $xpdo->newObject('modEvent');
$events['OnWebPageComplete']->fromArray(array (
  'name' => 'OnWebPageComplete',
  'service' => 5,
  'groupname' => 'System',
), '', true, true);
$events['OnBeforeManagerPageInit']= $xpdo->newObject('modEvent');
$events['OnBeforeManagerPageInit']->fromArray(array (
  'name' => 'OnBeforeManagerPageInit',
  'service' => 2,
  'groupname' => 'System',
), '', true, true);
$events['OnPageNotFound']= $xpdo->newObject('modEvent');
$events['OnPageNotFound']->fromArray(array (
  'name' => 'OnPageNotFound',
  'service' => 1,
  'groupname' => 'System',
), '', true, true);
$events['OnHandleRequest']= $xpdo->newObject('modEvent');
$events['OnHandleRequest']->fromArray(array (
  'name' => 'OnHandleRequest',
  'service' => 5,
  'groupname' => 'System',
), '', true, true);


/* Settings */
$events['OnSiteSettingsRender']= $xpdo->newObject('modEvent');
$events['OnSiteSettingsRender']->fromArray(array (
  'name' => 'OnSiteSettingsRender',
  'service' => 1,
  'groupname' => 'Settings',
), '', true, true);


/* Internationalization */
$events['OnInitCulture']= $xpdo->newObject('modEvent');
$events['OnInitCulture']->fromArray(array (
  'name' => 'OnInitCulture',
  'service' => 3,
  'groupname' => 'Internationalization',
), '', true, true);


/* Categories */
$events['OnCategorySave']= $xpdo->newObject('modEvent');
$events['OnCategorySave']->fromArray(array (
  'name' => 'OnCategorySave',
  'service' => 1,
  'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryBeforeSave']= $xpdo->newObject('modEvent');
$events['OnCategoryBeforeSave']->fromArray(array (
  'name' => 'OnCategoryBeforeSave',
  'service' => 1,
  'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryRemove']= $xpdo->newObject('modEvent');
$events['OnCategoryRemove']->fromArray(array (
  'name' => 'OnCategoryRemove',
  'service' => 1,
  'groupname' => 'Categories',
), '', true, true);
$events['OnCategoryBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnCategoryBeforeRemove']->fromArray(array (
  'name' => 'OnCategoryBeforeRemove',
  'service' => 1,
  'groupname' => 'Categories',
), '', true, true);


/* Chunks */
$events['OnChunkSave']= $xpdo->newObject('modEvent');
$events['OnChunkSave']->fromArray(array (
  'name' => 'OnChunkSave',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkBeforeSave']= $xpdo->newObject('modEvent');
$events['OnChunkBeforeSave']->fromArray(array (
  'name' => 'OnChunkBeforeSave',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkRemove']= $xpdo->newObject('modEvent');
$events['OnChunkRemove']->fromArray(array (
  'name' => 'OnChunkRemove',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnChunkBeforeRemove']->fromArray(array (
  'name' => 'OnChunkBeforeRemove',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormPrerender']= $xpdo->newObject('modEvent');
$events['OnChunkFormPrerender']->fromArray(array (
  'name' => 'OnChunkFormPrerender',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormRender']= $xpdo->newObject('modEvent');
$events['OnChunkFormRender']->fromArray(array (
  'name' => 'OnChunkFormRender',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnBeforeChunkFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforeChunkFormSave']->fromArray(array (
  'name' => 'OnBeforeChunkFormSave',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormSave']= $xpdo->newObject('modEvent');
$events['OnChunkFormSave']->fromArray(array (
  'name' => 'OnChunkFormSave',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnBeforeChunkFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforeChunkFormDelete']->fromArray(array (
  'name' => 'OnBeforeChunkFormDelete',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$events['OnChunkFormDelete']= $xpdo->newObject('modEvent');
$events['OnChunkFormDelete']->fromArray(array (
  'name' => 'OnChunkFormDelete',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);


/* Contexts */
$events['OnContextSave']= $xpdo->newObject('modEvent');
$events['OnContextSave']->fromArray(array (
  'name' => 'OnContextSave',
  'service' => 1,
  'groupname' => 'Contexts',
), '', true, true);
$events['OnContextBeforeSave']= $xpdo->newObject('modEvent');
$events['OnContextBeforeSave']->fromArray(array (
  'name' => 'OnContextBeforeSave',
  'service' => 1,
  'groupname' => 'Contexts',
), '', true, true);
$events['OnContextRemove']= $xpdo->newObject('modEvent');
$events['OnContextRemove']->fromArray(array (
  'name' => 'OnContextRemove',
  'service' => 1,
  'groupname' => 'Contexts',
), '', true, true);
$events['OnContextBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnContextBeforeRemove']->fromArray(array (
  'name' => 'OnContextBeforeRemove',
  'service' => 1,
  'groupname' => 'Contexts',
), '', true, true);
$events['OnContextFormPrerender']= $xpdo->newObject('modEvent');
$events['OnContextFormPrerender']->fromArray(array (
  'name' => 'OnContextFormPrerender',
  'service' => 2,
  'groupname' => 'Contexts',
), '', true, true);
$events['OnContextFormRender']= $xpdo->newObject('modEvent');
$events['OnContextFormRender']->fromArray(array (
  'name' => 'OnContextFormRender',
  'service' => 2,
  'groupname' => 'Contexts',
), '', true, true);


/* Plugins */
$events['OnPluginSave']= $xpdo->newObject('modEvent');
$events['OnPluginSave']->fromArray(array (
  'name' => 'OnPluginSave',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginBeforeSave']= $xpdo->newObject('modEvent');
$events['OnPluginBeforeSave']->fromArray(array (
  'name' => 'OnPluginBeforeSave',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginRemove']= $xpdo->newObject('modEvent');
$events['OnPluginRemove']->fromArray(array (
  'name' => 'OnPluginRemove',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnPluginBeforeRemove']->fromArray(array (
  'name' => 'OnPluginBeforeRemove',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormPrerender']= $xpdo->newObject('modEvent');
$events['OnPluginFormPrerender']->fromArray(array (
  'name' => 'OnPluginFormPrerender',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormRender']= $xpdo->newObject('modEvent');
$events['OnPluginFormRender']->fromArray(array (
  'name' => 'OnPluginFormRender',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnBeforePluginFormSave']= $xpdo->newObject('modEvent');
$events['OnBeforePluginFormSave']->fromArray(array (
  'name' => 'OnBeforePluginFormSave',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormSave']= $xpdo->newObject('modEvent');
$events['OnPluginFormSave']->fromArray(array (
  'name' => 'OnPluginFormSave',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnBeforePluginFormDelete']= $xpdo->newObject('modEvent');
$events['OnBeforePluginFormDelete']->fromArray(array (
  'name' => 'OnBeforePluginFormDelete',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$events['OnPluginFormDelete']= $xpdo->newObject('modEvent');
$events['OnPluginFormDelete']->fromArray(array (
  'name' => 'OnPluginFormDelete',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);


/* Property Sets */
$events['OnPropertySetSave']= $xpdo->newObject('modEvent');
$events['OnPropertySetSave']->fromArray(array (
  'name' => 'OnPropertySetSave',
  'service' => 1,
  'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetBeforeSave']= $xpdo->newObject('modEvent');
$events['OnPropertySetBeforeSave']->fromArray(array (
  'name' => 'OnPropertySetBeforeSave',
  'service' => 1,
  'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetRemove']= $xpdo->newObject('modEvent');
$events['OnPropertySetRemove']->fromArray(array (
  'name' => 'OnPropertySetRemove',
  'service' => 1,
  'groupname' => 'Property Sets',
), '', true, true);
$events['OnPropertySetBeforeRemove']= $xpdo->newObject('modEvent');
$events['OnPropertySetBeforeRemove']->fromArray(array (
  'name' => 'OnPropertySetBeforeRemove',
  'service' => 1,
  'groupname' => 'Property Sets',
), '', true, true);

return $events;