<?php

/* convert the authority index to be unique on modUserGroupRole */

/**
 * @var modX $modx
 * @var modInstallVersion $this
 */

$class = \MODX\Revolution\modUserGroupRole::class;
$table = $modx->getTableName(\MODX\Revolution\modUserGroupRole::class);

$description = $this->install->lexicon('drop_index', ['index' => 'authority', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'removeIndex'], [$class, 'authority']);
$description = $this->install->lexicon('add_index', ['index' => 'authority', 'table' => $table]);
if (!$this->processResults($class, $description, [$modx->manager, 'addIndex'], [$class, 'authority']))
{
    $this->runner->addResult(modInstallRunner::RESULT_FAILURE, $this->install->lexicon('authority_unique_index_error'));
}
