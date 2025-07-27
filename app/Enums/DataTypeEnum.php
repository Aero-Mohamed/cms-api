<?php

namespace App\Enums;

enum DataTypeEnum: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case DATE = 'date';
    case BOOLEAN = 'boolean';
}
