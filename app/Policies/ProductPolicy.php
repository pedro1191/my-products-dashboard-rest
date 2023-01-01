<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization;

    const MINIMUN_PRODUCTS_ALLOWED = 3;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Product $product)
    {
        return $product->category->products()->count() > self::MINIMUN_PRODUCTS_ALLOWED
            ? Response::allow()
            : Response::deny('There must be at least ' . self::MINIMUN_PRODUCTS_ALLOWED . ' products per category registered in the system.');
    }
}
