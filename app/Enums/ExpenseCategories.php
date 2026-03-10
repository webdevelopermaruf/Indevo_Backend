<?php
namespace App\Enums;
enum ExpenseCategories: string
{
    case Health = 'health';
    case Food = 'food';
    case Transport = 'transport';
    case Bills = 'bills';
    case Entertainment = 'entertainment';
    case Other = 'other';
}
