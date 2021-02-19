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
 * Represents an xPDOTransport package as required for MODX Providers and package installation
 *
 * @property string $signature The full signature of the package
 * @property string $created The time this package was created or added
 * @property string $updated The time this package was last update
 * @property string $installed The time this package was installed
 * @property int $state The state of the package; packed/unpacked/etc
 * @property int $workspace The workspace this package is installed into.
 * @property int $provider The provider ID of the package, if any.
 * @property boolean $disabled Whether or not this package is disabled (not currently used)
 * @property string $source The source data of the package
 * @property string $manifest The manifest of the package, containing transport information and methods
 * @property string $attributes Any package-level attributes
 * @property string $package_name The name of the package
 * @property string $metadata Any metadata transmitted with the package
 * @property int $version_major The major version number of the package
 * @property int $version_minor The minor version number of the package
 * @property int $version_patch The patch version number of the package
 * @property int $release_index The release index of the release. Optional.
 *
 * @package modx
 * @subpackage transport
 */
class modTransportPackage extends xPDOObject {
    /** @var xPDO|modX */
    public $xpdo = null;
    /**
     * @var string The unique identifier of a package.
     */
    public $identifier = null;
    /**
     * @var string The version number of a package.
     */
    public $version = null;
    /**
     * @var int The major version number of a package.
     */
    public $version_major = 0;
    /**
     * @var int The minor version number of a package.
     */
    public $version_minor = 0;
    /**
     * @var int The patch version number of a package.
     */
    public $version_patch = 0;
    /**
     * @var string The release number of a package; eg, pl, beta, alpha, dev
     */
    public $release = '';
    /**
     * @var int The release index of a package
     */
    public $release_index = 0;
    /**
     * @var xPDOTransport The package to transport.
     */
    public $package = null;

    /**
     * List the packages from this transport package
     * @static
     * @param modX $modx A reference to the modX instance
     * @param int $workspace The current active workspace ID
     * @param int $limit The limit of packages to return
     * @param int $offset The offset on which to list by
     * @param string $search An optional search value
     * @return array
     */
    public static function listPackages(modX &$modx, $workspace, $limit = 0, $offset = 0,$search = '') {
        return array('collection' => array(), 'total' => 0);
    }

    public function __construct(&$xpdo) {
        parent::__construct($xpdo);
        $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
    }

