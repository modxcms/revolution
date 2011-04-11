<?php
/**
 * Specific upgrades for Revolution 2.1.0-rc-1 on sqlsrv.
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


/* add uri field and index to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'uri','table' => $table));
$sql = "ALTER TABLE {$table} ADD [uri] NVARCHAR(1000) NULL"; // AFTER {$modx->escape('content_type')}
$uriAdded = $this->processResults($class,$description,$sql);

$sql = "CREATE INDEX [uri] ON {$table} ([uri])";
$modx->exec($sql);

$description = $this->install->lexicon('add_column',array('column' => 'uri_override','table' => $table));
$sql = "ALTER TABLE {$table} ADD [uri_override] BIT NOT NULL DEFAULT 0"; // AFTER {$modx->escape('uri')}
$this->processResults($class,$description,$sql);

$sql = "CREATE INDEX [uri_override] ON {$table} ([uri_override])";
$modx->exec($sql);

if ($uriAdded && $modx->getOption('friendly_urls')) {
    $modx->call('modResource', 'refreshURIs', array(&$modx));
}

/* add hash_class and salt to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'hash_class','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('hash_class')} NVARCHAR(100) NOT NULL DEFAULT 'hashing.modPBKDF2'"; // AFTER {$modx->escape('remote_data')}
$hashClassAdded = $this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_column',array('column' => 'salt','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('salt')} NVARCHAR(100) NOT NULL DEFAULT ''"; // AFTER {$modx->escape('hash_class')}
$this->processResults($class,$description,$sql);

if ($hashClassAdded) {
    $modx->exec("UPDATE {$table} SET {$modx->escape('hash_class')} = 'hashing.modMD5'");
}

/* remove haskeywords column in modResource */
$modx->getManager();

$class = 'modResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('drop_column',array('column' => 'haskeywords', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'haskeywords'));

/* remove hasmetatags column in modResource */
$description = $this->install->lexicon('drop_column',array('column' => 'hasmetatags', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'hasmetatags'));

/* remove modUserProfile.role column */
$class = 'modUserProfile';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('drop_column', array('column' => 'role', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'role'));

/* add description field to modUserGroup */
$class = 'modUserGroup';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'description','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'description'));

/* add username to modTransportProvider */
$class = 'transport.modTransportProvider';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'username','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'username',array(
    'after' => 'service_url',
)));

$description = $this->install->lexicon('add_index',array('index' => 'username','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'username'));

/* add tree_show_resource_ids+tree_show_element_ids to all AdministratorTemplate-based policies */
$adminTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'AdministratorTemplate'));
if ($adminTpl) {
    $policies = $adminTpl->getMany('Policies');
    if (!empty($policies) && is_array($policies)) {
        foreach ($policies as $policy) {
            $changed = false;
            $data = $policy->get('data');
            if (!isset($data['tree_show_resource_ids'])) {
                $data['tree_show_resource_ids'] = true;
                $changed = true;
            }
            if (!isset($data['tree_show_element_ids'])) {
                $data['tree_show_element_ids'] = true;
                $changed = true;
            }
            if (!isset($data['resource_quick_create'])) {
                $data['resource_quick_create'] = true;
                $changed = true;
            }
            if (!isset($data['resource_quick_update'])) {
                $data['resource_quick_update'] = true;
                $changed = true;
            }
            if ($changed) {
                ksort($data);
                $policy->set('data',$data);
                $policy->save();
            }
        }
    }
}

/* change help/welcome screen URL references */
$setting = $modx->getObject('modSystemSetting',array('key' => 'welcome_screen_url'));
if ($setting && $setting->get('value') == 'http://misc.modx.com/revolution/welcome.20.html') {
    $setting->set('value','http://misc.modx.com/revolution/welcome.21.html');
    $setting->save();
}