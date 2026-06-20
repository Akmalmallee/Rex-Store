<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Set the active AI provider for the fashion fitting assistant.
    | Options: 'mock', 'openai'
    |
    */
    'ai_provider' => env('FITTING_AI_PROVIDER', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Configuration
    |--------------------------------------------------------------------------
    |
    | API key and model settings for the OpenAI provider.
    |
    */
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('FITTING_OPENAI_MODEL', 'gpt-4o'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fit Score Thresholds
    |--------------------------------------------------------------------------
    |
    | Minimum score thresholds for different recommendation confidence levels.
    |
    */
    'thresholds' => [
        'excellent' => 0.85,
        'good' => 0.70,
        'fair' => 0.50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Size Mapping
    |--------------------------------------------------------------------------
    |
    | Rule-based size suggestions based on height (cm) and weight (kg).
    | Override in env if needed.
    |
    */
    'size_chart' => [
        ['label' => 'XS', 'height_max' => 160, 'weight_max' => 50],
        ['label' => 'S',  'height_max' => 168, 'weight_max' => 60],
        ['label' => 'M',  'height_max' => 178, 'weight_max' => 75],
        ['label' => 'L',  'height_max' => 185, 'weight_max' => 90],
        ['label' => 'XL', 'height_max' => 999, 'weight_max' => 999],
    ],
];
