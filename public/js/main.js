(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);
    
    
    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.nav-bar').addClass('sticky-top shadow-sm');
        } else {
            $('.nav-bar').removeClass('sticky-top shadow-sm');
        }
    });


    // Hero Header carousel
    $(".header-carousel").owlCarousel({
        items: 1,
        autoplay: true,
        smartSpeed: 2000,
        center: false,
        dots: false,
        loop: true,
        margin: 0,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ]
    });


    // ProductList carousel
    $(".productList-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 2000,
        dots: false,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:2
            },
            1200:{
                items:3
            }
        }
    });

    // ProductList categories carousel
    $(".productImg-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: false,
        loop: true,
        items: 1,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ]
    });


    // Single Products carousel
    $(".single-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: true,
        dotsData: true,
        loop: true,
        items: 1,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ]
    });


    // ProductList carousel
    $(".related-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: false,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });



    // Gestion du panier : quantité, total par produit et total général
    function getCartPrice(str) {
        // Prend en charge FCFA et $
        let priceStr = str.replace(/[^\d.,]/g, '').replace(',', '');
        return parseFloat(priceStr) || 0;
    }

    function updateRowTotal(row) {
        var priceText = row.find('td:nth-child(3) p').text();
        var qtyInput = row.find('.quantity input');
        var totalText = row.find('td:nth-child(5) p');
        var qty = parseInt(qtyInput.val()) || 1;
        var price = getCartPrice(priceText);
        var total = price * qty;
        if (priceText.includes('FCFA')) {
            totalText.text(total.toLocaleString() + ' FCFA');
        } else {
            totalText.text(total.toFixed(2) + ' $');
        }
    }

    function updateCartTotal() {
        var subtotal = 0;
        $('tbody tr').each(function() {
            var totalText = $(this).find('td:nth-child(5) p').text();
            var total = getCartPrice(totalText);
            subtotal += total;
        });
        // Livraison fixe 1000 FCFA
        var livraison = 1000;
        var totalGeneral = subtotal + livraison;
        // Mettre à jour les éléments du total
        $(".cart-subtotal").text(subtotal.toLocaleString() + ' FCFA');
        $(".cart-livraison").text(livraison.toLocaleString() + ' FCFA');
        $(".cart-total-general").text(totalGeneral.toLocaleString() + ' FCFA');
    }

    // Initialisation des boutons quantité et calculs
    $(document).ready(function() {
        // Pour chaque ligne du panier
        $('tbody tr').each(function() {
            var row = $(this);
            var minusBtn = row.find('.btn-minus');
            var plusBtn = row.find('.btn-plus');
            var qtyInput = row.find('.quantity input');

            // Récupérer l'id du panier depuis un attribut data-cart-id
            var cartId = row.data('cart-id');

            function updateAll(sendAjax = false) {
                updateRowTotal(row);
                updateCartTotal();
                if (sendAjax && cartId) {
                    $.ajax({
                        url: '/cart/qty',
                        type: 'POST',
                        data: {
                            cart_id: cartId,
                            quantity: qtyInput.val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Optionally show a success message
                        },
                        error: function() {
                            // Optionally show an error message
                        }
                    });
                }
            }

            minusBtn.on('click', function() {
                var qty = parseInt(qtyInput.val()) || 1;
                if (qty > 1) qtyInput.val(qty - 1);
                updateAll(true);
            });
            plusBtn.on('click', function() {
                var qty = parseInt(qtyInput.val()) || 1;
                qtyInput.val(qty + 1);
                updateAll(true);
            });
            qtyInput.on('input', function() {
                updateAll(true);
            });
            // Calcul initial
            updateAll();
        });
        // Calcul initial du total général
        updateCartTotal();
    });


    
   // Back to top button
   $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


   

})(jQuery);

