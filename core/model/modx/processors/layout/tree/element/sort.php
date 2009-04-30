<?php
/**
 * Sorts elements in the element tree
 *
 * @param json $data The JSON encoded data from the tree
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
$modx->lexicon->load('category');

$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);

sortNodes('modTemplate','template',$data);
sortNodes('modTemplateVar','tv',$data);
sortNodes('modChunk','chunk',$data);
sortNodes('modSnippet','snippet',$data);
sortNodes('modPlugin','plugin',$data);


function sortNodes($xname,$type,$data) {
	$s = $data['n_type_'.$type];
	if (is_array($s)) {
        sortNodesHelper($s,$xname);
    }
}


function sortNodesHelper($objs,$xname,$currentCategoryId = 0) {
    global $modx;

    foreach ($objs as $objar => $kids) {
        $oar = split('_',$objar);
        $nodeArray = processID($oar);

        if ($nodeArray['type'] == 'category') {
            sortNodesHelper($kids,$xname,$nodeArray['pk']);

        } elseif ($nodeArray['type'] == 'element') {
            $element = $modx->getObject($xname,$nodeArray['pk']);
            if ($element == null) continue;

            $element->set('category',$currentCategoryId);
            $element->save();
        }
    }
}

function processID($ar) {
    return array(
        'elementType' => $ar[1],
        'type' => $ar[2],
        'pk' => $ar[3],
        'elementCatId' => isset($ar[4]) ? $ar[4] : 0,
    );
}

return $modx->error->success();