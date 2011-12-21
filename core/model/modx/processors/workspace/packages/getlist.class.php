<?php
/**
 * Gets a list of packages
 *
 * @param integer $workspace (optional) The workspace to filter by. Defaults to
 * 1.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class modPackageGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'transport.modTransportPackage';
    public $checkListPermission = false;
    public $permission = 'packages';
    public $languageTopics = array('workspace');

    /** @var int $updatesCacheExpire */
    public $updatesCacheExpire = 300;
    /** @var string $productVersion */
    public $productVersion = '';
    /** @var array $providerCache */
    public $providerCache = array();

    public function initialize() {
        $this->modx->addPackage('modx.transport',$this->modx->getOption('core_path').'model/');
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'workspace' => 1,
            'dateFormat' => '%b %d, %Y %I:%M %p',
            'search' => '',
        ));
        return true;
    }

    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        $pkgList = $this->modx->call('transport.modTransportPackage', 'listPackages', array(
            &$this->modx,
            $this->getProperty('workspace',1),
            $limit > 0 ? $limit : 0,
            $start,
            $this->getProperty('search','')
        ));
        $data['results'] = $pkgList['collection'];
        $data['total'] = $pkgList['total'];
        return $data;
    }

    public function beforeIteration(array $list) {
        $this->updatesCacheExpire = $this->modx->getOption('auto_check_pkg_updates_cache_expire',null,5) * 60;
        $this->modx->getVersionData();
        $this->productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];
        return $list;
    }

    public function getSortClassKey() {
        return 'modTransportPackage';
    }

    /**
     * @param xPDOObject|modTransportPackage $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        if ($object->get('installed') == '0000-00-00 00:00:00') $object->set('installed',null);
        $packageArray = $object->toArray();
        $packageArray = $this->getVersionInfo($packageArray);
        $packageArray = $this->formatDates($packageArray);
        $packageArray['iconaction'] = empty($packageArray['installed']) ? 'icon-install' : 'icon-uninstall';
        $packageArray['textaction'] = empty($packageArray['installed']) ? $this->modx->lexicon('install') : $this->modx->lexicon('uninstall');
        $packageArray = $this->getPackageMeta($object,$packageArray);
        $packageArray = $this->checkForUpdates($object,$packageArray);

        return $packageArray;
    }

    /**
     * Get basic version information about the package
     * 
     * @param array $packageArray
     * @return array
     */
    public function getVersionInfo(array $packageArray) {
        $signatureArray = explode('-',$packageArray['signature']);
        $packageArray['name'] = $packageArray['package_name'];
        $packageArray['version'] = $signatureArray[1];
        if (isset($signatureArray[2])) {
            $packageArray['release'] = $signatureArray[2];
        }
        return $packageArray;
    }

    /**
     * Format installed, created and updated dates
     * @param array $packageArray
     * @return array
     */
    public function formatDates(array $packageArray) {
        if ($packageArray['updated'] != '0000-00-00 00:00:00' && $packageArray['updated'] != null) {
            $packageArray['updated'] = utf8_encode(strftime($this->getProperty('dateFormat'),strtotime($packageArray['updated'])));
        } else {
            $packageArray['updated'] = '';
        }
        $packageArray['created']= utf8_encode(strftime($this->getProperty('dateFormat'),strtotime($packageArray['created'])));
        if ($packageArray['installed'] == null || $packageArray['installed'] == '0000-00-00 00:00:00') {
            $packageArray['installed'] = null;
        } else {
            $packageArray['installed'] = utf8_encode(strftime($this->getProperty('dateFormat'),strtotime($packageArray['installed'])));
        }
        return $packageArray;
    }

    /**
     * Setup description, using either metadata or readme
     * 
     * @param modTransportPackage $package
     * @param array $packageArray
     * @return array
     */
    public function getPackageMeta(modTransportPackage $package,array $packageArray) {
        $metadata = $packageArray['metadata'];
        if (!empty($metadata)) {
            foreach ($metadata as $row) {
                if (!empty($row['name']) && $row['name'] == 'description') {
                    $packageArray['readme'] = nl2br($row['text']);
                    break;
                }
            }
        } else {
            /** @var xPDOTransport $transport */
            $transport = $package->getTransport();
            if ($transport) {
                $packageArray['readme'] = $transport->getAttribute('readme');
                $packageArray['readme'] = nl2br($packageArray['readme']);
            }
        }
        unset($packageArray['attributes']);
        unset($packageArray['metadata']);
        return $packageArray;
    }

    /**
     * @param modTransportPackage $package
     * @param array $packageArray
     * @return array
     */
    public function checkForUpdates(modTransportPackage $package,array $packageArray) {
        $updates = array('count' => 0);
        if ($package->get('provider') > 0 && $this->modx->getOption('auto_check_pkg_updates',null,false)) {
            $updateCacheKey = 'mgr/providers/updates/'.$package->get('provider').'/'.$package->get('signature');
            $updateCacheOptions = array(
                xPDO::OPT_CACHE_KEY => $this->modx->cacheManager->getOption('cache_packages_key', null, 'packages'),
                xPDO::OPT_CACHE_HANDLER => $this->modx->cacheManager->getOption('cache_packages_handler', null, $this->modx->cacheManager->getOption(xPDO::OPT_CACHE_HANDLER)),
            );
            $updates = $this->modx->cacheManager->get($updateCacheKey, $updateCacheOptions);
            if (empty($updates)) {
                /* cache providers to speed up load time */
                /** @var modTransportProvider $provider */
                if (!empty($this->providerCache[$package->get('provider')])) {
                    $provider =& $this->providerCache[$package->get('provider')];
                } else {
                    $provider = $package->getOne('Provider');
                    if ($provider) {
                        $this->providerCache[$provider->get('id')] = $provider;
                    }
                }
                if ($provider) {
                    $loaded = $provider->getClient();
                    if ($loaded) {
                        /** @var modRestResponse $response */
                        $response = $provider->request('package/update','GET',array(
                            'signature' => $package->get('signature'),
                            'supports' => $this->productVersion,
                        ));
                        if ($response && !$response->isError()) {
                            $updates = $response->toXml();
                        }
                    }
                    $updates = array('count' => count($updates));
                    $this->modx->cacheManager->set($updateCacheKey, $updates, $this->updatesCacheExpire, $updateCacheOptions);
                }
            }
        }
        $packageArray['updateable'] = (int)$updates['count'] >= 1 ? true : false;
        return $packageArray;
    }
}
return 'modPackageGetListProcessor';