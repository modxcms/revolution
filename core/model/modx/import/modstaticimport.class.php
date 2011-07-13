<?php
/**
 * @package modx
 * @subpackage import
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
     * @return
     */
    public function importFiles($allowedfiles, $parent, $filepath, $files, $context= 'web', $class= 'modStaticResource', $basefilepath= '') {
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
                    $this->log("Could not import resource from {$filepath}{$filename}: <br /><pre>" . print_r($resource->toArray('', true), true) . '</pre>');
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
                    $filetype= $this->getFileContentType($ext);
                    if ($class == 'modStaticResource') {
//                        $file= "{$filepath}{$filename}";
                        $file= $filepath.$filename;
                    } else {
                        $file= $this->getFileContent("{$filepath}{$filename}");
                        if ($filetype->get('mime_type') == 'text/html') {
                            if (preg_match("/<title>(.*)<\/title>/i", $file, $matches)) {
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
                    $resource= $this->modx->newObject($class);
                    $resource->set('context_key', $context);
                    $resource->set('content_type', $filetype->get('id'));
                    $resource->set('pagetitle', $pagetitle);
                    $resource->set('parent', $parent);
                    $resource->set('isfolder', false);

                    $alias= $this->getResourceAlias($resource, $value, $parent, $context);

                    $resource->set('alias', $alias);
                    $resource->set('published', false);
                    $resource->set('template', $this->modx->getOption('default_template'));
                    $resource->set('menuindex', $menuindex++);
                    $resource->set('searchable', $this->modx->getOption('search_default'));
                    $resource->set('cacheable', $this->modx->getOption('cache_default'));
                    $resource->_fields['content']= $content;

                    if (!$resource->save()) {
                        $this->log($this->modx->lexicon('import_site_failed') . "Could not import resource {$file} from {$filepath}{$filename}: <br /><pre>" . print_r($resource->toArray('', true), true) . '</pre>');
                    } else {
                        $this->log($this->modx->lexicon('import_site_success'));
                    }
                }
            }
            unset($resource, $content, $file, $pagetitle, $filetype, $alias);
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
        while (isset ($resourceContext->aliasMap[$fullAlias]) && $iterations > 0) {
            $iterations--;
            $duplicateId= $resourceContext->aliasMap[$fullAlias];
            $this->log($this->modx->lexicon('import_duplicate_alias_found',array(
                'id' => $duplicateId,
                'alias' => $fullAlias,
            )));
            $alias= $origAlias . '-' . substr(uniqid(''), -3);
            $fullAlias= $aliasPath . $alias . $extension;
        }
        return $alias;
    }
}