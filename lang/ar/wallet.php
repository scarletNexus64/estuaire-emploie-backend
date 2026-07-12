<?php

return [
    // Errors
    'stats_fetch_error' => 'حدث خطأ أثناء جلب الإحصائيات',
    'transactions_fetch_error' => 'حدث خطأ أثناء جلب المعاملات',
    'balances_fetch_error' => 'حدث خطأ أثناء جلب الأرصدة',
    'history_fetch_error' => 'حدث خطأ أثناء جلب السجل',
    'users_fetch_error' => 'حدث خطأ أثناء جلب المستخدمين',
    'invalid_data' => 'بيانات غير صالحة',
    'validation_error' => 'خطأ في التحقق',

    // Recharge
    'recharge_success' => 'تمت إعادة الشحن بنجاح',
    'recharge_initiated' => 'تم بدء إعادة الشحن بنجاح',
    'recharge_init_error' => 'حدث خطأ أثناء بدء إعادة الشحن',
    'verification_error' => 'حدث خطأ أثناء التحقق',

    // Payments
    'payment_success' => 'تمت عملية الدفع بنجاح',
    'payment_not_found' => 'لم يتم العثور على الدفعة',
    'payment_not_paypal' => 'هذه الدفعة ليست دفعة PayPal',
    'payment_already_completed' => 'تم إكمال الدفعة بالفعل',
    'payment_failed' => 'فشلت عملية الدفع',
    'payment_execution_error' => 'حدث خطأ أثناء تنفيذ الدفع',
    'status_retrieved' => 'تم جلب الحالة',
    'status_verification_error' => 'حدث خطأ أثناء التحقق من الحالة',

    // PayPal
    'paypal_order_created' => 'تم إنشاء طلب PayPal بنجاح',
    'paypal_order_create_error' => 'حدث خطأ أثناء إنشاء طلب PayPal',
    'paypal_capture_error' => 'حدث خطأ أثناء التقاط الدفعة',

    // Balances & withdrawals
    'insufficient_freemopay_balance' => 'رصيد FreeMoPay غير كافٍ. المتاح: :amount فرنك',
    'insufficient_paypal_balance' => 'رصيد PayPal غير كافٍ. المتاح: :amount فرنك (~:usd دولار)',
    'exchange_rate_unavailable' => 'أسعار الصرف غير متوفرة أو قديمة. يرجى المحاولة مرة أخرى لاحقًا.',
    'withdrawal_processing' => 'جاري معالجة طلب السحب. ستتلقى إشعارًا فور اكتمال العملية.',
    'withdrawal_processing_detail' => 'السحب قيد التنفيذ. ستتلقى إشعارًا فوريًا فور اكتماله (حوالي 1-2 دقيقة).',
    'paypal_withdrawal_processing' => 'جاري معالجة سحب PayPal. ستتلقى إشعارًا فور اكتمال العملية.',
    'paypal_withdrawal_processing_detail' => 'سحب PayPal قيد التنفيذ. ستتلقى إشعارًا فوريًا فور اكتماله (حوالي 2-3 دقائق).',
    'withdrawal_not_found' => 'لم يتم العثور على طلب السحب',

    // Transfer
    'recipient_not_found' => 'المستخدم المستلم غير موجود',
    'transfer_success' => 'تم تحويل :amount فرنك بنجاح عبر :provider',
];
