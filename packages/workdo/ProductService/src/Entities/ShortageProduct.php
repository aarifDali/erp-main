<?php

namespace Workdo\ProductService\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workdo\ProductService\Entities\ProductService;

class ShortageProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_service_id'];

    // Relationship to ProductService
    public function productService()
    {
        return $this->belongsTo(ProductService::class, 'product_service_id');
    }
}
