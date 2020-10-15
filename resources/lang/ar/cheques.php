<?php
return [
    'list' => 'قائمة الشيكات',
    'create' => 'اضافة شيك',
    'show' => 'عرض شيك',
    'due_date' => 'تاريخ الاستحقاق',
    'type' => 'نوع الشيك',
    'types' => 'انواع الشيكات',
    'status' => 'حالة الشيك',
    'statuses' => 'حالات الشيكات',
    'number' => 'رقم الشيك',
    'from' => 'من حساب',
    'to' => 'الى حساب',
    'beneficiary' => 'المستفيد',
    'id' => 'رقم الشيك',
    'from_account' => 'من ح/',
    'from_accounts' => 'من متعدد:',
    'to_account' => 'الى ح/',
    'to_accounts' => 'الى متعدد:',
    'date' => 'تاريخ الانشاء',
    'edit' => 'تعديل شيك',
    'delete' => 'حذف شيك',
    'cancel' => 'إلغاء شيك',
    'save_edit'          => 'حفظ وتعديل',
    'save_new'          => 'حفظ وإضافة شيك جديد',
    'save_list'          => 'حفظ و عرض قائمة الشيكات',
    'save_show'          => 'حفظ و عرض الشيك',
    'create_success'    => 'تمت اضافة الشيك بنجاح',
    'cancel_success'    => 'تم إلغاء الشيك بنجاح',
    'update_success'    => 'تم تعديل الشيك بنجاح',
    'delete_success'    => 'تم حذف الشيك بنجاح',
    'cancel_fail'       => 'فشلت عملية إلغاء الشيك',
    'create_fail'       => 'فشلت عملية إضافة الشيك',
    'update_fail'       => 'فشلت عملية تعديل الشيك',
    'delete_fail'       => 'فشلت عملية حذف الشيك',
    'delete_confirm'    => 'هل انت متأكد من انك تريد الحذف؟',
    'types' => [
        \App\Cheque::TYPE_COLLECT => 'تحصيل',
        \App\Cheque::TYPE_PAY => 'دفع',
    ],
    'statuses' => [
        \App\Cheque::STATUS_CANCELED => 'ملغي',
        \App\Cheque::STATUS_WAITING => 'قيد التحصيل',
        \App\Cheque::STATUS_DELEVERED => 'محصل',
    ],
];