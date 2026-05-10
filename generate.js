document.addEventListener('DOMContentLoaded', () => {
    const MALE_IMGS = [
        'https://images.unsplash.com/photo-1593030761757-71fae46af505?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1520975954732-57dd22299614?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=800&auto=format&fit=crop'
    ];
    const FEMALE_IMGS = [
        'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1591561954557-26941169b49e?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=800&auto=format&fit=crop'
    ];
    const FRAG_IMGS = [
        'https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?q=80&w=800&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=800&auto=format&fit=crop'
    ];
    
    const DB_PRODUCTS = [];
    
    // 20 Male
    for(let i=1; i<=20; i++) {
        DB_PRODUCTS.push({
            category: 'male_fashion', gender: 'Men', name: 'Premium Men Collection ' + i,
            price: 45000 + (Math.floor(Math.random() * 200) * 1000), img: MALE_IMGS[i % MALE_IMGS.length]
        });
    }
    // 20 Female
    for(let i=1; i<=20; i++) {
        DB_PRODUCTS.push({
            category: 'female_fashion', gender: 'Women', name: 'Luxury Women Collection ' + i,
            price: 65000 + (Math.floor(Math.random() * 200) * 1000), img: FEMALE_IMGS[i % FEMALE_IMGS.length]
        });
    }
    // 5 Fragrances
    for(let i=1; i<=5; i++) {
        DB_PRODUCTS.push({
            category: 'fragrance', gender: 'Unisex', name: 'Signature Fragrance ' + i,
            price: 85000 + (Math.floor(Math.random() * 100) * 1000), img: FRAG_IMGS[i % FRAG_IMGS.length]
        });
    }
    
    const container = document.querySelector('.products-container');
    if(container) {
        // Clear hardcoded HTML items
        container.innerHTML = '';
        
        // Generate new dynamic grid
        let html = '';
        DB_PRODUCTS.forEach(p => {
            html += `
            <div class="product-card fade-in" data-category="${p.category}">
                <div class="product-img-wrapper">
                    <img src="${p.img}" alt="${p.name}" loading="lazy" class="product-img">
                    <div class="badges">
                        <span class="badge badge-light">In Stock</span>
                    </div>
                    <div class="product-overlay">
                        <form class="add-to-cart-form">
                            <div class="qty-stepper">
                                <button type="button" class="qty-btn minus"><i class="fas fa-minus"></i></button>
                                <input type="number" name="quantity" value="1" min="1" max="10" class="qty-input" readonly>
                                <button type="button" class="qty-btn plus"><i class="fas fa-plus"></i></button>
                            </div>
                            <button type="submit" class="btn btn-add-cart"><i class="fas fa-shopping-bag"></i> Quick Add</button>
                        </form>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-meta">
                        <span class="product-gender">${p.gender}</span>
                    </div>
                    <h3 class="product-name" title="${p.name}">${p.name}</h3>
                    <div class="product-price">₦${p.price.toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
            </div>`;
        });
        container.innerHTML = html;

        // --- Re-bind event listeners after dynamic generation ---
        
        // 1. Re-bind Add to cart forms
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                let currentUser = localStorage.getItem('ff_user');
                if(!currentUser) {
                    window.showToast("Please login to add items to your cart.", "error");
                    return;
                }
                const btn = form.querySelector('.btn-add-cart');
                const qtyInput = form.querySelector('.qty-input');
                let qty = qtyInput ? parseInt(qtyInput.value) : 1;
                
                const card = form.closest('.product-card');
                const name = card.querySelector('.product-name').innerText;
                const priceText = card.querySelector('.product-price').innerText.replace(/[₦,]/g, '');
                const price = parseFloat(priceText);
                const img = card.querySelector('img').src;
                
                // Animate button
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-check"></i> Added';
                    setTimeout(() => { btn.innerHTML = originalText; }, 2000);
                    
                    // Dispatch a custom event to addToCart logic since addToCart is defined in index.html block
                    const cartEvent = new CustomEvent('app:addToCart', { detail: {name, price, img, qty} });
                    document.dispatchEvent(cartEvent);
                }, 400);
            });
        });

        // 2. Re-bind QTY steppers
        document.querySelectorAll('.qty-stepper').forEach(stepper => {
            const minusBtn = stepper.querySelector('.minus');
            const plusBtn = stepper.querySelector('.plus');
            const input = stepper.querySelector('.qty-input');
            if (minusBtn && plusBtn && input) {
                minusBtn.addEventListener('click', () => {
                    let val = parseInt(input.value);
                    if (val > parseInt(input.min || 1)) input.value = val - 1;
                });
                plusBtn.addEventListener('click', () => {
                    let val = parseInt(input.value);
                    let max = parseInt(input.max || 20);
                    if (val < max) input.value = val + 1;
                });
            }
        });

        // 3. Re-bind Intersection Observer for fade-in animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('appear');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

        document.querySelectorAll('.fade-in, .fade-in-up').forEach(el => {
            observer.observe(el);
        });
        
        // 4. Update the filter functionality to cover new items
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const productCards = document.querySelectorAll('.product-card');
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
                        setTimeout(() => { card.style.display = 'none'; }, 300);
                    }
                });
            });
        });
    }
});
