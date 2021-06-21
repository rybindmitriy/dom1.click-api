<?php

$finder =
    PhpCsFixer\Finder::create()
        ->in([
            __DIR__.'/migrations',
            __DIR__.'/src',
            __DIR__.'/tests',
        ])
        ->notPath('Kernel.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['operators' => ['=' => 'align', '=>' => 'align', '??=' => 'align', '??' => 'align']],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public_static',
                'property_protected_static',
                'property_private_static',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'phpunit',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'magic',
                'method_public',
                'method_protected',
                'method_private',
            ],
            'sort_algorithm' => 'alpha',
        ],
    ])
    ->setFinder($finder);
