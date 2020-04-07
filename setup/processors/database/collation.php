<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package setup
 */
$settings = $_POST;
$settings['database_charset'] = $settings['database_connection_charset'];
unset($settings['action']);
$install->settings->store($settings);
$mode = $install->settings->get('installmode');
/* if advanced upgrade, get old settings */
if($mode === modInstall::MODE_UPGRADE_REVO_ADVANCED) {
    $settings = array_merge($settings, $install->request->getConfig($mode));
}

$data = [];
$errors = [];

/* get an instance of xPDO using the install settings */
$xpdo = $install->getConnection($mode);
$dbExists = false;
if (!is_object($xpdo) || !($xpdo instanceof \xPDO\xPDO)) {
    $this->error->failure($install->lexicon('xpdo_err_ins'));
}

$xpdo->setLogTarget(
    [
    'target' => 'ARRAY'
    ,'options' => ['var' => & $errors]
    ]
);

/* try to get a connection to the actual database */
$dbExists = $xpdo->connect();
if (!$dbExists) {
    if ($mode != modInstall::MODE_NEW) {
        $this->error->failure($install->lexicon('db_err_connect_upgrade'), $errors);
    } elseif ($xpdo->getManager()) {
        /* otherwise try to create the database */
        $dbExists = $xpdo->manager->createSourceContainer(
            [
                'dbname' => $install->settings->get('dbase')
                ,'host' => $install->settings->get('database_server')
            ]
            , $install->settings->get('database_user')
            , $install->settings->get('database_password')
            ,
            [
                'charset' => $install->settings->get('database_connection_charset')
                ,'collation' => $install->settings->get('database_collation')
            ]
        );
        if (!$dbExists) {
            $this->error->failure($install->lexicon('db_err_create_database'), $errors);
        } else {
            $xpdo = $install->getConnection($mode);
            if (!is_object($xpdo) || !($xpdo instanceof \xPDO\xPDO)) {
                $this->error->failure($install->lexicon('xpdo_err_ins'), $errors);
            }
            $xpdo->setLogTarget(
                [
                'target' => 'ARRAY'
                ,'options' => ['var' => & $errors]
                ]
            );
        }
    } else {
        $this->error->failure($install->lexicon('db_err_connect_server'), $errors);
    }
}
if (!$xpdo->connect()) {
    $this->error->failure($install->lexicon('db_err_connect'), $errors);
}

$this->error->success($install->lexicon('db_success'), $data);
