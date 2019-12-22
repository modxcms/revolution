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
namespace MODX\Revolution\Tests\Processors\Resource;


use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\modResource;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Resource\Create;

/**
 * Tests related to resource/create processor
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Resource
 * @group ResourceProcessors
 * @group modResource
 */
class ResourceCreateProcessorTest extends MODxTestCase {
    public function setUp() {
        parent::setUp();
        $this->modx->eventMap = [];
        if ($this->modx instanceof modX) {
            $resources = $this->modx->getCollection(modResource::class, [
                'pagetitle:LIKE' => '%Unit Test Resource%'
            ]);
            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $resource->remove();
            }
        }
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        if ($this->modx instanceof modX) {
            $resources = $this->modx->getCollection(modResource::class, [
                'pagetitle:LIKE' => '%Unit Test Resource%'
            ]);
            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $resource->remove();
            }
        }
    }

    /**
     * Tests the resource/create processor
     *
     * @param boolean $shouldPass
     * @param string $pageTitle
     * @param array $fields
     * @param array $expectedFieldsToCheck
     * @param array $settings
     * @dataProvider providerCreate
     */
    public function testCreate($shouldPass = true,$pageTitle = '',array $fields = [],array $expectedFieldsToCheck = [],array $settings = []
    ) {
        if (empty($pageTitle)) {
            $this->fail('No pagetitle specified in test condition!');
            return;
        }

        $fields['pagetitle'] = $pageTitle;

        foreach ($settings as $k => $v) {
            $this->modx->setOption($k,$v);
        }

        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Create::class,$fields);
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        if ($shouldPass) {
            if ($s) {
                /** @var modResource $resource */
                $resource = $this->modx->getObject(modResource::class, ['pagetitle' => $pageTitle]);
                $this->assertNotEmpty($resource,'Resource not found, although processor returned true: `'.$pageTitle.'`: '.$result->getMessage());
                if ($resource) {
                    foreach ($expectedFieldsToCheck as $k => $v) {
                        $this->assertEquals($v,$resource->get($k));
                    }
                }
            } else {
                $this->assertNotEmpty($s,'Could not create Resource: `'.$pageTitle.'`: '.$result->getMessage());
            }
        } else {
            $this->assertFalse($s,'Processor succeeded when it should have failed.');
        }
    }
    /**
     * Data provider for resource/create processor test.
     * @return array
     */
    public function providerCreate() {
        return [
            [ /* test basic resource creation */
                true,
                'Unit Test Resource 1',
                [
                    'alias' => 'unit-test-1',
                    'template' => 0,
                    'published' => true,
                ],
                [
                    'alias' => 'unit-test-1',
                    'published' => true,
                    'template' => 0,
                ]
            ],
            [ /* test resource creation with parent */
                true,
                'Unit Test Resource 2',
                [
                    'parent' => 1,
                ],
                [
                    'parent' => 1,
                ]
            ],
            [ /* test resource creation with parent as context */
                true,
                'Unit Test Resource 3',
                [
                    'parent' => 'web',
                ],
                [
                    'parent' => 0,
                ]
            ],
            [ /* test resource creation with invalid parent */
                false,
                'Unit Test Resource 4',
                [
                    'parent' => 999999999,
                ],
            ],
            [ /* test resource creation with invalid context_key */
                false,
                'Unit Test Resource 5',
                [
                    'context_key' => 'never-would-exist-ever-you-hear-me',
                ],
            ],
            [ /* test resource creation with a template */
                true,
                'Unit Test Resource 6',
                [
                    'template' => 1,
                ],
                [
                    'template' => 1,
                ]
            ],
            [ /* test resource creation with no template passed, but using a default_template System Setting */
                true,
                'Unit Test Resource 7',
                [
                ],
                [
                    'template' => 10,
                ],
                [
                    'default_template' => 10,
                ],
            ],
            [ /* test resource creation with pagetitle with whitespace at end, should trim it */
                true,
                'Unit Test Resource 8  ',
                [
                ],
                [
                    'pagetitle' => 'Unit Test Resource 8',
                ],
            ],
            [ /* test resource creation with manual menuindex */
                true,
                'Unit Test Resource 9',
                [
                    'menuindex' => 100,
                ],
                [
                    'menuindex' => 100,
                ],
            ],
            [ /* test resource creation with auto_menuindex off and no menuindex passed */
                true,
                'Unit Test Resource 10',
                [
                ],
                [
                    'menuindex' => 0,
                ],
                [
                    'auto_menuindex' => false,
                ],
            ],
        ];
    }
}
