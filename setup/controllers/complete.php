<?php
$navbar= '
<button id="cmdnext" name="cmdnext" onclick="return doAction(\'complete\');">'.$install->lexicon['login'].'</button>
<span class="cleanup">
  <label>
    <input type="checkbox" value="1" id="cleanup" name="cleanup" /> '.$install->lexicon['delete_setup_dir'].'
  </label>
</span>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('complete.tpl');