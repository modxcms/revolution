<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Transport;

use MODX\Revolution\modWorkspace;
use MODX\Revolution\modX;

class modTransportManager
{

    /**
     * @var modX A reference to the MODX object.
     */
    public $modx = null;

    /**
     * @var array The configuration array for the TransportManager.
     */
    public $config = [];

    /**
     * @var array An array of active providers.
     */
    public $providers = [];

    /**
     * @var modWorkspace The active MODX workspace.
     */
    public $workspace = null;

    /**
     * Creates an instance of the modTransportManager class.
     *
     * @param  modX  $modx  A reference to a modX instance.
     */
    public function __construct(modX &$modx)
    {
        $this->modx = &$modx;
        $this->getActiveWorkspace();
    }

    /**
     * Get the active workspace for the MODX installation.
     *
     * @access public
     * @return modWorkspace
     */
    public function getActiveWorkspace()
    {
        if ($this->workspace == null) {
            $this->workspace = $this->modx->getObject(modWorkspace::class, ['active' => true]);
            if (!$this->workspace) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Could not find an active workspace!");
            }
        }
        return $this->workspace;
    }

    /**
     * Change the active workspace in MODX.
     *
     * @access public
     *
     * @param  int  $workspaceId  The PK of the modWorkspace.
     *
     * @return modWorkspace
     */
    public function changeActiveWorkspace($workspaceId)
    {
        /** @var modWorkspace $workspace */
        $workspace = $this->modx->getObject(modWorkspace::class, $workspaceId);
        if ($workspace) {
            if ($this->workspace) {
                $this->workspace->set('active', false);
                $this->workspace->save();
            }
            $workspace->set('active', true);
            $workspace->save();
            $this->workspace = $workspace;
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Could not change to workspace with id = {$workspaceId}");
        }
        return $this->workspace;
    }

    /**
     * Scans all providers for a list of updates for all packages.
     *
     * @access public
     * @return array An array of updates for packages.
     */
    public function scanForUpdates()
    {
        $updates = [];
        $this->getProviders();
        foreach ($this->providers as $providerId => $provider) {
            if ($update = $provider->scanForUpdates()) {
                $updates[$providerId] = $update;
            }
        }
        return $updates;
    }

    /**
     * Get a list of providers for the transports.
     *
     * @access public
     *
     * @param  bool  $refresh  If true, refresh the list of providers. Defaults to false.
     *
     * @return modTransportProvider[] A list of providers.
     */
    public function getProviders($refresh = false)
    {
        if (empty($this->providers) || $refresh) {
            $this->providers = $this->modx->getCollection(modTransportProvider::class, ['disabled' => false]);
            if (!$this->providers) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Could not find any active transport providers.");
            }
        }
        return $this->providers;
    }

    /**
     * Scans all providers for a list of packages.
     *
     * @access public
     * @return array An array of packages.
     */
    public function scanForPackages()
    {
        $packages = [];
        $this->getProviders();
        foreach ($this->providers as $providerId => $provider) {
            if ($package = $provider->scanForPackages()) {
                $packages[$providerId] = $package;
            }
        }
        return $packages;
    }

}