    /**
     * Overrides xPDOObject::save to set a default created time if new.
     *
     * @param boolean $cacheFlag
     * @return boolean True if the save was successful
     */
    public function save($cacheFlag= null) {
        if ($this->_new && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }

    /**
     * Overrides xPDOObject::set. Checks if signature is set, and if so,
     * parses it and sets the source if is a new package.
     *
     * @param string $k The key to set
     * @param mixed $v The value to set
     * @param string $vType The validation type to set against
     * @return boolean True if successfully set
     */
    public function set($k, $v= null, $vType= '') {
        $set = parent :: set($k, $v, $vType);
        if ($k == 'signature') {
            $this->parseSignature();
            if ($this->isNew() && !$this->get('source')) {
                $this->set('source', $this->get('signature') . '.transport.zip');
            }
        }
        return $set;
    }

    /**
     * Parses the signature.
     *
     * @return boolean True if successful.
     */
    public function parseSignature() {
        $parsed = false;
        $sig = $this->get('signature');
        if ($sig != null) {
            $parsedSig = xPDOTransport::parseSignature($sig);
            if (count($parsedSig) === 2 && !empty($parsedSig[0]) && !empty($parsedSig[1])) {
                $this->identifier = $parsedSig[0];
                $parsedVersion = explode('-', $parsedSig[1], 2);
                if (count($parsedVersion) === 2) {
                    $this->version = $parsedVersion[0];
                    $releaseChars = array();
                    parse_str($parsedVersion[1], $releaseChars);
                    $release = '';
                    $releaseIndex = '';
                    $char = reset($releaseChars);
                    while ($char !== false && !is_numeric($char)) {
                        $release .= $char;
                        $char = next($releaseChars);
                    }
                    while ($char !== false && is_numeric($char)) {
                        $releaseIndex .= $char;
                    }
                    $this->release = $release;
                    $this->release_index = (integer)$releaseIndex;
                    $parsed = true;
                } elseif (count($parsedVersion) === 1) {
                    $this->version = $parsedVersion[0];
                    $this->release = '';
                    $this->release_index = 0;
                    $parsed = true;
                }
                list($this->version_major, $this->version_minor, $this->version_patch) = explode('.', $this->version);
            }
        }
        return $parsed;
    }

    /**
     * Set package version data based on the signature
     * @return boolean
     */
    public function setPackageVersionData() {
        $sig = explode('-',$this->signature);
        if (is_array($sig)) {
            if (!empty($sig[1])) {
                $v = explode('.',$sig[1]);
                if (isset($v[0])) $this->set('version_major',$v[0]);
                if (isset($v[1])) $this->set('version_minor',$v[1]);
                if (isset($v[2])) $this->set('version_patch',$v[2]);
            }
            if (!empty($sig[2])) {
                $r = preg_split('/([0-9]+)/',$sig[2],-1,PREG_SPLIT_DELIM_CAPTURE);
                if (is_array($r) && !empty($r)) {
                    $this->set('release',$r[0]);
                    $this->set('release_index',(isset($r[1]) ? $r[1] : '0'));
                } else {
                    $this->set('release',$sig[2]);
                }
            }
        }

        return true;
    }

    /**
     * Gets the package's transport mechanism.
     *
     * @access public
     * @param integer $state The state of the package.
     * @return mixed The package.
     */
    public function getTransport($state = -1) {
        if (!is_object($this->package) || !($this->package instanceof xPDOTransport)) {
            $workspace = $this->getOne('Workspace');
            if ($workspace) {
                $packageDir = $workspace->get('path') . 'packages/';
                $sourceFile = $this->get('source');
                if ($sourceFile) {
                    $transferred= file_exists($packageDir . $sourceFile);
                    if (!$transferred) { /* if no transport zip, attempt to get it */
                        if (!$transferred= $this->transferPackage($sourceFile, $packageDir)) {
                            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_transfer',array(
                                'sourceFile' => $sourceFile,
                                'packageDir' => $packageDir,
                            )));
                        } else {
                            $sourceFile= basename($sourceFile);
                        }
                    }
                    if ($transferred) {
                        if ($state < 0) {
                            /* if directory is missing but zip exists, and DB state value is incorrect, fix here */
                            $targetDir = basename($sourceFile, '.transport.zip');
                            $state = is_dir($packageDir.$targetDir) ? $this->get('state') : xPDOTransport::STATE_PACKED;
                        }
                        /* retrieve the package */
                        $this->package = xPDOTransport :: retrieve($this->xpdo, $packageDir . $sourceFile, $packageDir, $state);
                        if ($this->package) {
                            /* set to unpacked state */
                            if ($state == xPDOTransport::STATE_PACKED) {
                                $this->set('state', xPDOTransport::STATE_UNPACKED);
                            }
                            $this->set('source', $sourceFile);
                            $this->set('attributes', $this->package->attributes);
                            $this->save();
                        }
                    }
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_source_nf'));
                }
            }
        }
        return $this->package;
    }

    /**
     * Removes and uninstalls the package.
     *
     * @param boolean $force Indicates if removal should be forced even if currently installed.
     * @param boolean $uninstall Indicates if the package should be uninstalled before removal.
     * @return boolean True if the package was successfully removed.
     */
    public function removePackage($force = false, $uninstall = true) {
        $removed = false;
        $uninstalled = false;
        if ($this->get('installed') == null || $this->get('installed') == '0000-00-00 00:00:00') {
            $uninstalled = true;
        } else if ($uninstall) {
            $uninstalled = $this->uninstall();
        }

        if ($uninstalled || $force) {
            $removed= $this->remove();
        }

        return $removed;
    }

    /**
     * Installs or upgrades the package.
     *
     * @access public
     * @param array $options An array of installation options
     * @return boolean True if successful.
     */
    public function install(array $options = array()) {
        $installed = false;
        if ($this->getTransport()) {
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO,$this->xpdo->lexicon('workspace_grabbing'));
            $this->getOne('Workspace');
            $wc = isset($this->Workspace->config) && is_array($this->Workspace->config) ? $this->Workspace->config : array();
            $at = is_array($this->get('attributes')) ? $this->get('attributes') : array();
            $attributes = array_merge($wc, $at);
            $attributes = array_merge($attributes, $options);
            $attributes[xPDOTransport::PACKAGE_ACTION] = $this->previousVersionInstalled() ? xPDOTransport::ACTION_UPGRADE : xPDOTransport::ACTION_INSTALL;
            @ini_set('max_execution_time', 0);
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO, $this->xpdo->lexicon('package_installing'));
            $requires = isset($attributes['requires']) && is_array($attributes['requires'])
                ? $attributes['requires']
                : array();
            $unsatisfied = $this->checkDependencies($requires);
            if (!empty($unsatisfied)) {
                $unsatisfied = $this->resolveDependencies($unsatisfied);
                if (!empty($unsatisfied)) {
                    foreach ($unsatisfied as $dependency => $constraint) {
                        $this->xpdo->log(
                            xPDO::LOG_LEVEL_ERROR,
                            $this->xpdo->lexicon(
                                'package_dependency_unsatisfied',
                                array(
                                    'signature' => $this->get('signature'),
                                    'requires' => "{$dependency} @ {$constraint}"
                                )
                            )
                        );
                    }
                    if ($this->getOption('abort_install_on_unsatisfied_dependency', $attributes, true)) {
                        return false;
                    }
                }
            }
            if ($this->package->install($attributes)) {
                $installed = true;
                $this->set('installed', strftime('%Y-%m-%d %H:%M:%S'));
                $this->set('attributes', $attributes);
                $this->save();
            }
        }
        return $installed;
    }

    /**
     * Uninstalls the package.
     *
     * @access public
     * @param array $options An array of uninstallation options
     * @return boolean True if successful.
     */
    public function uninstall(array $options = array()) {
        $uninstalled = false;
        if (!$this->getTransport()) {
            /* files have already been removed, so ignore this */
            return true;
        }
        if ($this->package) {
            $this->getOne('Workspace');
            $wc = isset($this->Workspace->config) && is_array($this->Workspace->config) ? $this->Workspace->config : array();
            $at = is_array($this->get('attributes')) ? $this->get('attributes') : array();
            $attributes = array_merge($wc,$at);
            $attributes = array_merge($attributes, $options);
            @ini_set('max_execution_time', 0);
            if ($this->package->uninstall($attributes)) {
                $uninstalled = true;
                $this->set('installed',null);
                $this->set('attributes',$attributes);
                $this->save();
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_uninstall',array(
                    'signature' => $this->get('signature'),
                )));
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_load'));
        }
        return $uninstalled;
    }

    /**
     * Transfers the package from one directory to another.
     *
     * @access public
     * @param string $sourceFile The file to transfer.
     * @param string $targetDir The directory to transfer into.
     * @return boolean True if successful.
     */
    public function transferPackage($sourceFile, $targetDir) {
        $transferred= false;
        $content= '';
        if (is_dir($targetDir) && is_writable($targetDir)) {
            if (!is_array($this->xpdo->version)) { $this->xpdo->getVersionData(); }
            $productVersion = $this->xpdo->version['code_name'].'-'.$this->xpdo->version['full_version'];

            $source= $this->get('service_url') . $sourceFile.(strpos($sourceFile,'?') !== false ? '&' : '?').'revolution_version='.$productVersion;

            /* see if user has allow_url_fopen on and is not behind a proxy */
            $proxyHost = $this->xpdo->getOption('proxy_host',null,'');
            if (ini_get('allow_url_fopen') && empty($proxyHost)) {
                if ($handle= @ fopen($source, 'rb')) {
                    $filesize= @ filesize($source);
                    $memory_limit= @ ini_get('memory_limit');
                    if (!$memory_limit) $memory_limit= '8M';
                    $byte_limit= $this->_bytes($memory_limit) * .5;
                    if (strpos($source, '://') !== false || $filesize > $byte_limit) {
                        $content= @ file_get_contents($source);
                    } else {
                        $content= @ fread($handle, $filesize);
                    }
                    @ fclose($handle);
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_file_read',array(
                        'source' => $source,
                    )));
                }
            }

            /* if not, try curl */
            if (empty($content) && function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $source);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT,180);
                $safeMode = @ini_get('safe_mode');
                $openBasedir = @ini_get('open_basedir');
                if (empty($safeMode) && empty($openBasedir)) {
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                }

                $proxyHost = $this->xpdo->getOption('proxy_host',null,'');
                if (!empty($proxyHost)) {
                    $proxyPort = $this->xpdo->getOption('proxy_port',null,'');
                    curl_setopt($ch, CURLOPT_PROXY,$proxyHost);
                    curl_setopt($ch, CURLOPT_PROXYPORT,$proxyPort);

                    $proxyUsername = $this->xpdo->getOption('proxy_username',null,'');
                    if (!empty($proxyUsername)) {
                        $proxyAuth = $this->xpdo->getOption('proxy_auth_type',null,'BASIC');
                        $proxyAuth = $proxyAuth == 'NTLM' ? CURLAUTH_NTLM : CURLAUTH_BASIC;
                        curl_setopt($ch, CURLOPT_PROXYAUTH,$proxyAuth);

                        $proxyPassword = $this->xpdo->getOption('proxy_password',null,'');
                        $up = $proxyUsername.(!empty($proxyPassword) ? ':'.$proxyPassword : '');
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD,$up);
                    }
                }
                $content = curl_exec($ch);
                curl_close($ch);
            }

            /* and as last-ditch resort, try fsockopen */
            if (empty($content)) {
                $content = $this->_getByFsockopen($source);
            }

            if ($content) {
                if ($cacheManager= $this->xpdo->getCacheManager()) {
                    $filename= $this->signature.'.transport.zip';
                    $target= $targetDir . $filename;
                    $transferred= $cacheManager->writeFile($target, $content);
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'MODX could not download the file. You must enable allow_url_fopen, cURL or fsockopen to use remote transport packaging.');
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_target_write',array(
                'targetDir' => $targetDir,
            )));
        }
        return $transferred;
    }

    /**
     * Check dependency constraints for the package.
     *
     * @param array $dependencies An array of dependencies to check.
     *
     * @return array An array of unsatisfied dependencies.
     */
    public function checkDependencies(array $dependencies) {
        $unsatisfied = array();
        $dependencies = xPDOTransport::checkPlatformDependencies($dependencies);
        foreach ($dependencies as $package => $constraint) {
            if (strtolower($package) === strtolower($this->identifier)) continue;
            switch (strtolower($package)) {
                case 'modx':
                    $versionData = $this->xpdo->getVersionData();
                    if (!xPDOTransport::satisfies($versionData['full_version'], $constraint)) {
                        $unsatisfied[$package] = $constraint;
                    }
                    break;
                default:
                    /* get latest installed package version */
                    $latestQuery = $this->xpdo->newQuery(
                        'modTransportPackage',
                        array(
                            array(
                                "UCASE({$this->xpdo->escape('package_name')}) LIKE UCASE({$this->xpdo->quote($package)})"
                            ),
                            'installed:IS NOT' => null,
                        )
                    );
                    $latestQuery->sortby('installed', 'DESC');
                    /** @var modTransportPackage $latest */
                    $latest = $this->xpdo->getObject('modTransportPackage', $latestQuery);
                    if ($latest) {
                        $latest->parseSignature();
                        if (xPDOTransport::satisfies($latest->version, $constraint)) {
                            unset($latest);
                            break;
                        }
                    }
                    $unsatisfied[$package] = $constraint;
                    break;
            }
        }
        return $unsatisfied;
    }

    public function checkDownloadedDependencies(array $dependencies) {
        $satisfied = array();
        foreach ($dependencies as $package => $constraint) {
            if (strtolower($package) === strtolower($this->identifier) || $package === 'php' || $package === 'modx') continue;

            /* get latest installed package version */
            $latestQuery = $this->xpdo->newQuery(
                'modTransportPackage',
                array(
                    array(
                        "UCASE({$this->xpdo->escape('package_name')}) LIKE UCASE({$this->xpdo->quote($package)})",
                        'OR:signature:LIKE' => $package . '-%'
                    ),
                    'installed:IS' => null,
                )
            );
            $latestQuery->sortby('installed', 'DESC');
            /** @var modTransportPackage $latest */
            $latest = $this->xpdo->getObject('modTransportPackage', $latestQuery);
            if ($latest) {
                $latest->parseSignature();
                if (xPDOTransport::satisfies($latest->version, $constraint)) {
                    $satisfied[strtolower($package)] = $latest->signature;
                    continue;
                }
            }
        }

        return $satisfied;
    }

    /**
     * Resolve unsatisfied dependencies defined for a package.
     *
     * @param array $requirements An array of unsatisfied package names and their constraints
     *
     * @return array Any unresolvable dependent package names and their failed constraints.
     */
    public function resolveDependencies(array $requirements) {
        $unresolved = array();
        foreach ($requirements as $dependency => $constraint) {
            if (!$this->resolveDependency($dependency, $constraint)) {
                $unresolved[$dependency] = $constraint;
            }
        }
        return $unresolved;
    }

    /**
     * Resolve an unsatisfied dependency defined for a package.
     *
     * @param string $package The dependent package name.
     * @param string $constraint A valid version constraint for the dependent package.
     *
     * @return bool TRUE if the dependency was resolved.
     */
    public function resolveDependency($package, $constraint) {
        $resolved = false;
        /** @var modTransportProvider|null $provider */
        $provider = null;
        $resolution = $this->findResolution($package, $constraint, $provider);
        if ($resolution !== false) {
            /** @var modTransportPackage $transport */
            $transport = isset($resolution['transport']) ? $resolution['transport'] : null;
            if ($provider !== null) {
                $transport = $provider->transfer($resolution['signature']);
            }
            if ($transport) {
                $installed = $transport->install();
                if (!$installed) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Error installing dependency {$package} from {$transport->source}", '', __METHOD__, __FILE__, __LINE__);
                }
            }
        }
        return $resolved;
    }

    /**
     * Search for package to satisfy a dependency.
     *
     * @param string $package The name of the dependent package.
     * @param string $constraint The version constraint the package must satisfy.
     * @param modTransportProvider|null $provider A reference which is set to the
     * modTransportProvider which satisfies the dependency.
     *
     * @return array|bool The metadata for the package version which satisfies the dependency, or FALSE.
     */
    public function findResolution($package, $constraint, &$provider = null) {
        $resolution = false;
        $conditions = array(
            'active' => true,
        );
        switch (strtolower($package)) {
            case 'php':
                /* you must resolve php dependencies manually */
                $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "PHP version dependencies must be resolved manually", '', __METHOD__, __FILE__, __LINE__);
                break;
            case 'modx':
            case 'revo':
            case 'revolution':
                /* resolve core dependencies manually for now */
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "MODX core version dependencies must be resolved manually", '', __METHOD__, __FILE__, __LINE__);
                break;
            default:
                /* TODO: scan for local packages to satisfy dependency */

                /* see if current provider can satisfy dependency */
                /** @var modTransportProvider $provider */
                $provider = $this->Provider;
                if ($provider) {
                    $resolution = $provider->latest($package, $constraint);
                }
                /* loop through active providers if all else fails */
                if (empty($resolution)) {
                    $query = $this->xpdo->newQuery('transport.modTransportProvider', $conditions);
                    $query->sortby('priority', 'ASC');
                    /** @var modTransportProvider $p */
                    foreach ($this->xpdo->getIterator('transport.modTransportProvider', $query) as $p) {
                        $resolution = $p->latest($package, $constraint);
                        if ($resolution) {
                            $provider = $p;
                            break;
                        }
                    }
                }
                if (empty($resolution)) {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not find package to satisfy dependency {$package} @ {$constraint} from your currently active providers", '', __METHOD__, __FILE__, __LINE__);
                }
                break;
        }
        return $resolution;
    }

    /**
     * Converts to bytes from PHP ini_get() format.
     *
     * PHP ini modifiers for byte values:
     * <ul>
     *  <li>G = gigabytes</li>
     *  <li>M = megabytes</li>
     *  <li>K = kilobytes</li>
     * </ul>
     *
     * @access protected
     * @param string $value Number of bytes represented in PHP ini value format.
     * @return integer The value converted to bytes.
     */
    protected function _bytes($value) {
        $value = trim($value);
        $modifier = strtolower($value[strlen($value) - 1]);
        switch($modifier) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        return $value;
    }

    /**
     * If for some reason the server does not have allow_url_fopen or cURL
     * enabled, use this function to get the file via fsockopen.
     *
     * @access protected
     * @param string $url The source URL to retrieve
     * @return string The response from the server
     */
    protected function _getByFsockopen($url) {
        $purl = parse_url($url);
        $host = $purl['host'];
        $path = !empty($purl['path']) ? $purl['path'] : '/';
        if (!empty($purl['query'])) { $path .= '?'.$purl['query']; }
        $port = !empty($purl['port']) ? $purl['port'] : '80';

        $timeout = 10;
        $response = '';
        $fp = @fsockopen($host,$port,$errno,$errstr,$timeout);

        if( !$fp ) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not retrieve from '.$url);
        } else {
            fwrite($fp, "GET $path ".$_SERVER['SERVER_PROTOCOL']."\r\n" .
                "Host: $host\r\n" .
                "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
                "Accept: */*\r\n" .
                "Accept-Language: en-us,en;q=0.5\r\n" .
                "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                "Keep-Alive: 300\r\n" .
                "Connection: keep-alive\r\n" .
                "Referer: http://$host\r\n\r\n");

          while ($line = fread($fp, 4096)) {
             $response .= $line;
          }
          fclose($fp);

          $pos = strpos($response, "\r\n\r\n");
          $response = substr($response, $pos + 4);
       }
       return $response;
    }

    /**
     * Gets a version string able to be used by version_compare for checking
     *
     * @return string The properly formatted string.
     */
    public function getComparableVersion() {
        $v = explode('-',$this->get('signature'));
        array_shift($v);
        $v = implode('-',$v);
        $v = str_replace('-ga','-pl',$v);
        return $v;
    }

    /**
     * Compares this version of the package to another
     *
     * @param string $version The version to compare to. Must be a PHP-
     * standardized version.
     * @param string $direction The direction to compare. Defaults to <=
     * @return boolean Result of comparison.
     */
    public function compareVersion($version,$direction = '<=') {
        $v = $this->getComparableVersion();
        return version_compare($version,$v,$direction);
    }

    /**
     * Indicates if a previous version of the package is installed.
     *
     * @return boolean True if a previous version of the package is installed.
     */
    public function previousVersionInstalled() {
        $this->parseSignature();
        $count = $this->xpdo->getCount('transport.modTransportPackage', array(array("UCASE({$this->xpdo->escape('package_name')}) LIKE UCASE({$this->xpdo->quote($this->identifier)})"), 'installed:IS NOT' => null, 'signature:!=' => $this->get('signature')));
        return $count > 0;
    }
}
