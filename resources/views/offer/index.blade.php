@extends('layouts.app')
@section('title', 'Products')

@section('content')

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="d-grid flex-wrap ">
        <div class="row">
            @foreach ($offers as $offer)
                <div class="product col-3 mx-2">
                    <figure class="product-media">

                        <img src={{ $offer->image_path }} alt="Product image" class="product-image">



                        <div class="product-action product-action-dark">
                            <a href={{ url('/offers/delete/' . $offer->id) }} class="btn-product btn-danger"
                                title="deletet"><span>Delete
                                    product</span></a>
                            <a href={{ url('/offers/update/' . $offer->id) }} class="btn-product btn-warning"
                                title="Quick view"><span>Update</span></a>
                        </div><!-- End .product-action -->
                    </figure><!-- End .product-media -->

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">{{ $offer->cateory }}</a>
                        </div><!-- End .product-cat -->
                        <h3 class="product-title"><a href="product.html">{{ $offer->name }}</a></h3>
                        <!-- End .product-title -->
                        <div class="product-price">
                            <span class="out-price">{{ $offer->price }}FCFA</span>
                            @if ($offer->instock)
                                <p></p>
                            @else
                                <span class="out-text">Out of Stock</span>
                            @endif
                        </div><!-- End .product-price -->
                    </div><!-- End .product-body -->
                </div>
            @endforeach

        </div>
    </div>
@endsection
