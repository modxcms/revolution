<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
class modPackageGetInfoProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }

    public function getLanguageTopics() {
        return array('workspace');
    }

    public function process() {
        if (!$this->loadProvider()) {
            return array();
        }
        if (!$this->provider->getClient()) {
            return $this->failure($this->modx->lexicon('provider_err_no_client'));
        }

        $info = $this->getData();
        if (empty($info)) {
            return $this->failure($this->modx->lexicon('provider_err_connect'));
        }

        /* setup output properties */
        $properties = array(
            'packages' => number_format((integer)$info->packages),
            'downloads' => number_format((integer)$info->downloads),
            'topdownloaded' => array(),
            'newest' => array(),
        );

        foreach ($info->topdownloaded as $package) {
            $properties['topdownloaded'][] = array(
                'url' => (string)$info->url,
                'id' => (string)$package->id,
                'name' => (string)$package->name,
                'downloads' => number_format((integer)$package->downloads,0),
            );
        }

        foreach ($info->newest as $package) {
            $properties['newest'][] = array(
                'url' => (string)$info->url,
                'id' => (string)$package->id,
                'name' => (string)$package->name,
                'package_name' => (string)$package->package_name,
                'releasedon' => strftime('%b %d, %Y',strtotime((string)$package->releasedon)),
            );
        }

        return $this->success('',$properties);
    }

    /**
     * Load the provider
     * @return boolean
     */
    public function loadProvider() {
        $loaded = false;
        $provider = $this->getProperty('provider',false);
        if (!empty($provider)) {
            $this->provider = $this->modx->getObject('transport.modTransportProvider',$provider);
            if (!empty($this->provider)) {
                $loaded = true;
            }
        }
        return $loaded;
    }

    /**
     * Get the data from the Provider
     *
     * @return array|string
     */
    public function getData() {
        $this->modx->getVersionData();
        $productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];

        /** @var modRestResponse $response */
        $response = $this->provider->request('home','GET',array(
            'supports' => $productVersion,
        ));
        if ($response->isError()) {
            return $this->failure($this->modx->lexicon('provider_err_connect',array('error' => $response->getError())));
        }
        return $response->toXml();
    }
}
return 'modPackageGetInfoProcessor';
