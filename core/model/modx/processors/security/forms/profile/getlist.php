<?php
/**
 * Gets a list of Form Customization profiles.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 *
 * @package modx
 * @subpackage processors.security.forms.profile
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* setup default properties */
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,0);
$sort = $modx->getOption('sort',$scriptProperties,'rank');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$search = $modx->getOption('search',$scriptProperties,'');

$criteria = array();
if(!empty($search)) {
    $criteria[] = array(
        'modFormCustomizationProfile.description:LIKE' => '%'.$search.'%',
        'OR:modFormCustomizationProfile.name:LIKE' => '%'.$search.'%',
    );
}

$profileResult = $modx->call('modFormCustomizationProfile', 'listProfiles', array(&$modx, $criteria, array($sort=> $dir), $limit, $start));
$count = $profileResult['count'];
$rules = $profileResult['collection'];

/* iterate through rules */
$data = array();
$canEdit = $modx->hasPermission('save');
$canRemove = $modx->hasPermission('remove');
foreach ($rules as $rule) {
    $ruleArray = $rule->toArray();
    
    $ruleArray['perm'] = array();
    if ($canEdit) $ruleArray['perm'][] = 'pedit';
    if ($canRemove) $ruleArray['perm'][] = 'premove';

    $data[] = $ruleArray;
}

return $this->outputArray($data,$count);