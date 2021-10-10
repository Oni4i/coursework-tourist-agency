<?php

namespace App\Model\CRUD;

interface CRUDShowFieldsInterface
{
    public function getTableFields(): array;
}