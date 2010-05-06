<?php
/**
 * Regenerates strings from the base lexicon files, resetting any customizations.
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

$language = 'en';
$namespace = 'core';
$corePath = $modx->getOption('core_path');

$modx->lexicon->clearCache();
$c = $modx->newQuery('modLexiconEntry');
$c->where(array(
    'namespace' => $namespace,
    'language' => $language,
));
$entries = $modx->getCollection('modLexiconEntry',$c);

$currentTopic = '';
$lex = array();
$i = 0;
foreach ($entries as $entry) {
    if ($currentTopic != $entry->get('topic')) {
        $currentTopic = $entry->get('topic');

        $topicPath = str_replace('//','/',$corePath.'/lexicon/'.$language.'/'.$currentTopic.'.inc.php');
        $lex = array();
        $_lang = array();
        if (file_exists($topicPath)) {
            include $topicPath;
            $lex = $_lang;
        }
    }

    if (!empty($lex[$entry->get('name')])) {
        $i++;
        $entry->remove();
    }
}

$modx->log(modX::LOG_LEVEL_WARN,'Successfully reloaded '.$i.' strings.');
sleep(1);
$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
return $modx->error->success(intval($i));