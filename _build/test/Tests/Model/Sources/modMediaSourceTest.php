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
namespace MODX\Revolution\Tests\Model\Sources;


use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Sources\modFileMediaSource;
use ReflectionMethod;

/**
 * Tests related to the modMediaSource class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Sources
 * @group modMediaSource
 */
class modMediaSourceTest extends MODxTestCase
{
    /** @var modFileMediaSource $source */
    public $source;

    /** @var array Saved options restored after each test */
    private $savedOptions = [];

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        $this->source = $this->modx->newObject(modFileMediaSource::class);
        $this->source->fromArray([
            'name' => 'UnitTestSource',
            'description' => '',
            'class_key' => modFileMediaSource::class,
            'properties' => [],
        ],'',true);
    }

    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();
        foreach ($this->savedOptions as $key => $value) {
            $this->modx->setOption($key, $value);
        }
        $this->savedOptions = [];
        $this->source = null;
    }

    /**
     * Remember and override a system option for the duration of the test.
     *
     * @param string $key
     * @param mixed $value
     */
    private function overrideOption($key, $value)
    {
        if (!array_key_exists($key, $this->savedOptions)) {
            $this->savedOptions[$key] = $this->modx->getOption($key);
        }
        $this->modx->setOption($key, $value);
    }

    /**
     * Invoke protected applyUploadTranslitToFile and return the resulting file name.
     *
     * @param string $name
     * @return string
     */
    private function applyUploadTranslit($name)
    {
        $file = ['name' => $name];
        $method = new ReflectionMethod($this->source, 'applyUploadTranslitToFile');
        $method->setAccessible(true);
        $method->invokeArgs($this->source, [&$file, '/']);
        return $file['name'];
    }

    /**
     * #16787: friendly_alias_max_length must not strip the upload file extension.
     */
    public function testUploadTranslitPreservesExtensionDespiteMaxLength()
    {
        $this->overrideOption('friendly_alias_max_length', 15);
        $this->overrideOption('friendly_alias_lowercase_only', true);
        $this->overrideOption('upload_translit_restrict_chars_pattern', '');

        $name = 'verylongfilename.jpg';
        $this->assertGreaterThan(15, strlen($name));

        // Reproduce the pre-fix failure mode: full-name filter with max_length truncates the extension.
        $truncated = $this->modx->filterPathSegment($name, []);
        $this->assertSame('verylongfilenam', $truncated);

        $result = $this->applyUploadTranslit($name);
        $this->assertSame('verylongfilename.jpg', $result);
    }

    /**
     * Basename is lowercased; the original extension case is preserved.
     */
    public function testUploadTranslitLowercasesBasenameOnly()
    {
        $this->overrideOption('friendly_alias_max_length', 15);
        $this->overrideOption('friendly_alias_lowercase_only', true);
        $this->overrideOption('upload_translit_restrict_chars_pattern', '');

        $result = $this->applyUploadTranslit('PHOTO.JPG');
        $this->assertSame('photo.JPG', $result);
    }

    /**
     * Alphanumeric FURL restrict replaces dots with the word delimiter on the filtered
     * string. Filtering the full name would turn photo.jpg into photo-jpg; basename-only
     * filtering must keep .jpg.
     */
    public function testUploadTranslitPreservesExtensionWithAlphanumericRestrict()
    {
        $this->overrideOption('friendly_alias_restrict_chars', 'alphanumeric');
        $this->overrideOption('friendly_alias_word_delimiter', '-');
        $this->overrideOption('friendly_alias_max_length', 0);
        $this->overrideOption('friendly_alias_lowercase_only', true);
        $this->overrideOption('upload_translit_restrict_chars_pattern', '');

        $broken = $this->modx->filterPathSegment('photo.jpg', [
            'friendly_alias_restrict_chars' => 'alphanumeric',
            'friendly_alias_word_delimiter' => '-',
        ]);
        $this->assertSame('photo-jpg', $broken);

        $result = $this->applyUploadTranslit('photo.jpg');
        $this->assertSame('photo.jpg', $result);
    }

    /**
     * Compound names: dots inside the basename may be rewritten by alphanumeric restrict,
     * but the final extension stays intact.
     */
    public function testUploadTranslitPreservesFinalExtensionWithAlphanumericRestrict()
    {
        $this->overrideOption('friendly_alias_restrict_chars', 'alphanumeric');
        $this->overrideOption('friendly_alias_word_delimiter', '-');
        $this->overrideOption('friendly_alias_max_length', 10);
        $this->overrideOption('friendly_alias_lowercase_only', true);
        $this->overrideOption('upload_translit_restrict_chars_pattern', '');

        $result = $this->applyUploadTranslit('archive.backup.tar.gz');
        $this->assertSame('archive-backup-tar.gz', $result);
    }

    /**
     * Names without an extension are still filtered; max_length does not apply to uploads.
     */
    public function testUploadTranslitWithoutExtension()
    {
        $this->overrideOption('friendly_alias_max_length', 5);
        $this->overrideOption('friendly_alias_lowercase_only', true);
        $this->overrideOption('upload_translit_restrict_chars_pattern', '');

        $result = $this->applyUploadTranslit('VeryLongName');
        $this->assertSame('verylongname', $result);
    }
}
