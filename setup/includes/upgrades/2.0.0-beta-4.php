<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-4
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* remove old providers */
$c = $this->install->xpdo->newQuery('transport.modTransportProvider');
$c->where(array(
    'service_url' => 'http://wtf.modxcms.com/addons2.js ',
));
$c->orCondition(array(
    'service_url' => 'http://wtf.modxcms.com/repos/addons.js',
));
$providers = $this->install->xpdo->getCollection('transport.modTransportProvider',$c);
foreach ($providers as $provider) { $provider->remove(); }
unset($c,$providers,$provider);

/* remove old security modAction objects */
$c = $this->install->xpdo->newQuery('modAction');
$c->where(array(
    'controller' => 'security/access',
));
$c->orCondition(array(
    'controller' => 'security/access/policy',
));
$actions = $this->install->xpdo->getCollection('modAction',$c);
foreach ($actions as $action) { $action->remove(); }
unset($c,$actions,$action);

/* remove old security modMenu objects */
$c = $this->install->xpdo->newQuery('modMenu');
$c->where(array(
    'text' => 'access_permissions',
));
$c->orCondition(array(
    'text' => 'policy_management',
));
$menus = $this->install->xpdo->getCollection('modMenu',$c);
foreach ($menus as $menu) { $menu->remove(); }
unset($c,$menus,$menu);

/* remove old security policy */
$c = $this->install->xpdo->newQuery('modAccessPolicy');
$c->where(array(
    'name' => 'Context',
));
$policy = $this->install->xpdo->getObject('modAccessPolicy',$c);
if ($policy != null) { $policy->remove(); }
unset($c,$policy);