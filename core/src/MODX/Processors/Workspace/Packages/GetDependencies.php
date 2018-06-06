<?php

namespace MODX\Processors\Workspace\Packages;

use MODX\Processors\modObjectGetListProcessor;
use MODX\Transport\modTransportPackage;
use xPDO\Transport\xPDOTransport;

/**
 * Gets a list of packages
 *
 * @param integer $workspace (optional) The workspace to filter by. Defaults to
 * 1.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class GetDependencies extends modObjectGetListProcessor
{
    public $classKey = 'transport.modTransportPackage';
    public $checkListPermission = false;
    public $permission = 'packages';
    public $languageTopics = ['workspace'];

    /** @var int $updatesCacheExpire */
    public $updatesCacheExpire = 300;
    /** @var string $productVersion */
    public $productVersion = '';
    /** @var array $providerCache */
    public $providerCache = [];
    /** @var modTransportPackage $package */
    public $package;
    /** @var xPDOTransport $transport */
    public $transport;


    public function initialize()
    {
        $signature = $this->getProperty('signature');
        if (empty($signature)) return $this->modx->lexicon('package_err_ns');

        $this->package = $this->modx->getObject('transport.modTransportPackage', $signature);
        if (empty($this->package)) return $this->modx->lexicon('package_err_nf');

        $this->transport = $this->package->getTransport();
        if (!$this->transport) {
            return $this->modx->lexicon('package_err_nf');
        }

        return true;
    }


    public function process()
    {
        $requires = $this->transport->getAttribute('requires');

        $dep = $this->package->checkDependencies($requires);
        $download = $this->package->checkDownloadedDependencies($dep);

        $returnArray = [];

        foreach ($requires as $pkg => $constraints) {
            if (isset($dep[$pkg])) {
                $installed = false;
            } else {
                $installed = true;
            }

            if (isset($download[$pkg])) {
                $downloaded = true;
                $signature = $download[$pkg];
            } else {
                $downloaded = false;
                $signature = '';
            }

            //@TODO: Get downlaoded property properly from somewhere and add signature property that will be needed for running installation
            $returnArray[] = [
                'name' => $pkg,
                'parentSignature' => $this->getProperty('signature'),
                'constraints' => $constraints,
                'installed' => $installed,
                'downloaded' => $downloaded,
                'signature' => $signature,
            ];

        }

        return $this->outputArray($returnArray, count($returnArray));
    }


}
