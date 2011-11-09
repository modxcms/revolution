<?php
/**
 * @package modx
 */
class modPackageRemoteGetListProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }

    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $provider = $this->getProperty('provider',false);
        if (empty($provider)) return $this->modx->lexicon('provider_err_ns');
        $this->provider = $this->modx->getObject('transport.modTransportProvider',$provider);
        if (empty($this->provider)) return $this->modx->lexicon('provider_err_nf');

        $loaded = $this->provider->getClient();
        if (!$loaded) return $this->modx->lexicon('provider_err_no_client');

        $this->setDefaultProperties(array(
            'query' => false,
            'tag' => false,
            'sorter' => false,
            'start' => 0,
            'limit' => 10,
            'dateFormat' => '%b %d, %Y',
            'supportsSeparator' => ', ',
        ));
        $start = $this->getProperty('start');
        $limit = $this->getProperty('limit');
        $this->setProperty('page',!empty($start) ? round($start / $limit) : 0);
        return true;
    }
    
    public function process() {
        $data = $this->getData();
        if (!is_array($data)) {
            $this->failure($data);
        }

        $list = array();
        foreach ($data->package as $package) {
            if ((string)$package->name == '') continue;
            $list[] = $this->prepareRow($package);
        }
        return $this->outputArray($list,(int)$data['total']);
    }


    public function getData() {
        /* get version */
        $this->modx->getVersionData();
        $productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];

        /* send request and process response */
        $where = array(
            'page' => $this->getProperty('page'),
            'supports' => $productVersion,
            'sorter' => $this->getProperty('sorter'),
        );
        
        $tag = $this->getProperty('tag');
        if (!empty($tag)) $where['tag'] = $tag;

        $query = $this->getProperty('query');
        if (!empty($query)) $where['query'] = $query;

        /** @var modRestResponse $response */
        $response = $this->provider->request('package','GET',$where);
        if ($response->isError()) {
            return $this->modx->lexicon('provider_err_connect',array('error' => $response->getError()));
        }
        return $response->toXml();
    }

    public function prepareRow($package) {
        $installed = $this->modx->getObject('transport.modTransportPackage',(string)$package->signature);

        $versionCompiled = rtrim((string)$package->version.'-'.(string)$package->release,'-');
        $releasedon = strftime($this->getProperty('dateFormat'),strtotime((string)$package->releasedon));

        $supports = '';
        foreach ($package->supports as $support) {
            $supports .= (string)$support.$this->getProperty('supportsSeparator');
        }

        return array(
            'id' => (string)$package->id,
            'version' => (string)$package->version,
            'release' => (string)$package->release,
            'signature' => (string)$package->signature,
            'author' => (string)$package->author,
            'description' => (string)$package->description,
            'instructions' => (string)$package->instructions,
            'changelog' => (string)$package->changelog,
            'createdon' => (string)$package->createdon,
            'editedon' => (string)$package->editedon,
            'name' => (string)$package->name,
            'downloads' => number_format((integer)$package->downloads,0),
            'releasedon' => $releasedon,
            'screenshot' => (string)$package->screenshot,
            'thumbnail' => !empty($package->thumbnail) ? (string)$package->thumbnail : (string)$package->screenshot,
            'license' => (string)$package->license,
            'minimum_supports' => (string)$package->minimum_supports,
            'breaks_at' => (integer)$package->breaks_at != 10000000 ? (string)$package->breaks_at : '',
            'supports_db' => (string)$package->supports_db,
            'location' => (string)$package->location,
            'version-compiled' => $versionCompiled,
            'downloaded' => !empty($installed) ? true : false,
            'featured' => (boolean)$package->featured,
            'audited' => (boolean)$package->audited,
            'dlaction-icon' => $installed ? 'package-installed' : 'package-download',
            'dlaction-text' => $installed ? $this->modx->lexicon('downloaded') : $this->modx->lexicon('download'),
        );
    }
}
return 'modPackageRemoteGetListProcessor';