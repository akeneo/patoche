<?php

declare(strict_types = 1);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'ordered_imports' => true,
        'visibility_required' => false,
        'declare_strict_types' => true,
        'standardize_not_equals' => false,
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_summary' => false,
        'phpdoc_return_self_reference' => false,
        'void_return' => true,
        'self_accessor' => false,
        'increment_style' => false
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->name('*.php')
            ->notName('*Spec.php')
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
    );