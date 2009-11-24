<?php
/**
 * @package setup
 */
if (!empty($_POST['proceed'])) {
    $this->proceed('options');
}

return $this->parser->fetch('welcome.tpl');