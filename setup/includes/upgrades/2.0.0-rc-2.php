<?php
/**
 * Specific upgrades for Revolution 2.0.0-rc-1
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

/* Plugin Event migration */
$pluginEventTbl = $this->install->xpdo->getTableName('modPluginEvent');
$eventTbl = $this->install->xpdo->getTableName('modEvent');

/* add event field to modPluginEvent */
$description = sprintf($this->install->lexicon['add_column'],'event',$pluginEventTbl);
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
$description = sprintf($this->install->lexicon['change_column'],'context_key web','context_key web',$table);
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