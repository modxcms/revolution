<?php
/**
 * @package modx
 */
class modScaffolder {

    function __construct($modx,$config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge($config,array(

        ));
    }

    function generate($name,$buildPath) {
        if (!$this->checkDirectory($buildPath.$name)) {
            return false;
        }


    }

    function checkDirectory($path) {
        if (is_dir($path)) {
            return false;
        }
    }
}