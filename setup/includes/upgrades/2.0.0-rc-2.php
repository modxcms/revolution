<?php
/**
 * Specific upgrades for Revolution 2.0.0-rc-2
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modAccessCategory',
    'modCategoryClosure'
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* Plugin Event migration */
$pluginEventTbl = $this->install->xpdo->getTableName('modPluginEvent');
$eventTbl = $this->install->xpdo->getTableName('modEvent');

/* add event field to modPluginEvent */
$description = $this->install->lexicon('add_column',array('column' => 'event','table' => $pluginEventTbl));
$sql = "ALTER TABLE {$pluginEventTbl} ADD `event` VARCHAR(255) NOT NULL DEFAULT '' AFTER `evtid`";
$this->processResults($class,$description,$sql);

/* resetup PKs for modPluginEvent table */
$sql = "ALTER TABLE {$pluginEventTbl} DROP PRIMARY KEY";
$this->install->xpdo->exec($sql);
$sql = "ALTER TABLE {$pluginEventTbl} ADD PRIMARY KEY (`pluginid`,`event`)";
$this->install->xpdo->exec($sql);

/* get current event names and ids */
$stmt = $this->install->xpdo->query("
    SELECT
        `PluginEvent`.`evtid` AS `evtid`,
        `Event`.`name` AS `name`
    FROM {$pluginEventTbl} AS `PluginEvent`
        INNER JOIN {$eventTbl} AS `Event` ON `Event`.`id` = `PluginEvent`.`evtid`
");
$events = array();
if (is_object($stmt) && $stmt instanceof PDOStatement) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $events[$row['evtid']] = $row['name'];
    }
    $stmt->closeCursor();
}
unset($stmt);

/* now set event field to proper name */
foreach ($events as $id => $name) {
    $sql = "UPDATE {$pluginEventTbl} SET `event` = '{$name}' WHERE `evtid` = {$id};";
    $this->install->xpdo->exec($sql);
}

/* and drop evtid field from modPluginEvent */
$sql = "ALTER TABLE {$pluginEventTbl} DROP COLUMN `evtid`";
if (!$debug) $this->install->xpdo->exec($sql);




/* change modResource.context_key to have default of 'web' */
$class = 'modResource';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('change_column',array('old' => 'context_key','new' => 'context_key DEFAULT "web"','table' => $table));
$sql = "ALTER TABLE {$table} CHANGE  `context_key` `context_key` VARCHAR(100) NOT NULL DEFAULT 'web'";
$this->processResults($class,$description,$sql);

/* remove deprecated events */
$deprecatedEvents = array(
    'OnBeforeDocGroupRemove',
    'OnContextDelete',
    'OnCreateDocGroup',
    'OnCreateUser',
    'OnDeleteUser',
    'OnDocGroupRemove',
    'OnLogPageHit',
    'OnManagerChangePassword',
    'OnManagerCreateGroup',
    'OnManagerDeleteUser',
    'OnManagerSaveUser',
    'OnUpdateUser',
    'OnUserGroupCreate',
    'OnUserGroupUpdate',
    'OnWebChangePassword',
    'OnWebCreateGroup',
    'OnWebDeleteUser',
    'OnWebSaveUser',
);
foreach ($deprecatedEvents as $eventName) {
    $event = $this->install->xpdo->getObject('modEvent',array('name' => $eventName));
    if ($event) {
        $event->remove();
    }
}


/* drop modEvent ID field */
$class = 'modEvent';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('drop_column',array('column' => 'id','table' => $table));
$sql = "ALTER TABLE {$table} DROP `id`";
$this->processResults($class,$description,$sql);

/* drop name unique index */
$class = 'modEvent';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('drop_column',array('column' => 'name','table' => $table));
$sql = "ALTER TABLE {$table} DROP INDEX `name`";
$this->processResults($class,$description,$sql);

/* add name as PK */
$sql = "ALTER TABLE {$table} ADD PRIMARY KEY (`name`)";
$this->install->xpdo->exec($sql);

