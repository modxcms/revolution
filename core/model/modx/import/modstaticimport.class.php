<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/* Make sure the modImport class is included. */
require_once MODX_CORE_PATH . 'model/modx/import/modimport.class.php';
/**
 * Provides common functions for importing content from static files.
 *
 * @package modx
 * @subpackage import
 */
class modStaticImport extends modImport {

    /**
     * Import files into MODX
     *
     * @param array $allowedfiles An array of allowed file types
     * @param integer $parent The parent Resource to pull into
     * @param string $filepath The path to the files to import
     * @param array $files An array of imported files
     * @param string $context The context to import into
     * @param string $class The class of Resource to import as
     * @param string $basefilepath The base file path for the import
     * @param array $elements Associations of resource fields and selectors
     */
    public function importFiles($allowedfiles, $parent, $filepath, $files, $context= 'web', $class= 'modStaticResource', $basefilepath= '', $elements = array()) {
        if (!is_array($files))
            return;
        if ($parent > 0) {
            if ($parentResource= $this->modx->getObject('modResource', $parent)) {
                $parentResource->set('isfolder', true);
                $parentResource->save();
            } else {
                $this->log("Could not get parent ({$parent}) resource to set isfolder attribute after import.");
                return;
            }
        }
        $menuindex= 0;
        $maxIdxQuery= $this->modx->newQuery('modResource', array ('parent' => $parent));
        $maxIdxQuery->select('MAX(menuindex)');
        if ($maxIdxQuery->prepare() && $maxIdxQuery->stmt->execute()) {
            $menuindex = $maxIdxQuery->stmt->fetch(PDO::FETCH_COLUMN);
            $maxIdxQuery->stmt->closeCursor();
        }
        $menuindex= intval($menuindex);
        if ($filepath[strlen($filepath) - 1] !== '/') {
            $filepath.= '/';
        }
        if ($basefilepath[strlen($basefilepath) - 1] !== '/') {
            $basefilepath.= '/';
        }
        foreach ($files as $id => $value) {
            $pagetitle= '';
            $content= '';
            $file= '';
            $alias= '';
            $filetype= null;
            if (is_array($value)) {
                // create folder
                /** @var modDocument $resource */
                $resource= $this->modx->newObject('modDocument');
                $resource->set('context_key', $context);
                $resource->set('content_type', 1);
                $resource->set('pagetitle', $id);
                $resource->set('parent', $parent);
                $resource->set('isfolder', true);

                $alias= $this->getResourceAlias($resource, $id, $parent, $context);

                $resource->set('alias', $alias);
                $resource->set('published', false);
                $resource->set('template', $this->modx->getOption('default_template'));
                $resource->set('menuindex', $menuindex++);
                $resource->set('searchable', $this->modx->getOption('search_default'));
                $resource->set('cacheable', $this->modx->getOption('cache_default'));

                $this->log(sprintf($this->modx->lexicon('import_site_importing_document'), $alias));
                if (!$resource->save()) {
                    $this->log("Could not import resource from {$filepath}: <br /><pre>" . print_r($resource->toArray('', true), true) . '</pre>');
                } else {
                    $this->log($this->modx->lexicon('import_site_success'));
                    $this->importFiles($allowedfiles, $resource->get('id'), $filepath . $id, $value, $context, $class, $basefilepath);
                }
            } else {
                // create resource
                $filename= $value;
                $fparts= explode(".", $value);
                $value= $fparts[0];
                $ext= (count($fparts) > 1) ? $fparts[count($fparts) - 1] : "";
                $this->log(sprintf($this->modx->lexicon('import_site_importing_document'), $filename));

                if (!empty($allowedfiles) && !in_array($ext, $allowedfiles))
                    $this->log($this->modx->lexicon('import_site_skip'));
                else {
                    /** @var modContentType $filetype */
                    $filetype= $this->getFileContentType($ext);
                    if ($class == 'modStaticResource') {
                        $file= $filepath.$filename;
                    } else {
                        $file= $this->getFileContent("{$filepath}{$filename}");
                        if ($filetype->get('mime_type') == 'text/html') {
                            if (preg_match("/<title>\s*(.*?)\s*<\/title>/is", $file, $matches)) {
                                $pagetitle= $matches[1];
                            } else
                                $pagetitle= $value;
                            if (!$pagetitle)
                                $pagetitle= $value;
                            if (preg_match("/<body[^>]*>(.*)[^<]+<\/body>/is", $file, $matches)) {
                                $content= $matches[1];
                            }
                        }
                    }
                    if (empty($pagetitle)) {
                        $pagetitle= $value;
                    }
                    if (empty($content)) {
                        $content = str_replace($basefilepath, '', $file);
                    }
                    /** @var modResource $resource */
                    $resource= $this->modx->newObject($class);
                    // default fields
                    $resource->set('context_key', $context);
                    $resource->set('content_type', $filetype->get('id'));
                    $resource->set('pagetitle', $pagetitle);
                    $resource->set('parent', $parent);
                    $resource->set('isfolder', false);
                    $resource->set('published', false);
                    $resource->set('template', $this->modx->getOption('default_template'));

                    $resource->set('menuindex', $menuindex++);
                    $resource->set('searchable', $this->modx->getOption('search_default'));
                    $resource->set('cacheable', $this->modx->getOption('cache_default'));

                    $resource->setContent($content);
                    $tvs = $this->parseElements($resource, $file, $elements);

                    if ($resource->get('alias') == '') {
                        $alias= $this->getResourceAlias($resource, $value, $parent, $context);
                        $resource->set('alias', $alias);
                    }

                    if (!$resource->save()) {
                        $this->log($this->modx->lexicon('import_site_failed') . "Could not import resource {$file} from {$filepath}{$filename}: <br /><pre>" . print_r($resource->toArray('', true), true) . '</pre>');
                    } else {
                        foreach ($tvs as $tvKey => $tvValue) {
                            $resource->setTVValue($tvKey, $tvValue);
                        }
                        $this->log($this->modx->lexicon('import_site_success'));
                    }
                }
            }
            unset($resource, $content, $file, $pagetitle, $filetype, $alias, $tvs);
        }
    }

