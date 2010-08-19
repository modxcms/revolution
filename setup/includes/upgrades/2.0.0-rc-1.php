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

/* add versions and release fields to transport.modTransportPackage */
$class = 'transport.modTransportPackage';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'version_major, version_minor, version_patch, release, release_index','table' => $table));
$sql = "ALTER TABLE {$table} ADD `version_major` TINYINT(4) NOT NULL DEFAULT '0'";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD `version_minor` TINYINT(4) NOT NULL DEFAULT '0'";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD `version_patch` TINYINT(4) NOT NULL DEFAULT '0'";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD `release` VARCHAR(100) NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD `release_index` TINYINT(4) NOT NULL DEFAULT '0'";
$this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD INDEX `version_major` (`version_major`)";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD INDEX `version_minor` (`version_minor`)";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD INDEX `version_patch` (`version_patch`)";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD INDEX `release` (`release`)";
$this->processResults($class,$description,$sql);
$sql = "ALTER TABLE {$table} ADD INDEX `release_index` (`release_index`)";
$this->processResults($class,$description,$sql);

/* get current transport packages and add versions/release field data */
$packages = $this->install->xpdo->getCollection('transport.modTransportPackage');
foreach ($packages as $package) {
    $signature = $package->get('signature');
    $sig = explode('-',$signature);
    if (is_array($sig)) {
        $package->set('package_name',$sig[0]);
        if (!empty($sig[1])) {
            $v = explode('.',$sig[1]);
            if (isset($v[0])) $package->set('version_major',$v[0]);
            if (isset($v[1])) $package->set('version_minor',$v[1]);
            if (isset($v[2])) $package->set('version_patch',$v[2]);
        }
        if (!empty($sig[2])) {
            $r = preg_split('/([0-9]+)/',$sig[2],-1,PREG_SPLIT_DELIM_CAPTURE);
            if (is_array($r) && !empty($r)) {
                $package->set('release',$r[0]);
                $package->set('release_index',(isset($r[1]) ? $r[1] : '0'));
            } else {
                $package->set('release',$sig[2]);
            }
        }
        $package->save();
    }
}

/* add permissions field to modMenu */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'permissions','table' => $table));
$sql = "ALTER TABLE {$table} ADD `permissions` TEXT NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);

/* add website field to modUserProfile */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'website','table' => $table));
$sql = "ALTER TABLE {$table} ADD `website` VARCHAR(255) NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);
