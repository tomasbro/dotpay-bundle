<?php

namespace Tomasbro\DotpayBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DotpayMd5 extends Constraint
{

    public $message = 'Obliczony hash md5 "%calculatedMd5% różni się od otrzymanego w callback "%callbackMd5%"';

    public function validatedBy()
    {
        return 'tomasbro_dotpay_md5validator';
    }
}
