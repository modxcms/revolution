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
 * Represents a remote transport package provider service.
 *
 * @uses modRestClient This REST implementation is used to communicate with a
 * remote server that can provide information about and downloads of one or more
 * MODX transport packages.
 *
 * @property string $name The name of the provider
 * @property string $description A description of the provider
 * @property string $service_url The service URL, or entry point, to the provider
 * @property string $username The username needed to connect to the provider
 * @property string $api_key The API key needed to connect to the specified provider
 * @property string $created When this provider was created
 * @property string $updated When this provider was last updated
 * @property bool|string $active If the provider is currently active
 *
 * @package modx
 * @subpackage transport
 */
class modTransportProvider extends xPDOSimpleObject {
    /** @var xPDO|modX */
    public $xpdo = null;

    public function __construct(&$xpdo) {
        parent::__construct($xpdo);
        $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
    }

    /**
     * Return a list repositories from this Provider.
     *
     * @return array A list of repositories in this Provider.
     */
    public function repositories() {
        /** @var modRestResponse $response */
        $response = $this->request('repository');
        if ($response->isError()) {
            return $this->xpdo->lexicon('provider_err_connect', array('error' => $response->getError()));
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
                'leaf' => false,
                'data' => $repositoryArray,
                'type' => 'repository',
                'iconCls' => 'icon icon-folder',
            );
        }
        return $list;
    }

    public function categories($node) {
        $this->xpdo->getVersionData();
        $productVersion = $this->xpdo->version['code_name'].'-'.$this->xpdo->version['full_version'];

        /** @var modRestResponse $response */
        $response = $this->request('repository/' . $node, 'GET', array(
            'supports' => $productVersion,
        ));
        if ($response->isError()) {
            return $this->xpdo->lexicon('provider_err_connect',array('error' => $response->getError()));
        }
        $tags = $response->toXml();

        $list = array();
        foreach ($tags as $tag) {
            if ((string)$tag->name == '') continue;
            $list[] = array(
                'id' => 'n_tag_' . (string)$tag->id . '_' . $node,
                'text' => (string)$tag->name,
                'leaf' => true,
                'data' => $tag,
                'type' => 'tag',
                'iconCls' => 'icon icon-tag',
            );
        }

        return $list;
    }

    /**
     * Return statistical data about this Provider
     *
     * @param array $args Additional arguments to pass to the provider service
     *
     * @return array An array of statistics
     */
    public function stats(array $args = array()) {
        $stats = array(
            'packages' => 0,
            'downloads' => 0,
            'topdownloaded' => array(),
            'newest' => array(),
        );
        $response = $this->request('home', 'GET', $args);
        if ($response) {
            if (!$response->isError()) {
                $xml = $response->toXml();

                $stats['packages'] = number_format((integer)$xml->packages);
                $stats['downloads'] = number_format((integer)$xml->downloads);
                /** @var SimpleXMLElement $package */
                foreach ($xml->topdownloaded as $package) {
                    $stats['topdownloaded'][] = array(
                        'url' => (string)$xml->url,
                        'id' => (string)$package->id,
                        'name' => (string)$package->name,
                        'downloads' => number_format((integer)$package->downloads,0),
                    );
                }
                /** @var SimpleXMLElement $package */
                foreach ($xml->newest as $package) {
                    $stats['newest'][] = array(
                        'url' => (string)$xml->url,
                        'id' => (string)$package->id,
                        'name' => (string)$package->name,
                        'package_name' => (string)$package->package_name,
                        'releasedon' => strftime('%b %d, %Y', strtotime((string)$package->releasedon)),
                    );
                }
            } else {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $response->getError(), '', __METHOD__, __FILE__, __LINE__);
            }
        }
        return $stats;
    }

    public function info($identifier, array $args = array()) {
        $info = array();
        if (strpos($identifier, '-') > 0) {
            $response = $this->request('package', 'GET', array('signature' => $identifier));
            if ($response) {
                if (!$response->isError()) {
                    $xml = $response->toXml();
                    $this->fromXML($xml, $info);
                }
            }
        } else {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not load package info from name for ' . $identifier . ', not yet implemented.');
            /* TODO: implement package info by package name */
        }
        return $info;
    }

    public function latest($identifier, $constraint = '*', array $args = array()) {
        $latest = array();
        if (strpos($identifier, '-') === false) {
            /** @var modRestResponse $response */
            $response = $this->request(
                'package/versions',
                'GET',
                array_merge(
                    array(
                        'package' => $identifier,
                        'constraint' => $constraint
                    ),
                    $args
                )
            );
            if ($response) {
                if (!$response->isError()) {
                    $xml = $response->toXml();
                    /** @var SimpleXMLElement $resolver */
                    foreach ($xml as $resolver) {
                        $node = array();
                        if (xPDOTransport::satisfies((string)$resolver->version, $constraint)) {
                            $this->fromXML($resolver, $node);
                            array_push($latest, $node);
                        }
                    }
                } else {
                    $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $response->getError(), '', __METHOD__, __FILE__, __LINE__);
                }
            }
        } else {
            $response = $this->request(
                'package/update',
                'GET',
                array_merge(
                    array(
                        'signature' => $identifier,
                        'constraint' => $constraint
                    ),
                    $args
                )
            );
            if ($response) {
                if (!$response->isError()) {
                    $xml = $response->toXml();
                    foreach ($xml as $resolver) {
                        $node = array();
                        if (xPDOTransport::satisfies((string)$resolver->version, $constraint)) {
                            $this->fromXML($resolver, $node);
                            array_push($latest, $node);
                        }
                    }
                }
            }
        }
        return $latest;
    }

    public function transfer($signature, $target = null, array $args = array()) {
        $result = false;
        $metadata = $this->info($signature);
        if (!empty($metadata)) {
            /** @var modTransportPackage $package */
            $package = $this->xpdo->newObject('transport.modTransportPackage');
            $package->set('signature', $signature);
            $package->set('state', 1);
            $package->set('workspace', 1);
            $package->set('created', strftime('%Y-%m-%d %H:%M:%S'));
            $package->set('provider', $this->get('id'));
            $package->set('metadata', $metadata);
            $package->set('package_name', $metadata['name']);

            $package->parseSignature();
            $package->setPackageVersionData();

            $locationArgs = (isset($metadata['file'])) ? array_merge($metadata['file'], $args) : $args;
            $url = $this->downloadUrl($signature, $this->arg('location', $locationArgs), $args);
            if (!empty($url)) {
                if (empty($target)) {
                    $target = $this->xpdo->getOption('core_path', $args, MODX_CORE_PATH) . 'packages/';
                }
                if ($package->transferPackage($url, $target)) {
                    if ($package->save()) {
                        $package->getTransport();
                        $result = $package;
                    }
                }
            }
        }

        return $result;
    }

    public function find(array $search = array(), array $args = array()) {
        $results = array();

        $where = array_merge(
            array(
                'query' => false,
                'tag' => false,
                'sorter' => false,
                'start' => 0,
                'limit' => 10,
                'dateFormat' => '%b %d, %Y',
                'supportsSeparator' => ', ',
            ),
            $search
        );
        $where['page'] = !empty($where['start']) ? round($where['start'] / $where['limit']) : 0;

        /** @var modRestResponse $response */
        $response = $this->request('package','GET', $where);
        if ($response->isError()) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, $response->getError(), '', __METHOD__, __FILE__, __LINE__);
            return $results;
        }
        $xml = $response->toXml();

        /** @var SimpleXMLElement $package */
        foreach ($xml as $package) {
            $installed = $this->xpdo->getObject('transport.modTransportPackage', (string)$package->signature);

            $versionCompiled = rtrim((string)$package->version . '-' . (string)$package->release, '-');
            $releasedon = strftime($this->arg('dateFormat', $where), strtotime((string)$package->releasedon));

            $supports = '';
            foreach ($package->supports as $support) {
                $supports .= (string)$support.$this->arg('supportsSeparator', $where);
            }
            $results[] = array(
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
                'featured' => ((string)$package->featured == 'true'),
                'audited' => ((string)$package->audited == 'true'),
                'dlaction-icon' => $installed ? 'package-installed' : 'package-download',
                'dlaction-text' => $installed ? $this->xpdo->lexicon('downloaded') : $this->xpdo->lexicon('download'),
            );
        }

        return array((int)$xml['total'], $results);
    }

    protected function downloadUrl($signature, $location, array $args = array()) {
        $url = false;
        /** @var modRestClient $rest */
        $rest = $this->xpdo->getService('rest','rest.modRestClient');
        if ($rest) {
            $responseType = $rest->responseType;
            $rest->setResponseType('text');
            $response = $rest->request(
                $location,
                '',
                'GET',
                array(
                    'revolution_version' => $this->arg('revolution_version', $this->args($args)),
                    'getUrl' => true,
                )
            );
            if (empty($response) || empty($response->response)) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not get download url for package {$signature} using location {$location}");
            } elseif ($response->isError()) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not get download url for package {$signature} using location {$location}: {$response->getError()}");
            } else {
                $url = (string)$response->response;
            }
            $rest->setResponseType($responseType);
        }
        return $url;
    }

    protected function fromXML(SimpleXMLElement $xml, array &$array, $level = 0) {
        if ($xml->count()) {
            $node = array();
            foreach ($xml->children() as $child) {
                $this->fromXML($child, $node, $level + 1);
            }
            $level === 0 ? $array = $node : $array[$xml->getName()] = $node;
        } else {
            $array[$xml->getName()] = (string)$xml;
        }
    }

    protected function arg($key, array $args = array(), $default = null) {
        $arg = $default;
        if (array_key_exists($key, $this->args($args))) {
            $arg = $args[$key];
        }
        return $arg;
    }

    protected function args(array $args = array()) {
        if (!is_array($this->xpdo->version)) {
            $this->xpdo->getVersionData();
        }
        $baseArgs = array(
            'api_key' => $this->get('api_key'),
            'username' => $this->get('username'),
            'uuid' => $this->xpdo->uuid,
            'database' => $this->xpdo->config['dbtype'],
            'revolution_version' => $this->xpdo->version['code_name'].'-'.$this->xpdo->version['full_version'],
            'supports' => $this->xpdo->version['code_name'].'-'.$this->xpdo->version['full_version'],
            'http_host' => $this->xpdo->getOption('http_host'),
            'php_version' => XPDO_PHP_VERSION,
            'language' => $this->xpdo->getOption('manager_language'),
        );
        return array_merge($baseArgs, $args);
    }

    /**
     * Sends a REST request to the provider
     *
     * @param string $path The path of the request
     * @param string $method The method of the request (GET/POST)
     * @param array $params An array of parameters to send to the REST request
     * @return modRestResponse|bool The response from the REST request, or false
     */
    public function request($path, $method = 'GET', $params = array()) {
        $response = false;
        $service = $this->getClient();
        if ($service) {
            $response = $service->request($this->get('service_url'), $path, $method, $this->args($params));
        } else {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, $this->xpdo->lexicon('provider_err_no_client'), '', __METHOD__, __FILE__, __LINE__);
        }
        return $response;
    }

    /**
     * Get the client responsible for communicating with the provider.
     *
     * @return modRestClient|bool A REST client instance, or FALSE.
     */
    public function getClient() {
        if (empty($this->xpdo->rest)) {
            $this->xpdo->getService('rest','rest.modRestClient');
            $loaded = $this->xpdo->rest->getConnection();
            if (!$loaded) return false;
        }
        return $this->xpdo->rest;
    }

    /**
     * Verifies the authenticity of the provider
     *
     * @return boolean True if verified, xml if failed
     */
    public function verify() {
        $response = $this->request('verify','GET');
        if ($response->isError()) {
            $message = $response->getError();
            if ($this->xpdo->lexicon && $this->xpdo->lexicon->exists('provider_err_'.$message)) {
                $message = $this->xpdo->lexicon('provider_err_'.$message);
            }
            return $message;
        }
        $status = $response->toXml();
        return (boolean)$status->verified;
    }

    /**
     * Overrides xPDOObject::save to set the created date.
     *
     * @param boolean $cacheFlag
     * @return boolean True if successful
     */
    public function save($cacheFlag= null) {
        if ($this->isNew() && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }
}
