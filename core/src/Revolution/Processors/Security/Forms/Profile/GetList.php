<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Profile;

use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of Form Customization profiles.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 * @package MODX\Revolution\Processors\Security\Forms\Profile
 */
class GetList extends GetListProcessor
{
    public $classKey = modFormCustomizationProfile::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $canEdit = false;
    public $canRemove = false;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties(['search' => '']);
        $this->canEdit = $this->modx->hasPermission('save');
        $this->canRemove = $this->modx->hasPermission('remove');
        return parent::initialize();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $criteria = [];
        $search = $this->getProperty('search', '');
        if (!empty($search)) {
            $criteria[] = [
                'modFormCustomizationProfile.description:LIKE' => '%' . $search . '%',
                'OR:modFormCustomizationProfile.name:LIKE' => '%' . $search . '%',
            ];
        }
        $profileResult = $this->modx->call(modFormCustomizationProfile::class, 'listProfiles', [
            &$this->modx,
            $criteria,
            [$this->getProperty('sort') => $this->getProperty('dir')],
            $this->getProperty('limit'),
            $this->getProperty('start'),
        ]);
        $data = [];
        $data['total'] = $profileResult['count'];
        $data['results'] = $profileResult['collection'];
        return $data;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['perm'] = [];
        if ($this->canEdit) {
            $objectArray['perm'][] = 'pedit';
        }
        if ($this->canRemove) {
            $objectArray['perm'][] = 'premove';
        }
        return $objectArray;
    }
}
