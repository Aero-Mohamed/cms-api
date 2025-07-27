<?php

namespace App\Enums;

enum RelationshipTypeEnum: string
{
    case ONE_TO_ONE = '1:1';
    case ONE_TO_MANY = '1:N';
    case MANY_TO_MANY = 'N:N';
}
