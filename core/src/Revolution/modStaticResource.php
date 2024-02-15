<?php

namespace MODX\Revolution;

use xPDO\xPDO;

/**
 * A derivative of modResource that stores content on the filesystem.
 * @package MODX\Revolution
 */
class modStaticResource extends modResource
{
    /**
     * @var string Path of the file containing the source content, relative to the media source or full absolute path
     */
    protected $_sourceFile= '';
    /**
     * @var integer Size of the source file content in bytes.
     */
    protected $_sourceFileSize= 0;

    /**
     * Overrides modResource::__construct to set the class key for this Resource type
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    public function __construct(xPDO $xpdo)
    {
        parent:: __construct($xpdo);
        $this->set('class_key', __CLASS__);
        $canCreate = (bool)$this->xpdo->hasPermission('new_static_resource');
        $this->allowListingInClassKeyDropdown = $canCreate;
        $this->showInContextMenu = $canCreate;
    }

    /**
     * Get the absolute path to the static source file represented by this instance.
     *
     * @param array $options An array of options.
     * @return string The absolute path to the static source file.
     */
    public function getSourceFile(array $options = [])
    {
        $filename = (string)parent::getContent($options);

        // Support placeholders/snippets in the filename by parsing it through the modParser
        $array = array();
        if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($filename, $array)) {
            $this->xpdo->parser->processElementTags('', $filename);
        }

        // Sanitize to avoid ../ style path traversal
        $filename = preg_replace(array("/\.*[\/|\\\]/i", "/[\/|\\\]+/i"), array('/', '/'), $filename);

        // If absolute paths are allowed (disabled by default for security reasons), and a file exists at the provided path, use it
        $allowAbsolute = (bool)$this->xpdo->getOption('resource_static_allow_absolute', null, false);
        if ($allowAbsolute && file_exists($filename)) {
            $this->_sourceFile = $filename;
            $this->_sourceFileSize = filesize($filename);
        }

        // If absolute paths are **not** allowed or an absolute file was not found, prefix the resource_static_path setting
        else {
            $sourcePath = $this->xpdo->getOption('resource_static_path', $options, '{assets_path}', true);
            if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($sourcePath, $array)) {
                $this->xpdo->parser->processElementTags('', $sourcePath);
            }

            // If an absolute path was provided that matches the required path, strip the absolute portion as it's added again below
            if (strpos($filename, $sourcePath) === 0) {
                $filename = substr($filename, strlen($sourcePath));
            }

            // When selecting a file using the media browser, that will provide a relative url like "assets/static/foo.pdf";
            // To avoid that from 404ing when the resource_static_path is set to {assets_path}, we need to check
            // if the provided $filename starts with the _relative_ url, matching against the base path.
            // This doesn't work for directories outside of the base path (ie a moved core), but that's too complex
            // to resolve without full media source support on static resources.
            $relativeSourcePath = strpos($sourcePath, MODX_BASE_PATH) === 0 ? substr($sourcePath, strlen(MODX_BASE_PATH)) : false;
            // if $filename starts with the $relativeSourcePath, remove the $relativeSourcePath from the start of $filename
            // to avoid that getting duplicated when adding the $sourcePath below.
            if ($relativeSourcePath && strpos($filename, $relativeSourcePath) === 0) {
                $filename = substr($filename, strlen($relativeSourcePath));
            }

