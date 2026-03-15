<?php
namespace App\Enums;

enum GoalCategories: string
{
    case Health      = 'health';
    case Learning    = 'learning';
    case Personal    = 'personal';
    case Finance    = 'finance';
    case Career      = 'career';
    case Other       = 'other';
}
