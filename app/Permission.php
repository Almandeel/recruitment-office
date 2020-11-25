<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];
    public const NAMES = [
    'create' => 'انشاء',
    'read' => 'قراءة',
    'update' => 'تعديل',
    'delete' => 'حذف',
    'print' => 'طباعه',
    'send' => 'ارسال',
    'dashboard' => 'لوحة التحكم',
    ];
    public const GROUPS = [
    'advances' => 'السلفيات | نظام المكاتب الخارجية',
    'logs' => 'حركات النظام | نظام المستخدمين',
    'accounts' => 'الحسابات | نظام المحاسبة',
    'attachments' => 'المرفقات | نظام المحاسبة',
    'attendance' => 'الحضور | نظام الموظفين',
    'bills' => 'الفواتير | نظام المكاتب الخارجية',
    'centers' => 'المراكز | نظام المحاسبة',
    'complaints' => 'الشكاوي | نظام خدمات العملاء',
    'contracts' => 'العقود | نظام خدمات العملاء',
    'countries' => 'الدول | نظام المكاتب الخارجية',
    'customers' => 'العملاء | نظام خدمات العملاء',
    'cv' => 'السير الذاتيه | نظام خدمات العملاء',
    'departments' => 'الاقسام | نظام الموظفين',
    'employees' => 'الموظفين | نظام الموظفين',
    'entries' => 'القيود | نظام المحاسبة',
    'expenses' => 'المصروفات | نظام المحاسبة',
    'lines' => 'الخطوط | نظام الموظفين',
    'letters' => 'الرسائل | نظام المراسلات',
    'marketers' => 'المسوقين | نظام خدمات العملاء',
    'notes' => 'الملاحظات | نظام المستخدمين',
    'offices' => 'المكاتب الخارجيه | نظام المكاتب الخارجية',
    'permissions' => 'الصلاحيات | نظام المستخدمين',
    'professions' => 'المهن | نظام المكاتب الخارجية',
    'pulls' => 'السحب | نظام المكاتب الخارجية',
    'returns' => 'الارجاع | نظام المكاتب الخارجية',
    'roles' => 'الادوار | نظام المستخدمين',
    'safes' => 'الخزن | نظام  المحاسبة',
    'salaries' => 'المرتبات | نظام المحاسبة',
    'suggestions' => 'الاقتراحات | نظام المستخدمين',
    'tasks' => 'المهام | نظام المستخدمين',
    'transactions' => 'المعاملات | نظام  المحاسبة',
    'transfers' => 'التحويلات | نظام المحاسبة',
    'users' => 'المستخدمين | نظام المستخدمين',
    'warehousecvs' => 'النزلاء | نظام  الايواء',
    'phones' => 'الهواتف الخاصة | نظام الموظفين',
    'vacations' => 'الاجازات | نظام الموظفين',
    'vouchers' => 'السندات | نظام المحاسبة',
    'warehouses' => 'الايواء | نظام الايواء',
    'years' => 'السنوات الماليه | نظام المحاسبة',
    'categories' => 'الاقسام | نظام مركز المعرفة',
    'services' => 'خدمات العملاء | نظام خدمات العملاء',
    'flights' => ' الرحلات | نظام خدمات العملاء',
    'delegations' => 'الوكالات | نظام خدمات العملاء',
    'bails' => 'الكفالات | نظام خدمات العملاء',
    'tutorials' => 'مركز المعرفة | نظام مركز المعرفة',
    'sms' => 'الرسائل النصية | نظام المستخدمين',
    'mail' => 'المراسلات | نظام المراسلات',
    'positions' => 'الوظائف | نظام الموظفين',
    'custodies' => 'العهد | نظام الموظفين',
    'safes' => 'الخزن | نظام المحاسبة',
    'statements' => 'كشف حساب العميل | نظام المحاسبة',
    ];
    public static function enternal(){
        return self::whereIn('display_name', ['e', 'ex'])->get();
    }
    public static function external(){
        return self::whereIn('display_name', ['x', 'ex'])->get();
    }

    public function getPermission(){
        return self::NAMES[$this->permission()];
    }

    public function permission(){
        $index = strpos($this->name, '-') + 1;
        return substr($this->name, $index,strlen($this->name));
    }

    public function getGroup(){
        if(isset(self::GROUPS[$this->group()])) {
            return self::GROUPS[$this->group()];
        }
    }

    public function group(){
        $index = strpos($this->name, '-');
        return substr($this->name, 0, $index);
    }
}
