<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
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
    const CATEGORIES_QUANTITY = 5;

    /**
     * Returns the category products
     */
    public function products() {
        return $this->hasMany('App\Product', 'category_id');
    }
}
