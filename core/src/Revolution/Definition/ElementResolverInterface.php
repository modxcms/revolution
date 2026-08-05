<?php

namespace MODX\Revolution\Definition;

use MODX\Revolution\modElement;

interface ElementResolverInterface
{
    public function getElement(string $class, string $name): ?modElement;

    public function getLastDecision(): array;
}
