<?php
/**
 * Specific upgrades for Revolution 2.0.1
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modAccessCategory',
    'modCategoryClosure',
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* remove some deprecated actions */
$deprecatedActions = array(
    'resource/staticresource/update',
    'resource/staticresource/create',
    'resource/symlink/update',
    'resource/symlink/create',
    'resource/weblink/update',
    'resource/weblink/create',
);
foreach ($deprecatedActions as $controller) {
    $action = $this->install->xpdo->getObject('modAction',array(
        'controller' => $controller,
        'namespace' => 'core',
    ));
    if ($action) {
        $action->remove();
    }
}
