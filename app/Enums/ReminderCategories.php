<?php
namespace App\Enums;
enum ReminderCategories: string
{
    case Work        = 'work';
    case Personal    = 'personal';
    case Health      = 'health';
    case Learning    = 'learning';
    case Shopping    = 'shopping';
    case Social      = 'social';
    case Other       = 'other';
}
