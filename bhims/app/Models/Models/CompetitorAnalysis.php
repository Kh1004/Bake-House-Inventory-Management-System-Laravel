<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitorAnalysis extends Model
{
    protected $table = 'competitor_analyses';
    protected $fillable = [
        'competitor_name',
        'product_name',
        'price',
        'currency',
        'location',
        'notes',
        'analysis_date'
    ];
}
