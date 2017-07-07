<?php
/**
 * Gets the parents of a resource and constructs a breadcrumb path to the resource.
 *
 * @param integer $id The ID of the resource
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceGetPathProcessor extends modProcessor {
    /** @var modResource $resource */
    public $resource;

    /** @var bool $returnNodeList */
    private $returnNodeList;

    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->resource = $this->modx->getObject('modResource', $id);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nfs',array('id' => $id));

        // whether we should include the list of id,pagetitle for every parent object
        $this->returnNodeList = $this->getProperty('nodelist', false);

        return true;
    }

    public function process() {
        $pathData = array();

        $path = ""; // path could also be generated on the client via js, but doing it here
                    // seems to be better performance
        $parentId = $this->resource->parent;
        while ($parentId!=0) {
            $parent = $this->modx->getObject('modResource', $parentId);
            $path = $parent->pagetitle . " > " . $path;
            $pathData[] = array('id' => $parentId, 'pagetitle' => $parent->pagetitle);
            $parentId = $parent->parent;
        }
        $idNote = $this->modx->hasPermission('tree_show_resource_ids') ? ' <span dir="ltr">(' . $this->resource->id . ')</span>' : '';
        $path .= '<strong>' . $this->resource->pagetitle . $idNote . '</strong>';

        if ($this->returnNodeList) {
            return $this->success('', [ 'path' => $path, 'parents' => $pathData ]);
        }

        return $this->success('', [ 'path' => $path ]);

    }

}
return 'modResourceGetPathProcessor';