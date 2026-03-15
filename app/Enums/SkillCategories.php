<?php

namespace App\Enums;

enum SkillCategories: string
{
    case FINANCE = 'Financial Literacy';                    // Budgeting, saving, investments, managing expenses
    case TIME_MANAGEMENT = 'Time Management';               // Planning, prioritization, productivity techniques
    case HEALTH_WELLNESS = 'Health & Wellness';             // Nutrition, exercise, mental health, sleep
    case COMMUNICATION = 'Communication';                   // Public speaking, writing, negotiation, active listening
    case SELF_AWARENESS = 'Self-Awareness';                 // Emotional intelligence, mindfulness, stress management
    case CAREER = 'Career';                                 // Resume building, interviews, networking, career planning
    case LIVING = 'Living';                                 // Cooking, cleaning, organization, basic repairs
    case DIGITAL = 'Digital';                               // Software skills, internet safety, productivity tools
    case SOCIAL = 'Social';                                 // Networking, relationship building, conflict resolution
    case CRITICAL_THINKING = 'Critical Thinking';          // Problem solving, decision making, logical reasoning
    case Other = 'Other';                                  // Problem solving, decision making, logical reasoning
}
