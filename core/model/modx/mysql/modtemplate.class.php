<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modtemplate.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modTemplate_mysql extends modTemplate {
    public static function listTemplateVars(modTemplate &$template, array $sort = array('name' => 'ASC'), $limit = 0, $offset = 0,array $conditions = array()) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $template->xpdo->newQuery('modTemplateVar');
        $result['total'] = $template->xpdo->getCount('modTemplateVar',$c);
        $c->select($template->xpdo->getSelectColumns('modTemplateVar','modTemplateVar'));
        $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate', array(
            "modTemplateVarTemplate.tmplvarid = modTemplateVar.id",
            'modTemplateVarTemplate.templateid' => $template->get('id')
        ));
        $c->leftJoin('modCategory','Category');
        if (!empty($conditions)) { $c->where($conditions); }
        $c->select(array(
            "IF(ISNULL(modTemplateVarTemplate.tmplvarid),0,1) AS access",
            "IF(ISNULL(modTemplateVarTemplate.rank),0,modTemplateVarTemplate.rank) AS tv_rank",
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