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

use MODX\Revolution\modNamespace;
use MODX\Revolution\modWorkspace;
use MODX\Revolution\modX;
use xPDO\Transport\xPDOTransport;
use xPDO\xPDOException;

/**
 * Abstracts the package building process
 *
 * @package MODX\Revolution\Transport
 */
class modPackageBuilder
{

    /**
     * @var string The directory in which the package file is located.
     * @access public
     */
    public $directory;

    /**
     * @var string The unique signature for the package.
     * @access public
     */
    public $signature;

    /**
     * @var string The filename of the actual package.
     * @access public
     */
    public $filename;

    /**
     * @var xPDOTransport The xPDOTransport package object.
     * @access public
     */
    public $package;

    /**
     * @var modNamespace The modNamespace that the package is associated with.
     * @access public
     */
    public $namespace;

    /**
     * @var array An array of classnames to automatically select by namespace
     * @access public
     */
    public $autoselects;

    /**
     * Creates an instance of the modPackageBuilder class.
     *
     * @param  modX &$modx  A reference to a modX instance.
     */
    public function __construct(modX &$modx)
    {
        $this->modx = &$modx;

        $workspace = $this->modx->getObject(modWorkspace::class, ['active' => 1]);

        if (!$workspace) {
            $this->modx->log(modX::LOG_LEVEL_FATAL, $this->modx->lexicon('core_err_invalid'));
            exit ();
        }

        $this->directory = $workspace->get('path') . 'packages/';
        $this->modx->lexicon->load('workspace');
        $this->autoselects = [];
    }

    /**
     * Allows for customization of the package workspace.
     *
     * @access public
     *
     * @param  int  $workspace_id  The ID of the workspace to select.
     *
     * @return modWorkspace|false The workspace set, false if invalid.
     */
    public function setWorkspace($workspace_id)
    {
        if (!is_numeric($workspace_id)) {
            return false;
        }
        /** @var modWorkspace $workspace */
        $workspace = $this->modx->getObject(modWorkspace::class, $workspace_id);
        if ($workspace == null) {
            return false;
        }

        $this->directory = $workspace->get('path') . 'packages/';
        return $workspace;
    }

    /**
     * Creates a new xPDOTransport package.
     *
     * @access public
     *
     * @param  string  $name  The name of the component the package represents.
     * @param  string  $version  A string representing the version of the package.
     * @param  string  $release  A string describing the specific release of this version of the package.
     *
     * @return xPDOTransport The xPDOTransport package object.
     */
    public function createPackage($name, $version, $release = '')
    {
        /* setup the signature and filename */
        $s['name'] = strtolower($name);
        $s['version'] = $version;
        $s['release'] = $release;
        $this->signature = $s['name'];
        if (!empty ($s['version'])) {
            $this->signature .= '-' . $s['version'];
        }
        if (!empty ($s['release'])) {
            $this->signature .= '-' . $s['release'];
        }
        $this->filename = $this->signature . '.transport.zip';

        /* remove the package if it's already been made */
        if (file_exists($this->directory . $this->filename)) {
            unlink($this->directory . $this->filename);
        }
        if (file_exists($this->directory . $this->signature) && is_dir($this->directory . $this->signature)) {
            $cacheManager = $this->modx->getCacheManager();
            if ($cacheManager) {
                $cacheManager->deleteTree($this->directory . $this->signature, true, false, []);
            }
        }

        /* create the transport package */
        $this->package = new xPDOTransport($this->modx, $this->signature, $this->directory);
        $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('package_created', ['signature' => $this->signature]));

