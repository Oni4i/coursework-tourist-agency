<?php

namespace App\Entity\Voucher;

interface VoucherAdditionalInterface
{
    const ADDITION_AIRPORT_TICKETS  = 'additional.airport_tickets';
    const ADDITION_ALL_INCLUSIVE    = 'additional.all_inclusive';

    const ADDITIONS = [
        self::ADDITION_AIRPORT_TICKETS,
        self::ADDITION_ALL_INCLUSIVE
    ];

    const ADDITIONS_FOR_FORMS = [
        self::ADDITION_AIRPORT_TICKETS  => 'airport tickets',
        self::ADDITION_ALL_INCLUSIVE    => 'all inclusive',
    ];
}