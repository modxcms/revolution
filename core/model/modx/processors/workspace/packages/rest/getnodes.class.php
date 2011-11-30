<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
class modPackageGetNodesProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;

    public $nodeType = 'root';
    public $nodeKey = '';

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

        return true;
    }

    public function process() {
        /* get client */
        /* load appropriate processor */
        $id = $this->getProperty('id','n_root_0');
        $ar = explode('_',$id);
        $this->nodeType = !empty($ar[1]) ? $ar[1] : 'root';
        $this->nodeKey = !empty($ar[2]) ? $ar[2] : null;
        switch ($this->nodeType) {
            case 'repository':
                $list = $this->getCategories();
                break;
            case 'tag':
                $list = array();
                break;
            case 'root':
            default:
                $list = $this->getRepositories();
                break;
        }
        if (!is_array($list)) {
            return $this->failure($list);
        }
        return $this->toJSON($list);
    }
    
    public function getRepositories() {
        /** @var modRestResponse $response */
        $response = $this->provider->request('repository');
        if ($response->isError()) {
            return $this->modx->lexicon('provider_err_connect',array('error' => $response->getError()));
        }
        $repositories = $response->toXml();
        
        $list = array();
        foreach ($repositories as $repository) {
            $repositoryArray = array();
            foreach ($repository->children() as $k => $v) {
                $repositoryArray[$k] = (string)$v;
            }
            $list[] = array(
                'id' => 'n_repository_'.(string)$repository->id,
                'text' => (string)$repository->name,
                'leaf' => $repository->packages > 0 ? false : true,
                'data' => $repositoryArray,
                'type' => 'repository',
            );
        }
        return $list;
    }

    public function getCategories() {
        $this->modx->getVersionData();
        $productVersion = $this->modx->version['code_name'].'-'.$this->modx->version['full_version'];

        /** @var modRestResponse $response */
        $response = $this->provider->request('repository/'.$this->nodeKey,'GET',array(
            'supports' => $productVersion,
        ));
        if ($response->isError()) {
            return $this->modx->lexicon('provider_err_connect',array('error' => $response->getError()));
        }
        $tags = $response->toXml();

        $list = array();
        foreach ($tags as $tag) {
            if ((string)$tag->name == '') continue;
            $list[] = array(
                'id' => 'n_tag_'.(string)$tag->id.'_'.$this->nodeKey,
                'text' => (string)$tag->name,
                'leaf' => true,
                'data' => $tag,
                'type' => 'tag',
            );
        }

        return $list;
    }
}
return 'modPackageGetNodesProcessor';