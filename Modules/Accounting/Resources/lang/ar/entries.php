<?php
return [
    'list' => 'قائمة القيود',
    'create' => 'اضافة قيد',
    'show' => 'عرض قيد',
    'id' => 'رقم القيد',
    'account_prefix' => 'ح/',
    'amount' => 'المبلغ',
    'details' => 'البيان',
    'from_account' => 'من ح/',
    'from_accounts' => 'من متعدد:',
    'to_account' => 'الى ح/',
    'to_accounts' => 'الى متعدد:',
    'date' => 'التاريخ',
    'edit' => 'تعديل قيد',
    'delete' => 'حذف قيد',
    'amount_full' => 'تم استيفاء قيمة القيد',
    'amount_invalid' => 'قيمة القيد غير مقبولة',
    'sides_invalid' => 'قيمة المدين او الدائن غير مقبولة',
    'debt_credit_mismatch' => 'قيمة المدين لا تساوي قيمة الدائن',
    'amount_mismatch' => 'قيمة القيد لا تساوي قيمة المدين والدائن',
    'save_edit'          => 'حفظ وتعديل',
    'save_new'          => 'حفظ وإضافة قيد جديد',
    'save_list'          => 'حفظ و عرض قائمة القيود',
    'save_show'          => 'حفظ و عرض القيد',
    'create_success'    => 'تمت اضافة القيد بنجاح',
    'update_success'    => 'تم تعديل القيد بنجاح',
    'delete_success'    => 'تم حذف القيد بنجاح',
    'create_fail'       => 'فشلت عملية إضافة القيد',
    'update_fail'       => 'فشلت عملية تعديل القيد',
    'delete_fail'       => 'فشلت عملية حذف القيد',
    'delete_confirm'    => 'هل انت متأكد من انك تريد الحذف؟',
    'details_expense' => 'عبارة عن قيد للمنصرف رقم: __id__',
    'details_voucher' => 'عبارة عن قيد __type__ رقم: __id__',
    'details_transaction' => 'عبارة عن قيد __type__ رقم: __id__',
    'details_adverse' => 'عبارة عن قيد عكسي للقيد رقم: __entry_id__',
    'adverse_success' => 'تم عكس القيد رقم: __entry_id__ بنجاح',
    'types' => [
        \Modules\Accounting\Models\Entry::TYPE_JOURNAL => 'قيد يومية',
        \Modules\Accounting\Models\Entry::TYPE_ADJUST => 'قيد تسوية',
        \Modules\Accounting\Models\Entry::TYPE_ADVERSE => 'قيد عكسي',
        \Modules\Accounting\Models\Entry::TYPE_CLOSE => 'قيد إقفال',
        \Modules\Accounting\Models\Entry::TYPE_USE => 'قيد إهلاك',
        \Modules\Accounting\Models\Entry::TYPE_DOUBLE => 'قيد مزدوج',
        \Modules\Accounting\Models\Entry::TYPE_OPEN => 'قيد افتتاحي',
        \Modules\Accounting\Models\Entry::TYPE_OTHER => 'قيد اخر',
    ],

    'guides' => [
        'index' => [
            'guide_create_btn' => [
                'title' => 'انشاء قيد',
                'description' => 'عند الضغط على هذا الزر سيتم نقلك الى صفحة انشاء القيود',
            ],
            'guide_advanced_search' => [
                'title' => 'البحث المتقدم',
                'description' => 'من خلال هذا النموذج يمكنك البحث عن قيود حسب الحساب او النوع وبين تاريخين',
            ],

        ],
        'create' => [
            'guide_entry_date' => [
                'title' => 'حقل التاريخ',
                'description' => 'هنا تقوم بإدخال تاريخ القيد',
            ],
            'guide_entry_type' => [
                'title' => 'حقل نوع القيد',
                'description' => 'هنا تقوم بإختيار نوع القيد',
            ],
            'guide_amount' => [
                'title' => 'حقل قيمة القيد',
                'description' => 'هنا تقوم بادخال قيمة القيد',
            ],
            'guide_details' => [
                'title' => 'حقل البيان',
                'description' => 'هنا تقوم بكتابة بيان القيد وتفاصيله',
            ],
            'guide_debts' => [
                'title' => 'الحسابات المدينة',
                'description' => 'قائمة الحسابات المدينة في القيد',
            ],
            'guide_debts_add' => [
                'title' => 'زر إضافة حساب مدين',
                'description' => 'يمكنك اضافة حساب لقائمة الحسابات المدينة من هنا',
            ],
            'guide_credits' => [
                'title' => 'الحسابات الدائنة',
                'description' => 'قائمة الحسابات الدائنة في القيد',
            ],
            'guide_credits_add' => [
                'title' => 'زر إضافة حساب دائن',
                'description' => 'يمكنك اضافة حساب لقائمة الحسابات الدائنة من هنا',
            ],
            'guide_submit' => [
                'title' => 'زر اكمال العملية',
                'description' => 'عند الضغط على هذا الزر وبعد التأكد من صلاحية بيانات القيد يتم انشاءه',
            ],
        ],
    ],
];