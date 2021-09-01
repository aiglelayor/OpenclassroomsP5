<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductModel::inRandomOrder()->take(12)->get();
        return view('products.index')->with('products', $products);
    }

    public function show($slug)
    {
        $product = ProductModel ::where('slug', $slug)->firstOrFail();

        return view('products.show')->with('product', $product);
    }
}
