<?php

use MODX\Revolution\modX;
use MODX\Revolution\modDashboardWidgetInterface;
use MODX\Revolution\Processors\Workspace\Packages\GetList;
use MODX\Revolution\Smarty\modSmarty;
use MODX\Revolution\Transport\modTransportPackage;
use xPDO\xPDO;

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetUpdates extends modDashboardWidgetInterface
{
    /** @var modX $modx */
    public $modx;
    public $latest_url = 'https://raw.githubusercontent.com/modxcms/revolution/3.x/_build/build.xml';
    public $download_url = 'https://modx.com/download/latest';
    public $updatesCacheExpire = 3600;


    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $processor = new GetList($this->modx);

        $updateCacheKey = 'mgr/providers/updates/modx-core';
        $updateCacheOptions = [
            xPDO::OPT_CACHE_KEY => $this->modx->cacheManager->getOption('cache_packages_key', null, 'packages'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->cacheManager->getOption('cache_packages_handler', null, $this->modx->cacheManager->getOption(xPDO::OPT_CACHE_HANDLER)),
        ];

        if (!$data = $this->modx->cacheManager->get($updateCacheKey, $updateCacheOptions)) {
            $data = [
                'modx' => [
                    'updateable' => 0,
                ],
                'packages' => [
                    'names' => [],
                    'updateable' => 0,
                ],
            ];

            if (function_exists('curl_init')) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $this->latest_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                $content = curl_exec($curl);
                curl_close($curl);
                if ($content) {
                    $xml = new SimpleXMLElement($content);
                    foreach ($xml->property as $key => $value) {
                        $name = (string)$value->attributes()->name;
                        if ($name == 'modx.core.version') {
                            $data['modx']['version'] = (string)$value->attributes()->value;
                        } elseif ($name == 'modx.core.release') {
                            $data['modx']['release'] = (string)$value->attributes()->value;
                        }
                    }
                }
                if (!empty($data['modx']['version']) && !empty($data['modx']['release'])) {
                    if ($version = $this->modx->getVersionData()) {
                        $data['modx']['full_version'] = $data['modx']['version'] . '-' . $data['modx']['release'];
                        $data['modx']['updateable'] = (int)version_compare($version['full_version'], $data['modx']['full_version'], '<');
                    }
                }
            }

            $packages = $this->modx->call(modTransportPackage::class, 'listPackages', [$this->modx, 1, 11, 0]);
            /** @var modTransportPackage $package */
            foreach ($packages['collection'] as $package) {
                $tmp = [];
                $tmp = $processor->checkForUpdates($package, $tmp);
                if (!empty($tmp['updateable'])) {
                    $data['packages']['names'][] = $package->get('package_name');
                    $data['packages']['updateable']++;
                }
            }

            $this->modx->cacheManager->set($updateCacheKey, $data, $this->updatesCacheExpire, $updateCacheOptions);
        }

        $this->modx->getService('smarty', modSmarty::class);
        foreach ($data as $key => $value) {
            $this->modx->smarty->assign($key, $value);
        }

        return $this->modx->smarty->fetch('dashboard/updates.tpl');
    }

}

return 'modDashboardWidgetUpdates';