    /**
     * Calculate a resource alias from the imported file
     *
     * @param modResource $resource A reference to the new modResource object
     * @param string $alias A suggested alias
     * @param integer $parent The parent ID of the Resource
     * @param string $context The context of the Resource
     * @return string The formatted alias
     */
    public function getResourceAlias(& $resource, $alias, $parent, $context= 'web') {
        // auto assign alias
        if ($alias == '' && $this->modx->getOption('automatic_alias')) {
            $alias= strtolower(trim($resource->cleanAlias($resource->get('pagetitle'))));
        } else {
            $alias= $resource->cleanAlias($alias);
        }
        $resourceContext= $this->modx->getObject('modContext', $context);
        $resourceContext->prepare(true);

        $isHtml= true;
        $extension= '';
        $containerSuffix= $this->modx->getOption('container_suffix',null,'');
        /** @var modContentType $contentType */
        if ($contentType= $resource->getOne('ContentType')) {
            $extension= $contentType->getExtension();
            $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
        }
        if ($resource->get('isfolder') && $isHtml && !empty ($containerSuffix)) {
            $extension= $containerSuffix;
        }
        $aliasPath= '';
        if ($this->modx->getOption('use_alias_path')) {
            $pathParentId= intval($parent);
            $parentResources= array ();
            $currResource= $this->modx->getObject('modResource', $pathParentId);
            while ($currResource) {
                $parentAlias= $currResource->get('alias');
                if (empty ($parentAlias))
                    $parentAlias= "{$pathParentId}";
                $parentResources[]= "{$parentAlias}";
                $pathParentId= $currResource->get('parent');
                $currResource= $currResource->getOne('Parent');
            }
            $aliasPath= !empty ($parentResources) ? implode('/', array_reverse($parentResources)) : '';
        }
        $fullAlias= $aliasPath . $alias . $extension;

        $iterations= 3;
        $origAlias= $alias;
        while ($duplicateId = $this->modx->findResource($fullAlias) && $iterations > 0) {
            $iterations--;
            $this->log($this->modx->lexicon('import_duplicate_alias_found',array(
                'id' => $duplicateId,
                'alias' => $fullAlias,
            )));
            $alias= $origAlias . '-' . substr(uniqid(''), -3);
            $fullAlias= $aliasPath . $alias . $extension;
        }
        return $alias;
    }

