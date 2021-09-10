<?php

namespace App\Http\Controllers;

use App\Models\Order;
use DateTime;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

//require 'vendor/autoload.php';

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cart::count() == 0) {
            return redirect()->route('products.index');
        }
        /* Stripe - société de paiement en ligne - a été utilisée ici */
        Stripe::setApiKey('sk_test_51JVImyLtSNYQRudIsA2NG7DKLyCl0WGMgjj98SaVOwICMCtgokOtvw5QCDMRDqCu6knpcPBH1BgTGOKAGW093XOv00ie0lGC0T');

        $intent = PaymentIntent::create([
            'amount' => round(Cart::total()),
            'currency' => 'eur'
            // 'metadata' => [
            //     Récupérer l'id d'utilisateur
            //     'userId' => Auth::user()->id
            // ]
        ]);


        $clientSecret = Arr::get($intent, 'client_secret');

        return view('checkout.index',[
            'clientSecret' => $clientSecret
        ]);
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
        $data = $request->json()->all();

        $order = new Order();
        
        $order->payment_intent_id = $data['paymentIntent']['id'];
        $order->amount = $data['paymentIntent']['amount'];

        $order->payment_created_at = (new DateTime())
            ->setTimestamp($data['paymentIntent']['created'])
            ->format('Y-m-d H:i:s');

        $products = [];
        $i = 0;

        foreach (Cart::content() as $product) {
            $products['product_' . $i][] = $product->model->title;
            $products['product_' . $i][] = $product->model->price;
            $products['product_' . $i][] = $product->qty;
            $i++;
        }

        $order->products = serialize($products);
        $order->user_id = 15;
        $order->save();

        if($data['paymentIntent']['status'] === 'succeeded')
        {
            Cart::destroy();
            Session::flash('success', 'Votre commande a été traitée avec succès.');
            return response()->json(['success' => 'Payment Intent Succeeded']);
        } else {
            return response()->json(['error' => 'Payment Intent Not Succeeded']);
        }
    }

    public function thankYou()
    {
       // Si success alors view('checkout.thankYou') sinon ('products.index') : redirect()->route('products.index')
        return Session::has('success') ? view('checkout.thankYou'): redirect()->route('products.index');
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
