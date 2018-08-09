<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
class modTransportManager {
	/**
	 * @var MODX A reference to the MODX object.
	 */
    public $modx = null;
    /**
     * @var array The configuration array for the TransportManager.
     */
    public $config = array ();
    /**
     * @var array An array of active providers.
     */
    public $providers = array ();
    /**
     * @var modWorkspace The active MODX workspace.
     */
    public $workspace = null;

    /**
     * Creates an instance of the modTransportManager class.
     *
     * @param xPDO &$modx A reference to a modX instance.
     * @return modTransportManager
     */
    function __construct(xPDO &$modx) {
        $this->modx = &$modx;
        $this->getActiveWorkspace();
        $this->modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH . 'om/', true, true);
    }

	/**
	 * Get a list of providers for the transports.
	 *
     * @access public
	 * @param boolean $refresh If true, refresh the list of providers. Defaults
	 * to false.
	 * @return array A list of providers.
	 */
    public function getProviders($refresh = false) {
        if (empty($this->providers) || $refresh) {
            $this->providers = $this->modx->getCollection('transport.modTransportProvider', array (
                'disabled' => false,
            ));
            if (!$this->providers) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Could not find any active transport providers.");
            }
        }
        return $this->providers;
    }

	/**
	 * Get the active workspace for the MODX installation.
	 *
     * @access public
	 * @return modWorkspace
	 */
    public function getActiveWorkspace() {
        if ($this->workspace == null) {
            if (!$this->workspace = $this->modx->getObject('transport.modWorkspace', array ('active' => true))) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Could not find an active workspace!");
            }
        }
        return $this->workspace;
    }

	/**
	 * Change the active workspace in MODX.
	 *
     * @access public
	 * @param integer $workspaceId The PK of the modWorkspace.
	 * @return modWorkspace
	 */
    public function changeActiveWorkspace($workspaceId) {
        $workspace = $this->modx->getObject('transport.modWorkspace', $workspaceId);
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
    public function scanForUpdates() {
        $updates = array ();
        $this->getProviders();
        foreach ($this->providers as $providerId => $provider) {
            if ($update = $provider->scanForUpdates()) {
                $updates[$providerId] = $update;
            }
        }
        return $updates;
    }

	/**
	 * Scans all providers for a list of packages.
	 *
     * @access public
	 * @return array An array of packages.
	 */
    public function scanForPackages() {
        $packages = array ();
        $this->getProviders();
        foreach ($this->providers as $providerId => $provider) {
            if ($package = $provider->scanForPackages()) {
                $packages[$providerId] = $package;
            }
        }
        return $packages;
    }
}
