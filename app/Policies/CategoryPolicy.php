<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    use HandlesAuthorization;

    const MINIMUN_CATEGORIES_ALLOWED = 3;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Category $category)
    {
        return Category::count() > self::MINIMUN_CATEGORIES_ALLOWED
            ? Response::allow()
            : Response::deny('There must be at least ' . self::MINIMUN_CATEGORIES_ALLOWED . ' categories registered in the system.');
    }
}
