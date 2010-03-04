<?php
/**
 * @package modx
 * @subpackage processors.system.import
 */
if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('import');

if (!function_exists('getFiles')) {
    function getFiles(& $modx, & $results, & $filesfound, $directory, $listing= array (), $count= 0) {
        $dummy= $count;
        if (@ $handle= opendir($directory)) {
            while ($file= readdir($handle)) {
                if ($file == '.' || $file == '..' || strpos($file, '.') === 0)
                    continue;
                else
                    if ($h= @ opendir($directory . $file . "/")) {
                        closedir($h);
                        $count= -1;
                        $listing["$file"]= getFiles($modx, $results, $filesfound, $directory . $file . "/", array (), $count +1);
                    } else {
                        $listing[$dummy]= $file;
                        $dummy= $dummy +1;
                        $filesfound++;
                    }
            }
        } else {
            $results .= $modx->lexicon('import_site_failed') . " Could not open '$directory'.<br />";
        }
        @ closedir($handle);
        return ($listing);
    }
}
if (!function_exists('importFiles')) {
    function importFiles(& $modx, & $results, $allowedfiles, $parent, $filepath, $files, $context= 'web') {
        if (!is_array($files))
            return;
        if ($parent > 0) {
            if ($parentResource= $modx->getObject('modResource', $parent)) {
                $parentResource->set('isfolder', true);
                $parentResource->save();
            } else {
                $results .= "Could not get parent ({$parent}) resource to set isfolder attribute after import.";
                return;
            }
        }
        foreach ($files as $id => $value) {
            if (is_array($value)) {
                /* create folder */
                $resource= $modx->newObject('modDocument');
                $resource->set('context_key', $context);
                $resource->set('content_type', 1);
                $resource->set('pagetitle', $id);
                $resource->set('parent', $parent);
                $resource->set('isfolder', true);

                $alias= getResourceAlias($modx, $resource, $results, $id, $parent, $context);

                $resource->set('alias', $alias);
                $resource->set('published', false);
                $resource->set('template', $modx->getOption('default_template'));
                $resource->set('menuindex', $modx->getCount('modResource', array (
                    'parent' => $parent
                )));
                $resource->set('searchable', $modx->getOption('search_default'));
                $resource->set('cacheable', $modx->getOption('cache_default'));

                $results .= sprintf($modx->lexicon('import_site_importing_document'), $alias);

                if (!$resource->save()) {
                    $results .= "Could not import resource from {$filepath}/{$filename}: <br />" . nl2br(print_r($modx->errorInfo(), true));
                } else {
                    $results .= $modx->lexicon('import_site_success') . "<br />";
                    importFiles($modx, $results, $allowedfiles, $resource->get('id'), $filepath . "/{$id}/", $value, $context);
                }
            } else {
                /* create resource */
                $filename= $value;
                $fparts= explode(".", $value);
                $value= $fparts[0];
                $ext= (count($fparts) > 1) ? $fparts[count($fparts) - 1] : "";
                $results .= sprintf($modx->lexicon('import_site_importing_document'), $filename);

                if (!in_array($ext, $allowedfiles))
                    $results .= $modx->lexicon('import_site_skip') . "<br />";
                else {
                    $file= getFileContent($modx, $results, "$filepath/$filename");
                    if (preg_match("/<title>(.*)<\/title>/i", $file, $matches)) {
                        $pagetitle= $matches[1];
                    } else
                        $pagetitle= $value;
                    if (!$pagetitle)
                        $pagetitle= $value;
                    if (preg_match("/<body[^>]*>(.*)[^<]+<\/body>/is", $file, $matches)) {
                        $content= $matches[1];
                    } else
                        $content= $file;

                    $resource= $modx->newObject('modDocument');
                    $resource->set('context_key', $context);
                    $resource->set('content_type', 1);
                    $resource->set('pagetitle', $pagetitle);
                    $resource->set('parent', $parent);
                    $resource->set('isfolder', false);

                    $alias= getResourceAlias($modx, $resource, $results, $value, $parent, $context);

                    $resource->set('alias', $alias);
                    $resource->set('published', false);
                    $resource->set('template', $modx->getOption('default_template'));
                    $resource->set('menuindex', $modx->getCount('modResource', array ('parent' => $parent)));
                    $resource->set('searchable', $modx->getOption('search_default'));
                    $resource->set('cacheable', $modx->getOption('cache_default'));
                    $resource->set('content', $content);

                    if (!$resource->save()) {
                        $results .= $modx->lexicon('import_site_failed') . "Could not import resource from {$filepath}/{$filename}: <br />" . nl2br(print_r($modx->errorInfo(), true));
                    } else {
                        $results .= $modx->lexicon('import_site_success') . "<br />";
                    }
                }
            }
        }
    }
}
if (!function_exists('getFileContent')) {
    function getFileContent(& $modx, & $results, $file) {
        /* get the file */
        if (@ $handle= fopen($file, "r")) {
            $buffer= "";
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $results .= $modx->lexicon('import_site_failed') . " Could not retrieve document '$file'.<br />";
        }
        return $buffer;
    }
}
if (!function_exists('aliasCheck')) {
    function getResourceAlias(& $modx, & $resource, & $results, $alias, $parent, $context= 'web') {
        /* auto assign alias */
        if ($alias == '' && $modx->getOption('automatic_alias')) {
            $alias= strtolower(trim($resource->cleanAlias($resource->get('pagetitle'))));
        } else {
            $alias= $resource->cleanAlias($alias);
        }
        $resourceContext= $modx->getObject('modContext', $context);
        $resourceContext->prepare(true);

        $isHtml= true;
        $extension= '';
        $containerSuffix= $modx->getOption('container_suffix',null,'');
        if ($contentType= $modx->getObject('modContentType', 1)) {
            $extension= $contentType->getExtension();
            $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
        }
        if ($resource->get('isfolder') && $isHtml && !empty ($containerSuffix)) {
            $extension= $containerSuffix;
        }
        $aliasPath= '';
        if ($modx->getOption('use_alias_path')) {
            $pathParentId= intval($parent);
            $parentResources= array ();
            $currResource= $modx->getObject('modResource', $pathParentId);
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
            $results .= $modx->lexicon('import_duplicate_alias_found',array(
                'id' => $duplicateId,
                'alias' => $fullAlias
            ));
            $alias= $origAlias . '-' . substr(uniqid(''), -3);
            $fullAlias= $aliasPath . $alias . $extension;
        }
        return $alias;
    }
}

$importstart= $modx->getMicroTime();

$results= '';
$allowedfiles= array (
    'html',
    'htm',
    'xml'
);

$context= 'web';
$parent= 0;
if (isset ($scriptProperties['import_context'])) {
    $context= $scriptProperties['import_context'];
}
if (isset ($scriptProperties['import_parent'])) {
    $parent= intval($scriptProperties['import_parent']);
}
$element= isset ($scriptProperties['content_element']) ? $scriptProperties['content_element'] : 'body';
$filepath= isset ($scriptProperties['filepath']) ? $scriptProperties['filepath'] : $modx->getOption('core_path') . 'import/';
$filesfound= 0;

$files= getFiles($modx, $results, $filesfound, $filepath);

/* no. of files to import */
$results .= sprintf($modx->lexicon('import_files_found'), $filesfound);

/* import files */
if (count($files) > 0) {
    importFiles($modx, $results, $allowedfiles, $parent, $filepath, $files, $context);
}

$importend= $modx->getMicroTime();
$totaltime= ($importend - $importstart);
$results .= sprintf("<p />" . $modx->lexicon('import_site_time'), round($totaltime, 3));

return $modx->error->success($results);