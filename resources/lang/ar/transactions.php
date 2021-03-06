<?php
return [
    'list' => 'قائمة المعاملات الماليه',
    'add' => 'اضافة معامله',
    'empty' => 'لا توجد معاملات',
    'show' => 'عرض معامله',
    'edit' => 'تعديل معامله',
    'delete' => 'حذف معامله',
    'choose' => 'اختر معامله',
    'details' => 'تفاصيل معامله',
    'confirm_delete' => 'تأكيد حذف المعامله',
    'create_success' => 'تمت اضافة المعامله بنجاح',
    'create_fail' => 'فشلت عملية الاضافة',
    'delete_success' => 'تم حذف المعامله بنجاح',
    'delete_fail' => 'فشلت عملية الحذف',
    'update_success' => 'تمت تعديل المعامله بنجاح',
    'update_fail' => 'فشلت عملية التعديل',
    'exists' => 'المعامله موجود في القائمة',
    'types' => [
        \Modules\Employee\Models\Transaction::TYPE_DEBT => 'سلفه',
        \Modules\Employee\Models\Transaction::TYPE_DEDUCATION => 'خصم',
        \Modules\Employee\Models\Transaction::TYPE_BONUS => 'علاوة',
    ],
];