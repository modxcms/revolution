<?php
require_once 'phing/Task.php';

/**
 * Phing Task class to compress files using the YUICompressor library.
 */
class yuiCompressTask extends Task {
    /**
     * Path to the YUICompressor library.
     *
     * @var string
     */
    protected $lib;

    /**
     * The source files to compress.
     *
     * @var FileSet
     */
    protected $filesets = array();

    /**
     * Indicates if build should stop if an error is encountered.
     *
     * @var boolean
     */
    protected $stopOnError = false;

    protected $nomunge = false;
    protected $preservesemicolons = false;
    protected $disableoptimizations = false;
    protected $charset = '';
    protected $type = '';
    protected $linebreak = -1;
    protected $outputsuffix = '-min';

    /**
     * Where to output the compressed files.
     *
     * @var string
     */
    protected $dest;

    public function setNomunge($string) {
        $this->nomunge = $string;
    }

    public function setPreservesemicolons($string) {
        $this->preservesemicolons = $string;
    }

    public function setDisableoptimizations($string) {
        $this->disableoptimizations = $string;
    }

    public function setCharset($string) {
        $this->charset = strtolower($string);
    }

    public function setType($string) {
        if (in_array(strtolower($string), array('', 'js', 'css'))) {
            $this->type = strtolower($string);
        }
    }

    public function setLinebreak($string) {
        if (!empty($string) || $string === '0') {
            $this->type = (integer) $string;
        } else {
            $this->type = -1;
        }
    }

    public function setOutputsuffix($string) {
        $this->outputsuffix = $string;
    }

    /**
     * Set the absolute path to the yuicompressor-x.y.z.jar.
     *
     * @param string $path The absolute path to the YUICompressor.
     */
    public function setLib($path) {
        $this->lib = $path;
    }

    /**
     * Add a FileSet to the stack and return it.
     *
     * @return FileSet The new FileSet that was added to the stack.
     */
    public function createFileSet() {
        $num = array_push($this->filesets, new FileSet());
        return $this->filesets[$num - 1];
    }

    /**
     * Set a flag to indicate if the build should stop when an error occurs.
     *
     * @param boolean $value True if the build should stop on error.
     */
    public function setStopOnError($value) {
        $this->stopOnError = (boolean) $value;
    }

    /**
     * Set the location to output the compressed files.
     *
     * @param string $path The location to output the compressed files.
     */
    public function setDest($path) {
        $this->dest = $path;
    }

    public function init() {}

    public function main() {
        $options = array();
        if (empty($this->type) || $this->type !== 'css') {
            if ($this->nomunge) $options[] = "--nomunge";
            if ($this->preservesemicolons) $options[] = "--preserve-semi";
            if ($this->disableoptimizations) $options[] = "--disable-optimizations";
        }
        if (!empty($this->type)) $options[] = "--type {$this->type}";
        if (!empty($this->charset)) $options[] = "--charset {$this->charset}";
        if ($this->linebreak > -1) $options[] = "--line-break {$this->linebreak}";

        foreach ($this->filesets as $fs) {
            try {
                $files = $fs->getDirectoryScanner($this->project)->getIncludedFiles();
                $fullPath = realpath($fs->getDir($this->project));

                foreach ($files as $file) {
                    $filename = $file;
                    if (!empty($this->outputsuffix)) {
                        $extPos = strrpos($filename, '.');
                        if ($extPos > 0) {
                            $ext = substr($filename, $extPos);
                            $filename = substr($filename, 0, $extPos);
                            $filename .= $this->outputsuffix . $ext;
                        }
                    }
                    $outfile = $this->dest . '/' . $filename;

                    if (file_exists(dirname($outfile)) == false) {
                        mkdir(dirname($outfile), 0700, true);
                    }

                    $lib = realpath($this->lib);
                    $args = implode(' ', $options);
                    $src = $fullPath . DIRECTORY_SEPARATOR . $file;
                    $command = "java -jar {$lib} -o {$outfile} {$args} {$src}";

                    $output = array();
                    $return = null;

                    $this->log("Compressing src {$src} to {$outfile} using args {$args}");
                    $this->log("Command executed: {$command}");

                    exec($command, $output, $return);

                    foreach ($output as $line) {
                        $this->log($line);
                    }

                    if ($return != 0) {
                        throw new BuildException("Task exited with code {$return}");
                    }
                }
            } catch (BuildException $be) {
                // directory doesn't exist or is not readable
                if ($this->stopOnError) {
                    throw $be;
                } else {
                    $this->log($be->getMessage(), Project::MSG_WARN);
                }
            }
        }
    }
}
