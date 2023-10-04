<?php

namespace App\Helpers\Services;

use App\Models\Product;
use App\Models\Roles;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

trait ProductService
{
    public function getProductsForUser(Authenticatable $user): Model
    {
        $products = Product::query();
        $role = Roles::query()->where('id', $user->id)->first()->name;

        if ($role == 'admin') {
            $products = $products->get();
        } elseif ($role == 'user') {
            $products = $products->where('user_id', $user->id)->get();
        }

        return $products;
    }
}