/* add remote_key and remote_data to modUser */
$class = 'modUser';
$modUserTbl = $this->install->xpdo->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'remote_key','table' => $modUserTbl));
$sql = "ALTER TABLE {$modUserTbl} ADD `remote_key` VARCHAR(255) NULL DEFAULT NULL AFTER `active`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_column',array('column' => 'remote_data','table' => $modUserTbl));
$sql = "ALTER TABLE {$modUserTbl} ADD `remote_data` TEXT NULL DEFAULT NULL AFTER `remote_key`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_index',array('index' => 'remote_key','table' => $modUserTbl));
$sql = "ALTER TABLE {$modUserTbl} ADD INDEX `remote_key` (`remote_key`)";
$this->processResults($class,$description,$sql);

/* add extended to modUserProfile */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'extended','table' => $table));
$sql = "ALTER TABLE {$table} ADD `extended` TEXT NULL DEFAULT NULL AFTER `website`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_index',array('index' => 'extended','table' => $table));
$sql = "ALTER TABLE {$table} ADD FULLTEXT INDEX `extended` (`extended`)";
$this->processResults($class,$description,$sql);

/* add lexicon to modAccessPolicy */
$class = 'modAccessPolicy';
$table = $this->install->xpdo->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'lexicon','table' => $table));
$sql = "ALTER TABLE {$table} ADD `lexicon` VARCHAR(255) NOT NULL DEFAULT 'permissions' AFTER `data`";
$this->processResults($class,$description,$sql);

/* lexicon stuff */
$manager = $this->install->xpdo->getManager();

/* adjust entry table */
$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('change_column',array('old' => 'topic VARCHAR(100)','topic VARCHAR(255)','table' => $table));
$sql = "ALTER TABLE {$table} CHANGE `topic` `topic` VARCHAR(255) NOT NULL DEFAULT 'default'";
$this->processResults($class,$description,$sql);

/* remove all un-overridden Lexicon Entries */
$this->install->xpdo->getService('lexicon','modLexicon');
$c = $this->install->xpdo->newQuery('modLexiconEntry');
$c->select('
    `modLexiconEntry`.*,
    `Topic`.`name` AS `topic_name`
');
$c->where(array(
    'namespace' => 'core',
));
$c->innerJoin('modLexiconTopic','Topic','`Topic`.`id` = `modLexiconEntry`.`topic`');
$c->sortby('`Topic`.`name`');
$entries = $this->install->xpdo->getCollection('modLexiconEntry',$c);
$currentTopic = null;
$fileEntries = array();
foreach ($entries as $entry) {
    if ($currentTopic != $entry->get('topic_name')) {
        $currentTopic = $entry->get('topic_name');
        /* get new topic entries. wont need any other language than en since non-en languages didnt exist pre lex changes */
        $fileEntries = $this->install->xpdo->lexicon->getFileTopic('en','core',$currentTopic);
    }
    if (!empty($fileEntries[$entry->get('name')]) && $fileEntries[$entry->get('name')] == $entry->get('value')) {
        /* remove non-overridden entries */
        $entry->remove();
    } elseif (empty($fileEntries[$entry->get('name')])) {
        /* cleanup deprecated entries */
        $entry->remove();
    } else {
        /* reset topic to string for overridden entries */
        $entry->set('topic',$entry->get('topic_name'));
        $entry->save();
    }
}

/* drop deprecated language/topic tables */
$manager->removeObjectContainer('modLexiconLanguage');
$manager->removeObjectContainer('modLexiconTopic');


/* add api_key to modTransportProvider */
$class = 'transport.modTransportProvider';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'api_key','table' => $table));
$sql = "ALTER TABLE {$table} ADD `api_key` VARCHAR(255) NOT NULL DEFAULT '' AFTER `service_url`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_index',array('index' => 'api_key','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `api_key` (`api_key`)";
$this->processResults($class,$description,$sql);


/* (re)build modCategoryClosure data */
$class = 'modCategoryClosure';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('update_closure_table',array('class' => 'modCategory'));
$sql = "TRUNCATE TABLE {$table}";
$this->processResults($class,$description,$sql);

$categories = $this->install->xpdo->getCollection('modCategory');
foreach ($categories as $category) {
    $category->buildClosure();
}

/* add active field and index to modActionDom */
$class = 'modActionDom';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'active','table' => $table));
$sql = "ALTER TABLE {$table} ADD `active` TINYINT(1) NOT NULL DEFAULT '1' AFTER `constraint_class`";
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_index',array('index' => 'active','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `active` (`active`)";
$this->processResults($class,$description,$sql);