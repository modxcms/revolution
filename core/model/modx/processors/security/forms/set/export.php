<?php
/**
 * Export a FC Set to XML
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (!extension_loaded('XMLWriter') || !class_exists('XMLWriter')) {
    return $modx->error->failure($modx->lexicon('xmlwriter_err_nf'));
}

if (empty($scriptProperties['download'])) {
    if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('set_err_ns'));
    $c = $modx->newQuery('modFormCustomizationSet');
    $c->innerJoin('modAction','Action');
    $c->leftJoin('modTemplate','Template');
    $c->where(array(
        'id' => $scriptProperties['id'],
    ));
    $c->select(array(
        'modFormCustomizationSet.*',
        'Action.controller',
        'Template.templatename',
    ));
    $set = $modx->getObject('modFormCustomizationSet',$c);
    if ($set == null) return $modx->error->failure($modx->lexicon('set_err_nf'));

    $profile = $set->getOne('Profile');
    if ($profile == null) return $modx->error->failure($modx->lexicon('profile_err_nf'));

    $setArray = $set->toArray();
    $setArray = array_merge($setArray,$set->getData());

    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->startDocument('1.0','UTF-8');
    $xml->setIndent(true);
    $xml->setIndentString('    ');

    $xml->startElement('set');

    $xml->writeElement('action',$setArray['controller']);
    $xml->writeElement('template',$setArray['templatename']);
    $xml->writeElement('description',$setArray['description']);
    $xml->writeElement('constraint_field',$setArray['constraint_field']);
    $xml->writeElement('constraint',$setArray['constraint']);
    $xml->writeElement('active',$setArray['active']);

    
    $xml->startElement('fields');
    foreach ($setArray['fields'] as $field) {
        $xml->startElement('field');
        $xml->writeElement('name',$field['name']);
        $xml->writeElement('visible',$field['visible']);
        $xml->writeElement('label',$field['label']);
        $xml->writeElement('default_value',$field['default_value']);
        $xml->endElement(); // end field
    }
    $xml->endElement(); // end fields


    $xml->startElement('tabs');
    foreach ($setArray['tabs'] as $tab) {
        $xml->startElement('tab');
        $xml->writeElement('name',$tab['name']);
        $xml->writeElement('visible',$tab['visible']);
        $xml->writeElement('label',$tab['label']);
        $xml->endElement(); // end tab
    }
    $xml->endElement(); // end tabs

    $xml->startElement('tvs');
    foreach ($setArray['tvs'] as $tv) {
        $xml->startElement('tv');
        $xml->writeElement('name',$tv['name']);
        $xml->writeElement('visible',$tv['visible']);
        $xml->writeElement('label',$tv['label']);
        $xml->writeElement('default_value',$tv['default_value']);
        $xml->writeElement('tab',$tv['tab']);
        $xml->writeElement('tab_rank',$tv['rank']);
        $xml->endElement(); // end tv
    }
    $xml->endElement(); // end tvs

    $xml->endElement(); // end set
    $xml->endDocument();
    $data = $xml->outputMemory();

    $f = 'export.xml';
    $fileName = $modx->getOption('core_path').'export/fcset/'.$f;

    $cacheManager = $modx->getCacheManager();
    $s = $cacheManager->writeFile($fileName,$data);

    return $modx->error->success($f);
} else {
    $file = $scriptProperties['download'];
    $f = $modx->getOption('core_path').'export/fcset/'.$file;

    if (empty($scriptProperties['id'])) return '';
    $set = $modx->getObject('modFormCustomizationSet',$scriptProperties['id']);
    if (empty($set)) return '';
    $profile = $set->getOne('Profile');
    if (empty($profile)) return '';
    $action = $set->getOne('Action');
    if (empty($action)) return '';
    
    $name = $profile->get('name').'.'.$action->get('controller');
    $name = strtolower(str_replace(array(' ','/'),'-',$name));

    if (!is_file($f)) return $o;

    $o = file_get_contents($f);

    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="'.$name.'.fcset.xml"');

    return $o;
}

return $modx->error->success();