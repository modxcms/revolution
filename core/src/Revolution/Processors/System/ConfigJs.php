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

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modContext;
use MODX\Revolution\modNamespace;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;

/**
 * Outputs the $modx->config to JSON
 *
 * @param string $action If set with context, will output the context info for a
 * custom context by the action
 * @param string $context If set with action, will output the context info for a
 * custom context by its action
 * @package MODX\Revolution\Processors\System
 */
class ConfigJs extends Processor
{
    /**
     * @return mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        if (!$this->modx->user->isAuthenticated('mgr')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->modx->getVersionData();

        $wctx = (string)$this->getProperty('wctx');
        if (!empty($wctx)) {
            $workingContext = $this->modx->getContext($wctx);
            if (!$workingContext) {
                return $this->modx->error->failure($this->modx->lexicon('permission_denied'));
            }
        } else {
            $workingContext =& $this->modx->context;
        }

        /* calculate custom resource classes */
        $this->modx->lexicon->load('resource');
        $resourceClasses = [];
        $resourceClassesDrop = [];
        $resourceClassNames = $this->modx->getDescendants(modResource::class);
        $resourceClassNames = array_diff($resourceClassNames, [modResource::class]);
        foreach ($resourceClassNames as $resourceClassName) {
            /** @var modResource $obj */
            $obj = $this->modx->newObject($resourceClassName);
            if ($obj->showInContextMenu) {
                $lex = $obj->getContextMenuText();
                $resourceClasses[$resourceClassName] = $lex;
            }

            if ($obj->allowDrop !== -1) {
                $resourceClassesDrop[$resourceClassName] = $obj->allowDrop;
            }
        }

        $template_url = $workingContext->getOption(
            'manager_url', MODX_MANAGER_URL,
            $this->modx->_userConfig
        ) . 'templates/' . $workingContext->getOption(
            'manager_theme', 'default',
            $this->modx->_userConfig
        ) . '/';
        $c = [
            'base_url' => $workingContext->getOption('base_url', MODX_BASE_URL, $this->modx->_userConfig),
            'connectors_url' => $workingContext->getOption(
                'connectors_url', MODX_CONNECTORS_URL,
                $this->modx->_userConfig
            ),
            'icons_url' => $template_url . 'images/ext/modext/',
            'manager_url' => $workingContext->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig),
            'template_url' => $template_url,
            'http_host' => $workingContext->getOption('http_host', MODX_HTTP_HOST, $this->modx->_userConfig),
            'site_url' => $workingContext->getOption('site_url', MODX_SITE_URL, $this->modx->_userConfig),
            'http_host_remote' => MODX_URL_SCHEME . $workingContext->getOption(
                'http_host', MODX_HTTP_HOST,
                $this->modx->_userConfig
            ),
            'user' => $this->modx->user->get('id'),
            'version' => $this->modx->version['full_version'],
            'resource_classes' => $resourceClasses,
            'resource_classes_drop' => $resourceClassesDrop,
        ];

        // Handle default context
        $ctx = $this->modx->getContext($this->modx->getOption('default_context', null, 'web'));
        if ($ctx instanceof modContext && $ctx->prepare()) {
            $c['default_site_url'] = $ctx->makeUrl($ctx->getOption('site_start'));
        }

        $namespace = (string)$this->getProperty('namespace', 'core');
        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject(modNamespace::class, $namespace);
        if ($namespace) {
            $c['namespace'] = $namespace->get('name');
            $c['namespace_path'] = $namespace->get('path');
            $c['namespace_assets_path'] = $namespace->get('assets_url');
        }

        $c = array_merge($this->modx->config, $workingContext->config, $this->modx->_userConfig, $c);

        unset($c['password'], $c['username'], $c['mail_smtp_pass'], $c['mail_smtp_user'], $c['proxy_password'], $c['proxy_username'], $c['connections'], $c['connection_init'], $c['connection_mutable'], $c['dbname'], $c['database'], $c['table_prefix'], $c['driverOptions'], $c['dsn'], $c['session_name'], $c['assets_path'], $c['base_path'], $c['cache_path'], $c['connectors_path'], $c['core_path'], $c['friendly_alias_translit_class_path'], $c['manager_path'], $c['processors_path']);

        $o = "Ext.namespace('MODx'); MODx.config = ";
        $o .= $this->modx->toJSON($c);
        $o .= '; MODx.perm = {};';

        if ($this->modx->user) {
            $accessPermissionsQuery = $this->modx->newQuery(modAccessPermission::class);
            $accessPermissionsQuery->select('name');
            $accessPermissionsQuery->distinct();
            $permissions = $this->modx->getIterator(modAccessPermission::class, $accessPermissionsQuery);
            $permissionValues = [];
            foreach ($permissions as $permission) {
                $name = $permission->get('name');
                $permissionValues[$name] = $this->modx->hasPermission($name);
            }
            $o .= 'MODx.perm = ' . $this->modx->toJSON($permissionValues) . ';';

            $o .= 'MODx.user = {id:"' . $this->modx->user->get('id') . '",username:"' . $this->modx->user->get('username') . '"}';
        }
        @session_write_close();
        header('Content-Type: application/x-javascript');
        return $o;
    }
}
