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
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'rank');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$search = $modx->getOption('search',$scriptProperties,'');

/* query for rules */
$c = $modx->newQuery('modFormCustomizationProfile');

if (!empty($search)) {
    $c->where(array(
        'modFormCustomizationProfile.description:LIKE' => '%'.$search.'%',
        'OR:modFormCustomizationProfile.name:LIKE' => '%'.$search.'%',
    ),null,2);
}
$count = $modx->getCount('modFormCustomizationProfile',$c);
$c->select(array(
    'modFormCustomizationProfile.*',
));
$c->select('
    (SELECT GROUP_CONCAT(`UserGroup`.`name`) FROM '.$modx->getTableName('modUserGroup').' AS `UserGroup`
        INNER JOIN '.$modx->getTableName('modFormCustomizationProfileUserGroup').' AS `fcpug`
        ON `fcpug`.`usergroup` = `UserGroup`.`id`
     WHERE `fcpug`.`profile` = `modFormCustomizationProfile`.`id`
    ) AS `usergroups`
');

$c->sortby($sort,$dir);
if ($limit) $c->limit($limit,$start);

$rules = $modx->getCollection('modFormCustomizationProfile', $c);

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