<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution\Tests\Model\FormCustomization;


use MODX\Revolution\modActionDom;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modMail class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group FormCustomization
 * @group modActionDom
 */
class modActionDomTest extends MODxTestCase {
    public function setUp() {
        parent::setUp();
    }

    /**
     * @param string $expected
     * @param string $ruleType
     * @param string $name
     * @param string $value
     * @param string $container
     * @dataProvider providerApply
     */
    public function testApply($expected,$ruleType,$name,$value,$container) {
        /** @var modActionDom $rule */
        $rule = $this->modx->newObject(modActionDom::class);
        $rule->fromArray(array(
            'set' => 0,
            'action' => 1,
            'xtype' => '',
            'active' => true,
            'rule' => $ruleType,
            'name' => $name,
            'value' => $value,
            'container' => $container,
            'for_parent' => 0,
            'rank' => 0,
        ));
        $content = $rule->apply(1);
        $this->assertEquals($expected,$content);
    }
    /**
     * @return array
     */
    public function providerApply() {
        return array(
            array('MODx.hideField("modx-panel-resource",["description"]);',
                'fieldVisible','description',0,'modx-panel-resource'),

            array('MODx.renameLabel("modx-panel-resource",["published"],["Active"]);',
                'fieldTitle','published','Active','modx-panel-resource'),

            array('MODx.renameTab("modx-resource-settings","Other Settings");',
                'tabTitle','modx-resource-settings','Other Settings','modx-resource-tabs'),

            array('MODx.hideRegion("modx-resource-tabs","modx-resource-settings");',
                'tabVisible','modx-resource-settings',0,'modx-resource-tabs'),

            array('MODx.addTab("modx-resource-tabs",{title:"Other Tab",id:"tab-other"});',
                'tabNew','tab-other','Other Tab','modx-resource-tabs'),

            array('MODx.moveTV(["tv15"],"modx-resource-settings");',
                'tvMove','tv15','modx-resource-settings','modx-panel-resource'),
        );
    }

}
