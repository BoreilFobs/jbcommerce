@extends('layouts.web')
@section('content')
    <main class="main">
        <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
            <div class="container">
                <h1 class="page-title">Shopping Cart<span>Shop</span></h1>
            </div>
        </div>
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                </ol>
            </div>
        </nav>

        <div class="page-content">
            <div class="cart">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <table class="table table-cart table-mobile">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $offerIds = $carts->pluck('offer_id');
                                        $offers = App\Models\offers::whereIn('id', $offerIds)->get()->keyBy('id');
                                        $cartTotal = 0;
                                    @endphp

                                    @foreach ($carts as $cart)
                                        @php
                                            $offer = $offers[$cart->offer_id];
                                            $itemTotal = $offer->price * $cart->quantity;
                                            $cartTotal += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{ $offer->image_path }}" alt="Product image">
                                                        </a>
                                                    </figure>
                                                    <h3 class="product-title">
                                                        <a href="#">{{ $offer->name }}</a>
                                                    </h3>
                                                </div>
                                            </td>
                                            <td class="price-col">{{ $offer->price }} FCFA</td>
                                            <td class="quantity-col">
                                                <div class="cart-product-quantity">
                                                    <input type="number" class="form-control" value="{{ $cart->quantity }}"
                                                        min="1" max="10" step="1" data-decimals="0"
                                                        required>
                                                </div>
                                            </td>
                                            <td class="total-col">{{ $itemTotal }} FCFA</td>
                                            <td class="remove-col">
                                                <a href="{{ url('/cart/delete/' . $cart->id) }}" class="btn-remove"><i
                                                        class="icon-close"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <aside class="col-lg-3">
                            <div class="summary summary-cart">
                                <h3 class="summary-title">Cart Total</h3>
                                <table class="table table-summary">
                                    <tbody>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td>{{ $cartTotal }} FCFA</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="checkout.html" class="btn btn-outline-primary-2 btn-order btn-block">BUY</a>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
