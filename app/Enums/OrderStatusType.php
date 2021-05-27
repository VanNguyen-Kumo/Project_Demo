<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatusType extends Enum
{
    const WaitForConfirmation =   0;
    const WaitingForTheGoods =   1;
    const Delivering = 2;
    const Delivered = 3;
    const Cancelled = 4;
}
