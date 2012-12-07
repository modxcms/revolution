<?php
/**
 * Sorts elements in the element tree
 *
 * @param json $data The JSON encoded data from the tree
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
class modElementSortProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('element_tree');
    }
    public function getLanguageTopics() {
        return array('category');
    }

    public function process() {
        $data = $this->getData();
        if (empty($data)) return $this->failure();

		$error = false;
        if (!empty($data['n_category']) && is_array($data['n_category'])) {
            $error = $this->handleCategoryDrop($data['n_category']);
        } else if (!empty($data)) {
            $error = $this->handleSubCategoryDrop($data);
        }

		if (is_string($error)) return $this->failure($error);
        return $this->success();
    }

    /**
     * Get the data formatted and ready for sorting
     * @return array
     */
    public function getData() {
        $data = $this->getProperty('data');
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);

        $this->sortNodes('modTemplate','template',$data);
        $this->sortNodes('modTemplateVar','tv',$data);
        $this->sortNodes('modChunk','chunk',$data);
        $this->sortNodes('modSnippet','snippet',$data);
        $this->sortNodes('modPlugin','plugin',$data);
        return $data;
    }

    /**
     * Handle dropping of Elements or Categories onto Categories
     * 
     * @param array $data
     * @return boolean|string
     */
    public function handleCategoryDrop(array $data) {
        /* if dropping an element onto a category, do that here */
        foreach ($data as $key => $elements) {
            if (!is_array($elements) || empty($elements)) continue;

            $key = explode('_',$key);
            if (empty($key[1]) || empty($key[2]) || $key[1] != 'category') continue;

            foreach ($elements as $elKey => $elArray) {
                $elKey = explode('_',$elKey);
                if (empty($elKey[1]) || empty($elKey[3])) continue;

                $className = 'mod'.ucfirst($elKey[1]);
                if ($className == 'modTv') $className = 'modTemplateVar';

                /** @var modElement $element */
                $element = $this->modx->getObject($className,$elKey[3]);
                if ($element) {
                    $element->set('category',$key[2]);
                    $element->save();
                }
            }
        }

        /* if sorting categories, do that here */
        $cdata = array();
        $this->getNodesFormatted($cdata,$data);
        foreach ($cdata as $categoryArray) {
            if ($categoryArray['type'] != 'category') continue;
            /** @var modCategory $category */
            $category = $this->modx->getObject('modCategory',$categoryArray['id']);
            if ($category && $categoryArray['parent'] != $category->get('parent')) {
				$exists = $this->modx->getCount('modCategory',array('category' => $category->get('category'), 'parent' => $categoryArray['parent'])) > 0;
				if ($exists) return $this->modx->lexicon('category_err_ae');
				
                $category->set('parent',$categoryArray['parent']);
                $category->save();
            }
        }
		return true;
    }

    /**
     * Handle dropping of Categories onto other Categories
     * @param array $data
     * @return boolean
     */
    public function handleSubCategoryDrop(array $data) {
        $cdata = array();
        foreach ($data as $typeKey => $type) {
            if (!empty($type)) {
                $this->getCategoryNodeDrop($cdata,$type);
            }
        }
        foreach ($cdata as $item) {
            if (empty($item['class']) || empty($item['pk'])) continue;

            if ($item['class'] == 'modCategory') {
                /** @var modCategory $category */
                $category = $this->modx->getObject('modCategory',$item['pk']);
                if ($category) {
                    $category->set('parent',$item['category']);
                    $category->save();
                }
            }
        }
		return true;
    }


    /**
     * Properly sort the data
     * @param string $xname
     * @param string $type
     * @param array $data
     * @return void
     */
    public function sortNodes($xname,$type,$data) {
        $s = $data['n_type_'.$type];
        if (is_array($s)) {
            $this->sortNodesHelper($s,$xname);
        }
    }


    public function sortNodesHelper($objs,$xname,$currentCategoryId = 0) {
        foreach ($objs as $objar => $kids) {
            $oar = explode('_',$objar);
            $nodeArray = $this->processID($oar);

            if ($nodeArray['type'] == 'category') {
                $this->sortNodesHelper($kids,$xname,$nodeArray['pk']);

            } elseif ($nodeArray['type'] == 'element') {
                /** @var modElement $element */
                $element = $this->modx->getObject($xname,$nodeArray['pk']);
                if (empty($element)) continue;

                $element->set('category',$currentCategoryId);
                $element->save();
            }
        }
    }

    public function processID($ar) {
        return array(
            'elementType' => $ar[1],
            'type' => $ar[2],
            'pk' => $ar[3],
            'elementCatId' => isset($ar[4]) ? $ar[4] : 0,
        );
    }


    public function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
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
            $this->getNodesFormatted($ar_nodes,$children,$ar[2]);
        }
    }

    public function getCategoryNodeDrop(&$cdata,$type = array(),$currentParent = 0) {
        foreach ($type as $itemKey => $item) {
            $nar = explode('_',$itemKey);
            $pk = !empty($nar[3]) ? $nar[3] : 0;
            $cdata[] = array(
                'type' => $nar[1],
                'class' => 'mod'.ucfirst($nar[2]),
                'pk' => $pk,
                'category' => $currentParent,
            );
            if (!empty($item)) {
                $this->getCategoryNodeDrop($cdata,$item,$pk);
            }
        }
    }
}
return 'modElementSortProcessor';