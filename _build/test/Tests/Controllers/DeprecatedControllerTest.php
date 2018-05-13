<?php

/**
 * Tests related to the modManagerControllerDeprecated controller class
 */
class DeprecatedControllerTest extends MODxTestCase {
    /**
     * @var MODX\modManagerControllerDeprecated
     */
    public $controller;

    public function setUp() {
        parent::setUp();

        $this->controller = new MODX\modManagerControllerDeprecated($this->modx);
    }
    public function tearDown() {
        parent::tearDown();
        $this->controller = null;
    }

    public function testCustomAssets() {
        $data = <<<HTML
<script>console.log('exists!');</script>
HTML;

        $this->controller->addHtml($data);
        $output = $this->controller->render();

        $this->assertContains($data, $output, 'Deprecated controller did not process additionally added assets/HTML');
    }
}
