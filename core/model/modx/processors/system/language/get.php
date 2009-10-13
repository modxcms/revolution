<?php
/**
 * Gets a language
 *
 * @param string $name The name of the language
 *
 * @package modx
 * @subpackage processors.system.action
 */
if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* get language */
if (empty($_REQUEST['name'])) return $modx->error->failure($modx->lexicon('language_err_ns'));
$language = $modx->getObject('modLexiconLanguage',$_REQUEST['name']);
if ($language == null) return $modx->error->failure($modx->lexicon('language_err_nf'));

return $modx->error->success('',$language);