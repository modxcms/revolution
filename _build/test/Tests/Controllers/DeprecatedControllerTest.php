<?php

/**
 * Tests related to the modManagerControllerDeprecated controller class
 */
class DeprecatedControllerTest extends MODxTestCase {
    /**
     * @var modManagerControllerDeprecated
     */
    public $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = new modManagerControllerDeprecated($this->modx);
    }
    public function tearDown(): void
    {
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
