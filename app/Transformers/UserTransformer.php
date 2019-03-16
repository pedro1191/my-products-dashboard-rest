<?php
namespace App\Transformers;

use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
    public function transform(\App\User $user)
    {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'link' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.users.show', ['id' => $user->id]),
        ];
    }
}
