<?php

namespace App\Models;

use App\Services\QueryBuilders\CategoryQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'user_id', 
    ];

    // Relationship: Category has many Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }
}
