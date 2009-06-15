<?php
/**
 * Gets a language
 *
 * @param string $name The name of the language
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get language */
if (empty($_POST['name'])) return $modx->error->failure($modx->lexicon('language_err_ns'));
$language = $modx->getObject('modLexiconLanguage',$_POST['name']);
if ($language == null) return $modx->error->failure($modx->lexicon('language_err_nf'));

return $modx->error->success('',$language);