    /**
     * Convert $selector into an XPath string.
     * @param string $selector
     * @return string
     */
    public function toXPath($selector) {
        // remove spaces around operators
        $selector = preg_replace('/\s*>\s*/', '>', $selector);
        $selector = preg_replace('/\s*~\s*/', '~', $selector);
        $selector = preg_replace('/\s*\+\s*/', '+', $selector);
        $selector = preg_replace('/\s*,\s*/', ',', $selector);
        $selectors = preg_split("/\s+(?![^\[]+\])/", $selector);
        foreach ($selectors as &$selector) {
            // ,
            $selector = preg_replace('/\s*,\s*/', '|descendant-or-self::', $selector);
            // :button, :submit, etc
            $selector = preg_replace('/:(button|submit|file|checkbox|radio|image|reset|text|password)/', 'input[@type="\1"]', $selector);
            // [id]
            $selector = preg_replace('/\[(\w+)\]/', '*[@\1]', $selector);
            // foo[id=foo]
            $selector = preg_replace('/\[(\w+)=[\'"]?(.*?)[\'"]?\]/', '[@\1="\2"]', $selector);
            // [id=foo]
            $selector = preg_replace('/^\[/', '*[', $selector);
            // div#foo
            $selector = preg_replace('/([\w\-]+)\#([\w\-]+)/', '\1[@id="\2"]', $selector);
            // #foo
            $selector = preg_replace('/\#([\w\-]+)/', '*[@id="\1"]', $selector);
            // div.foo
            $selector = preg_replace('/([\w\-]+)\.([\w\-]+)/', '\1[contains(concat(" ",@class," ")," \2 ")]', $selector);
            // .foo
            $selector = preg_replace('/\.([\w\-]+)/', '*[contains(concat(" ",@class," ")," \1 ")]', $selector);
            // div:first-child
            $selector = preg_replace('/([\w\-]+):first-child/', '*/\1[position()=1]', $selector);
            // div:last-child
            $selector = preg_replace('/([\w\-]+):last-child/', '*/\1[position()=last()]', $selector);
            // :first-child
            $selector = str_replace(':first-child', '*/*[position()=1]', $selector);
            // :last-child
            $selector = str_replace(':last-child', '*/*[position()=last()]', $selector);
            // :nth-last-child
            $selector = preg_replace('/:nth-last-child\((\d+)\)/', '[position()=(last() - (\1 - 1))]', $selector);
            // div:nth-child
            $selector = preg_replace('/([\w\-]+):nth-child\((\d+)\)/', '*/*[position()=\2 and self::\1]', $selector);
            // :nth-child
            $selector = preg_replace('/:nth-child\((\d+)\)/', '*/*[position()=\1]', $selector);
            // :contains(Foo)
            $selector = preg_replace('/([\w\-]+):contains\((.*?)\)/', '\1[contains(string(.),"\2")]', $selector);
            // >
            $selector = preg_replace('/\s*>\s*/', '/', $selector);
            // ~
            $selector = preg_replace('/\s*~\s*/', '/following-sibling::', $selector);
            // +
            $selector = preg_replace('/\s*\+\s*([\w\-]+)/', '/following-sibling::\1[position()=1]', $selector);
            $selector = str_replace(']*', ']', $selector);
            $selector = str_replace(']/*', ']', $selector);
        }
        // ' '
        $selector = 'descendant-or-self::' . implode('/descendant::', $selectors);
        return $selector;
    }

    /**
     * Return innerHTML of an element
     * @param DOMNode $element
     * @return string
     */
    public function DOMinnerHTML(DOMNode $element) {
        $innerHTML = "";
        $children  = $element->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    /**
     * Parse values of resource fields and TVs. Set resource fields and return TVs.
     * @param modResource $resource
     * @param $file
     * @param array $elements
     * @return array
     */
    public function parseElements(modResource &$resource, $file, $elements = array()) {
        if (empty($elements)) {
            return false;
        }

        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput       = true;
        $doc->loadHTML($file);
        $xpath = new DOMXPath($doc);
        $body = $doc->documentElement;

        $tvs = array();
        foreach ($elements as $field => $selector) {
            if ($resource->getField($field, true)) {
                $fieldValue = $this->getFieldValue($selector, $body, $xpath);
                if ($fieldValue !== false) {
                    $resource->set($field, $fieldValue);
                }

            } else {
                $tv = $this->modx->getObject('modTemplateVar', array('name' => $field));
                if ($tv) {
                    $tvs[$field] = $this->getFieldValue($selector, $body, $xpath);
                }
            }
        }

        return $tvs;
    }

    /**
     * Return field value by selector
     * @param $selector
     * @param $parentNode
     * @param DOMXPath $xpath
     * @return bool|string
     */
    protected function getFieldValue($selector, $parentNode, DOMXPath $xpath) {
        if (strpos($selector, '$') === 0) {
            $xpathQuery = $this->toXPath(substr($selector,1));
            /** @var DOMNodeList $entries */
            $entries = $xpath->query($xpathQuery, $parentNode);
            $fieldValue = ($entries->length > 0) ? $this->DOMinnerHTML($entries->item(0)) : false;
        } else {
            $fieldValue = $selector;
        }

        return $fieldValue;
    }
}
