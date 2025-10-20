@extends('layouts.web')
@section('content')
    <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Panier</h1>
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="{{route("home")}}">Accueil</a></li>
            {{-- <li class="breadcrumb-item"><a href="#">Pages</a></li> --}}
            <li class="breadcrumb-item active text-white">Panier</li>
            </ol>
        </div>
        <!-- En-tête de page unique Fin -->

        <!-- Page Panier Début -->
        <div class="container-fluid py-5">
            <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Modèle</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Total</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                    <tr data-cart-id="{{ $cart->id }}">
                    <th scope="row">
                        <p class="mb-0 py-4">{{ $cart->offer->name}}</p>
                    </th>
                    <td>
                        <p class="mb-0 py-4">G{{rand(1000,9999)}}</p>
                    </td>
                    <td>
                        <p class="mb-0 py-4">{{ number_format($cart->offer->price, 0, '.', ',') }}FCFA</p>
                    </td>
                    <td>
                        <div class="input-group quantity py-4" style="width: 100px;">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" >
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control form-control-sm text-center border-0" value="{{$cart->quantity}}">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                            <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        </div>
                    </td>
                    <td>
                        <p class="mb-0 py-4">2.99 $</p>
                    </td>
                     <td class="py-4">
                        <a href="{{ url('/cart/delete/' . $cart->id) }}" class="btn btn-md rounded-circle bg-light border" >
                        <i class="fa fa-times text-danger"></i>
                        </a>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            {{-- <div class="mt-5">
                <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Code Promo">
                <button class="btn btn-primary rounded-pill px-4 py-3" type="button">Appliquer le code</button>
            </div> --}}
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                    <h1 class="display-6 mb-4">Total <span class="fw-normal">du Panier</span></h1>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0 me-4">Sous-total :</h5>
                        <p class="mb-0 cart-subtotal">0 FCFA</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0 me-4">Livraison</h5>
                        <div>
                        <p class="mb-0 cart-livraison">1000 FCFA</p>
                        </div>
                    </div>
                    <p class="mb-0 text-end">Livraison au Cameroun.</p>
                    </div>
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                    <h5 class="mb-0 ps-4 me-4">Total général</h5>
                    <p class="mb-0 pe-4 cart-total-general">0 FCFA</p>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary rounded-pill px-4 py-3 text-uppercase mb-4 ms-4">
                        <i class="fas fa-lock me-2"></i>Procéder au Paiement
                    </a>
                </div>
                </div>
            </div>
            </div>
        </div>
    <!-- Cart Page End -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            document.querySelectorAll('.quantity').forEach(function(group) {
                const minusBtn = group.querySelector('.btn-minus');
                const plusBtn = group.querySelector('.btn-plus');
                const qtyInput = group.querySelector('input');
                const row = group.closest('tr');
                const priceText = row.querySelector('td:nth-child(3) p');
                const totalText = row.querySelector('td:nth-child(5) p');

                function getPrice() {
                    let priceStr = priceText.textContent.replace(/[^\d.,]/g, '').replace(',', '');
                    return parseFloat(priceStr) || 0;
                }

                function updateTotal() {
                    let qty = parseInt(qtyInput.value) || 1;
                    let price = getPrice();
                    let total = price * qty;
                    totalText.textContent = priceText.textContent.includes('FCFA')
                        ? `${total.toLocaleString()}FCFA`
                        : `${total.toFixed(2)} $`;
                }

                minusBtn.addEventListener('click', function() {
                    let qty = parseInt(qtyInput.value) || 1;
                    if (qty >= 1) qtyInput.value = qty;
                    updateTotal();
                });

                plusBtn.addEventListener('click', function() {
                    let qty = parseInt(qtyInput.value) || 1;
                    qtyInput.value = qty ;
                    updateTotal();
                });

                qtyInput.addEventListener('input', updateTotal);

                updateTotal();
            });
        </script>
@endsection
