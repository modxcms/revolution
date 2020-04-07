<?php

use MODX\Revolution\modSession;
use MODX\Revolution\modSystemSetting;
use xPDO\xPDOException;

require_once strtr(realpath(MODX_SETUP_PATH.'includes/runner/modinstallrunner.class.php'),'\\','/');
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
class modInstallRunnerWeb extends modInstallRunner {

    public function initialize() {
        /* set the time limit infinite in case it takes a bit
         * TODO: fix this by allowing resume when it takes a long time
         */
        @ set_time_limit(0);
    }

    /**
     * Execute the installation process.
     *
     * @param integer $mode The install mode.
     *
     * @return array An array of result messages collected during execution.
     * @throws xPDOException
     */
    public function execute($mode) {
        /* write config file */
        $this->writeConfig();

        /* get connection */
        $this->install->getConnection($mode);

        /* run appropriate database routines */
        switch ($mode) {
            case modInstall::MODE_UPGRADE_REVO :
            case modInstall::MODE_UPGRADE_REVO_ADVANCED :
                $this->loadVersionInstaller();

                if (version_compare($this->versioner->version, '2.6.0', '<')) {
                    $this->addResult(modInstallRunner::RESULT_FAILURE, '<p class="notok">' . $this->install->lexicon('upgrade_unsupported_version', ['version' => $this->versioner->version]) . '</p>');
                    return $this->getResults();
                }

                $this->versioner->install();
                break;
                /* new install, create tables */
            default :
                $modx =& $this->install->xpdo;
                $install =& $this->install;
                include MODX_SETUP_PATH . 'includes/tables_create.php';
                break;
        }

        if ($this->install->xpdo) {
            if (!$this->installPackage()) {
                return $this->getResults();
            }

            $this->updateWorkspace();

            $modx =& $this->install->xpdo;
            $settings =& $this->install->settings;
            $install =& $this->install;

            /* if new install */
            if ($mode == modInstall::MODE_NEW) {
                include MODX_SETUP_PATH.'includes/new.install.php';

            /* if upgrade */
            } else {
                include MODX_SETUP_PATH.'includes/upgrade.install.php';
            }

            $this->postRun();

            $this->success = true;
        }

        return $this->getResults();
    }

    /**
     * Do post-run cleanups
     * @return void
     */
    public function cleanup() {
        /* empty sessions table to prevent old permissions from loading */
        $tableName = $this->install->xpdo->getTableName(modSession::class);
        $this->install->xpdo->exec($this->install->driver->truncate($tableName));

        /* clear cache */
        $this->install->xpdo->cacheManager->deleteTree(MODX_CORE_PATH.'cache/', [
            'skipDirs' => false,
            'extensions' => [
                '.cache.php',
                '.tpl.php',
            ],
        ]);

        $this->install->lock();

        $this->install->settings->store([
            'finished' => true,
        ]);
    }

    public function postRun() {
        $compressJs = $this->install->settings->get('compress_js');
        if ($compressJs === 0) {
            /** @var modSystemSetting $setting */
            $setting = $this->install->xpdo->getObject(modSystemSetting::class, [
                'key' => 'compress_js',
            ]);
            if (empty($setting)) {
                $setting = $this->install->xpdo->newObject(modSystemSetting::class);
                $setting->fromArray([
                    'key' => 'compress_js',
                    'xtype' => 'combo-boolean',
                    'namespace' => 'core',
                    'area' => 'manager',
                ],'',true);
            }
            $setting->set('value',0);
            $setting->save();
        }
        $compressCss = $this->install->settings->get('compress_css');
        if ($compressCss === 0) {
            /** @var modSystemSetting $setting */
            $setting = $this->install->xpdo->getObject(modSystemSetting::class, [
                'key' => 'compress_css',
            ]);
            if (empty($setting)) {
                $setting = $this->install->xpdo->newObject(modSystemSetting::class);
                $setting->fromArray([
                    'key' => 'compress_css',
                    'xtype' => 'combo-boolean',
                    'namespace' => 'core',
                    'area' => 'manager',
                ],'',true);
            }
            $setting->set('value',0);
            $setting->save();
        }

        // If the user opted in to send the powered by header, enable the setting here
        // The setting is installed disabled by default, so we don't have to force it off if unchecked
        $sendPoweredByHeader = $this->install->settings->get('send_poweredby_header');
        if (!empty($sendPoweredByHeader)) {
            $setting = $this->install->xpdo->getObject(modSystemSetting::class, [
                'key' => 'send_poweredby_header',
            ]);
            if ($setting instanceof modSystemSetting) {
                $setting->set('value', 1);
                $setting->save();
            }
        }
        return true;
    }
}
