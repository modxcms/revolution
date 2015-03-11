<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
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
        $rule = $this->modx->newObject('modActionDom');
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
