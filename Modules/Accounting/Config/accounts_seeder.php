<?php
$type_primary = 0;
$type_secondary = 1;
$side_debt = -1;
$side_credit = 1;
return [
    'truncate_tables' => false,
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
                                ],
                                [
                                    'id' => 1212,
                                    'number' => 1212,
                                    'name' => 'البنوك',
                                    'type' => $type_primary,
                                    'side' => $side_debt,
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
                    'type' => $type_primary,
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
                    'type' => $type_primary,
                    'side' => $side_debt,
                ],
            ],
        ],
        [
            'id' => 5,
            'number' => 5,
            'name' => 'الايرادات',
            'type' => $type_primary,
            'side' => $side_debt,
            'accounts' => [
                [
                    'id' => 51,
                    'number' => 51,
                    'name' => 'ايرادات',
                    'type' => $type_primary,
                    'side' => $side_debt,
                ],
            ],
        ],
    ],
];