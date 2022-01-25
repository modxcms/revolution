<?php

namespace MODX\Revolution\Transport;

use MODX\Revolution\modX;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use xPDO\Om\xPDOSimpleObject;
use xPDO\Transport\xPDOTransport;
use xPDO\xPDO;

/**
 * Class modTransportProvider
 *
 * @property string  $name
 * @property string  $description
 * @property string  $service_url
 * @property string  $username
 * @property string  $api_key
 * @property string  $created
 * @property string  $updated
 * @property boolean $active
 * @property integer $priority
 * @property array   $properties
 *
 * @package MODX\Revolution\Transport
 */
class modTransportProvider extends xPDOSimpleObject
{
    /** @var xPDO|modX */
    public $xpdo = null;
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * Return a list repositories from this Provider.
     *
     * @return array|string A list of repositories in this Provider, or an error message.
     */
    public function repositories()
    {
        $response = $this->request('repository');
        if (!$response) {
            return $this->xpdo->lexicon('provider_err_blank_response');
        }

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml->getName() === 'error') {
            return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
        }

        $list = [];
        foreach ($xml as $repository) {
            $repositoryArray = [];
            foreach ($repository->children() as $k => $v) {
                $repositoryArray[$k] = (string)$v;
            }
            $list[] = [
                'id' => 'n_repository_' . (string)$repository->id,
                'text' => (string)$repository->name,
                'leaf' => false,
                'data' => $repositoryArray,
                'type' => 'repository',
                'iconCls' => 'icon icon-folder',
            ];
        }

