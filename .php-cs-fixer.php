<?php

$finder = PhpCsFixer\Finder::create()->in([
        __DIR__.DIRECTORY_SEPARATOR.'src',
        __DIR__.DIRECTORY_SEPARATOR.'config',
        __DIR__.DIRECTORY_SEPARATOR.'routes',
        __DIR__.DIRECTORY_SEPARATOR.'database',
        __DIR__.DIRECTORY_SEPARATOR.'tests',
]);

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'declare_strict_types' => false,
        'class_definition' => false,
        'braces' => false,
        'return_type_declaration' => [
                'space_before' => 'one',
        ],
        'array_indentation' => false,
        'heredoc_indentation' => ['indentation' => 'same_as_start'],
        'native_function_invocation' => false,
        'phpdoc_align' => false,
        'function_declaration' => false,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'phpdoc_to_comment' => ['ignored_tags' => ['var', 'psalm-suppress']],
        'php_unit_test_class_requires_covers' => false,
        'final_internal_class' => false,
        'blank_line_before_statement' => ['statements' => ['goto', 'phpdoc', 'return']],
        'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'switch', 'throw', 'use']],
        'single_trait_insert_per_statement' => false,
])->setRiskyAllowed(true)->setFinder($finder);
