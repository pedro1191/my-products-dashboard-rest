<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image', 'category_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The number of records to be created while seeding the database
     */
    const PRODUCTS_PER_CATEGORY_QUANTITY = 5;

    /**
     * Returns the product category
     */
    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }
}
