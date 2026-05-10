import os, re
import random

male_images = [
    ('https://images.unsplash.com/photo-1593030761757-71fae46af505?q=80&w=800&auto=format&fit=crop', 'Classic Tailored Suit'),
    ('https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?q=80&w=800&auto=format&fit=crop', 'Luxury Silk Shirt'),
    ('https://images.unsplash.com/photo-1523170335258-f5ed11844a49?q=80&w=800&auto=format&fit=crop', 'Chronograph Watch'),
    ('https://images.unsplash.com/photo-1520975954732-57dd22299614?q=80&w=800&auto=format&fit=crop', 'Premium Leather Jacket'),
    ('https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=800&auto=format&fit=crop', 'Designer Sneakers'),
    ('https://images.unsplash.com/photo-1588099768531-a72d4a198538?q=80&w=800&auto=format&fit=crop', 'Casual Polo Shirt'),
    ('https://images.unsplash.com/photo-1507680434267-325d0f6222b6?q=80&w=800&auto=format&fit=crop', 'Wool Overcoat'),
    ('https://images.unsplash.com/photo-1621252179027-94459d278660?q=80&w=800&auto=format&fit=crop', 'Summer Linen Shirt'),
]

female_images = [
    ('https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?q=80&w=800&auto=format&fit=crop', 'Elegant Evening Gown'),
    ('https://images.unsplash.com/photo-1591561954557-26941169b49e?q=80&w=800&auto=format&fit=crop', 'Designer Handbag'),
    ('https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=800&auto=format&fit=crop', 'Stiletto Heels'),
    ('https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=800&auto=format&fit=crop', 'Diamond Necklace'),
    ('https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=800&auto=format&fit=crop', 'Leather Biker Jacket'),
    ('https://images.unsplash.com/photo-1583394838336-acd977736f90?q=80&w=800&auto=format&fit=crop', 'Floral Summer Dress'),
    ('https://images.unsplash.com/photo-1603400521630-9f2de124b4ac?q=80&w=800&auto=format&fit=crop', 'Cashmere Sweater'),
    ('https://images.unsplash.com/photo-1509631179647-0c37cb110060?q=80&w=800&auto=format&fit=crop', 'Silk Scarf'),
]

fragrance_images = [
    ('https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800&auto=format&fit=crop', 'Midnight Oud'),
    ('https://images.unsplash.com/photo-1585386959984-a4155224a1ad?q=80&w=800&auto=format&fit=crop', 'Royal Amber'),
    ('https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=800&auto=format&fit=crop', 'Floral Essence'),
    ('https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=800&auto=format&fit=crop', 'Ocean Breeze'),
    ('https://images.unsplash.com/photo-1616406432452-07bc5938759d?q=80&w=800&auto=format&fit=crop', 'Vanilla Musk'),
]

random.seed(42)

def generate_html(category, gender_label, items, count, start_index):
    html = ''
    for i in range(count):
        img, base_name = items[i % len(items)]
        name = f'{base_name} {i+1}' if i >= len(items) else base_name
        price = random.randint(45, 300) * 1000
        html += f'''                <!-- Product {start_index + i} -->
                <div class="product-card fade-in" data-category="{category}">
                    <div class="product-img-wrapper">
                        <img src="{img}" alt="{name}" loading="lazy" class="product-img">
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
                            <span class="product-gender">{gender_label}</span>
                        </div>
                        <h3 class="product-name" title="{name}">{name}</h3>
                        <div class="product-price">₦{price:,.2f}</div>
                    </div>
                </div>
'''
    return html

all_html = '            <div class="products-container">\n'
all_html += generate_html('male_fashion', 'Men', male_images, 20, 1)
all_html += generate_html('female_fashion', 'Women', female_images, 20, 21)
all_html += generate_html('fragrance', 'Unisex', fragrance_images, 5, 41)
all_html += '            </div>'

with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Using regex to replace the old products-container
pattern = re.compile(r'<div class="products-container">.*?</div>\s*</div>\s*</main>', re.DOTALL)
new_content = re.sub(pattern, all_html + '\n        </div>\n    </main>', content)

with open('index.html', 'w', encoding='utf-8') as f:
    f.write(new_content)
print("Updated index.html successfully with 45 products.")
