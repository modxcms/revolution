<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * @var modX $modx
 */

use MODX\Revolution\modChunk;
use MODX\Revolution\modElementPropertySet;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\Sources\modMediaSourceElement;

/* modify legacy core class references in modElementPropertySet element_class column */

$class = modElementPropertySet::class;
$table = $modx->getTableName($class);

$elementClass = $this->install->lexicon('alter_column', ['column' => 'element_class', 'table' => $table]);
$this->processResults($class, $elementClass, [$modx->manager, 'alterField'], [$class, 'element_class']);

$modx->updateCollection($class, ['element_class' => modChunk::class], ['element_class' => 'modChunk']);
$modx->updateCollection($class, ['element_class' => modTemplate::class], ['element_class' => 'modTemplate']);
$modx->updateCollection($class, ['element_class' => modTemplateVar::class], ['element_class' => 'modTemplateVar']);
$modx->updateCollection($class, ['element_class' => modPlugin::class], ['element_class' => 'modPlugin']);
$modx->updateCollection($class, ['element_class' => modSnippet::class], ['element_class' => 'modSnippet']);

/* modify legacy core class references in modMediaSourceElement object_class column */

$class = modMediaSourceElement::class;
$table = $modx->getTableName($class);

$elementClass = $this->install->lexicon('alter_column', ['column' => 'object_class', 'table' => $table]);
$this->processResults($class, $elementClass, [$modx->manager, 'alterField'], [$class, 'object_class']);

$modx->updateCollection($class, ['object_class' => modTemplateVar::class], ['object_class' => 'modTemplateVar']);
