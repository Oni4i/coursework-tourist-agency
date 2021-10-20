<?php

namespace App\Service;

use App\Entity\Voucher\VoucherAdditionalInterface;

class VoucherManager
{
    /**
     * Return combined additional for forms
     *
     * @return array
     */
    public function getAdditionalChoices(): array
    {
        return \array_combine(
            \array_values(VoucherAdditionalInterface::ADDITIONS_FOR_FORMS),
            VoucherAdditionalInterface::ADDITIONS_FOR_FORMS
        );
    }
}