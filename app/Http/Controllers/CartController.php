<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $duplicata = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $rowId === $request->product_id;
        });

        if($duplicata->isNotEmpty())
        {
            return redirect()->route('products.index')->with('success', 'Le produit a déjà été ajouté.');
        }else {
            $product = ProductModel::find($request->product_id);

            Cart::add($product->id, $product->title, 1, $product->price)
                ->associate('App\Models\ProductModel');

            return redirect()->route('products.index')->with('success', 'Le produit a été ajouté au panier avec succès.'); 
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $rowId)
    {
        $data = $request->json()->all();

        // On ajoute un validator pour empêcher le changement de qté dans inspecteur
        $validator = Validator::make($request->all(),
        [
            'qty' => 'required|numeric|between:1,6'
        ]);

        if($validator->fails()){
            Session::flash('danger', 'La quantité du produit ne doit pas dépasser 6.');
            return response()->json(['error' => 'Cart quantity has not been updated.']);
        }

        Cart::update($rowId, $data['qty']);

        Session::flash('success', 'La quantité de ce produit est maintenant à ' . $data['qty'] . '.');

        return response()->json(['success' => 'Cart quantity has been updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($rowId)
    {
        Cart::remove($rowId);

        return back()->with('success', 'Le produit a été supprimé.');
        
    }
}
