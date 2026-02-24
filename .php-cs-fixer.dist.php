<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/core')
    ->in(__DIR__ . '/manager')
    ->in(__DIR__ . '/connectors')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'single_quote' => true,
        'no_extra_blank_lines' => true,
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setRiskyAllowed(true);
