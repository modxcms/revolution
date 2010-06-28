<?php
/**
 * @package modx
 * @subpackage transport
 */

/**
 * Represents an xPDOTransport package as required for MODx Web Transport Facilities.
 *
 * @package modx
 * @subpackage transport
 */
class modTransportPackage extends xPDOObject {
    /**
     * @var string The unique identifier of a package.
     * @access public
     */
    public $identifier = null;
    /**
     * @var string The version number of a package.
     * @access public
     */
    public $version = null;
    /**
     * @var string The release number of a package.
     * @access public
     */
    public $release = null;
    /**
     * @var mixed The package to transport.
     * @access protected
     */
    public $package = null;

    /**
     * Overrides xPDOObject::save to set a default created time if new.
     *
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function set($k, $v, $vType = '') {
        $set = parent :: set($k, $v, $vType);
        if ($k == 'signature') {
            $this->parseSignature();
            if ($this->_new && !$this->get('source')) {
                $this->set('source', $this->get('signature') . '.transport.zip');
            }
        }
        return $set;
    }

    /**
     * Parses the signature.
     *
     * @access public
     * @return boolean True if successful.
     */
    public function parseSignature() {
        $parsed = false;
        $sig = $this->get('signature');
        if ($sig != NULL) {
            $parsed = explode('-',$sig);
            if (count($parsed) == 3) {
                $this->identifier = next($parsed);
                $this->version = next($parsed);
                $this->release = next($parsed);
                $parsed = true;
            }
        }
        return $parsed;
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
            if ($this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true)) {
                if ($workspace = $this->getOne('Workspace')) {
                    $packageDir = $workspace->get('path') . 'packages/';
                    if ($sourceFile = $this->get('source')) {
                        $transferred= file_exists($packageDir . $sourceFile);
                        if (!$transferred) {
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
                            if ($state < 0) $state = $this->get('state');
                            if ($this->package = xPDOTransport :: retrieve($this->xpdo, $packageDir . $sourceFile, $packageDir, $state)) {
                                if ($state == xPDOTransport::STATE_PACKED) {
                                    $this->set('state', xPDOTransport::STATE_UNPACKED);
                                }
                                $this->set('source', $sourceFile);
                                /*
//                                $this->set('manifest', array(
//                                    xPDOTransport::MANIFEST_VERSION => $this->package->manifestVersion,
//                                    xPDOTransport::MANIFEST_ATTRIBUTES => $this->package->attributes,
//                                    xPDOTransport::MANIFEST_VEHICLES => $this->package->vehicles
//                                ));
 */
                                $this->set('attributes', $this->package->attributes);
                                $this->save();
                            }
                        }
                    } else {
                        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_source_nf'));
                    }
                }
            }
        }
        return $this->package;
    }

    /**
     * Overrides xPDOObject::remove. Removes and uninstalls the package.
     *
     * {@inheritdoc}
     */
    public function remove($force = false,array $ancestors = array(),$uninstall = true) {
        $removed = false;
        if ($this->get('installed') == null || $this->get('installed') == '0000-00-00 00:00:00') {
            $uninstalled = true;
        } else if ($uninstall) {
            $uninstalled = $this->uninstall();
        }

        if ($uninstalled || $force) {
            $removed= parent::remove($ancestors);
        }

        return $removed;
    }


    /**
     * Installs the package.
     *
     * @access public
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
            @ini_set('max_execution_time', 0);
            $this->xpdo->log(xPDO::LOG_LEVEL_INFO,$this->xpdo->lexicon('package_installing'));
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
     * @return boolean True if successful.
     */
    public function uninstall(array $options = array()) {
        $uninstalled = false;
        if ($this->getTransport()) {
            $this->getOne('Workspace');
            $wc = isset($this->Workspace->config) && is_array($this->Workspace->config) ? $this->Workspace->config : array();
            $at = is_array($this->get('attributes')) ? $this->get('attributes') : array();
            $attributes = array_merge($wc,$at);
            $attributes = array_merge($attributes, $options);
            @ini_set('max_execution_time', 0);
            if ($this->package->uninstall($attributes)) {
                $uninstalled = true;
                $this->set('installed',NULL);
                $this->set('attributes',$attributes);
                $this->save();
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_uninstall',array(
                    'signature' => $this->package->get('signature'),
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

            /* see if user has allow_url_fopen on */
            if (ini_get('allow_url_fopen')) {
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

            /* if not, try curl */
            } else if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $source);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT,120);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $content = curl_exec($ch);
                curl_close($ch);

            /* and as last-ditch resort, try fsockopen */
            } else {
                $content = $this->_getByFsockopen($source);
            }

            if ($content) {
                if ($cacheManager= $this->xpdo->getCacheManager()) {
                    $filename= $this->signature.'.transport.zip';
                    $target= $targetDir . $filename;
                    $transferred= $cacheManager->writeFile($target, $content);
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'MODx could not download the file. You must enable allow_url_fopen, cURL or fsockopen to use remote transport packaging.');
            }
        } else {
             $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('package_err_target_write',array(
                'targetDir' => $targetDir,
            )));
        }
        return $transferred;
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
        $modifier = strtolower($value{strlen($value)-1});
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
            fwrite($fp, "GET $path HTTP/1.0\r\n" .
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
}