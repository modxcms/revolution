<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-5
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

/* add unique index to modLexiconEntry */
$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_index',array('index' => 'uniqentry','table' => $table));
$sql = "ALTER TABLE  {$table} ADD UNIQUE `uniqentry` (`name`,`topic`,`namespace`,`language`)";
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);


/* add city field to modUserProfile */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'city','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `city` VARCHAR(255) NOT NULL DEFAULT '' AFTER `country`";
$this->processResults($class,$description,$sql);

/* adjust country field to modUserProfile */
$description = $this->install->lexicon('add_column',array('column' => 'country','table' => $table));
$sql = "ALTER TABLE {$table} CHANGE `country` `country` VARCHAR( 255 ) NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);


/* add address field to modUserProfile */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'address','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `address` TEXT NOT NULL DEFAULT '' AFTER `gender`";
$this->processResults($class,$description,$sql);

/* change session.id field to precision 40 */
$class = 'modSession';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('change_column',array('old' => 'id varchar(32)','new' => 'id varchar(40)','table' => $table));
$sql = "ALTER TABLE {$table} CHANGE `id` `id` VARCHAR(40) NOT NULL";
$this->processResults($class,$description,$sql);


/* add help_url field to modAction */
$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'help_url','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `help_url` TEXT NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);

/* reset provider URL to new provider location */
$provider = $this->install->xpdo->getObject('transport.modTransportProvider',array(
    'name' => 'modxcms.com',
));
if (empty($provider)) {
    $provider = $this->install->xpdo->newObject('transport.modTransportProvider');
    $provider->set('name','modxcms.com');
    $provider->set('description','The official MODX transport facility for 3rd party components.');
    $provider->set('created',strftime('%Y-%m-%d %H:%M:%S'));
}
$provider->set('service_url','http://rest.modxcms.com/extras/');
$provider->save();

/* add package, metadata fields to modTransportPackage */
$class = 'transport.modTransportPackage';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'package_name','table' => $table));
$sql = "ALTER TABLE {$table} ADD `package_name` VARCHAR(255) NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD  `metadata` TEXT NULL";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD INDEX `package_name` (`package_name`)";
$this->processResults($class,$description,$sql);

/* get current transport packages and add package field data */
$packages = $this->install->xpdo->getCollection('transport.modTransportPackage');
foreach ($packages as $package) {
    $signature = $package->get('signature');
    $sig = explode('-',$signature);
    if (is_array($sig)) {
        $package->set('package_name',$sig[0]);
        $package->save();
    }
}