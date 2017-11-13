<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

/**
 * Class Product
 * @package App
 */
class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';
    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'product_name', 'product_price', 'product_stock'
    ];

    
    /**
     * fetch all products which have stock
     * @return string
     */
    public function getAllProducts()
    {
        try {
            // fetching posts of this user
            $postings = Product::where('product_stock', '>', 0)
                ->get();

            return $postings;
        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }
   
   
    /**
     * fetch all products for search key
     * @param $searchKey
     * @return string
     */
    public function getSearchProducts($searchKey)
    {
        try {
            // fetching posts of this user
            $postings = Product::where('product_name', 'like', '%' . $searchKey . '%')
                ->get()
                ->take(1)
                ->toArray();

            return $postings;
        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }
    
    
    // we need more methods to save product in future 
}
