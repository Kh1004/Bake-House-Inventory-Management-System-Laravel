<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use App\Models\User;

class PredictionFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'prediction_method',
        'prediction_date',
        'prediction_data',
        'actual_data',
        'accuracy_rating',
        'user_notes'
    ];

    protected $casts = [
        'prediction_date' => 'date',
        'prediction_data' => 'array',
        'actual_data' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
