<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_number';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'product_number',
        'category_id',
        'department_id',
        'manufacturer_id',
        'upc',
        'sku',
        'regular_price',
        'sale_price',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