            $this->_sourceFile = $sourcePath . $filename;
            if (file_exists($this->_sourceFile)) {
                $this->_sourceFileSize = filesize($this->_sourceFile);
            }
        }

        return $this->_sourceFile;
    }

    /**
     * Get the filesize of the static source file represented by this instance.
     *
     * @param array $options An array of options.
     * @return integer The filesize of the source file in bytes.
     */
    public function getSourceFileSize(array $options = array()) {
        $this->getSourceFile($options);
        return $this->_sourceFileSize;
    }

    /**
     * Treats the local content as a filename to load the raw content from.
     *
     * For resources with a binary content type, this renders out the file to the browser immediately.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = [])
    {
        $this->getSourceFile($options);
        $content = $this->getFileContent($this->_sourceFile);
        if ($content === false) {
            $this->xpdo->sendErrorPage();
        }
        return $content;
    }

    /**
     * Retrieve the resource content stored in a physical file.
     *
     * @param string $file @deprecated internal _sourceFile is always used
     * @param array $options
     * @return string The content of the file, of false if it could not be
     * retrieved.
     */
    public function getFileContent($file, array $options = [])
    {
        /** @var modContentType $contentType */
        $contentType = $this->getOne('ContentType');
        if (!$contentType) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "modStaticResource->getFileContent() for resource {$this->get('id')}: Could not get content type.");
            return false;
        }

        $content = false;
        if (file_exists($this->_sourceFile) && is_readable($this->_sourceFile)) {
            $content = $this->_sourceFile;
        }

        if (empty($content)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "modStaticResource->getFileContent() for resource {$this->get('id')}: Could not load content from file {$this->_sourceFile}");
            return false;
        }

        // Return the content if not binary
        if (!$contentType->get('binary')) {
            return file_get_contents($content);
        }

        // Set the appropriate content type header
        $mimeType = $contentType->get('mime_type') ?: 'text/html';
        $header = 'Content-Type: ' . $mimeType;
        header($header);

        // Apply a content-length header if we know the size in bytes
        $filesize = $this->getSourceFileSize($options);
        if ($filesize > 0) {
            header('Content-Length: ' . $filesize);
        }

        // Set content disposition header based on what's configured on the resource (bool)
        if ($this->get('content_dispo')) {
            $name = $this->getAttachmentName($contentType);
            header('Content-Disposition: attachment; filename=' . $name);
        }
        else {
            header('Content-Disposition: inline');
        }

        // Cache control headers
        header('Cache-Control: public');
        header('Vary: User-Agent');

        // Custom headers defined on the content type, if any
        if ($customHeaders = $contentType->get('headers')) {
            foreach ($customHeaders as $headerKey => $headerString) {
                header($headerString);
            }
        }

        // Close the user session, clean out the output buffer
        @session_write_close();
        while (ob_get_level() && @ob_end_clean()) {}

        readfile($content);

        exit();
    }

    /**
     * Converts to bytes from PHP ini_get() format.
     *
     * PHP ini modifiers for byte values:
     * <ul>
     *  <li>G = gigabytes</li>
     *  <li>M = megabytes</li>
     *  <li>K = kilobytes</li>
     * </ul>
     *
     * @access protected
     * @param string $value Number of bytes represented in PHP ini value format.
     * @return integer The value converted to bytes.
     */
    protected function _bytes($value)
    {
        $value = trim($value);
        $modifier = strtolower($value[strlen($value)-1]);
        switch($modifier) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        return $value;
    }

    /**
     * Sets the path to the Static Resource manager controller
     * @static
     * @param xPDO $modx A reference to the modX instance
     * @return string
     */
    public static function getControllerPath(xPDO &$modx)
    {
        $path = modResource::getControllerPath($modx);
        return $path.'staticresource/';
    }

    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true.
     * @return array
     */
    public function getContextMenuText()
    {
        return [
            'text_create' => $this->xpdo->lexicon('static_resource'),
            'text_create_here' => $this->xpdo->lexicon('static_resource_create_here'),
        ];
    }

    /**
     * Use this in your extended Resource class to return a translatable name for the Resource Type.
     * @return string
     */
    public function getResourceTypeName()
    {
        return $this->xpdo->lexicon('static_resource');
    }

    /**
     * Gets the name for the downloaded file for the resource
     *
     * @param modContentType $contentType
     * @return string
     */
    private function getAttachmentName(modContentType $contentType)
    {
        $ext = $contentType->getExtension();
        if ($alias = $this->get('uri')) {
            $name = basename($alias);
        }
        elseif ($this->get('alias')) {
            $name = $this->get('alias') . $ext;
        }
        elseif ($name = $this->get('pagetitle')) {
            $name = $this->cleanAlias($name) . $ext;
        }
        else {
            $name = 'download' . $ext;
        }

        return $name;
    }
}
