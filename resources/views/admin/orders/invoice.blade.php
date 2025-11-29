<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $order->order_number }} - JB Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .invoice-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
        }

        .company-logo {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
        }

        .invoice-info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }

        .total-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .grand-total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .footer-note {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .invoice-container {
                margin: 10px;
            }
            .invoice-header {
                padding: 20px;
            }
            .company-logo {
                font-size: 1.5rem;
            }
            .invoice-title {
                font-size: 1.2rem;
            }
            .table {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print text-center py-3">
        <button onclick="window.print()" class="btn btn-primary btn-lg">
            <i class="fas fa-print me-2"></i>Imprimer la Facture
        </button>
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-lg ms-2">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Invoice Container -->
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="company-logo">
                        <i class="fas fa-shopping-bag me-2"></i>JB Shop
                    </div>
                    <p class="mb-0">Électronique & High-Tech</p>
                    <p class="mb-0 small">Bafoussam, Cameroun</p>
                    <p class="mb-0 small"><i class="fas fa-phone me-1"></i>+237 657 528 859 / +237 693 662 715</p>
                    <p class="mb-0 small"><i class="fas fa-envelope me-1"></i>brayeljunior8@gmail.com</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="invoice-title">FACTURE</div>
                    <p class="mb-1"><strong>N° {{ $order->order_number }}</strong></p>
                    <p class="mb-1">Date: {{ $order->created_at->format('d/m/Y') }}</p>
                    <p class="mb-0">{!! $order->status_badge !!}</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-4">
            <!-- Customer & Order Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="invoice-info-box">
                        <h5 class="mb-3"><i class="fas fa-user me-2 text-primary"></i>Facturer à:</h5>
                        <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                        <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $order->shipping_phone }}</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $order->shipping_email }}</p>
                        <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $order->shipping_address }}</p>
                        <p class="mb-0">{{ $order->shipping_city }}, {{ $order->shipping_region }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="invoice-info-box">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Détails de la Commande:</h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1 small text-muted">Date de commande:</p>
                                <p class="mb-2"><strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1 small text-muted">Statut paiement:</p>
                                <p class="mb-2">{!! $order->payment_status_badge !!}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1 small text-muted">Méthode de paiement:</p>
                                <p class="mb-2"><strong>{!! $order->payment_method_name !!}</strong></p>
                            </div>
                            @if($order->tracking_number)
                                <div class="col-6">
                                    <p class="mb-1 small text-muted">N° de suivi:</p>
                                    <p class="mb-2"><strong>{{ $order->tracking_number }}</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->customer_notes)
                <div class="alert alert-info mb-4">
                    <h6 class="mb-2"><i class="fas fa-comment-dots me-2"></i>Notes du client:</h6>
                    <p class="mb-0">{{ $order->customer_notes }}</p>
                </div>
            @endif

            <!-- Items Table -->
            <h5 class="mb-3"><i class="fas fa-box me-2 text-primary"></i>Articles Commandés</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Produit</th>
                            <th class="text-center" style="width: 100px;">Quantité</th>
                            <th class="text-end" style="width: 130px;">Prix Unitaire</th>
                            <th class="text-end" style="width: 100px;">Remise</th>
                            <th class="text-end" style="width: 130px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product_name }}</strong>
                                    @if($item->discount_percentage > 0)
                                        <br><span class="badge bg-danger">-{{ $item->discount_percentage }}%</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                <td class="text-end">
                                    @if($item->discount_amount > 0)
                                        <span class="text-danger">-{{ number_format($item->discount_amount, 0, ',', ' ') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end"><strong>{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="row justify-content-end mt-4">
                <div class="col-md-5">
                    <div class="total-section">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <strong>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Frais de livraison/retrait:</span>
                            <strong>{{ number_format($order->shipping_cost, 0, ',', ' ') }} FCFA</strong>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-danger">
                                <span>Remise totale:</span>
                                <strong>-{{ number_format($order->discount_amount, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        @endif
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5"><strong>TOTAL À PAYER:</strong></span>
                            <span class="grand-total">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="invoice-info-box">
                        <h6 class="mb-3"><i class="fas fa-credit-card me-2 text-primary"></i>Informations de Paiement</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1 small text-muted">Méthode de paiement:</p>
                                <p class="mb-0"><strong>{!! $order->payment_method_name !!}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 small text-muted">Statut du paiement:</p>
                                <p class="mb-0">{!! $order->payment_status_badge !!}</p>
                            </div>
                            @if($order->payment_reference)
                                <div class="col-md-4">
                                    <p class="mb-1 small text-muted">Référence de paiement:</p>
                                    <p class="mb-0"><strong>{{ $order->payment_reference }}</strong></p>
                                </div>
                            @endif
                            @if($order->payment_phone)
                                <div class="col-md-4 mt-2">
                                    <p class="mb-1 small text-muted">Téléphone Mobile Money:</p>
                                    <p class="mb-0"><strong>{{ $order->payment_phone }}</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            @if($order->paid_at || $order->shipped_at || $order->delivered_at || $order->cancelled_at)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="invoice-info-box">
                            <h6 class="mb-3"><i class="fas fa-clock me-2 text-primary"></i>Historique de la Commande</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="mb-1 small text-muted"><i class="fas fa-plus-circle me-1"></i>Créée:</p>
                                    <p class="mb-0 small"><strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></p>
                                </div>
                                @if($order->paid_at)
                                    <div class="col-md-3">
                                        <p class="mb-1 small text-muted"><i class="fas fa-credit-card me-1"></i>Payée:</p>
                                        <p class="mb-0 small"><strong>{{ $order->paid_at->format('d/m/Y H:i') }}</strong></p>
                                    </div>
                                @endif
                                @if($order->shipped_at)
                                    <div class="col-md-3">
                                        <p class="mb-1 small text-muted"><i class="fas fa-shipping-fast me-1"></i>Expédiée:</p>
                                        <p class="mb-0 small"><strong>{{ $order->shipped_at->format('d/m/Y H:i') }}</strong></p>
                                    </div>
                                @endif
                                @if($order->delivered_at)
                                    <div class="col-md-3">
                                        <p class="mb-1 small text-muted"><i class="fas fa-check-circle me-1"></i>Livrée:</p>
                                        <p class="mb-0 small"><strong>{{ $order->delivered_at->format('d/m/Y H:i') }}</strong></p>
                                    </div>
                                @endif
                                @if($order->cancelled_at)
                                    <div class="col-md-3">
                                        <p class="mb-1 small text-muted"><i class="fas fa-times-circle me-1"></i>Annulée:</p>
                                        <p class="mb-0 small"><strong>{{ $order->cancelled_at->format('d/m/Y H:i') }}</strong></p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Footer Note -->
            <div class="footer-note">
                <p class="mb-2"><strong>Merci pour votre commande!</strong></p>
                <p class="mb-2 small">Pour toute question concernant cette facture, veuillez nous contacter:</p>
                <p class="mb-0 small">
                    <i class="fas fa-phone me-1"></i>+237 657 528 859 | 
                    <i class="fas fa-envelope me-1 ms-2"></i>brayeljunior8@gmail.com | 
                    <i class="fas fa-globe me-1 ms-2"></i>jbshop237.com
                </p>
                <hr class="my-3">
                <p class="mb-0 small text-muted">
                    <i class="fas fa-shield-alt me-1"></i>Cette facture a été générée électroniquement et est valable sans signature.
                </p>
            </div>
        </div>
    </div>

    <!-- Print Scripts -->
    <script>
        // Auto-print on load (optional - can be removed)
        // window.onload = function() { window.print(); }

        // Responsive table for small screens
        if (window.innerWidth < 768) {
            document.addEventListener('DOMContentLoaded', function() {
                const tables = document.querySelectorAll('.table');
                tables.forEach(table => {
                    table.style.fontSize = '0.75rem';
                });
            });
        }
    </script>
</body>
</html>
