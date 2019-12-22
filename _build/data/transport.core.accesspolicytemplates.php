<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
use MODX\Revolution\modAccessPolicyTemplate;

$templates = [];

/* administrator template/policy */
$templates['1']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['1']->fromArray([
    'id' => 1,
    'name' => 'AdministratorTemplate',
    'description' => 'Context administration policy template with all permissions.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.administrator.php';
if (is_array($permissions)) {
    $templates['1']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Administrator Policy Template.'); }

/* resource template/policy */
$templates['2']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['2']->fromArray([
    'id' => 2,
    'name' => 'ResourceTemplate',
    'description' => 'Resource Policy Template with all attributes.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.resource.php';
if (is_array($permissions)) {
    $templates['2']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Resource Template Permissions.'); }

/* object template and policies */
$templates['3']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['3']->fromArray([
    'id' => 3,
    'name' => 'ObjectTemplate',
    'description' => 'Object Policy Template with all attributes.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.object.php';
if (is_array($permissions)) {
    $templates['3']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Object Template Permissions.'); }

/* element template/policy */
$templates['4']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['4']->fromArray([
    'id' => 4,
    'name' => 'ElementTemplate',
    'description' => 'Element Policy Template with all attributes.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.element.php';
if (is_array($permissions)) {
    $templates['4']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Element Template Permissions.'); }

/* media source template/policy */
$templates['5']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['5']->fromArray([
    'id' => 5,
    'name' => 'MediaSourceTemplate',
    'description' => 'Media Source Policy Template with all attributes.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.media_source.php';
if (is_array($permissions)) {
    $templates['5']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Media Source Template Permissions.'); }

/* context template policies */
$templates['6']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['6']->fromArray([
    'id' => 6,
    'name' => 'ContextTemplate',
    'description' => 'Context Policy Template with all attributes.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.context.php';
if (is_array($permissions)) {
    $templates['6']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Context Template Permissions.'); }

/* namespace template/policy */
$templates['7']= $xpdo->newObject(modAccessPolicyTemplate::class);
$templates['7']->fromArray([
    'id' => 7,
    'name' => 'NamespaceTemplate',
    'description' => 'Namespace Policy Template with all attributes.',
    'lexicon' => 'permissions',
]);
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.namespace.php';
if (is_array($permissions)) {
    $templates['7']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Namespace Template Permissions.'); }

return $templates;
