<?php

namespace MODX\Transport;

/**
 * @package modx
 * @subpackage transport
 */
use MODX\MODX;
use xPDO\Transport\xPDOTransport;
use xPDO\xPDO;

/**
 * Abstracts the package building process for XML builds
 *
 * @property resource xmlParser
 * @property string openVehicle
 * @property array build
 * @property string buildXML
 * @package modx
 * @subpackage transport
 */
class modXMLPackageBuilder extends modPackageBuilder
{
    /**
     * Build the package from a specific XML file
     *
     * @param string $fileName The XML file to parse
     *
     * @return bool True if successful
     */
    public function build($fileName)
    {
        if (!$this->parseXML($fileName)) {
            $this->modx->log(MODX::LOG_LEVEL_ERROR, 'XML parsing failed for ' . $fileName);

            return false;
        }

        /* create a new package */
        $this->createPackage($this->build['name'], $this->build['version'], $this->build['release']);
        $this->registerNamespace($this->build['namespace'], $this->build['autoincludes']);

        /* set up some attributes that define install behavior */
        $attributes = [
            xPDOTransport::UNIQUE_KEY => 'name',
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::RESOLVE_FILES => true,
            xPDOTransport::RESOLVE_PHP => true,
            xPDOTransport::PRESERVE_KEYS => false,
        ];

        foreach ($this->build['vehicles'] as $signature => $vehicle) {
            $c = $this->modx->getObject($vehicle['class_key'], $vehicle['object']);
            if ($c == null) continue;

            if (!isset($vehicle['attributes'])) $vehicle['attributes'] = [];
            $attr = array_merge($attributes, $vehicle['attributes']);

            $v = $this->createVehicle($c, $attr);
            if (isset($vehicle['resolvers']) && !empty($vehicle['resolvers'])) {
                foreach ($vehicle['resolvers'] as $resolver) {
                    $v->resolve($resolver['type'], $resolver);
                }
            }
            $this->putVehicle($v);
        }

        /* zip up the package */
        $this->pack();

        return true;
    }


    /**
     * Parse the XML file
     *
     * @param string $fileName
     *
     * @return array|bool
     */
    public function parseXML($fileName)
    {
        $this->build = [
            'autoincludes' => [],
            'vehicles' => [],
        ];

        if (!is_file($fileName)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Could not find specified XML build file {$fileName}");

            return false;
        } else {
            $fileContent = @ file($fileName);
            $this->buildXML = implode('', $fileContent);
        }
        /* Create the parser and set handlers. */
        $this->xmlParser = xml_parser_create('UTF-8');

        xml_set_object($this->xmlParser, $this);
        xml_parser_set_option($this->xmlParser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($this->xmlParser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
        xml_set_element_handler($this->xmlParser, '_handleOpenElement', '_handleCloseElement');
        xml_set_character_data_handler($this->xmlParser, "_handleCData");

        /* Parse it. */
        if (!xml_parse($this->xmlParser, $this->buildXML)) {
            $ln = xml_get_current_line_number($this->xmlParser);
            $msg = xml_error_string(xml_get_error_code($this->xmlParser));
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Error parsing XML schema on line $ln: $msg");

            return false;
        }

        /* Free up the parser and clear memory */
        xml_parser_free($this->xmlParser);
        unset ($this->xmlParser);


        return $this->build;
    }


    /**
     * @param resource $parser A reference to the xml parser instance
     * @param string|array $element The opening XML element
     * @param array $attributes An array of attributes on the element
     *
     * @return void
     */
    protected function _handleOpenElement(& $parser, & $element, & $attributes)
    {
        $element = strtolower($element);
        switch ($element) {
            case 'component':
                foreach ($attributes as $attrName => $attrValue) {
                    $this->build[$attrName] = $attrValue;
                }
                break;
            case 'autoinclude':
                foreach ($attributes as $attrName => $attrValue) {
                    if ($attrName == 'class') {
                        $this->build['autoincludes'][$attrValue] = $attrValue;
                    }
                }
                break;
            case 'vehicle':
                $vehicle = [
                    'resolvers' => [],
                    'attributes' => [],
                ];
                foreach ($attributes as $attrName => $attrValue) {
                    switch ($attrName) {
                        case 'class':
                            $vehicle['class_key'] = $attrValue;
                            break;
                        case 'id':
                            $vehicle['object'] = $attrValue;
                            break;
                        default:
                            $vehicle['attributes'][$attrName] = $attrValue;
                            break;
                    }
                }
                $this->build['vehicles'][$vehicle['class_key'] . '-' . $vehicle['object']] = $vehicle;
                $this->openVehicle = $vehicle['class_key'] . '-' . $vehicle['object'];
                break;
            case 'resolver':
                if ($this->openVehicle == '') break;

                $resolver = [];
                foreach ($attributes as $attrName => $attrValue) {
                    $resolver[$attrName] = $attrValue;
                }
                if (isset($resolver['prependbase']) && $resolver['prependbase'] == true) {
                    $resolver['source'] = $this->modx->getOption('base_path') . $resolver['source'];
                }
                $this->build['vehicles'][$this->openVehicle]['resolvers'][] = $resolver;
                break;
        }
    }


    /**
     * Handles a closed XML tag
     *
     * @param resource $parser A reference to the xml parser instance
     * @param string|array $element The closing element
     *
     * @return void
     */
    protected function _handleCloseElement(& $parser, & $element)
    {
        switch ($element) {
            case 'vehicle':
                $this->openVehicle = '';
                break;
        }
    }


    /**
     * @param resource $parser A reference to the xml parser instance
     * @param resource $data The data being wrapped in CDATA tags
     *
     * @return void
     */
    protected function _handleCData(& $parser, & $data)
    {
    }
}
