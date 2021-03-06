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
    'amount_invalid' => 'قيمة القيد غير مقبولة',
    'amount_mismatch' => 'قيمة المدين لا تساوي قيمة الدائن',
    'debt_credit_mismatch' => 'قيمة القيد لا تساوي قيمة المدين والدائن',
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
    'types' => [
        \App\Entry::TYPE_JOURNAL => 'قيد يومية',
        \App\Entry::TYPE_ADJUST => 'قيد تسوية',
        \App\Entry::TYPE_ADVERSE => 'قيد عكسي',
        \App\Entry::TYPE_CLOSE => 'قيد إقفال',
        \App\Entry::TYPE_USE => 'قيد إهلاك',
        \App\Entry::TYPE_DOUBLE => 'قيد مزدوج',
        \App\Entry::TYPE_OPEN => 'قيد افتتاحي',
        \App\Entry::TYPE_OTHER => 'قيد اخر',
    ],
];