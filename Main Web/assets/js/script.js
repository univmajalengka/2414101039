document.addEventListener('DOMContentLoaded', function() {

    // Fungsi untuk menampilkan notifikasi toast
    function showToast(message, isSuccess = true) {
        const container = document.getElementById('toast-container');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast-notification ${isSuccess ? 'success' : 'error'}`;
        const iconClass = isSuccess ? 'fa-check-circle' : 'fa-times-circle';
        toast.innerHTML = `<div class="toast-icon"><i class="fas ${iconClass}"></i></div><div class="toast-message">${message}</div>`;
        container.appendChild(toast);
        setTimeout(() => { toast.classList.add('show'); }, 100);
        setTimeout(() => {
            toast.classList.remove('show');
            toast.addEventListener('transitionend', () => toast.remove());
        }, 4000);
    }

    // FUNGSI BARU UNTUK UPDATE ANGKA DI KERANJANG
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-count-badge');
        if (badge) {
            badge.textContent = count;
            if (count > 0) {
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }
    }

    // Tangani semua form 'add-to-cart'
    const cartForms = document.querySelectorAll('form.add-to-cart-form');
    cartForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            const originalButtonText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            button.disabled = true;

            fetch('cart_action.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, data.success);
                if (data.totalItems !== undefined) {
                    updateCartBadge(data.totalItems); // Panggil fungsi update
                }
                button.innerHTML = originalButtonText;
                button.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = originalButtonText;
                button.disabled = false;
            });
        });
    });

    // Tangani semua form 'remove-from-cart'
    const removeForms = document.querySelectorAll('form.remove-from-cart-form');
    removeForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;

            fetch('cart_action.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, data.success);
                if (data.totalItems !== undefined) {
                    updateCartBadge(data.totalItems); // Panggil fungsi update
                }
                if (data.success) {
                    setTimeout(() => { location.reload(); }, 1500);
                } else {
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
            });
        });
    });

    // Tangani input kuantitas
    const quantityInputs = document.querySelectorAll('.quantity-input');
    let debounceTimer;

    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const productId = this.dataset.id;
            const price = parseFloat(this.dataset.price);
            let quantity = parseInt(this.value);
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                this.value = 1;
            }
            updateSubtotal(productId, price, quantity);
            updateGrandTotal();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                updateCartOnServer(productId, quantity, this);
            }, 500);
        });
    });

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function updateSubtotal(productId, price, quantity) {
        const subtotalElement = document.querySelector(`.product-item-row[data-id="${productId}"] .item-subtotal`);
        if (subtotalElement) {
            subtotalElement.textContent = formatRupiah(price * quantity);
        }
    }

    function updateGrandTotal() {
        const grandTotalElement = document.getElementById('grand-total');
        if (grandTotalElement) {
            let total = 0;
            document.querySelectorAll('.product-item-row').forEach(row => {
                const price = parseFloat(row.querySelector('.quantity-input').dataset.price);
                const quantity = parseInt(row.querySelector('.quantity-input').value);
                if (!isNaN(price) && !isNaN(quantity)) {
                    total += price * quantity;
                }
            });
            grandTotalElement.textContent = formatRupiah(total);
        }
    }

    function updateCartOnServer(productId, quantity, inputElement) {
        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        fetch('cart_action.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.totalItems !== undefined) {
                updateCartBadge(data.totalItems); // Panggil fungsi update
            }
            if (!data.success) {
                showToast(data.message, false);
                if (data.max_stock) {
                    inputElement.value = data.max_stock;
                    updateSubtotal(productId, parseFloat(inputElement.dataset.price), data.max_stock);
                    updateGrandTotal();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Inisialisasi slider
    const productSlider = new Swiper('.product-slider', {
        loop: false,
        spaceBetween: 30,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: {
            320: { slidesPerView: 1, spaceBetween: 20 },
            576: { slidesPerView: 2, spaceBetween: 20 },
            992: { slidesPerView: 4, spaceBetween: 30 }
        }
    });

    const reviewModal = document.getElementById('reviewModal');
if (reviewModal) {
    reviewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const productName = button.getAttribute('data-product-name');
        const productId = button.getAttribute('data-product-id');
        const orderId = button.getAttribute('data-order-id');

        const modalTitle = reviewModal.querySelector('#modalProductName');
        const modalProductIdInput = reviewModal.querySelector('#modalProductId');
        const modalOrderIdInput = reviewModal.querySelector('#modalOrderId');

        modalTitle.textContent = productName;
        modalProductIdInput.value = productId;
        modalOrderIdInput.value = orderId;
    });

    const stars = document.querySelectorAll('.star-rating input');
    const ratingValueInput = document.getElementById('ratingValue');
    stars.forEach(star => {
        star.addEventListener('change', function() {
            ratingValueInput.value = this.value;
        });
    });
}
});