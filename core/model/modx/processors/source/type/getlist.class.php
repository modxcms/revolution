<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Gets a list of media source types
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.source.type
 */
class modMediaSourceTypeGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('sources');
    }
    public function getLanguageTopics() {
        return array('source');
    }

    public function process() {
        $this->modx->setPackageMeta('sources',$this->modx->getOption('core_path',null,MODX_CORE_PATH).'model/modx/');
        $this->modx->loadClass('sources.modMediaSource');
        $descendants = $this->modx->getDescendants('modMediaSource');
        $coreSources = $this->modx->getOption('core_media_sources',null,'modFileMediaSource,modS3MediaSource');
        $coreSources = explode(',',$coreSources);

        $list = array();
        foreach ($descendants as $descendant) {
            $key = in_array($descendant,$coreSources) ? 'sources.'.$descendant : $descendant;
            /** @var xPDOObject|modMediaSource $obj */
            $obj = $this->modx->newObject($key);
            if (!$obj) continue;

            $list[] = array(
                'class' => $key,
                'name' => $obj->getTypeName(),
                'description' => $obj->getTypeDescription(),
            );
        }

        return $this->outputArray($list);
    }
}
return 'modMediaSourceTypeGetListProcessor';
