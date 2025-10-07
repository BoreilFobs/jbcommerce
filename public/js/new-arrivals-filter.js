document.addEventListener('DOMContentLoaded', function() {
    // Listen for click on New Arrivals tab
    const newArrivalsTab = document.querySelector('[href="#tab-2"]');
    if (!newArrivalsTab) return;
    newArrivalsTab.addEventListener('click', function(e) {
        fetch('/offers/new-arrivals')
            .then(response => response.json())
            .then(offers => {
                const tabPane = document.querySelector('#tab-2 .row.g-4');
                if (!tabPane) return;
                tabPane.innerHTML = '';
                if (offers.length === 0) {
                    tabPane.innerHTML = '<div class="col-12 text-center py-5">Aucun nouvel arrivage ce mois-ci.</div>';
                    return;
                }
                offers.forEach(offer => {
                    // Assuming offer.images is an array of image filenames
                    const images = Array.isArray(offer.images) ? offer.images : JSON.parse(offer.images || '[]');
                    const firstImage = images.length > 0 ? images[0] : 'default.jpg';
                    tabPane.innerHTML += `
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                            <div class="product-item-inner border rounded">
                                <div class="product-item-inner-item">
                                    <img src="/storage/offer_img/product${offer.id}/${firstImage}" class="img-fluid w-100 rounded-top" alt="">
                                    <div class="product-new">New</div>
                                    <div class="product-details">
                                        <a href="/product/details/${offer.id}"><i class="fa fa-eye fa-1x"></i></a>
                                    </div>
                                </div>
                                <div class="text-center rounded-bottom p-4">
                                    <a href="#" class="d-block mb-2">${offer.category}</a>
                                    <a href="#" class="d-block h4">${offer.name} <br> G${Math.floor(Math.random()*9000+1000)}</a>
                                    <del class="me-2 fs-5">${(offer.price + offer.price * 0.15).toLocaleString()} FCFA</del>
                                    <span class="text-primary fs-5">${Number(offer.price).toLocaleString()} FCFA</span>
                                </div>
                            </div>
                            <div class="product-item-add border border-top-0 rounded-bottom  text-center p-4 pt-0">
                                <a href="/cart/${offer.id}/create/${offer.user_id || ''}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4"><i class="fas fa-shopping-cart me-2"></i> Add To Cart</a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="d-flex">
                                        <a href="/wish-list/${offer.id}/create/${offer.user_id || ''}" class="text-primary d-flex align-items-center justify-content-center me-0"><span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                });
            });
    });
});