        return $this->package;
    }

    /**
     * Registers a namespace to the transport package. If no namespace is found, will create a namespace.
     *
     * @access public
     *
     * @param  string|modNamespace  $ns  The modNamespace object or the string name of the namespace
     * @param  bool|array  $autoincludes  If true, will automatically select relative resources to the namespace.
     * @param  bool  $packageNamespace  If false, will not package the namespace as a vehicle.
     * @param  string  $path  The path for the namespace to be created.
     * @param  string  $assetsPath  An optional custom assets_path for the namespace.
     *
     * @return bool True if successful.
     */
    public function registerNamespace($ns = 'core', $autoincludes = true, $packageNamespace = true, $path = '', $assetsPath = '')
    {
        if (!($ns instanceof modNamespace)) {
            $namespace = $this->modx->getObject(modNamespace::class, $ns);
            if (!$namespace) {
                $namespace = $this->modx->newObject(modNamespace::class);
                $namespace->set('name', $ns);
                $namespace->set('path', $path);
                $namespace->set('assets_path', $assetsPath);
            }
            if (!empty($path)) {
                $namespace->set('path', $path);
            }
            if (!empty($assetsPath)) {
                $namespace->set('assets_path', $assetsPath);
            }
        } else {
            $namespace = $ns;
        }
        $this->{'namespace'} = $namespace;

        $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('namespace_registered', ['namespace' => $this->{'namespace'}->get('name')]));

        /* define some basic attributes */
        $attributes = [
            xPDOTransport::UNIQUE_KEY    => 'name',
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RESOLVE_FILES => true,
            xPDOTransport::RESOLVE_PHP   => true,
        ];
        if ($packageNamespace) {
            /* create the namespace vehicle */
            $v = $this->createVehicle($namespace, $attributes);

            /* put it into the package */
            if (!$this->putVehicle($v)) {
                return false;
            }
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('namespace_packaged', ['namespace' => $this->{'namespace'}->get('name')]));
        }

        /* Can automatically package in certain classes based upon their namespace values */
        if ($autoincludes == true || (is_array($autoincludes) && !empty ($autoincludes))) {
            $this->modx->log(modx::LOG_LEVEL_INFO, $this->modx->lexicon('autoincludes_packaging', ['autoincludes' => print_r($autoincludes, true)]));
            if (is_array($autoincludes)) {
                /* set automatically included packages */
                $this->setAutoSelects($autoincludes);
            }

            /* grab all related classes that can be auto-packaged and package them in */
            foreach ($this->autoselects as $classname) {
                $objs = $this->modx->getCollection($classname, ['namespace' => $namespace->get('name')]);
                foreach ($objs as $obj) {
                    $v = $this->createVehicle($obj, $attributes);
                    if (!$this->putVehicle($v)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Creates the modTransportVehicle for the specified object.
     *
     * @access public
     *
     * @param  mixed  $obj  The payload being abstracted as a vehicle.
     * @param  array  $attr  Attributes for the vehicle.
     *
     * @return modTransportVehicle The created modTransportVehicle instance.
     */
    public function createVehicle($obj, $attr)
    {
        if ($this->{'namespace'}) {
            $attr['namespace'] = $this->{'namespace'}; /* package the namespace into the metadata */
        }

        return new modTransportVehicle($obj, $attr);
    }

    /**
     * Puts the vehicle into the package.
     *
     * @access public
     *
     * @param  modTransportVehicle  $vehicle  The vehicle to insert into the package.
     *
     * @return bool True if successful.
     */
    public function putVehicle($vehicle)
    {
        $attr = $vehicle->compile();
        $obj = $vehicle->fetch();
        return $this->package->put($obj, $attr);
    }

    /**
     * Sets the classes that are to automatically be included and built into the package.
     *
     * @access public
     *
     * @param  array  $classes  An array of class names to build in
     *
     * @return void
     */
    public function setAutoSelects(array $classes = [])
    {
        $this->autoselects = $classes;
    }

    /**
     * Packs the package.
     *
     * @return bool True if successful.
     * @see xPDOTransport::pack
     *
     * @access public
     */
    public function pack()
    {
        return $this->package->pack();
    }

    /**
     * Retrieves the package signature.
     *
     * @access public
     * @return string The signature of the included package.
     */
    public function getSignature()
    {
        return $this->package->signature;
    }

    /**
     * Generates the model from a schema.
     *
     * @access public
     *
     * @param  string  $model  The directory path of the model to generate to.
     * @param  string  $schema  The schema file to generate from.
     *
     * @return bool true if successful
     * @throws xPDOException
     */
    public function buildSchema($model, $schema)
    {
        $manager = $this->modx->getManager();
        $generator = $manager->getGenerator();
        $generator->parseSchema($schema, $model);
        return true;
    }

    /**
     * Set an array of attributes into the xPDOTransport manifest.
     *
     * @access public
     *
     * @param  array  $attributes  An array of attributes to set in the manifest of the package being built.
     *
     * @return void
     */
    public function setPackageAttributes(array $attributes = [])
    {
        if ($this->package !== null) {
            foreach ($attributes as $k => $v) {
                $this->package->setAttribute($k, $v);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('package_err_spa'));
        }
    }

}
