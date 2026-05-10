// script.js
document.addEventListener('DOMContentLoaded', () => {
    
    // --- Sticky Header & Scroll Effects ---
    const header = document.getElementById('main-header');
    const backToTop = document.getElementById('back-to-top');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        if (window.scrollY > 500) {
            backToTop?.classList.add('visible');
        } else {
            backToTop?.classList.remove('visible');
        }
    });

    backToTop?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // --- Mobile Menu ---
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.getElementById('main-nav');
    const hamburger = document.querySelector('.hamburger');

    if (mobileMenuBtn && nav) {
        mobileMenuBtn.addEventListener('click', () => {
            nav.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // --- Off-Canvas Cart ---
    const cartToggleBtns = document.querySelectorAll('.cart-toggle');
    const offcanvasCart = document.getElementById('offcanvas-cart');
    const closeCartBtn = document.querySelector('.close-cart');
    const offcanvasOverlay = document.querySelector('.offcanvas-overlay');

    function openCart() {
        if(offcanvasCart) offcanvasCart.classList.add('active');
        if(offcanvasOverlay) offcanvasOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }

    function closeCart() {
        if(offcanvasCart) offcanvasCart.classList.remove('active');
        if(offcanvasOverlay) offcanvasOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    cartToggleBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            // If logged out, redirect to login
            if (typeof IS_LOGGED_IN !== 'undefined' && !IS_LOGGED_IN) {
                window.location.href = 'login.php';
                return;
            }
            openCart();
        });
    });

    closeCartBtn?.addEventListener('click', closeCart);
    offcanvasOverlay?.addEventListener('click', closeCart);

    // --- Password Visibility Toggle ---
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // --- Quantity Stepper ---
    document.querySelectorAll('.qty-stepper').forEach(stepper => {
        const minusBtn = stepper.querySelector('.minus');
        const plusBtn = stepper.querySelector('.plus');
        const input = stepper.querySelector('.qty-input');

        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', () => {
                let val = parseInt(input.value);
                if (val > parseInt(input.min || 1)) {
                    input.value = val - 1;
                }
            });

            plusBtn.addEventListener('click', () => {
                let val = parseInt(input.value);
                let max = parseInt(input.max || 20);
                if (val < max) {
                    input.value = val + 1;
                }
            });
        }
    });

    // --- Product Filtering (Frontend Only) ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');

            productCards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filterValue === 'all' || category === filterValue) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300); // match transition duration
                }
            });
        });
    });

    // Handle initial navigation hash for filtering
    function handleHashFilter() {
        const hash = window.location.hash;
        if(hash) {
            const targetMap = {
                '#men': 'male_fashion',
                '#women': 'female_fashion',
                '#fragrance': 'fragrance'
            };
            const filterValue = targetMap[hash];
            if(filterValue) {
                const btn = document.querySelector(`.filter-btn[data-filter="${filterValue}"]`);
                if(btn) btn.click();
            }
        }
    }
    window.addEventListener('hashchange', handleHashFilter);
    handleHashFilter();

    // --- Add to Cart Interception (Fetch) ---
    const addCartForms = document.querySelectorAll('.add-to-cart-form');
    addCartForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent standard POST
            
            if (typeof IS_LOGGED_IN !== 'undefined' && !IS_LOGGED_IN) {
                window.location.href = 'login.php';
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            submitBtn.disabled = true;

            const formData = new FormData(form);
            const qty = parseInt(formData.get('quantity'));
            const productName = form.getAttribute('data-product-name');
            const productImg = form.getAttribute('data-product-image');
            const productPrice = parseFloat(form.getAttribute('data-product-price'));

            try {
                // Post to current page to let PHP handle insertion
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // We don't care about the response content as long as it succeeds (PHP will redirect, fetch follows it)
                if (response.ok) {
                    showToast(`${productName} added to cart!`, 'success');
                    
                    // Update header cart badge
                    const badge = document.getElementById('cart-badge-count');
                    if(badge) {
                        badge.innerText = parseInt(badge.innerText || 0) + qty;
                    }

                    // Update off-canvas cart badge
                    const ocBadge = document.querySelector('.offcanvas-count');
                    if(ocBadge) {
                        ocBadge.innerText = parseInt(ocBadge.innerText || 0) + qty;
                    }

                    // Prepend item to off-canvas list
                    const ocBody = document.querySelector('.offcanvas-items');
                    const ocEmpty = document.querySelector('.offcanvas-empty');
                    
                    if(ocEmpty) {
                        ocEmpty.remove(); // Remove empty state
                        const newContainer = document.createElement('div');
                        newContainer.className = 'offcanvas-items';
                        document.querySelector('.offcanvas-body').appendChild(newContainer);
                    }
                    
                    const targetContainer = document.querySelector('.offcanvas-items');
                    
                    // Add visually to off-canvas
                    const newItemHTML = `
                        <div class="offcanvas-item new-item-added">
                            <img src="${productImg}" alt="Product">
                            <div class="offcanvas-item-details">
                                <h4>${productName}</h4>
                                <div class="offcanvas-price">₦${productPrice.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                                <div class="offcanvas-qty">Qty: +${qty}</div>
                            </div>
                        </div>
                    `;
                    if(targetContainer) {
                        targetContainer.insertAdjacentHTML('afterbegin', newItemHTML);
                    }
                } else {
                    showToast('Failed to add product. Please try again.', 'error');
                }
            } catch (error) {
                showToast('Network error.', 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    });

    // --- Intersection Observer for Scroll Animations ---
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in, .fade-in-up').forEach(el => {
        observer.observe(el);
    });

});

// --- Toast Notification System ---
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    toast.innerHTML = `
        <i class="fas ${iconClass}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Remove after 3s
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
};
