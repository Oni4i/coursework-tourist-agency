<?php

namespace App\Model\CRUD;

/**
 * Interface for showing fields in crud like template
 */
interface CRUDShowFieldsInterface
{
    public function getTableFields(): array;
}