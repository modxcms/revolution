<?php
use xPDO\Transport\xPDOTransport;

/**
 * Gets an attribute of a package
 *
 * @param string $signature The signature of the package
 * @param string $attr The attribute to select
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
class modPackageGetAttributeProcessor extends modProcessor {
    /** @var modTransportPackage $package */
    public $package;
    /** @var xPDOTransport $transport */
    public $transport;

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }
    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $signature = $this->getProperty('signature');
        if (empty($signature)) return $this->modx->lexicon('package_err_ns');
        $this->package = $this->modx->getObject('transport.modTransportPackage',$signature);
        if (empty($this->package)) return $this->modx->lexicon('package_err_nf');

        $this->transport = $this->package->getTransport();
        if (!$this->transport) {
            return $this->modx->lexicon('package_err_nf');
        }
        return true;
    }

    public function process() {
        $attributes = array();
        $attributesToGet = explode(',',$this->getProperty('attributes',''));
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        foreach ($attributesToGet as $attribute) {
            $data = $this->transport->getAttribute($attribute);
            $attributes[$attribute] = in_array($attribute, ['changelog', 'license', 'readme'])
                ? $Parsedown->text(htmlentities($data))
                : $data;

            /* if setup options, include setup file */
            if ($attribute == 'setup-options') {
                @ob_start();
                $options = $this->package->toArray();
                $options[xPDOTransport::PACKAGE_ACTION] = $this->package->previousVersionInstalled()
                    ? xPDOTransport::ACTION_UPGRADE
                    : xPDOTransport::ACTION_INSTALL;
                $attributeFile = $this->modx->getOption('core_path').'packages/'.$this->package->signature.'/'.$attribute.'.php';
                if (file_exists($attributeFile) && $attribute != '') {
                    $modx =& $this->modx;
                    $attributes['setup-options'] = include $attributeFile;
                }
                @ob_end_clean();
            }
        }

        return $this->success('',$attributes);
    }
}
return 'modPackageGetAttributeProcessor';
