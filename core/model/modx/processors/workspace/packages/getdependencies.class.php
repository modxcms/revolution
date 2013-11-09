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
class modPackageGetDependenciesProcessor extends modObjectGetListProcessor {
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
    /** @var modTransportPackage $package */
    public $package;
    /** @var xPDOTransport $transport */
    public $transport;

    public function initialize() {
        $signature = $this->getProperty('signature');
        if (empty($signature)) return $this->modx->lexicon('package_err_ns');

        $this->package = $this->modx->getObject('transport.modTransportPackage',$signature);
        if (empty($this->package)) return $this->modx->lexicon('package_err_nf');

        $this->transport = $this->package->getTransport();
        if (!$this->transport) {
            return $this->modx->lexicon('package_err_nf');
        }

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

    public function process() {
        $requires = $this->transport->getAttribute('requires');

        $dep = $this->package->checkDependencies($requires);

        $dependencies = array();

        $this->updatesCacheExpire = $this->modx->getOption('auto_check_pkg_updates_cache_expire',null,5) * 60;
        $this->modx->getVersionData();
        $this->productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];

        foreach ($dep as $pkg => $cons) {
            $packageArray = array_pop($this->package->findResolution($pkg, $cons));
            $packageArray = $this->formatDates($packageArray);
            $packageArray['iconaction'] = empty($packageArray['installed']) ? 'icon-install' : 'icon-uninstall';
            $packageArray['textaction'] = empty($packageArray['installed']) ? $this->modx->lexicon('install') : $this->modx->lexicon('uninstall');
            $dependencies[] = $packageArray;
        }

        return $this->outputArray($dependencies, count($dep));
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
                    $updates = $provider->latest($package->get('signature'));
                    $updates = array('count' => count($updates));
                    $this->modx->cacheManager->set($updateCacheKey, $updates, $this->updatesCacheExpire, $updateCacheOptions);
                }
            }
        }
        $packageArray['updateable'] = (int)$updates['count'] >= 1 ? true : false;
        return $packageArray;
    }
}
return 'modPackageGetDependenciesProcessor';
