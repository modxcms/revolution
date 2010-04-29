<?php
/**
 * Sorts elements in the element tree
 *
 * @param json $data The JSON encoded data from the tree
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
if (!$modx->hasPermission('element_tree')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

$data = urldecode($scriptProperties['data']);
$data = $modx->fromJSON($data);

sortNodes($modx,'modTemplate','template',$data);
sortNodes($modx,'modTemplateVar','tv',$data);
sortNodes($modx,'modChunk','chunk',$data);
sortNodes($modx,'modSnippet','snippet',$data);
sortNodes($modx,'modPlugin','plugin',$data);

if (!empty($data['n_category']) && is_array($data['n_category'])) {
    /* if dropping an element onto a category, do that here */
    foreach ($data['n_category'] as $key => $elements) {
        if (!is_array($elements) || empty($elements)) continue;

        $key = explode('_',$key);
        if (empty($key[1]) || empty($key[2]) || $key[1] != 'category') continue;

        foreach ($elements as $elKey => $elArray) {
            $elKey = explode('_',$elKey);
            if (empty($elKey[1]) || empty($elKey[3])) continue;

            $className = 'mod'.ucfirst($elKey[1]);
            if ($className == 'modTv') $className = 'modTemplateVar';

            $element = $modx->getObject($className,$elKey[3]);
            if ($element) {
                $element->set('category',$key[2]);
                $element->save();
            }
        }
    }

    /* if sorting categories, do that here */
    $cdata = array();
    getNodesFormatted($cdata,$data['n_category']);
    foreach ($cdata as $categoryArray) {
        if ($categoryArray['type'] != 'category') continue;
        $category = $modx->getObject('modCategory',$categoryArray['id']);
        if ($category && $categoryArray['parent'] != $category->get('parent')) {
            $category->set('parent',$categoryArray['parent']);
            $category->save();
        }
    }
}

function sortNodes(modX &$modx,$xname,$type,$data) {
    $s = $data['n_type_'.$type];
    if (is_array($s)) {
        sortNodesHelper($modx,$s,$xname);
    }
}


function sortNodesHelper(modX &$modx,$objs,$xname,$currentCategoryId = 0) {
    foreach ($objs as $objar => $kids) {
        $oar = explode('_',$objar);
        $nodeArray = processID($oar);

        if ($nodeArray['type'] == 'category') {
            sortNodesHelper($modx,$kids,$xname,$nodeArray['pk']);

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


function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
    $order = 0;
    foreach ($cur_level as $nodeId => $children) {
        $ar = explode('_',$nodeId);
        if (empty($ar[1]) || empty($ar[2])) continue;
        
        $ar_nodes[] = array(
            'id' => $ar[2],
            'type' => $ar[1],
            'parent' => $parent,
            'order' => $order,
        );
        $order++;
        getNodesFormatted($ar_nodes,$children,$ar[2]);
    }
}

return $modx->error->success();