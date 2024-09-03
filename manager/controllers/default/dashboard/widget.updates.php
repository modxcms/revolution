<?php

use MODX\Revolution\modX;
use MODX\Revolution\modDashboardWidgetInterface;
use MODX\Revolution\Processors\SoftwareUpdate\GetList as SoftwareUpdateGetList;
use MODX\Revolution\Smarty\modSmarty;
use xPDO\xPDO;

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetUpdates extends modDashboardWidgetInterface
{
    public $updatesCacheExpire = 3600;

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $updateCacheKey = 'mgr/providers/updates/modx-core';
        $updateCacheOptions = [
            xPDO::OPT_CACHE_KEY => $this->modx->cacheManager->getOption(
                'cache_packages_key',
                null,
                'packages'
            ),
            xPDO::OPT_CACHE_HANDLER => $this->modx->cacheManager->getOption(
                'cache_packages_handler',
                null,
                $this->modx->cacheManager->getOption(xPDO::OPT_CACHE_HANDLER)
            ),
        ];

        if (!$data = $this->modx->cacheManager->get($updateCacheKey, $updateCacheOptions)) {
            $data = [
                'modx' => [],
                'extras' => []
            ];

            $modxUpdatesProcessor = new SoftwareUpdateGetList($this->modx);
            $modxData = $modxUpdatesProcessor->run()->getObject();
            if (is_array($modxData) && array_key_exists('updateable', $modxData)) {
                $data['modx'] = $modxData;
            }

            $extrasUpdatesProcessor = new SoftwareUpdateGetList($this->modx, ['softwareType' => 'extras']);
            $extrasData = $extrasUpdatesProcessor->run()->getObject();
            if (is_array($extrasData) && array_key_exists('updateable', $extrasData)) {
                $data['extras'] = $extrasData;
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
