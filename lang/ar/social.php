<?php

return [
    'deleted_user' => 'مستخدم محذوف',

    'errors' => [
        'not_found' => 'المورد غير موجود.',
        'item_not_found' => 'هذا المنشور غير موجود أو لم يعد متاحًا.',
        'comment_not_found' => 'هذا التعليق غير موجود أو تم حذفه.',
        'parent_not_found' => 'التعليق الذي ترد عليه لم يعد موجودًا.',
        'company_not_found' => 'الشركة غير موجودة.',
        'forbidden' => 'غير مسموح لك بتنفيذ هذا الإجراء.',
        'not_comment_author' => 'يمكن للكاتب فقط تعديل هذا التعليق.',
        'not_company_member' => 'هذه المعلومات مخصصة لأعضاء الشركة فقط.',
        'cannot_repost_own' => 'لا يمكنك إعادة نشر منشورك الخاص.',
        'cannot_follow_own_company' => 'لا يمكنك متابعة شركتك الخاصة.',
        'user_not_found' => 'المستخدم غير موجود.',
        'cannot_follow_self' => 'لا يمكنك متابعة نفسك.',
    ],

    'validation' => [
        'body_required' => 'لا يمكن أن يكون التعليق فارغًا.',
        'body_max' => 'لا يمكن أن يتجاوز التعليق :max حرفًا.',
        'parent_invalid' => 'التعليق الأصلي غير صالح.',
        'repost_comment_max' => 'لا يمكن أن يتجاوز نص إعادة النشر :max حرفًا.',
        'search_min' => 'يجب أن يحتوي البحث على حرفين على الأقل.',
    ],

    'push' => [
        'like_job' => [
            'title' => 'إعجاب جديد',
            'body' => 'أُعجب :name بعرضك «:title»',
        ],
        'like_service' => [
            'title' => 'إعجاب جديد',
            'body' => 'أُعجب :name بخدمتك «:title»',
        ],
        'comment' => [
            'title' => 'تعليق جديد',
            'body' => 'علّق :name على «:title»: :excerpt',
        ],
        'reply' => [
            'title' => 'رد جديد',
            'body' => 'ردّ :name على تعليقك',
        ],
        'repost' => [
            'title' => 'إعادة نشر جديدة',
            'body' => 'أعاد :name نشر «:title»',
        ],
        'follow' => [
            'title' => 'متابع جديد',
            'body' => 'أصبح :name يتابع :company',
        ],
        'user_follow' => [
            'title' => 'متابع جديد',
            'body' => 'بدأ :name بمتابعتك',
        ],
        'comment_like' => [
            'title' => 'إعجاب جديد',
            'body' => 'أعجب :name بتعليقك',
        ],
    ],
];
