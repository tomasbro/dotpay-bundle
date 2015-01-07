<?php

namespace Tomasbro\DotpayBundle;

final class TomasbroDotpayEvents
{

    const DOTPAY_CALLBACK_PRE_VALIDATION = 'tomasbro.dotpay.callback.pre_validation';

    const DOTPAY_CALLBACK_POST_VALIDATION = 'tomasbro.dotpay.callback.post_validation';
    
    const DOTPAY_PAYMENT_PREPARING = 'tomasbro.dotpay.payment.preparing';
    
    const DOTPAY_PAYMENT_VALIDATION_FAIL = 'tomasbro.dotpay.payment.validation_false';
    
    const DOTPAY_PAYMENT_VALIDATION_SUCCESS = 'tomasbro.dotpay.payment.validation_success';
    
}
