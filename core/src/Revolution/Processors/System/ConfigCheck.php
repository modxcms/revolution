<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System;

use MODX\Revolution\modContextSetting;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modSystemSetting;
use PDO;

/**
 * Runs a config check
 * @package MODX\Revolution\Processors\System
 */
class ConfigCheck extends Processor
{
    protected $warnings = [];

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->lexicon->load('configcheck');

        $this->checkRemoteAccess();
        $this->checkSystem();
        $this->checkPaths();
        $this->checkResources();
        $this->checkSettings();

        return count($this->warnings) ? $this->failure('', $this->warnings) : $this->success();
    }


    /**
     * @param $key
     * @return bool
     */
    protected function ini_get_bool($key)
    {
        $value = ini_get($key);

        switch (strtolower($value)) {
            case 'on':
            case 'yes':
            case 'true':
                $value = 'assert.active' !== $key;
                break;
            case 'stdout':
            case 'stderr':
                $value = 'display_errors' === $key;
                break;
            default:
                $value = (int)$value;
                break;
        }

        return (bool)$value;
    }


    /**
     * @param $criteria
     * @return mixed|null
     */
    protected function getResource($criteria)
    {
        $resource = null;
        $c = $this->modx->newQuery(modResource::class);
        $c->select(['id', 'published']);
        $c->where($criteria);
        if ($c->prepare() && $c->stmt->execute() && $row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
            $resource = $row;
        }

        return $resource;
    }


    /**
     * @param $url
     * @return bool|mixed
     */
    protected function makeRequest($url)
    {
        if (!function_exists('curl_init')) {
            return false;
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_RANGE, '0-0');
        $openBasedir = @ini_get('open_basedir');
        if (empty($openBasedir)) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP + CURLPROTO_HTTPS);
        }

        curl_exec($curl);
        $response = curl_getinfo($curl);
        $err = curl_errno($curl);
        curl_close($curl);

        return !$err ? $response : false;
    }

    public function checkRemoteAccess()
    {
        $real_base = realpath(MODX_BASE_PATH);
        $real_core = realpath(MODX_CORE_PATH);
        if (strpos($real_core, $real_base) === 0) {
            $core_name = ltrim(str_replace($real_base, '', $real_core), '/');
            $url = $this->modx->getOption('site_url') . $core_name . '/docs/changelog.txt';
            if ($response = $this->makeRequest($url)) {
                $http_code = $response['http_code'];
                if ($http_code >= 200 && $http_code <= 210) {
                    $this->warnings[$this->modx->lexicon('configcheck_htaccess')] = $this->modx->lexicon('configcheck_htaccess_msg',
                        [
                            'checkUrl' => $url,
                            'fileLocation' => $real_core,
                        ]);
                }
            }
        }
    }

    public function checkSystem()
    {
        $require = $this->modx->getOption('configcheck_min_phpversion', null, '7.2.5');

        $compare = version_compare(PHP_VERSION, $require, '>=');
        if (!$compare) {
            $this->warnings[$this->modx->lexicon('configcheck_phpversion')] = $this->modx->lexicon('configcheck_phpversion_msg',
                [
                    'phpversion' => PHP_VERSION,
                    'phprequired' => $require,
                ]);
        }

        if (@$this->ini_get_bool('register_globals')) {
            $this->warnings[$this->modx->lexicon('configcheck_register_globals')] = $this->modx->lexicon('configcheck_register_globals_msg');
        }
    }

    public function checkPaths()
    {
        $config = MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
        if (is_writable($config) && @fileperms($config) & 0x0002) {
            $this->warnings[$this->modx->lexicon('configcheck_configinc')] = $this->modx->lexicon('configcheck_configinc_msg',
                [
                    'path' => $config,
                ]);
        }

        $setup = MODX_BASE_PATH . 'setup/';
        if (is_dir($setup)) {
            $this->warnings[$this->modx->lexicon('configcheck_installer')] = $this->modx->lexicon('configcheck_installer_msg',
                [
                    'path' => $setup,
                ]);
        }

        $cache = $this->modx->getCachePath();
        if (!is_writable($cache)) {
            $this->warnings[$this->modx->lexicon('configcheck_cache')] = $this->modx->lexicon('configcheck_cache_msg');
        }
    }


    public function checkResources()
    {
        $auth = $this->getResource($this->modx->getOption('unauthorized_page', null, 1));
        if (!$auth) {
            $this->warnings[$this->modx->lexicon('configcheck_unauthorizedpage_unavailable')] = $this->modx->lexicon('configcheck_unauthorizedpage_unavailable_msg');
        } elseif (!$auth['published']) {
            $this->warnings[$this->modx->lexicon('configcheck_unauthorizedpage_unpublished')] = $this->modx->lexicon('configcheck_unauthorizedpage_unpublished_msg');
        }

        $err = $this->getResource($this->modx->getOption('error_page', null, 1));
        if (!$err) {
            $this->warnings[$this->modx->lexicon('configcheck_errorpage_unavailable')] = $this->modx->lexicon('configcheck_errorpage_unavailable_msg');
        } elseif (!$err['published']) {
            $this->warnings[$this->modx->lexicon('configcheck_errorpage_unpublished')] = $this->modx->lexicon('configcheck_errorpage_unpublished_msg');
        }
    }


    public function checkSettings()
    {

        $tags = $this->modx->getCount(modSystemSetting::class, [
            'key' => 'allow_tags_in_post',
            'value' => 1,
        ]);
        if ($tags) {
            $this->warnings[$this->modx->lexicon('configcheck_allowtagsinpost_system_enabled')] = $this->modx->lexicon('configcheck_allowtagsinpost_system_enabled_msg');
        }

        $tags = $this->modx->getCount(modContextSetting::class, [
            'context_key:!=' => 'mgr',
            'key' => 'allow_tags_in_post',
            'value' => 1,
        ]);
        if ($tags) {
            $this->warnings[$this->modx->lexicon('configcheck_allowtagsinpost_context_enabled')] = $this->modx->lexicon('configcheck_allowtagsinpost_context_enabled_msg');
        }
    }

}
