<?php

namespace App\Helpers;

class GradeHelper
{
    public static function calculateGradeLetter($percentage)
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }
    
    public static function getGradeColor($grade)
    {
        $colors = [
            'A' => 'bg-green-100 text-green-800',
            'B' => 'bg-blue-100 text-blue-800',
            'C' => 'bg-yellow-100 text-yellow-800',
            'D' => 'bg-orange-100 text-orange-800',
            'F' => 'bg-red-100 text-red-800',
        ];
        
        return $colors[$grade] ?? 'bg-gray-100 text-gray-800';
    }
    
    public static function getPercentageColor($percentage)
    {
        if ($percentage >= 90) return 'bg-green-100 text-green-800';
        if ($percentage >= 80) return 'bg-blue-100 text-blue-800';
        if ($percentage >= 70) return 'bg-yellow-100 text-yellow-800';
        if ($percentage >= 60) return 'bg-orange-100 text-orange-800';
        return 'bg-red-100 text-red-800';
    }
}