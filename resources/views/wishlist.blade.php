@extends('layouts.web')
@section('content')
    <main class="main">
        <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
            <div class="container">
                <h1 class="page-title">Wishlist<span>Shop</span></h1>
            </div><!-- End .container -->
        </div><!-- End .page-header -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href={{ url('/') }}>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->

        <div class="page-content">
            <div class="container">
                <table class="table table-wishlist table-mobile">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($wishes as $wish)
                            <tr>
                                <td class="product-col">
                                    <div class="product">
                                        <figure class="product-media">
                                            <a href="#">
                                                <img src={{ App\Models\offers::findOrFail($wish->offer_id)->image_path }}
                                                    alt="Product image">
                                            </a>
                                        </figure>

                                        <h3 class="product-title">
                                            <a href="#">{{ App\Models\offers::findOrFail($wish->offer_id)->name }}</a>
                                        </h3><!-- End .product-title -->
                                    </div><!-- End .product -->
                                </td>
                                <td class="price-col">{{ App\Models\offers::findOrFail($wish->offer_id)->price }}FCFA</td>
                                <td class="stock-col">
                                    @if (App\Models\offers::findOrFail($wish->offer_id)->instock)
                                        <span class="in-stock">In stock</span>
                                    @else
                                        <span class="out-of-stock">Out of stock</span>
                                    @endif
                                </td>
                                <td class="action-col">
                                    <div class="dropdown">
                                        <a href={{ url('/cart/' . $wish->offer_id . '/create/' . $wish->user_id) }}
                                            class="btn btn-block btn-outline-primary-2" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-list-alt"></i>Add to cart
                                        </a>

                                    </div>
                                </td>
                                <td class="remove-col"><a href={{ url('wish-list/delete/' . $wish->id) }}
                                        class="btn-remove"><i class="icon-close"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table><!-- End .table table-wishlist -->
                <div class="wishlist-share">
                    <div class="social-icons social-icons-sm mb-2">
                        <label class="social-label">Share on:</label>
                        <a href="#" class="social-icon" title="Facebook" target="_blank"><i
                                class="icon-facebook-f"></i></a>
                        <a href="#" class="social-icon" title="Twitter" target="_blank"><i
                                class="icon-twitter"></i></a>
                        <a href="#" class="social-icon" title="Instagram" target="_blank"><i
                                class="icon-instagram"></i></a>
                        <a href="#" class="social-icon" title="Youtube" target="_blank"><i
                                class="icon-youtube"></i></a>
                        <a href="#" class="social-icon" title="Pinterest" target="_blank"><i
                                class="icon-pinterest"></i></a>
                    </div><!-- End .soial-icons -->
                </div><!-- End .wishlist-share -->
            </div><!-- End .container -->
        </div><!-- End .page-content -->
    </main><!-- End .main -->
@endsection
