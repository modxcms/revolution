<?php
/**
 * Gets a list of Form Customization sets.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'action');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$profile = $modx->getOption('profile',$scriptProperties,0);
$search = $modx->getOption('search',$scriptProperties,'');

/* query for rules */
$c = $modx->newQuery('modFormCustomizationSet');
$c->innerJoin('modAction','Action');
$c->leftJoin('modTemplate','Template');
if (!empty($profile)) {
    $c->where(array(
        'profile' => $profile,
    ));
}
if (!empty($search)) {
    $c->where(array(
        'modFormCustomizationSet.description:LIKE' => '%'.$search.'%',
        'OR:Template.templatename:LIKE' => '%'.$search.'%',
        'OR:modFormCustomizationSet.constraint_field:LIKE' => '%'.$search.'%',
    ),null,2);
}
$count = $modx->getCount('modFormCustomizationSet',$c);
$c->select(array(
    'modFormCustomizationSet.*',
    'Action.controller',
    'Template.templatename',
));

$c->sortby($sort,$dir);
if ($limit) $c->limit($limit,$start);

$sets = $modx->getCollection('modFormCustomizationSet', $c);

/* iterate through rules */
$data = array();
$canEdit = $modx->hasPermission('save');
$canRemove = $modx->hasPermission('remove');
foreach ($sets as $set) {
    $setArray = $set->toArray();

    $constraint = $set->get('constraint');
    if (!empty($constraint)) {
        $setArray['constraint_data'] = $set->get('constraint_class').'.'.$set->get('constraint_field').' = '.$constraint;
    }
    $setArray['perm'] = array();
    if ($canEdit) $setArray['perm'][] = 'pedit';
    if ($canRemove) $setArray['perm'][] = 'premove';

    $data[] = $setArray;
}

return $this->outputArray($data,$count);