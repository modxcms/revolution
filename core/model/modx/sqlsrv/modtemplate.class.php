<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
require_once (dirname(dirname(__FILE__)) . '/modtemplate.class.php');
class modTemplate_sqlsrv extends modTemplate {
    public static function listTemplateVars(modTemplate &$template, array $sort = array('name' => 'ASC'), $limit = 0, $offset = 0) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $template->xpdo->newQuery('modTemplateVar');
        $result['total'] = $template->xpdo->getCount('modTemplateVar',$c);
        $c->select($template->xpdo->getSelectColumns('modTemplateVar','modTemplateVar'));
        $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate', array(
            "modTemplateVarTemplate.tmplvarid = modTemplateVar.id",
            'modTemplateVarTemplate.templateid' => $template->get('id')
        ));
        $c->select(array(
            "CASE ISNULL(modTemplateVarTemplate.tmplvarid) THEN 0 ELSE 1 END AS access",
            "modTemplateVarTemplate.rank AS tv_rank"
        ));
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($sortKey, $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $template->xpdo->getCollection('modTemplateVar',$c);
        return $result;
    }
}
