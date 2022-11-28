<?php declare (strict_types = 1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static annual()
 * @method static static comp()
 * @method static static personal()
 * @method static static offical()
 * @method static static marriage()
 * @method static static funeral()
 * @method static static menstrul()
 * @method static static maternity()
 * @method static static paternity()
 */
final class LeaveType extends Enum
{
    const annual = '特休';
    const comp = '補休';
    const personal = '事假';
    const offical = '公假';
    const marriage = '婚假';
    const funeral = '喪假';
    const menstrul = '生理假';
    const maternity = '產假';
    const paternity = '陪產假';
}