        return $list;
    }

    /**
     * @param string $node
     * @return array|string An array of categories, or an error message
     */
    public function categories(string $node)
    {
        $version = $this->xpdo->getVersionData();
        $productVersion = $version['code_name'] . '-' . $version['full_version'];

        $response = $this->request('repository/' . $node, 'GET', [
            'supports' => $productVersion,
        ]);
        if (!$response) {
            return $this->xpdo->lexicon('provider_err_blank_response');
        }

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml->getName() === 'error') {
            return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
        }

        $list = [];
        foreach ($xml as $tag) {
            if ((string)$tag->name == '') {
                continue;
            }
            $list[] = [
                'id' => 'n_tag_' . (string)$tag->id . '_' . $node,
                'text' => (string)$tag->name,
                'leaf' => true,
                'data' => $tag,
                'type' => 'tag',
                'iconCls' => 'icon icon-tag',
            ];
        }

        return $list;
    }

    /**
     * Return statistical data about this Provider
     *
     * @param array $args Additional arguments to pass to the provider service
     *
     * @return array|string An array of statistics or an error message
     */
    public function stats(array $args = [])
    {
        $stats = [
            'packages' => 0,
            'downloads' => 0,
            'topdownloaded' => [],
            'newest' => [],
        ];
        $response = $this->request('home', 'GET', $args);
        if (!$response) {
            return $this->xpdo->lexicon('provider_err_blank_response');
        }

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml->getName() === 'error') {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load package provider statistics: " . $xml->message, '', __METHOD__, __FILE__, __LINE__);
            return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
        }

        $stats['packages'] = number_format((int)$xml->packages);
        $stats['downloads'] = number_format((int)$xml->downloads);

        /** @var SimpleXMLElement $package */
        foreach ($xml->topdownloaded as $package) {
            $stats['topdownloaded'][] = [
                'url' => (string)$xml->url,
                'id' => (string)$package->id,
                'name' => (string)$package->name,
                'downloads' => number_format((integer)$package->downloads, 0),
            ];
        }
        /** @var SimpleXMLElement $package */
        foreach ($xml->newest as $package) {
            $stats['newest'][] = [
                'url' => (string)$xml->url,
                'id' => (string)$package->id,
                'name' => (string)$package->name,
                'package_name' => (string)$package->package_name,
                'releasedon' => date('M d, Y', strtotime((string)$package->releasedon)),
            ];
        }

        return $stats;
    }

    /**
     * @param string $identifier
     * @return array|string An array of information about the provided package, or a string error message
     */
    public function info(string $identifier)
    {
        if (strpos($identifier, '-') > 0) {
            $response = $this->request('package', 'GET', ['signature' => $identifier]);
            if (!$response) {
                return $this->xpdo->lexicon('provider_err_blank_response');
            }

            $xml = simplexml_load_string($response->getBody()->getContents());
            if ($xml->getName() === 'error') {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load package info for {$identifier}: {$xml->message}", '', __METHOD__, __FILE__, __LINE__);
                return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
            }
            $info = [];
            $this->fromXML($xml, $info);
            return $info;
        }

        /* TODO: implement package info by package name */
        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Could not load package info from name for ' . $identifier . ', not yet implemented.');

        return [];
    }

    /**
     * Retrieves either the available versions for a provided package name, or checks if updates are available
     * when provided an exact package signature.
     *
     * @param string $identifier
     * @param string $constraint
     * @param array $args
     * @return array|string Array of package versions, or an error string
     */
    public function latest(string $identifier, string $constraint = '*', array $args = [])
    {
        $latest = [];

        // Given a package name, we check the available versions
        if (strpos($identifier, '-') === false) {
            $response = $this->request(
                'package/versions',
                'GET',
                array_merge(
                    [
                        'package' => $identifier,
                        'constraint' => $constraint,
                    ],
                    $args
                )
            );

            if (!$response) {
                return $this->xpdo->lexicon('provider_err_blank_response');
            }

            $xml = simplexml_load_string($response->getBody()->getContents());
            if ($xml->getName() === 'error') {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load latest versions for {$identifier} with constraint {$constraint}: {$xml->message}", '', __METHOD__, __FILE__, __LINE__);
                return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
            }

            foreach ($xml as $resolver) {
                $node = [];
                if (xPDOTransport::satisfies((string)$resolver->version, $constraint)) {
                    $this->fromXML($resolver, $node);
                    array_push($latest, $node);
                }
            }
            return $latest;
        }

        // Given a signature, we ask if an update is available for it
        $response = $this->request(
            'package/update',
            'GET',
            array_merge(
                [
                    'signature' => $identifier,
                    'constraint' => $constraint,
                ],
                $args
            )
        );
        if (!$response) {
            return $this->xpdo->lexicon('provider_err_blank_response');
        }

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml->getName() === 'error') {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load updates for {$identifier}: {$xml->message}", '', __METHOD__, __FILE__, __LINE__);
            return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
        }

        foreach ($xml as $resolver) {
            $node = [];
            if (xPDOTransport::satisfies((string)$resolver->version, $constraint)) {
                $this->fromXML($resolver, $node);
                array_push($latest, $node);
            }
        }

        return $latest;
    }

    /**
     * Downloads the package and creates the modTransportPackage if successful.
     *
     * @param $signature
     * @param null $target
     * @param array $args
     * @return false|modTransportPackage
     */
    public function transfer($signature, $target = null, array $args = [])
    {
        $result = false;
        $metadata = $this->info($signature);
        if (is_array($metadata)) {
            /** @var modTransportPackage $package */
            $package = $this->xpdo->newObject(modTransportPackage::class);
            $package->set('signature', $signature);
            $package->set('state', 1);
            $package->set('workspace', 1);
            $package->set('created', date('Y-m-d H:i:s'));
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

    /**
     * Retrieves packages from the provider that matches the provided search options.
     *
     * @param array $search Array with keys query, tag, sorter, start, limit, dateFormat, supportsSeparator controlling the search request
     * @return array|string Array of packages, or a string error message if the request failed
     */
    public function find(array $search = [])
    {
        $results = [];

        $where = array_merge([
            'query' => false,
            'tag' => false,
            'sorter' => false,
            'start' => 0,
            'limit' => 10,
            'dateFormat' => '%b %d, %Y',
            'supportsSeparator' => ', ',
        ], $search);
        $where['page'] = !empty($where['start']) ? round($where['start'] / $where['limit']) : 0;

        $response = $this->request('package', 'GET', $where);

        if (!$response) {
            return $this->xpdo->lexicon('provider_err_blank_response');
        }

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml->getName() === 'error') {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load packages for search " . json_encode($where) . ": {$xml->message}", '', __METHOD__, __FILE__, __LINE__);
            return $this->xpdo->lexicon('provider_err_connect', ['error' => (string)$xml->message]);
        }

        /** @var SimpleXMLElement $package */
        foreach ($xml as $package) {
            $installed = $this->xpdo->getObject(modTransportPackage::class, (string)$package->signature);

            $versionCompiled = rtrim((string)$package->version . '-' . (string)$package->release, '-');
            $releasedon = strftime($this->arg('dateFormat', $where), strtotime((string)$package->releasedon));

            $supports = '';
            foreach ($package->supports as $support) {
                $supports .= (string)$support . $this->arg('supportsSeparator', $where);
            }
            $results[] = [
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
                'downloads' => number_format((integer)$package->downloads, 0),
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
            ];
        }

        return [(int)$xml['total'], $results];
    }

    /**
     * Grabs the download URL for a file. This allows a provider to specify a download location on AWS for example.
     *
     * @param string $signature
     * @param string $location
     * @param array $args
     * @return string The download URL, or an empty string if the request failed.
     */
    protected function downloadUrl(string $signature, string $location, array $args = [])
    {
        /** @var ClientInterface $client */
        $client = $this->xpdo->services->get(ClientInterface::class);
        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $this->xpdo->services->get(RequestFactoryInterface::class);

        $uri = $location;
        $uri .= (strpos($uri, '?') > 0) ? '&' : '?';
        $uri .= http_build_query([
            'revolution_version' => $this->arg('revolution_version', $this->args($args)),
            'getUrl' => true,
        ]);
        $request = $requestFactory->createRequest('GET', $uri)
            ->withHeader('Accept', 'text/plain');

        try {
            $response = $client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not get download url for package {$signature} using location {$location}");
            return '';
        }

        return $response->getBody()->getContents();
    }

    protected function fromXML(SimpleXMLElement $xml, array &$array, $level = 0)
    {
        if ($xml->count()) {
            $node = [];
            foreach ($xml->children() as $child) {
                $this->fromXML($child, $node, $level + 1);
            }
            $level === 0 ? $array = $node : $array[$xml->getName()] = $node;
        } else {
            $array[$xml->getName()] = (string)$xml;
        }
    }

    protected function arg($key, array $args = [], $default = null)
    {
        $arg = $default;
        if (array_key_exists($key, $this->args($args))) {
            $arg = $args[$key];
        }

        return $arg;
    }

    protected function args(array $args = [])
    {
        if (!is_array($this->xpdo->version)) {
            $this->xpdo->getVersionData();
        }
        $baseArgs = [
            'api_key' => $this->get('api_key'),
            'username' => $this->get('username'),
            'uuid' => $this->xpdo->uuid,
            'database' => $this->xpdo->config['dbtype'],
            'revolution_version' => $this->xpdo->version['code_name'] . '-' . $this->xpdo->version['full_version'],
            'supports' => $this->xpdo->version['code_name'] . '-' . $this->xpdo->version['full_version'],
            'http_host' => $this->xpdo->getOption('http_host'),
            'php_version' => PHP_VERSION,
            'language' => $this->xpdo->getOption('manager_language', $_SESSION,
                $this->xpdo->getOption('cultureKey', null, 'en')),
        ];

        return array_merge($baseArgs, $args);
    }

    /**
     * Sends a REST request to the provider
     *
     * @param string $path   The path of the request
     * @param string $method The method of the request (GET/POST)
     * @param array  $params An array of parameters to send to the REST request
     *
     * @return ResponseInterface|bool
     */
    public function request(string $path, string $method = 'GET', array $params = [])
    {
        $client = $this->getClient();

        $uri = $this->get('service_url');
        $uri = rtrim(trim($uri), '/') . '/' . ltrim($path, '/');

        // Add default params (authentication, versions, etc)
        $params = $this->args($params);

        // Add params to the URI if this is a GET request
        if ($method === 'GET') {
            $uri .= (strpos($uri, '?') > 0) ? '&' : '?';
            $uri .= http_build_query($params);
        }

        // Create the PSR-7 request
        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Accept', 'application/xml');

        // Add params to the body if this is a POST request
        if ($method === 'POST') {
            $request = $request->withHeader('Content-Type','application/x-www-form-urlencoded');
            $request->getBody()->write(http_build_query($params));
        }

        $response = false;
        try {
            $response = $client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR, get_class($e) . " sending {$method} {$path} for provider {$this->get('name')}: {$e->getMessage()}", '', '', $e->getFile(), $e->getLine());
        }

        return $response;
    }

    /**
     * Get the client responsible for communicating with the provider.
     *
     * @return ClientInterface A REST client instance, or FALSE.
     */
    public function getClient(): ClientInterface
    {
        if ($this->client) {
            return $this->client;
        }
        $this->client = $this->xpdo->services->get(ClientInterface::class);
        $this->requestFactory = $this->xpdo->services->get(RequestFactoryInterface::class);
        return $this->client;
    }

    /**
     * Verifies the authenticity of the provider
     *
     * @return bool|string Boolean indicating success or failure; string if request failed
     */
    public function verify()
    {
        $response = $this->request('verify', 'GET');
        if (!$response) {
            return $this->xpdo->lexicon('provider_err_blank_response');
        }

        $body = simplexml_load_string($response->getBody()->getContents());
        return (bool)$body->verified;
    }

    /**
     * Overrides xPDOObject::save to set the created date.
     *
     * @param boolean $cacheFlag
     *
     * @return boolean True if successful
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew() && !$this->get('created')) {
            $this->set('created', date('Y-m-d H:i:s'));
        }
        $saved = parent:: save($cacheFlag);

        return $saved;
    }
}
