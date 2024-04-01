<?php

/*
 * @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/tree/master/doc
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR12' => true,

    // Basic
    'curly_braces_position' => true,
    'encoding' => true,
    'no_trailing_comma_in_singleline' => true,
    'no_multiple_statements_per_line' => true,
    'psr_autoloading' => true,
    'single_line_empty_body' => false,

    // Language Construct
    'declare_equal_normalize' => [
        'space' => 'single'
    ],
    'declare_parentheses' => true,
    'nullable_type_declaration' => true,
    'single_space_around_construct' => true,

    // Control structure
    'control_structure_braces' => true,
    'elseif' => true,
    'empty_loop_body' => [
        'style' => 'braces'
    ],
    'include' => true,
    'no_break_comment' => true,
    'no_superfluous_elseif' => false,
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'trailing_comma_in_multiline' => true, // Default value: ['arrays']

    // Casing
    'class_reference_name_casing' => true,
    'constant_case' => true, // Default value: 'lower'
    'integer_literal_case' => true,
    'lowercase_keywords' => true,
    'lowercase_static_reference' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,

    // PHP tag
    'blank_line_after_opening_tag' => true,
    'full_opening_tag' => true,
    'linebreak_after_opening_tag' => true,
    'no_closing_tag' => true,

    // Comments
    'comment_to_phpdoc' => true,
    'multiline_comment_opening_closing' => true,
    'no_empty_comment' => false,
    'no_trailing_whitespace_in_comment' => true,
    'single_line_comment_spacing' => true,
    'single_line_comment_style' => [
        'comment_types' => ['hash'],
    ],

    // Whitespace
    'array_indentation' => true,
    'blank_line_before_statement' => [
        'statements' => [
            'return',
            'yield',
            'yield_from',
            'case',
            'try',
            'exit',
            'declare'
        ],
    ],
    'compact_nullable_typehint' => true,
    'indentation_type' => true,
    'line_ending' => true,
    'no_extra_blank_lines' => [
        'tokens' => [
            'extra',
            'throw',
            'use',
            'break',
            'continue',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
        ],
    ],
    'no_spaces_around_offset' => true,
    'no_trailing_whitespace' => true,
    'no_whitespace_in_blank_line' => true,
    'single_blank_line_at_eof' => true,
    'spaces_inside_parentheses' => true,
    'statement_indentation' => true,
    'type_declaration_spaces' => true,
    'types_spaces' => true,

    // Semicolon
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],
    'no_empty_statement' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'semicolon_after_instruction' => true,
    'space_after_semicolon' => true,

    // Return
    'no_useless_return' => true,

    // Operator
    'binary_operator_spaces' => [
        'default' => 'single_space',
    ],
    'concat_space' => [
        'spacing' => 'one',
    ],
    'new_with_braces' => true,
    'no_space_around_double_colon' => true,
    'no_useless_concat_operator' => [
        'juggle_simple_strings' => true
    ],
    'not_operator_with_space' => false,
    'object_operator_without_whitespace' => true,
    'operator_linebreak' => [
        'only_booleans' => true,
        'position' => 'end'
    ],
    'ternary_operator_spaces' => true,
    'unary_operator_spaces' => true,

    // Cast notation
    'cast_spaces' => [
        'space' => 'none'
    ],
    'lowercase_cast' => true,
    'modernize_types_casting' => true,
    'no_short_bool_cast' => true,
    'no_unset_cast' => true,
    'short_scalar_cast' => true,

    // Array notation
    'array_syntax' => [
        'syntax' => 'short'
    ],
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_whitespace_before_comma_in_array' => true,
    'normalize_index_brace' => true,
    'trim_array_spaces' => true,
    'whitespace_after_comma_in_array' => [
        'ensure_single_space' => true
    ],

    // String notation
    'heredoc_to_nowdoc' => true,
    'no_trailing_whitespace_in_string' => false,
    'single_quote' => true,
    'string_length_to_empty' => false,

    // Function notation
    'fopen_flag_order' => true,
    'function_declaration' => [
        'closure_function_spacing' => 'one',
        'closure_fn_spacing' => 'none',
        'trailing_comma_single_line' => true
    ],
    'method_argument_space' => [
        'on_multiline' => 'ignore'
    ],
    'no_spaces_after_function_name' => true,
    'no_useless_sprintf' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'phpdoc_to_param_type' => false, // Too risky for a framework like Laravel
    'phpdoc_to_property_type' => false,
    'phpdoc_to_return_type' => false,
    'return_type_declaration' => true,

    // Imports
    'fully_qualified_strict_types' => true,
    'group_import' => false,
    'global_namespace_import' => true,
    'no_leading_import_slash' => true,
    'no_unneeded_import_alias' => true,
    'no_unused_imports' => true,
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'case_sensitive' => true,
        'imports_order' => [
            'class',
            'function',
            'const',
        ]
    ],
    'single_line_after_imports' => true,

    // Namespace
    'blank_line_after_namespace' => true,
    'blank_lines_before_namespace' => true,
    'clean_namespace' => true,
    'no_leading_namespace_whitespace' => true,

    // Class notation
    'class_attributes_separation' => [
        'elements' => [
            'const' => 'none',
            'trait_import' => 'none',
            'property' => 'one',
            'method' => 'one',
        ],
    ],
    'class_definition' => true,
    'final_class' => false,
    'final_internal_class' => false,
    'no_blank_lines_after_class_opening' => true,
    'no_unneeded_final_method' => false,
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
            'property_public_readonly',
            'property_protected_readonly',
            'property_private_readonly',
            'public',
            'protected',
            'private',
            'construct',
            'destruct',
            'method:__invoke',
            'method_public_abstract_static',
            'method_protected_abstract_static',
            'method_private_abstract_static',
            'method_public_abstract',
            'method_protected_abstract',
            'method_private_abstract',
            'method_public_static',
            'method_protected_static',
            'method_private_static',
            'method_public',
            'method_protected',
            'method_private',
            'magic',
            'phpunit'
        ],
        'sort_algorithm' => 'none',
        'case_sensitive' => true
    ],
    'ordered_interfaces' => [
        'case_sensitive' => true
    ],
    'ordered_traits' => true,
    'protected_to_private' => false,
    'self_accessor' => false,
    'single_class_element_per_statement' => true,
    'single_trait_insert_per_statement' => false,
    'visibility_required' => [
        'elements' => ['method', 'property'],
    ],

    // phpDoc
    'align_multiline_comment' => true,
    'general_phpdoc_tag_rename' => [
        'replacements' => [
            'inheritDocs' => 'inheritDoc',
        ],
        'case_sensitive' => true
    ],
    'no_blank_lines_after_phpdoc' => true,
    'no_empty_phpdoc' => true,
    'phpdoc_add_missing_param_annotation' => [
        'only_untyped' => false
    ],
    'phpdoc_align' => [
        'align' => 'left'
    ],
    'phpdoc_indent' => true,
    'phpdoc_inline_tag_normalizer' => true,
    'phpdoc_line_span' => true,
    'phpdoc_no_access' => true,
    'phpdoc_no_useless_inheritdoc' => true,
    'phpdoc_order' => [
        'order' => [
            'param',
            'return',
            'throws'
        ]
    ],
    'phpdoc_param_order' => true,
    'phpdoc_return_self_reference' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
    'phpdoc_tag_casing' => true,
    'phpdoc_tag_type' => true,
    'phpdoc_to_comment' => true,
    'phpdoc_trim' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'phpdoc_types' => true,
    'phpdoc_types_order' => [
        'null_adjustment' => 'always_last',
        'case_sensitive' => true,
    ],
    'phpdoc_var_annotation_correct_order' => true,
    'phpdoc_var_without_name' => true,
];

$directories = [
    __DIR__ . '/app',
    __DIR__ . '/config',
    __DIR__ . '/database',
    __DIR__ . '/modules',
    __DIR__ . '/resources',
    __DIR__ . '/routes',
    __DIR__ . '/tests',
];

$directories = array_filter($directories, 'is_dir');

$finder = Finder::create()->in($directories)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();

return $config
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
