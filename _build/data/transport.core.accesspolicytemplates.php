<?php
/**
 * @package modx
 * @subpackage build
 */
$templates = array();

/* administrator template/policy */
$templates['1']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['1']->fromArray(array(
    'id' => 1,
    'name' => 'AdministratorTemplate',
    'description' => 'Context administration policy template with all permissions.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.administrator.php';
if (is_array($permissions)) {
    $templates['1']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Administrator Policy Template.'); }

/* resource template/policy */
$templates['2']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['2']->fromArray(array(
    'id' => 2,
    'name' => 'ResourceTemplate',
    'description' => 'Resource Policy Template with all attributes.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.resource.php';
if (is_array($permissions)) {
    $templates['2']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Resource Template Permissions.'); }

/* object template and policies */
$templates['3']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['3']->fromArray(array(
    'id' => 3,
    'name' => 'ObjectTemplate',
    'description' => 'Object Policy Template with all attributes.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.object.php';
if (is_array($permissions)) {
    $templates['3']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Object Template Permissions.'); }

/* element template/policy */
$templates['4']= $xpdo->newObject('modAccessPolicyTemplate');
$templates['4']->fromArray(array(
    'id' => 4,
    'name' => 'ElementTemplate',
    'description' => 'Element Policy Template with all attributes.',
    'lexicon' => 'permissions',
));
$permissions = include dirname(__FILE__).'/permissions/transport.policy.tpl.element.php';
if (is_array($permissions)) {
    $templates['4']->addMany($permissions);
} else { $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load Element Template Permissions.'); }

return $templates;