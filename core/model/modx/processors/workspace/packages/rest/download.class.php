<?php
/**
 * Download a package by passing in its location
 *
 * @var modX $this->modx
 *
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
class modPackageDownloadProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;
    /** @var string $location The actual file location of the download */
    public $location;
    /** @var string $signature The signature of the transport package */
    public $signature;
    /** @var modTransportPackage $package */
    public $package;

    /**
     * Ensure user has access to do this
     * 
     * {@inheritDoc}
     * @return boolean
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }

    /**
     * The language topics to load
     * 
     * {@inheritDoc}
     * @return array
     */
    public function getLanguageTopics() {
        return array('workspace');
    }

    /**
     * Ensure the info was properly passed and initialize the processor
     * 
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        @set_time_limit(0);
        $info = $this->getProperty('info','');
        if (empty($info)) return $this->modx->lexicon('package_download_err_ns');
        if (!$this->parseInfo($info)) {
            return $this->modx->lexicon('invalid_data');
        }
        return parent::initialize();
    }

    /**
     * Run the processor, downloading and transferring the package, and creating the metadata in the database
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        if (!$this->loadProvider()) {
            return $this->failure($this->modx->lexicon('provider_err_nf'));
        }
        if (!$this->provider->getClient()) {
            return $this->failure($this->modx->lexicon('provider_err_no_client'));
        }

        $this->getTransportPackage();

        $metaLoaded = $this->getPackageMetadata();
        if ($metaLoaded !== true) {
            return $this->failure($metaLoaded);
        }

        $this->setPackageVersionData();

        $url = $this->getFileDownloadUrl();
        if (!is_string($url)) {
            return $url;
        }

        if (!$this->downloadPackage($url)) {
            $msg = $this->modx->lexicon('package_download_err',array('location' => $url));
            $this->modx->log(modX::LOG_LEVEL_ERROR,$msg);
            return $this->failure($msg);
        }

        if (!$this->package->save()) {
            $msg = $this->modx->lexicon('package_download_err_create',array('signature' => $this->signature));
            $this->modx->log(modX::LOG_LEVEL_ERROR,$msg);
            return $this->failure($msg);
        }

        $this->package->getTransport();
        return $this->success('',$this->package);
    }

    /**
     * Load the provider for the package
     * @return boolean
     */
    public function loadProvider() {
        $provider = $this->getProperty('provider');
        if (empty($provider)) {
            $c = $this->modx->newQuery('transport.modTransportProvider');
            $c->where(array(
                'name:=' => 'modxcms.com',
                'OR:name:=' => 'modx.com',
            ));
            $this->provider = $this->modx->getObject('transport.modTransportProvider',$c);
            if (!empty($this->provider)) {
                $this->setProperty('provider',$this->provider->get('id'));
            }
        } else {
            $this->provider = $this->modx->getObject('transport.modTransportProvider',$provider);
        }
        return !empty($this->provider);
    }

    /**
     * Parse the information sent to the processor
     * @param string $info
     * @return boolean
     */
    public function parseInfo($info) {
        $parsed = false;
        $parsedInfo = explode('::',$info);
        if (!empty($parsedInfo) && !empty($parsedInfo[1])) {
            $this->location = $parsedInfo[0];
            $this->signature = $parsedInfo[1];
            $parsed = true;
        }
        return $parsed;
    }

    /**
     * Prepare the soon-to-be-created Transport Package object
     * 
     * @return modTransportPackage
     */
    public function getTransportPackage() {
        /** @var modTransportPackage $package */
        $this->package = $this->modx->newObject('transport.modTransportPackage');
        $this->package->set('signature',$this->signature);
        $this->package->set('state',1);
        $this->package->set('workspace',1);
        $this->package->set('created',date('Y-m-d h:i:s'));
        $this->package->set('provider',$this->provider->get('id'));
        return $this->package;
    }

    /**
     * Get Package metadata from the provider
     * 
     * @return array|string
     */
    public function getPackageMetadata() {
        /** @var modRestResponse $response */
        $response = $this->provider->request('package','GET',array(
            'signature' => $this->signature,
        ));
        if ($response->isError()) {
            return $this->modx->lexicon('provider_err_connect',array('error' => $response->getError()));
        }
        $metadataXml = $response->toXml();

        /* set package metadata */
        $metadata = array();
        $this->modx->rest->xml2array($metadataXml,$metadata);
        $this->package->set('metadata',$metadata);
        $this->package->set('package_name', (string) $metadataXml->name);

        return true;
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
                if (isset($v[0])) $this->package->set('version_major',$v[0]);
                if (isset($v[1])) $this->package->set('version_minor',$v[1]);
                if (isset($v[2])) $this->package->set('version_patch',$v[2]);
            }
            if (!empty($sig[2])) {
                $r = preg_split('/([0-9]+)/',$sig[2],-1,PREG_SPLIT_DELIM_CAPTURE);
                if (is_array($r) && !empty($r)) {
                    $this->package->set('release',$r[0]);
                    $this->package->set('release_index',(isset($r[1]) ? $r[1] : '0'));
                } else {
                    $this->package->set('release',$sig[2]);
                }
            }
        }
        return true;
    }

    /**
     * Get the actual file location from the provider
     * @return array|string
     */
    public function getFileDownloadUrl() {
        if (!is_array($this->modx->version)) { $this->modx->getVersionData(); }
        $productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];
        $this->modx->rest->setResponseType('text');
        $response = $this->modx->rest->request($this->location,'','GET',array(
            'revolution_version' => $productVersion,
            'getUrl' => true,
        ));
        $this->modx->rest->setResponseType('xml');
        if (empty($response) || empty($response->response)) {
            return $this->failure($this->modx->lexicon('provider_err_connect',array('error' => $response->getError())));
        }
        return (string)$response->response;
    }

    /**
     * Download the actual transport package file to this server
     * @param string $url
     * @return boolean
     */
    public function downloadPackage($url) {
        $_package_cache = $this->modx->getOption('core_path',null,MODX_CORE_PATH).'packages/';
        return $this->package->transferPackage($url,$_package_cache);
    }
}
return 'modPackageDownloadProcessor';