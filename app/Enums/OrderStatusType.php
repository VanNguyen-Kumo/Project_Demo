<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WaitForConfirmation()
 * @method static static WaitingForTheGoods()
 * @method static static Delivering()
 * * @method static static Delivered()
 * * @method static static Cancelled()
 */
final class OrderStatusType extends Enum
{
    const WaitForConfirmation =   0;
    const WaitingForTheGoods =   1;
    const Delivering = 2;
    const Delivered = 3;
    const Cancelled = 4;
}
