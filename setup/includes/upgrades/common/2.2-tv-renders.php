<?php
/**
 * Common upgrade script for 2.2 render classes
 *
 * @var modX $modx
 * @package setup
 */
$corePath = $modx->getOption('core_path',null,MODX_CORE_PATH);
$inputRendersPath = $corePath.'model/modx/processors/element/tv/renders/mgr/input/';
$outputRendersPath = $corePath.'model/modx/processors/element/tv/renders/web/output/';

/* first nuke the old, deprecated TV renders */
@unlink($inputRendersPath.'textareamini.php');
@unlink($inputRendersPath.'textbox.php');
@unlink($inputRendersPath.'dropdown.php');
@unlink($inputRendersPath.'htmlarea.php');

/* migrate display_params to output_properties, change deprecated  */
$tvs = $modx->getCollection('modTemplateVar',array(
    'type:IN' => array('textareamini','textbox','dropdown','htmlarea'),
));
/** @var modTemplateVar $tv */
foreach ($tvs as $tv) {
    $changed = false;
    switch ($tv->get('type')) {
        case 'textareamini':
            $tv->set('type','textarea');
            $changed = true;
            break;
        case 'textbox':
            $tv->set('type','text');
            $changed = true;
            break;
        case 'dropdown':
            $tv->set('type','listbox');
            $changed = true;
            break;
        case 'htmlarea':
            $tv->set('type','richtext');
            $changed = true;
            break;
    }

    if ($changed) {
        $tv->save();
    }
}

/* now nuke the flat-file TV renders since we have classes now */
@unlink($inputRendersPath.'autotag.php');
@unlink($inputRendersPath.'checkbox.php');
@unlink($inputRendersPath.'date.php');
@unlink($inputRendersPath.'email.php');
@unlink($inputRendersPath.'file.php');
@unlink($inputRendersPath.'hidden.php');
@unlink($inputRendersPath.'image.php');
@unlink($inputRendersPath.'listbox.php');
@unlink($inputRendersPath.'listbox-multiple.php');
@unlink($inputRendersPath.'number.php');
@unlink($inputRendersPath.'option.php');
@unlink($inputRendersPath.'resourcelist.php');
@unlink($inputRendersPath.'richtext.php');
@unlink($inputRendersPath.'tag.php');
@unlink($inputRendersPath.'text.php');
@unlink($inputRendersPath.'textarea.php');
@unlink($inputRendersPath.'url.php');

@unlink($outputRendersPath.'date.php');
@unlink($outputRendersPath.'default.php');
@unlink($outputRendersPath.'delim.php');
@unlink($outputRendersPath.'htmltag.php');
@unlink($outputRendersPath.'image.php');
@unlink($outputRendersPath.'richtext.php');
@unlink($outputRendersPath.'string.php');
@unlink($outputRendersPath.'text.php');
@unlink($outputRendersPath.'url.php');
