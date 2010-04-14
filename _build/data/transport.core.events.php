<?php
$collection['1']= $xpdo->newObject('modEvent');
$collection['1']->fromArray(array (
  'id' => 1,
  'name' => 'OnDocPublished',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['2']= $xpdo->newObject('modEvent');
$collection['2']->fromArray(array (
  'id' => 2,
  'name' => 'OnDocUnPublished',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['3']= $xpdo->newObject('modEvent');
$collection['3']->fromArray(array (
  'id' => 3,
  'name' => 'OnWebPagePrerender',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['4']= $xpdo->newObject('modEvent');
$collection['4']->fromArray(array (
  'id' => 4,
  'name' => 'OnWebLogin',
  'service' => 3,
  'groupname' => '',
), '', true, true);
$collection['5']= $xpdo->newObject('modEvent');
$collection['5']->fromArray(array (
  'id' => 5,
  'name' => 'OnBeforeWebLogout',
  'service' => 3,
  'groupname' => '',
), '', true, true);
$collection['6']= $xpdo->newObject('modEvent');
$collection['6']->fromArray(array (
  'id' => 6,
  'name' => 'OnWebLogout',
  'service' => 3,
  'groupname' => '',
), '', true, true);
$collection['10']= $xpdo->newObject('modEvent');
$collection['10']->fromArray(array (
  'id' => 10,
  'name' => 'OnWebCreateGroup',
  'service' => 3,
  'groupname' => '',
), '', true, true);
$collection['11']= $xpdo->newObject('modEvent');
$collection['11']->fromArray(array (
  'id' => 11,
  'name' => 'OnManagerLogin',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['12']= $xpdo->newObject('modEvent');
$collection['12']->fromArray(array (
  'id' => 12,
  'name' => 'OnBeforeManagerLogout',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['13']= $xpdo->newObject('modEvent');
$collection['13']->fromArray(array (
  'id' => 13,
  'name' => 'OnManagerLogout',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['14']= $xpdo->newObject('modEvent');
$collection['14']->fromArray(array (
  'id' => 14,
  'name' => 'OnManagerSaveUser',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['15']= $xpdo->newObject('modEvent');
$collection['15']->fromArray(array (
  'id' => 15,
  'name' => 'OnManagerDeleteUser',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['17']= $xpdo->newObject('modEvent');
$collection['17']->fromArray(array (
  'id' => 17,
  'name' => 'OnManagerCreateGroup',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['18']= $xpdo->newObject('modEvent');
$collection['18']->fromArray(array (
  'id' => 18,
  'name' => 'OnBeforeCacheUpdate',
  'service' => 4,
  'groupname' => '',
), '', true, true);
$collection['19']= $xpdo->newObject('modEvent');
$collection['19']->fromArray(array (
  'id' => 19,
  'name' => 'OnCacheUpdate',
  'service' => 4,
  'groupname' => '',
), '', true, true);
$collection['20']= $xpdo->newObject('modEvent');
$collection['20']->fromArray(array (
  'id' => 20,
  'name' => 'OnLoadWebPageCache',
  'service' => 4,
  'groupname' => '',
), '', true, true);
$collection['21']= $xpdo->newObject('modEvent');
$collection['21']->fromArray(array (
  'id' => 21,
  'name' => 'OnBeforeSaveWebPageCache',
  'service' => 4,
  'groupname' => '',
), '', true, true);
$collection['22']= $xpdo->newObject('modEvent');
$collection['22']->fromArray(array (
  'id' => 22,
  'name' => 'OnChunkFormPrerender',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$collection['23']= $xpdo->newObject('modEvent');
$collection['23']->fromArray(array (
  'id' => 23,
  'name' => 'OnChunkFormRender',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$collection['24']= $xpdo->newObject('modEvent');
$collection['24']->fromArray(array (
  'id' => 24,
  'name' => 'OnBeforeChunkFormSave',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$collection['25']= $xpdo->newObject('modEvent');
$collection['25']->fromArray(array (
  'id' => 25,
  'name' => 'OnChunkFormSave',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$collection['26']= $xpdo->newObject('modEvent');
$collection['26']->fromArray(array (
  'id' => 26,
  'name' => 'OnBeforeChunkFormDelete',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$collection['27']= $xpdo->newObject('modEvent');
$collection['27']->fromArray(array (
  'id' => 27,
  'name' => 'OnChunkFormDelete',
  'service' => 1,
  'groupname' => 'Chunks',
), '', true, true);
$collection['28']= $xpdo->newObject('modEvent');
$collection['28']->fromArray(array (
  'id' => 28,
  'name' => 'OnDocFormPrerender',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['29']= $xpdo->newObject('modEvent');
$collection['29']->fromArray(array (
  'id' => 29,
  'name' => 'OnDocFormRender',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['30']= $xpdo->newObject('modEvent');
$collection['30']->fromArray(array (
  'id' => 30,
  'name' => 'OnBeforeDocFormSave',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['31']= $xpdo->newObject('modEvent');
$collection['31']->fromArray(array (
  'id' => 31,
  'name' => 'OnDocFormSave',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['32']= $xpdo->newObject('modEvent');
$collection['32']->fromArray(array (
  'id' => 32,
  'name' => 'OnBeforeDocFormDelete',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['33']= $xpdo->newObject('modEvent');
$collection['33']->fromArray(array (
  'id' => 33,
  'name' => 'OnDocFormDelete',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['34']= $xpdo->newObject('modEvent');
$collection['34']->fromArray(array (
  'id' => 34,
  'name' => 'OnPluginFormPrerender',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$collection['35']= $xpdo->newObject('modEvent');
$collection['35']->fromArray(array (
  'id' => 35,
  'name' => 'OnPluginFormRender',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$collection['36']= $xpdo->newObject('modEvent');
$collection['36']->fromArray(array (
  'id' => 36,
  'name' => 'OnBeforePluginFormSave',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$collection['37']= $xpdo->newObject('modEvent');
$collection['37']->fromArray(array (
  'id' => 37,
  'name' => 'OnPluginFormSave',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$collection['38']= $xpdo->newObject('modEvent');
$collection['38']->fromArray(array (
  'id' => 38,
  'name' => 'OnBeforePluginFormDelete',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$collection['39']= $xpdo->newObject('modEvent');
$collection['39']->fromArray(array (
  'id' => 39,
  'name' => 'OnPluginFormDelete',
  'service' => 1,
  'groupname' => 'Plugins',
), '', true, true);
$collection['40']= $xpdo->newObject('modEvent');
$collection['40']->fromArray(array (
  'id' => 40,
  'name' => 'OnSnipFormPrerender',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$collection['41']= $xpdo->newObject('modEvent');
$collection['41']->fromArray(array (
  'id' => 41,
  'name' => 'OnSnipFormRender',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$collection['42']= $xpdo->newObject('modEvent');
$collection['42']->fromArray(array (
  'id' => 42,
  'name' => 'OnBeforeSnipFormSave',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$collection['43']= $xpdo->newObject('modEvent');
$collection['43']->fromArray(array (
  'id' => 43,
  'name' => 'OnSnipFormSave',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$collection['44']= $xpdo->newObject('modEvent');
$collection['44']->fromArray(array (
  'id' => 44,
  'name' => 'OnBeforeSnipFormDelete',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$collection['45']= $xpdo->newObject('modEvent');
$collection['45']->fromArray(array (
  'id' => 45,
  'name' => 'OnSnipFormDelete',
  'service' => 1,
  'groupname' => 'Snippets',
), '', true, true);
$collection['46']= $xpdo->newObject('modEvent');
$collection['46']->fromArray(array (
  'id' => 46,
  'name' => 'OnTempFormPrerender',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$collection['47']= $xpdo->newObject('modEvent');
$collection['47']->fromArray(array (
  'id' => 47,
  'name' => 'OnTempFormRender',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$collection['48']= $xpdo->newObject('modEvent');
$collection['48']->fromArray(array (
  'id' => 48,
  'name' => 'OnBeforeTempFormSave',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$collection['49']= $xpdo->newObject('modEvent');
$collection['49']->fromArray(array (
  'id' => 49,
  'name' => 'OnTempFormSave',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$collection['50']= $xpdo->newObject('modEvent');
$collection['50']->fromArray(array (
  'id' => 50,
  'name' => 'OnBeforeTempFormDelete',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$collection['51']= $xpdo->newObject('modEvent');
$collection['51']->fromArray(array (
  'id' => 51,
  'name' => 'OnTempFormDelete',
  'service' => 1,
  'groupname' => 'Templates',
), '', true, true);
$collection['52']= $xpdo->newObject('modEvent');
$collection['52']->fromArray(array (
  'id' => 52,
  'name' => 'OnTVFormPrerender',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$collection['53']= $xpdo->newObject('modEvent');
$collection['53']->fromArray(array (
  'id' => 53,
  'name' => 'OnTVFormRender',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$collection['54']= $xpdo->newObject('modEvent');
$collection['54']->fromArray(array (
  'id' => 54,
  'name' => 'OnBeforeTVFormSave',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$collection['55']= $xpdo->newObject('modEvent');
$collection['55']->fromArray(array (
  'id' => 55,
  'name' => 'OnTVFormSave',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$collection['56']= $xpdo->newObject('modEvent');
$collection['56']->fromArray(array (
  'id' => 56,
  'name' => 'OnBeforeTVFormDelete',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$collection['57']= $xpdo->newObject('modEvent');
$collection['57']->fromArray(array (
  'id' => 57,
  'name' => 'OnTVFormDelete',
  'service' => 1,
  'groupname' => 'Template Variables',
), '', true, true);
$collection['58']= $xpdo->newObject('modEvent');
$collection['58']->fromArray(array (
  'id' => 58,
  'name' => 'OnUserFormPrerender',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$collection['59']= $xpdo->newObject('modEvent');
$collection['59']->fromArray(array (
  'id' => 59,
  'name' => 'OnUserFormRender',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$collection['60']= $xpdo->newObject('modEvent');
$collection['60']->fromArray(array (
  'id' => 60,
  'name' => 'OnBeforeUserFormSave',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$collection['61']= $xpdo->newObject('modEvent');
$collection['61']->fromArray(array (
  'id' => 61,
  'name' => 'OnUserFormSave',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$collection['62']= $xpdo->newObject('modEvent');
$collection['62']->fromArray(array (
  'id' => 62,
  'name' => 'OnBeforeUserFormDelete',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$collection['63']= $xpdo->newObject('modEvent');
$collection['63']->fromArray(array (
  'id' => 63,
  'name' => 'OnUserFormDelete',
  'service' => 1,
  'groupname' => 'Users',
), '', true, true);
$collection['70']= $xpdo->newObject('modEvent');
$collection['70']->fromArray(array (
  'id' => 70,
  'name' => 'OnSiteRefresh',
  'service' => 1,
  'groupname' => '',
), '', true, true);
$collection['71']= $xpdo->newObject('modEvent');
$collection['71']->fromArray(array (
  'id' => 71,
  'name' => 'OnFileManagerUpload',
  'service' => 1,
  'groupname' => '',
), '', true, true);
$collection['78']= $xpdo->newObject('modEvent');
$collection['78']->fromArray(array (
  'id' => 78,
  'name' => 'OnBeforeWebLogin',
  'service' => 3,
  'groupname' => '',
), '', true, true);
$collection['79']= $xpdo->newObject('modEvent');
$collection['79']->fromArray(array (
  'id' => 79,
  'name' => 'OnWebAuthentication',
  'service' => 3,
  'groupname' => '',
), '', true, true);
$collection['80']= $xpdo->newObject('modEvent');
$collection['80']->fromArray(array (
  'id' => 80,
  'name' => 'OnBeforeManagerLogin',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['81']= $xpdo->newObject('modEvent');
$collection['81']->fromArray(array (
  'id' => 81,
  'name' => 'OnManagerAuthentication',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['82']= $xpdo->newObject('modEvent');
$collection['82']->fromArray(array (
  'id' => 82,
  'name' => 'OnSiteSettingsRender',
  'service' => 1,
  'groupname' => 'System Settings',
), '', true, true);
$collection['87']= $xpdo->newObject('modEvent');
$collection['87']->fromArray(array (
  'id' => 87,
  'name' => 'OnRichTextEditorRegister',
  'service' => 1,
  'groupname' => 'RichText Editor',
), '', true, true);
$collection['88']= $xpdo->newObject('modEvent');
$collection['88']->fromArray(array (
  'id' => 88,
  'name' => 'OnRichTextEditorInit',
  'service' => 1,
  'groupname' => 'RichText Editor',
), '', true, true);
$collection['89']= $xpdo->newObject('modEvent');
$collection['89']->fromArray(array (
  'id' => 89,
  'name' => 'OnManagerPageInit',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['90']= $xpdo->newObject('modEvent');
$collection['90']->fromArray(array (
  'id' => 90,
  'name' => 'OnWebPageInit',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['91']= $xpdo->newObject('modEvent');
$collection['91']->fromArray(array (
  'id' => 91,
  'name' => 'OnLoadWebDocument',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['92']= $xpdo->newObject('modEvent');
$collection['92']->fromArray(array (
  'id' => 92,
  'name' => 'OnParseDocument',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['93']= $xpdo->newObject('modEvent');
$collection['93']->fromArray(array (
  'id' => 93,
  'name' => 'OnManagerLoginFormRender',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['94']= $xpdo->newObject('modEvent');
$collection['94']->fromArray(array (
  'id' => 94,
  'name' => 'OnWebPageComplete',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['96']= $xpdo->newObject('modEvent');
$collection['96']->fromArray(array (
  'id' => 96,
  'name' => 'OnBeforeManagerPageInit',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['97']= $xpdo->newObject('modEvent');
$collection['97']->fromArray(array (
  'id' => 97,
  'name' => 'OnBeforeEmptyTrash',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['98']= $xpdo->newObject('modEvent');
$collection['98']->fromArray(array (
  'id' => 98,
  'name' => 'OnEmptyTrash',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['99']= $xpdo->newObject('modEvent');
$collection['99']->fromArray(array (
  'id' => 99,
  'name' => 'OnManagerLoginFormPrerender',
  'service' => 2,
  'groupname' => '',
), '', true, true);
$collection['100']= $xpdo->newObject('modEvent');
$collection['100']->fromArray(array (
  'id' => 100,
  'name' => 'OnCreateDocGroup',
  'service' => 1,
  'groupname' => 'Documents',
), '', true, true);
$collection['101']= $xpdo->newObject('modEvent');
$collection['101']->fromArray(array (
  'id' => 101,
  'name' => 'OnPageUnauthorized',
  'service' => 1,
  'groupname' => '',
), '', true, true);
$collection['102']= $xpdo->newObject('modEvent');
$collection['102']->fromArray(array (
  'id' => 102,
  'name' => 'OnPageNotFound',
  'service' => 1,
  'groupname' => '',
), '', true, true);
$collection['103']= $xpdo->newObject('modEvent');
$collection['103']->fromArray(array (
  'id' => 103,
  'name' => 'OnContextCreate',
  'service' => 2,
  'groupname' => 'modContext',
), '', true, true);
$collection['104']= $xpdo->newObject('modEvent');
$collection['104']->fromArray(array (
  'id' => 104,
  'name' => 'OnContextUpdate',
  'service' => 2,
  'groupname' => 'modContext',
), '', true, true);
$collection['105']= $xpdo->newObject('modEvent');
$collection['105']->fromArray(array (
  'id' => 105,
  'name' => 'OnContextDelete',
  'service' => 2,
  'groupname' => 'modContext',
), '', true, true);
$collection['106']= $xpdo->newObject('modEvent');
$collection['106']->fromArray(array (
  'id' => 106,
  'name' => 'OnContextFormPrerender',
  'service' => 2,
  'groupname' => 'modContext',
), '', true, true);
$collection['107']= $xpdo->newObject('modEvent');
$collection['107']->fromArray(array (
  'id' => 107,
  'name' => 'OnContextFormRender',
  'service' => 2,
  'groupname' => 'modContext',
), '', true, true);
$collection['108']= $xpdo->newObject('modEvent');
$collection['108']->fromArray(array (
  'id' => 108,
  'name' => 'OnUserNotFound',
  'service' => 6,
  'groupname' => 'modUser',
), '', true, true);
$collection['109']= $xpdo->newObject('modEvent');
$collection['109']->fromArray(array (
  'id' => 109,
  'name' => 'OnHandleRequest',
  'service' => 5,
  'groupname' => '',
), '', true, true);
$collection['110']= $xpdo->newObject('modEvent');
$collection['110']->fromArray(array (
  'id' => 110,
  'name' => 'OnPluginEventRemove',
  'service' => 1,
  'groupname' => 'modPluginEvent',
), '', true, true);
$collection['111']= $xpdo->newObject('modEvent');
$collection['111']->fromArray(array (
  'id' => 111,
  'name' => 'OnDocGroupRemove',
  'service' => 1,
  'groupname' => 'modResourceGroup',
), '', true, true);
$collection['112']= $xpdo->newObject('modEvent');
$collection['112']->fromArray(array (
  'id' => 112,
  'name' => 'OnBeforeDocGroupRemove',
  'service' => 1,
  'groupname' => 'modResourceGroup',
), '', true, true);
$collection['113']= $xpdo->newObject('modEvent');
$collection['113']->fromArray(array (
  'id' => 113,
  'name' => 'OnCreateUser',
  'service' => 1,
  'groupname' => 'modUser',
), '', true, true);
$collection['114']= $xpdo->newObject('modEvent');
$collection['114']->fromArray(array (
  'id' => 114,
  'name' => 'OnDeleteUser',
  'service' => 1,
  'groupname' => 'modUser',
), '', true, true);
$collection['115']= $xpdo->newObject('modEvent');
$collection['115']->fromArray(array (
  'id' => 115,
  'name' => 'OnUpdateUser',
  'service' => 1,
  'groupname' => 'modUser',
), '', true, true);
$collection['116']= $xpdo->newObject('modEvent');
$collection['116']->fromArray(array (
  'id' => 116,
  'name' => 'OnBeforeUserActivate',
  'service' => 1,
  'groupname' => 'modUser',
), '', true, true);
$collection['117']= $xpdo->newObject('modEvent');
$collection['117']->fromArray(array (
  'id' => 117,
  'name' => 'OnUserActivate',
  'service' => 1,
  'groupname' => 'modUser',
), '', true, true);
$collection['118']= $xpdo->newObject('modEvent');
$collection['118']->fromArray(array (
  'id' => 118,
  'name' => 'OnRichTextBrowserInit',
  'service' => 1,
  'groupname' => 'RichText Editor',
), '', true, true);
$collection['119']= $xpdo->newObject('modEvent');
$collection['119']->fromArray(array (
  'id' => 119,
  'name' => 'OnInitCulture',
  'service' => 3,
  'groupname' => '',
), '', true, true);