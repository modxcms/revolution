<?php
/**
 * Update a package from its provider.
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class modPackageCheckForUpdatesProcessor extends modProcessor {
    /** @var modTransportPackage $package */
    public $package;
    /** @var modTransportProvider $provider */
    public $provider;
    /** @var string $packageSignature */
    public $packageSignature = '';

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $signature = $this->getProperty('signature');
        $this->package = $this->modx->getObject('transport.modTransportPackage',$signature);
        if (empty($this->package)) {
            $msg = $this->modx->lexicon('package_err_nf');
            $this->modx->log(modX::LOG_LEVEL_ERROR,$msg);
            return $msg;
        }
        $this->packageSignature = explode('-',$this->package->get('signature'));
        if ($this->package->provider != 0) { /* if package has a provider */
            $this->provider = $this->package->getOne('Provider');
            if (empty($this->provider)) {
                $msg = $this->modx->lexicon('provider_err_nf');
                $this->modx->log(modX::LOG_LEVEL_ERROR,$msg);
                return $msg;
            }
        } else {
            /* if no provider, output error. you can't update something without a provider! */
            $msg = $this->modx->lexicon('package_update_err_provider_nf');
            $this->modx->log(modX::LOG_LEVEL_ERROR,$msg);
            return $msg;
        }

        return parent::initialize();
    }

    public function process() {
        $this->modx->log(modX::LOG_LEVEL_INFO,$this->modx->lexicon('package_update_info_provider_scan',array('provider' => $this->provider->get('name'))));

        /* get provider client */
        $loaded = $this->provider->getClient();
        if (!$loaded) return $this->failure($this->modx->lexicon('provider_err_no_client'));

        $packages = $this->getPackages();
        if (is_string($packages)) {
            return $this->failure($packages);
        }

        /* if no newer packages were found */
        if (count($packages) < 1) {
            $msg = $this->modx->lexicon('package_err_uptodate',array('signature' => $this->package->get('signature')));
            $this->modx->log(modX::LOG_LEVEL_INFO,$msg);
            return $this->failure($msg);
        }

        $list = array();
        /** @var SimpleXMLObject $p */
        foreach ($packages as $package) {
            $packageArray = array(
                'id' => (string)$package->id,
                'package' => (string)$package->package,
                'version' => (string)$package->version,
                'release' => (string)$package->release,
                'signature' => (string)$package->signature,
                'location' => (string)$package->location,
                'info' => ((string)$package->location).'::'.((string)$package->signature),
            );
            $list[] = $packageArray;
        }

        return $this->success('',$list);
    }

    public function getPackages() {
        /* get current version for supportability */
        $this->modx->getVersionData();
        $productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];

        /** @var modRestResponse $response */
        $response = $this->provider->request('package/update','GET',array(
            'signature' => $this->package->get('signature'),
            'supports' => $productVersion,
        ));
        if ($response->isError()) {
            return $this->modx->lexicon('provider_err_connect',array('error' => $response->getError()));
        }
        return $response->toXml();
    }
}
return 'modPackageCheckForUpdatesProcessor';
