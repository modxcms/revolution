<?php
/**
 * Specific upgrades for Revolution 2.1.0-rc-1
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


/* add input_properties, output_properties fields to modTemplateVar */
$class = 'modTemplateVar';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'input_properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD `input_properties` TEXT NULL AFTER `properties`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_column',array('column' => 'output_properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD `output_properties` TEXT NULL AFTER `input_properties`";
$this->processResults($class,$description,$sql);

/* migrate display_params to output_properties, change deprecated  */
$tvs = $modx->getCollection('modTemplateVar');
foreach ($tvs as $tv) {
    $changed = false;
    $op = $tv->get('output_properties');
    $params = $tv->getDisplayParams();
    if (empty($op) && !empty($params)) {
        $tv->set('output_properties',$params);
        $op = $params;
        $changed = true;
    }
    switch ($tv->get('type')) {
        case 'textareamini':
            $tv->set('type','textarea');
            $changed = true;
            break;
        case 'textbox':
            $tv->set('type','text');
            $changed = true;
            break;
        case 'dropdown':
            $tv->set('type','listbox');
            $changed = true;
            break;
        case 'htmlarea':
            $tv->set('type','richtext');
            $changed = true;
            break;
        case 'resourcelist': /* migrate parents of 2.0.x of resourcelist TV to input props */
            $elements = $tv->get('elements');
            if (!empty($elements)) {
                $elements = explode('||',$elements);
                $ip = $tv->get('input_properties');
                if (!is_array($ip)) $ip = array();
                $ip['parents'] = implode(',',$elements);
                $tv->set('input_properties',$elements);
                $changed = true;
            }
            break;
            
    }

    if ($changed) {
        $tv->save();
    }
}

/* and drop display_params field from modTemplateVar */
$sql = "ALTER TABLE {$table} DROP COLUMN `display_params`";
$this->install->xpdo->exec($sql);

/* add uri field and index to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'uri','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('uri')} TEXT NULL AFTER {$modx->escape('content_type')}";
$uriAdded = $this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD INDEX uri (uri(1000))";
$modx->exec($sql);

/* add uri_override field and index to modResource */
$description = $this->install->lexicon('add_column',array('column' => 'uri_override','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('uri_override')} TINYINT(1) NOT NULL DEFAULT 0 AFTER {$modx->escape('uri')}";
$this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD INDEX uri_override (uri_override)";
$modx->exec($sql);

if ($uriAdded && $modx->getOption('friendly_urls')) {
    $modx->call('modResource', 'refreshURIs', array(&$modx));
}

/* add hash_class and salt to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'hash_class','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN {$modx->escape('hash_class')} VARCHAR(100) NOT NULL DEFAULT 'hashing.modPBKDF2' AFTER {$modx->escape('remote_data')}";
$hashClassAdded = $this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_column',array('column' => 'salt','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN {$modx->escape('salt')} VARCHAR(100) NOT NULL DEFAULT '' AFTER {$modx->escape('hash_class')}";
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
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'description', array('after' => 'name')));

/* add username to modTransportProvider */
$class = 'transport.modTransportProvider';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'username','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'username',array('after' => 'service_url')));

$description = $this->install->lexicon('add_index',array('index' => 'username','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'username'));

/* add new permissions to all AdministratorTemplate-based policies */
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

/* add rank to modUserGroup */
$class = 'modUserGroup';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'rank'));

$description = $this->install->lexicon('add_index',array('index' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'rank'));