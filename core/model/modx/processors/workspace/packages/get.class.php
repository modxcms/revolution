<?php
/**
 * Gets a Transport Package.
 *
 * @param integer $id The ID of the chunk.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modPackageGetProcessor extends modObjectGetProcessor {
    public $classKey = 'transport.modTransportPackage';
    public $languageTopics = array('workspace');
    public $permission = 'packages';
    public $objectType = 'package';
    public $primaryKeyField = 'signature';
    public $checkViewPermission = false;
    /** @var string $dateFormat */
    public $dateFormat = '%b %d, %Y %I:%M %p';
    /** @var modTransportProvider $provider */
    public $provider;
    /** @var modTransportPackage $object */
    public $object;

    public function initialize() {
        $this->modx->addPackage('modx.transport',$this->modx->getOption('core_path').'model/');
        $this->dateFormat = $this->getProperty('dateFormat','%b %d, %Y %I:%M %p');
        return parent::initialize();
    }

    /**
     * Get the metadata for the object
     * @return void
     */
    public function getMetadata() {
        /** @var xPDOTransport $transport */
        $transport = $this->object->getTransport();
        if ($transport) {
            $this->object->set('readme',$transport->getAttribute('readme'));
            $this->object->set('license',$transport->getAttribute('license'));
            $this->object->set('changelog',$transport->getAttribute('changelog'));
        }
    }


    public function cleanup() {
        $this->getMetadata();
        $packageArray = $this->object->toArray();
        unset($packageArray['attributes']);
        unset($packageArray['metadata']);
        unset($packageArray['manifest']);
        $packageArray = $this->formatDates($packageArray);
        return $this->success('',$packageArray);
    }

    /**
     * Format the dates for readability
     * 
     * @param array $packageArray
     * @return array
     */
    public function formatDates(array $packageArray) {
        if ($this->object->get('updated') != '0000-00-00 00:00:00' && $this->object->get('updated') != null) {
            $packageArray['updated'] = strftime($this->dateFormat,strtotime($this->object->get('updated')));
        } else {
            $packageArray['updated'] = '';
        }
        $packageArray['created']= strftime($this->dateFormat,strtotime($this->object->get('created')));
        if ($this->object->get('installed') == null || $this->object->get('installed') == '0000-00-00 00:00:00') {
            $packageArray['installed'] = null;
        } else {
            $packageArray['installed'] = strftime($this->dateFormat,strtotime($this->object->get('installed')));
        }
        return $packageArray;
    }
}
return 'modPackageGetProcessor';