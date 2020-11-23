<?php
return [
    'list' => 'قائمة السنوات المالية',
    'create' => 'اضافة سنة مالية',
    'notfound' => 'لم يتم ايجاد السنة المالية',
    'close' => 'اغلاق السنة المالية',
    'show' => 'عرض سنة مالية',
    'edit' => 'تعديل سنة مالية',
    'delete' => 'حذف سنة مالية',
    'archive' => 'ارشفة سنة مالية',
    'details' => 'تفاصيل السنة',
    'closed' => 'تم اغلاق السنة المالية بنجاح',
    'confirm_close' => 'بإغلاق السنة المالية سوف لن تتمكن من اجراء اي قيود في هذه السنة هل انت متأكد من انك تريد اغلاق السنة المالية؟',
    'settings' => 'اعدادات السنة',
    'is_opened' => 'السنة المالية مفتوحة',
    'is_closed' => 'السنة المالية مغلقة',
    'is_archived' => 'السنة المالية مؤرشفة',
    'inactive' => 'السنة المالية غير نشطة',
    'last' => 'السنة المالية السابقة',
    "confirm_close_title"             => "تأكيد الإغلاق",
    "confirm_close_text"             => "سوف يتم إغلاق السنة المالية هل انت متأكد؟",
    'errors' => [
        'id_exists' => 'لا يمكن انشاء سنة مالية في هذا الشهر لانها موجودة بالفعل',
        'income_summary' => 'اختر حساب ملخص الدخل', 
        'capital' => 'اختر حساب رأس المال',
    ],
    'accounts' => [
        'safe' => 'ح/ الحزنة',
        'cash' => 'خ/ النقدية',
        'bank' => 'خ/ البنك',
        'expenses' => 'ح/ المصروفات',
        'revenues' => 'ح/ الايرادات',
    ],
    'statuses' => [
        Modules\Accounting\Models\Year::STATUS_OPENED => 'مفتوحة',
        Modules\Accounting\Models\Year::STATUS_CLOSED => 'مغلقة',
        Modules\Accounting\Models\Year::STATUS_ARCHIVED => 'مؤرشفة',
        Modules\Accounting\Models\Year::STATUS_ACTIVE => 'نشطة',
    ],
    'entries_details' => [
        'income_summary_debt' => 'عبارة عن قيد لترحيل الايرادات الى حساب ملخص الدخل',
        'income_summary_credit' => 'عبارة عن قيد لترحيل المصروفات الى حساب ملخص الدخل',
        'capitals' => 'عبارة عن قيد لاغلاق مسحوبات الملاك',
        'profit' => 'عبارة عن قيد لترحيل الارباح الى حساب رأس المال',
        'lost' => 'عبارة عن قيد لترحيل الخسائر الى حساب رأس المال',
    ],

    'guides' => [
        'index' => [
            'guide_create_btn' => [
                'title' => 'انشاء سنة مالية',
                'description' => 'عند الضغط على هذا الزر سيتم نقلك الى صفحة انشاء سنة مالية جديدة',
            ],
            'guide_advanced_search' => [
                'title' => 'البحث المتقدم',
                'description' => 'من خلال هذا النموذج يمكنك البحث عن سنوات حسب الحساب او النوع وبين تاريخين',
            ],
        ],
    ],
];