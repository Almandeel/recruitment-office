<?php
$type_primary = 1;
$type_secondary = 0;
$side_debt = 0;
$side_credit = 1;
return [
    'name' => 'نظام الحسابات',

    

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'ar',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'ar',

    'seeder' => [
        'truncate_tables' => true,
        'accounts' => [
            [
                'id' => 1,
                'number' => 1,
                'name' => 'الاصول',
                'type' => $type_primary,
                'side' => $side_debt,
                'accounts' => [
                    [
                        'id' => 11,
                        'number' => 11,
                        'name' => 'الاصول الثابته',
                        'type' => $type_primary,
                        'side' => $side_debt,
                    ],
                    [
                        'id' => 12,
                        'number' => 12,
                        'name' => 'الاصول المتداولة',
                        'type' => $type_primary,
                        'side' => $side_debt,
                        'accounts' => [
                            [
                                'id' => 121,
                                'number' => 121,
                                'name' => 'الخزن',
                                'type' => $type_primary,
                                'side' => $side_debt,
                                'accounts' => [
                                    [
                                        'id' => 1211,
                                        'number' => 1211,
                                        'name' => 'النقدية',
                                        'type' => $type_primary,
                                        'side' => $side_debt,
                                        'accounts' => [
                                            [
                                                'id' => 12111,
                                                'number' => 12111,
                                                'name' => 'النقدية',
                                                'type' => $type_secondary,
                                                'side' => $side_debt,
                                            ],                                    
                                        ],                                    
                                    ],
                                    [
                                        'id' => 1212,
                                        'number' => 1212,
                                        'name' => 'البنوك',
                                        'type' => $type_primary,
                                        'side' => $side_debt,
                                        'accounts' => [
                                            [
                                                'id' => 12121,
                                                'number' => 12121,
                                                'name' => 'البنك',
                                                'type' => $type_secondary,
                                                'side' => $side_debt,
                                            ],                                    
                                        ],  
                                    ],
                                ],
                            ],
                            [
                                'id' => 122,
                                'number' => 122,
                                'name' => 'العملاء',
                                'type' => $type_primary,
                                'side' => $side_debt,
                            ],
                            [
                                'id' => 123,
                                'number' => 123,
                                'name' => 'الموظفين',
                                'type' => $type_primary,
                                'side' => $side_debt,
                            ],
                            [
                                'id' => 124,
                                'number' => 124,
                                'name' => 'المكاتب الخارجية',
                                'type' => $type_primary,
                                'side' => $side_debt,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 2,
                'number' => 2,
                'name' => 'الالتزامات',
                'type' => $type_primary,
                'side' => $side_credit,
                'accounts' => [
                ],
            ],
            [
                'id' => 3,
                'number' => 3,
                'name' => 'الملاك',
                'type' => $type_primary,
                'side' => $side_credit,
                'accounts' => [
                    [
                        'id' => 31,
                        'number' => 31,
                        'name' => 'رأس المال',
                        'type' => $type_secondary,
                        'side' => $side_credit,
                    ],
                ],
            ],
            [
                'id' => 4,
                'number' => 4,
                'name' => 'المصروفات',
                'type' => $type_primary,
                'side' => $side_debt,
                'accounts' => [
                    [
                        'id' => 41,
                        'number' => 41,
                        'name' => 'مصروفات',
                        'type' => $type_secondary,
                        'side' => $side_debt,
                    ],
                ],
            ],
            [
                'id' => 5,
                'number' => 5,
                'name' => 'الايرادات',
                'type' => $type_primary,
                'side' => $side_credit,
                'accounts' => [
                    [
                        'id' => 51,
                        'number' => 51,
                        'name' => 'ايرادات',
                        'type' => $type_secondary,
                        'side' => $side_credit,
                    ],
                ],
            ],
            [
                'id' => 6,
                'number' => 6,
                'name' => 'الحسابات الختامية',
                'type' => $type_primary,
                'side' => $side_credit,
            ],
        ],
    ],
];
