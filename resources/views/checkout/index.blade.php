@extends('layouts.web')
@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Paiement</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/cart') }}">Panier</a></li>
        <li class="breadcrumb-item active text-white">Paiement</li>
    </ol>
</div>

<!-- Checkout Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            
            <div class="row g-5">
                <!-- Left Column - Forms -->
                <div class="col-lg-7 wow fadeInLeft" data-wow-delay="0.1s">
                    <!-- Shipping Information -->
                    <div class="bg-light rounded p-4 mb-4">
                        <h4 class="mb-4">
                            <i class="fas fa-shipping-fast text-primary me-2"></i>
                            Informations de Livraison
                        </h4>
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="shipping_name" class="form-label">Nom Complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" 
                                       id="shipping_name" name="shipping_name" 
                                       value="{{ old('shipping_name', Auth::user()->name) }}" required>
                                @error('shipping_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="shipping_phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror" 
                                       id="shipping_phone" name="shipping_phone" 
                                       value="{{ old('shipping_phone', Auth::user()->phone) }}" 
                                       placeholder="+237 6XX XXX XXX" required>
                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="shipping_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('shipping_email') is-invalid @enderror" 
                                       id="shipping_email" name="shipping_email" 
                                       value="{{ old('shipping_email', Auth::user()->email ?? '') }}" 
                                       placeholder="email@example.com" required>
                                @error('shipping_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="shipping_address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                          id="shipping_address" name="shipping_address" 
                                          rows="2" placeholder="Numéro, rue, quartier..." required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="shipping_city" class="form-label">Ville <span class="text-danger">*</span></label>
                                <select class="form-select @error('shipping_city') is-invalid @enderror" 
                                        id="shipping_city" name="shipping_city" required>
                                    <option value="">Sélectionner une ville</option>
                                    <option value="Douala" {{ old('shipping_city') == 'Douala' ? 'selected' : '' }}>Douala</option>
                                    <option value="Yaoundé" {{ old('shipping_city') == 'Yaoundé' ? 'selected' : '' }}>Yaoundé</option>
                                    <option value="Bafoussam" {{ old('shipping_city') == 'Bafoussam' ? 'selected' : '' }}>Bafoussam</option>
                                    <option value="Bamenda" {{ old('shipping_city') == 'Bamenda' ? 'selected' : '' }}>Bamenda</option>
                                    <option value="Garoua" {{ old('shipping_city') == 'Garoua' ? 'selected' : '' }}>Garoua</option>
                                    <option value="Maroua" {{ old('shipping_city') == 'Maroua' ? 'selected' : '' }}>Maroua</option>
                                    <option value="Ngaoundéré" {{ old('shipping_city') == 'Ngaoundéré' ? 'selected' : '' }}>Ngaoundéré</option>
                                    <option value="Bertoua" {{ old('shipping_city') == 'Bertoua' ? 'selected' : '' }}>Bertoua</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="shipping_region" class="form-label">Région <span class="text-danger">*</span></label>
                                <select class="form-select @error('shipping_region') is-invalid @enderror" 
                                        id="shipping_region" name="shipping_region" required>
                                    <option value="">Sélectionner une région</option>
                                    <option value="Adamaoua" {{ old('shipping_region') == 'Adamaoua' ? 'selected' : '' }}>Adamaoua</option>
                                    <option value="Centre" {{ old('shipping_region') == 'Centre' ? 'selected' : '' }}>Centre</option>
                                    <option value="Est" {{ old('shipping_region') == 'Est' ? 'selected' : '' }}>Est</option>
                                    <option value="Extrême-Nord" {{ old('shipping_region') == 'Extrême-Nord' ? 'selected' : '' }}>Extrême-Nord</option>
                                    <option value="Littoral" {{ old('shipping_region') == 'Littoral' ? 'selected' : '' }}>Littoral</option>
                                    <option value="Nord" {{ old('shipping_region') == 'Nord' ? 'selected' : '' }}>Nord</option>
                                    <option value="Nord-Ouest" {{ old('shipping_region') == 'Nord-Ouest' ? 'selected' : '' }}>Nord-Ouest</option>
                                    <option value="Ouest" {{ old('shipping_region') == 'Ouest' ? 'selected' : '' }}>Ouest</option>
                                    <option value="Sud" {{ old('shipping_region') == 'Sud' ? 'selected' : '' }}>Sud</option>
                                    <option value="Sud-Ouest" {{ old('shipping_region') == 'Sud-Ouest' ? 'selected' : '' }}>Sud-Ouest</option>
                                </select>
                                @error('shipping_region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="shipping_postal_code" class="form-label">Code Postal (Optionnel)</label>
                                <input type="text" class="form-control @error('shipping_postal_code') is-invalid @enderror" 
                                       id="shipping_postal_code" name="shipping_postal_code" 
                                       value="{{ old('shipping_postal_code') }}">
                                @error('shipping_postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-light rounded p-4 mb-4">
                        <h4 class="mb-4">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            Méthode de Paiement
                        </h4>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="payment-option border rounded p-3 h-100 {{ old('payment_method') == 'cash_on_delivery' || !old('payment_method') ? 'border-primary bg-white' : '' }}" 
                                     data-payment="cash_on_delivery" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="cash_on_delivery" value="cash_on_delivery" 
                                               {{ old('payment_method') == 'cash_on_delivery' || !old('payment_method') ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="cash_on_delivery">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave fa-2x text-success me-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Paiement à la Livraison</h6>
                                                    <small class="text-muted">Payez en espèces</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="payment-option border rounded p-3 h-100 {{ old('payment_method') == 'mobile_money_mtn' ? 'border-primary bg-white' : '' }}" 
                                     data-payment="mobile_money_mtn" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="mobile_money_mtn" value="mobile_money_mtn"
                                               {{ old('payment_method') == 'mobile_money_mtn' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="mobile_money_mtn">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-mobile-alt fa-2x text-warning me-3"></i>
                                                <div>
                                                    <h6 class="mb-0">MTN Mobile Money</h6>
                                                    <small class="text-muted">Paiement mobile</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="payment-option border rounded p-3 h-100 {{ old('payment_method') == 'mobile_money_orange' ? 'border-primary bg-white' : '' }}" 
                                     data-payment="mobile_money_orange" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="mobile_money_orange" value="mobile_money_orange"
                                               {{ old('payment_method') == 'mobile_money_orange' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="mobile_money_orange">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-mobile-alt fa-2x text-danger me-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Orange Money</h6>
                                                    <small class="text-muted">Paiement mobile</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="payment-option border rounded p-3 h-100 {{ old('payment_method') == 'bank_transfer' ? 'border-primary bg-white' : '' }}" 
                                     data-payment="bank_transfer" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="bank_transfer" value="bank_transfer"
                                               {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="bank_transfer">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-university fa-2x text-info me-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Virement Bancaire</h6>
                                                    <small class="text-muted">Transfert bancaire</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @error('payment_method')
                            <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                        @enderror
                        
                        <!-- Mobile Money Phone (Hidden by default) -->
                        <div id="mobileMoneyPhone" class="mt-3" style="display: none;">
                            <label for="payment_phone" class="form-label">Numéro Mobile Money <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('payment_phone') is-invalid @enderror" 
                                   id="payment_phone" name="payment_phone" 
                                   value="{{ old('payment_phone') }}" 
                                   placeholder="+237 6XX XXX XXX">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Vous recevrez une demande de paiement sur ce numéro
                            </small>
                            @error('payment_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="bg-light rounded p-4">
                        <h4 class="mb-4">
                            <i class="fas fa-comment-dots text-primary me-2"></i>
                            Notes Additionnelles (Optionnel)
                        </h4>
                        
                        <textarea class="form-control @error('customer_notes') is-invalid @enderror" 
                                  id="customer_notes" name="customer_notes" 
                                  rows="3" 
                                  placeholder="Instructions de livraison, demandes spéciales, etc.">{{ old('customer_notes') }}</textarea>
                        @error('customer_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-5 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="bg-light rounded p-4 sticky-top" style="top: 100px;">
                        <h4 class="mb-4">
                            <i class="fas fa-shopping-cart text-primary me-2"></i>
                            Résumé de la Commande
                        </h4>
                        
                        <!-- Cart Items -->
                        <div class="cart-items mb-4" style="max-height: 300px; overflow-y: auto;">
                            @foreach($cartItems as $item)
                                @if($item->offer)
                                    <div class="d-flex align-items-center mb-3 p-2 bg-white rounded">
                                        @php
                                            $images = is_string($item->offer->images) ? json_decode($item->offer->images, true) : $item->offer->images;
                                            $firstImage = $images && is_array($images) && count($images) > 0 
                                                ? '/storage/offer_img/product' . $item->offer->id . "/" . $images[0]
                                                : '/img/default-product.jpg';
                                        @endphp
                                        <img src="{{ asset($firstImage) }}" alt="{{ $item->offer->name }}" 
                                             class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ Str::limit($item->offer->name, 30) }}</h6>
                                            <small class="text-muted">Quantité: {{ $item->quantity }}</small>
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $price = $item->offer->discount_percentage > 0 
                                                    ? $item->offer->discounted_price 
                                                    : $item->offer->price;
                                                $itemTotal = $price * $item->quantity;
                                            @endphp
                                            <p class="mb-0 fw-bold">{{ number_format($itemTotal, 0, '.', ',') }} F</p>
                                            @if($item->offer->discount_percentage > 0)
                                                <small class="text-muted"><del>{{ number_format($item->offer->price * $item->quantity, 0, '.', ',') }} F</del></small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <hr>

                        <!-- Price Breakdown -->
                        <div class="price-breakdown mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total:</span>
                                <span class="fw-bold">{{ number_format($subtotal, 0, '.', ',') }} FCFA</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Frais de Livraison:</span>
                                <span class="fw-bold">{{ number_format($shippingCost, 0, '.', ',') }} FCFA</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">Total:</h5>
                                <h5 class="mb-0 text-primary">{{ number_format($total, 0, '.', ',') }} FCFA</h5>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" class="text-primary">Termes et Conditions</a> <span class="text-danger">*</span>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary rounded-pill w-100 py-3" id="submitBtn">
                            <i class="fas fa-lock me-2"></i>
                            Passer la Commande
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Paiement sécurisé
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentOptions = document.querySelectorAll('.payment-option');
    const mobileMoneyPhone = document.getElementById('mobileMoneyPhone');
    const paymentPhoneInput = document.getElementById('payment_phone');
    
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update UI
            paymentOptions.forEach(opt => {
                opt.classList.remove('border-primary', 'bg-white');
            });
            this.classList.add('border-primary', 'bg-white');
            
            // Show/hide mobile money phone input
            const paymentMethod = radio.value;
            if (paymentMethod === 'mobile_money_mtn' || paymentMethod === 'mobile_money_orange') {
                mobileMoneyPhone.style.display = 'block';
                paymentPhoneInput.required = true;
            } else {
                mobileMoneyPhone.style.display = 'none';
                paymentPhoneInput.required = false;
            }
        });
    });
    
    // Initialize on page load
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
    if (selectedPayment) {
        const paymentMethod = selectedPayment.value;
        if (paymentMethod === 'mobile_money_mtn' || paymentMethod === 'mobile_money_orange') {
            mobileMoneyPhone.style.display = 'block';
            paymentPhoneInput.required = true;
        }
    }
    
    // Form validation
    const checkoutForm = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitBtn');
    
    checkoutForm.addEventListener('submit', function(e) {
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Veuillez accepter les termes et conditions.');
            termsCheckbox.focus();
            return false;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
    });
});
</script>
@endpush

<style>
.payment-option {
    transition: all 0.3s ease;
}

.payment-option:hover {
    border-color: #f28b00 !important;
    background-color: #fff !important;
}

.sticky-top {
    position: sticky;
}

@media (max-width: 991px) {
    .sticky-top {
        position: relative !important;
        top: 0 !important;
    }
}

/* Mobile responsive */
@media (max-width: 576px) {
    .payment-option {
        margin-bottom: 10px;
    }
    
    .cart-items {
        max-height: 200px !important;
    }
}
</style>

@endsection
