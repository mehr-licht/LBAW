<?php

namespace App\Policies;

use App\User;
use App\Product;
use App\Auction;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProductPolicy
{
  use HandlesAuthorization;

  public function show(User $user, Product $product)
  {
    // Only a product owner can see it
    return Auth::id() === $product->id_owner;
  }

  public function edit(User $user, Product $product)
  {
    // Only a product owner can edit it
    return Auth::id() === $product->id_owner;
  }

  public function list(User $user)
  {
    // Any user can list its own products
    return Auth::check() && $product->state_product === 'active';
  }

  public function create(User $user)
  {
    // Any user can create a new product
    return Auth::check();
  }

  public function delete(User $user, Product $product)
  {
    // Only a product owner can delete it
    return Auth::id() === $product->id_owner;
  }

  public function bid(User $user, Product $product)
  {
    // Product owner can not bid on it
    return Auth::id() !== $product->id_owner;
  }

  public function report(User $user, Product $product)
  {
    // Product owner can not report it
    return Auth::id() !== $product->id_owner;
  }

  public function buy(User $user, Product $product)
  {
    // Product owner can not buy it
    return Auth::id() !== $product->id_owner && $product->state_product !== 'bought';
  }

  public function pay(User $user, Product $product)
  {
    // Product buyer can not pay for it
    return Auth::id() !== $product->transaction->buyer && $product->state_product === 'bought';
  }
}