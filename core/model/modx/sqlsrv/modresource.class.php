<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__) . '/modresource.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modResource_sqlsrv extends modResource {
    public static function listGroups(modResource &$resource, array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $resource->xpdo->newQuery('modResourceGroup');
        $c->leftJoin('modResourceGroupResource', 'ResourceGroupResource', array(
            "ResourceGroupResource.document_group = modResourceGroup.id",
            'ResourceGroupResource.document' => $resource->get('id')
        ));
        $result['total'] = $resource->xpdo->getCount('modResourceGroup',$c);
        $c->select($resource->xpdo->getSelectColumns('modResourceGroup', 'modResourceGroup'));
        $c->select(array("ISNULL(ResourceGroupResource.document,0) AS access"));
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($resource->xpdo->escape('modResourceGroup') . '.' . $resource->xpdo->escape($sortKey), $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $resource->xpdo->getCollection('modResourceGroup', $c);
        return $result;
    }

    public static function getTemplateVarCollection(modResource &$resource) {
        $c = $resource->xpdo->newQuery('modTemplateVar');
        $c->query['distinct'] = 'DISTINCT';
        $c->select($resource->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
        if ($resource->isNew()) {
            $c->select(array(
                "modTemplateVar.default_text AS value",
                "0 AS resourceId"
            ));
        } else {
            $c->select(array(
                "ISNULL(tvc.value,modTemplateVar.default_text) AS value",
                "{$resource->get('id')} AS resourceId"
            ));
        }
        $c->select($resource->xpdo->getSelectColumns('modTemplateVarTemplate', 'tvtpl', '', array('rank')));
        $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
            'tvtpl.tmplvarid = modTemplateVar.id',
            'tvtpl.templateid' => $resource->get('template'),
        ));
        if (!$resource->isNew()) {
            $c->leftJoin('modTemplateVarResource','tvc',array(
                'tvc.tmplvarid = modTemplateVar.id',
                'tvc.contentid' => $resource->get('id'),
            ));
        }
        $c->sortby('tvtpl.rank,modTemplateVar.rank');

        return $resource->xpdo->getCollection('modTemplateVar', $c);
    }
}
