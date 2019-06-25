<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Rest;


use DOMDocument;
use SimpleXMLElement;

/**
 * Utility class for array-to-XML transformations.
 *
 * @package MODX\Revolution\Rest
 */
class modRestArrayToXML
{
    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array            $data
     * @param string           $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml          - should only be used recursively
     *
     * @return string XML
     */
    public static function toXML($data, $rootNodeName = 'ResultSet', &$xml = null)
    {

        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }
        if (is_null($xml)) {
            $xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><' . $rootNodeName . '></' . $rootNodeName . '>');
        }

        // loop through the data passed in.
        foreach ($data as $key => $value) {

            // no numeric keys in our xml please!
            if (is_numeric($key)) {
                $numeric = 1;
                $key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            // if there is another array found recrusively call this function
            if (is_array($value)) {
                $node = modRestArrayToXML::isAssoc($value) || $numeric ? $xml->addChild($key) : $xml;

                // recrusive call.
                if ($numeric) {
                    $key = 'anon';
                }
                modRestArrayToXML::toXml($value, $key, $node);
            } else {

                // add single node.
                $value = htmlentities($value);
                $xml->addChild($key, $value);
            }
        }

        // pass back as XML
        //return $xml->asXML();

        // if you want the XML to be formatted, use the below instead to return the XML
        $doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($xml->asXML());
        $doc->formatOutput = true;

        return $doc->saveXML();
    }


    /**
     * Convert an XML document to a multi dimensional array
     * Pass in an XML document (or SimpleXMLElement object) and this recursively loops through and builds a representative array
     *
     * @param string $xml - XML document - can optionally be a SimpleXMLElement object
     *
     * @return array ARRAY
     */
    public static function toArray($xml)
    {
        if (is_string($xml)) {
            $xml = new SimpleXMLElement($xml);
        }
        $children = $xml->children();
        if (!$children) {
            return (string)$xml;
        }
        $arr = [];
        foreach ($children as $key => $node) {
            $node = modRestArrayToXML::toArray($node);

            // support for 'anon' non-associative arrays
            if ($key == 'anon') {
                $key = count($arr);
            }

            // if the node is already set, put it into an array
            if (isset($arr[$key])) {
                if (!is_array($arr[$key]) || $arr[$key][0] == null) {
                    $arr[$key] = [$arr[$key]];
                }
                $arr[$key][] = $node;
            } else {
                $arr[$key] = $node;
            }
        }

        return $arr;
    }

    /**
     * Determine if a variable is an associative array
     *
     * @static
     *
     * @param mixed $array The variable to check
     *
     * @return boolean True if is an array
     */
    public static function isAssoc($array)
    {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
}
