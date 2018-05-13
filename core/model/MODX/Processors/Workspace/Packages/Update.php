<?php

namespace MODX\Processors\Workspace\Packages;

use MODX\MODX;
use MODX\Processors\modProcessor;
use MODX\Transport\modTransportPackage;

/**
 * Gets a chunk.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class Update extends modProcessor
{
    /** @var modTransportPackage $package */
    public $package;


    public function checkPermissions()
    {
        return $this->modx->hasPermission('packages');
    }


    public function getLanguageTopics()
    {
        return ['workspace'];
    }


    public function initialize()
    {
        $this->setDefaultProperties([
            'signature' => '',
        ]);
        $this->modx->log(MODX::LOG_LEVEL_INFO, $this->modx->lexicon('package_uninstall_info_find', ['signature' => $this->getProperty('signature')]));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject('transport.modTransportPackage', $signature);
        if (empty($this->package)) {
            $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->modx->lexicon('package_err_nfs', [
                'signature' => $signature,
            ]);
        }

        return true;
    }


    public function process()
    {
        $this->package->fromArray($this->getProperties());

        if (!$this->package->save()) {
            return $this->failure($this->modx->lexicon('package_err_save'));
        }

        $this->logManagerAction();

        return $this->success('', $this->package);
    }


    public function logManagerAction()
    {
        $this->modx->logManagerAction('package_update', 'transport.modTransportPackage', $this->package->get('id'));
    }
}