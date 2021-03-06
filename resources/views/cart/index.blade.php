@extends('layouts.master')

@section('extra-meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @if (\Gloudemans\Shoppingcart\Facades\Cart::count() > 0)
        <div class="px-4 px-lg-0">
            <div class="pb-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">

                            <!-- Shopping cart table -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="p-2 px-3 text-uppercase">Produit</div>
                                            </th>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="py-2 text-uppercase">Prix</div>
                                            </th>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="py-2 text-uppercase">Quantité</div>
                                            </th>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="py-2 text-uppercase">Supprimer</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::content() as $product)
                                        <tr>
                                            <th scope="row" class="border-0">
                                                <div class="p-2">
                                                    <img src="{{ $product->model->image }}" alt="" width="70" class="img-fluid rounded shadow-sm">
                                                    <div class="ml-3 d-inline-block align-middle">
                                                        <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle">{{ $product->model->title }}</a></h5><span class="text-muted font-weight-normal font-italic d-block">Category: Watches</span>
                                                    </div>
                                                </div>
                                            </th>
                                            <td class="border-0 align-middle"><strong>{{ getPrice($product->subtotal()) }}</strong></td>
                                            <td class="border-0 align-middle">
                                                <select name="qty" id="qty" data-id="{{ $product->rowId }}" class="form-select">
                                                    @for ($i = 1; $i < 7; $i++)
                                                        <option value="{{ $i }}" {{ $i == $product ->qty ? 'selected' : ''}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td class="border-0 align-middle">
                                                <form action="{{ route('cart.remove', $product->rowId) }}" method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="text-dark"><i class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <!-- End -->
                        </div>
                    </div>

                    <div class="row py-5 p-4 bg-white rounded shadow-sm">
                        <div class="col-lg-6">
                            <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Code Promo</div>
                            <div class="p-4">
                                <p class="font-italic mb-4">Si vous avez un code promo, veuillez le rentrer ci-dessous.</p>
                                <div class="input-group mb-4 border rounded-pill p-2">
                                    <input type="text" placeholder="Apply coupon" aria-describedby="button-addon3" class="form-control border-0">
                                    <div class="input-group-append border-0">
                                        <button id="button-addon3" type="button" class="btn btn-dark px-4 rounded-pill"><i class="fa fa-gift mr-2"></i>Appliquer le code promo</button>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Remarques</div>
                            <div class="p-4">
                                <p class="font-italic mb-4">Si vous avez des remarques concernant votre commande, veuillez les écrire ci-dessous. Nous restons à votre écoute.</p>
                                <textarea name="" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Détails de la commande</div>
                            <div class="p-4">
                                <p class="font-italic mb-4">La livraison est calculée en fonction des critères de livraison que vous avez demandé.</p>
                                <ul class="list-unstyled mb-4">
                                    <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Sous-total</strong><strong>{{ getPrice(\Gloudemans\Shoppingcart\Facades\Cart::subtotal()) }}</strong></li>
                                    <!-- <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Livraison</strong><strong>$10.00</strong></li> -->
                                    <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Taxe</strong><strong>{{ getPrice(\Gloudemans\Shoppingcart\Facades\Cart::tax()) }}</strong></li>
                                    <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                                        <h5 class="font-weight-bold">{{ getPrice(\Gloudemans\Shoppingcart\Facades\Cart::total()) }}</h5>
                                    </li>
                                </ul><a href="{{ route('checkout.index') }}" class="btn btn-dark rounded-pill py-2 btn-block">Passer la commande</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="col-md-12">
        <p>Votre panier est vide.</p>
    </div>
    @endif
@endsection

@section('extra-js')
<script>
    var selects = document.querySelectorAll('#qty');
    Array.from(selects).forEach((element) => {
        element.addEventListener('change', function (){
            var rowId = this.getAttribute('data-id');
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(
                `/panier/${rowId}`,
                {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token
                    },
                    method: 'PATCH',
                    body: JSON.stringify({
                        qty: this.value
                    })
                }
            ).then((data) => {
                console.log(data);
                location.reload();
            }).catch((error) => {
                console.log(error)
            })
        })
    });
</script>
@endsection