<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
require_once (dirname(__DIR__) . '/modtemplate.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modTemplate_sqlsrv extends modTemplate {
    public static function listTemplateVars(modTemplate &$template, array $sort = array('name' => 'ASC'), $limit = 0, $offset = 0,array $conditions = array()) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $template->xpdo->newQuery('modTemplateVar');
        $result['total'] = $template->xpdo->getCount('modTemplateVar',$c);
        $c->select($template->xpdo->getSelectColumns('modTemplateVar','modTemplateVar'));
        $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate', array(
            "modTemplateVarTemplate.tmplvarid = modTemplateVar.id",
            'modTemplateVarTemplate.templateid' => $template->get('id'),
        ));
        $c->leftJoin('modCategory','Category');
        if (!empty($conditions)) { $c->where($conditions); }
        $c->select(array(
            "CASE modTemplateVarTemplate.tmplvarid WHEN NULL THEN 0 ELSE 1 END AS access",
            "ISNULL(modTemplateVarTemplate.rank, 0) AS tv_rank",
            'category_name' => 'Category.category',
        ));
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($sortKey, $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $template->xpdo->getCollection('modTemplateVar',$c);
        return $result;
    }
}
