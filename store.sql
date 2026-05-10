CREATE DATABASE IF NOT EXISTS fashion_store;
USE fashion_store;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category ENUM('male_fashion', 'female_fashion', 'fragrance') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    gender ENUM('male', 'female', 'unisex') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert 60 products
-- 20 Male Fashion
INSERT INTO products (name, category, price, description, image_url, gender) VALUES
('Classic White T-Shirt', 'male_fashion', 5000, 'A comfortable, breathable classic white t-shirt for everyday wear.', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&auto=format&fit=crop&q=60', 'male'),
('Slim Fit Blue Jeans', 'male_fashion', 15000, 'Durable slim fit denim jeans perfect for casual outings.', 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&auto=format&fit=crop&q=60', 'male'),
('Black Leather Jacket', 'male_fashion', 35000, 'Premium black leather jacket with a sleek design.', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop&q=60', 'male'),
('Formal Oxford Shoes', 'male_fashion', 25000, 'Elegant brown leather oxford shoes for formal events.', 'https://images.unsplash.com/photo-1614252339460-e1f422894372?w=500&auto=format&fit=crop&q=60', 'male'),
('Grey Pullover Hoodie', 'male_fashion', 12000, 'Warm and cozy grey hoodie with front pocket.', 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&auto=format&fit=crop&q=60', 'male'),
('Navy Blue Blazer', 'male_fashion', 28000, 'Stylish tailored navy blue blazer for smart casual looks.', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&auto=format&fit=crop&q=60', 'male'),
('Khaki Chino Pants', 'male_fashion', 14000, 'Comfortable stretch khaki chinos for work and weekend.', 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=500&auto=format&fit=crop&q=60', 'male'),
('Striped Button-Down Shirt', 'male_fashion', 9500, 'Light blue and white striped cotton shirt.', 'https://images.unsplash.com/photo-1598032895397-b9472444bf93?w=500&auto=format&fit=crop&q=60', 'male'),
('Denim Jacket', 'male_fashion', 18000, 'Classic blue denim jacket with metallic buttons.', 'https://images.unsplash.com/photo-1495105787522-5334e3ffa0ef?w=500&auto=format&fit=crop&q=60', 'male'),
('Running Sneakers', 'male_fashion', 22000, 'Lightweight and breathable sneakers for training.', 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=500&auto=format&fit=crop&q=60', 'male'),
('Polo T-Shirt', 'male_fashion', 6500, 'Red cotton polo shirt with a snug fit.', 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=500&auto=format&fit=crop&q=60', 'male'),
('Cargo Shorts', 'male_fashion', 8500, 'Olive green cargo shorts with multiple pockets.', 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500&auto=format&fit=crop&q=60', 'male'),
('Wool Winter Coat', 'male_fashion', 45000, 'Thick charcoal wool coat to keep you warm.', 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=500&auto=format&fit=crop&q=60', 'male'),
('Graphic Print T-Shirt', 'male_fashion', 5500, 'Trendy graphic print tee for casual wear.', 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=500&auto=format&fit=crop&q=60', 'male'),
('Black Skinny Jeans', 'male_fashion', 15000, 'Jet black skinny fit jeans with stretch.', 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop&q=60', 'male'),
('Loafer Shoes', 'male_fashion', 19000, 'Suede brown loafers for easy slip-on style.', 'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=500&auto=format&fit=crop&q=60', 'male'),
('V-Neck Sweater', 'male_fashion', 11000, 'Soft knit v-neck sweater in burgundy.', 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=500&auto=format&fit=crop&q=60', 'male'),
('Tracksuit Set', 'male_fashion', 21000, 'Matching grey tracksuit jacket and pants.', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=500&auto=format&fit=crop&q=60', 'male'),
('Linen Summer Shirt', 'male_fashion', 10500, 'Breathable beige linen shirt for hot days.', 'https://images.unsplash.com/photo-1596755094514-f87e32f85e2c?w=500&auto=format&fit=crop&q=60', 'male'),
('Leather Belt', 'male_fashion', 5000, 'Genuine brown leather belt with silver buckle.', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&auto=format&fit=crop&q=60', 'male');

-- 20 Female Fashion
INSERT INTO products (name, category, price, description, image_url, gender) VALUES
('Floral Summer Dress', 'female_fashion', 12500, 'Beautiful floral print dress perfect for sunny days.', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=500&auto=format&fit=crop&q=60', 'female'),
('High-Waist Jeans', 'female_fashion', 14000, 'Blue high-waisted denim jeans that flatter every figure.', 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500&auto=format&fit=crop&q=60', 'female'),
('Elegant Evening Gown', 'female_fashion', 45000, 'Stunning red evening gown for formal events.', 'https://images.unsplash.com/photo-1566160983997-7e9b0680cb4e?w=500&auto=format&fit=crop&q=60', 'female'),
('Stiletto Heels', 'female_fashion', 22000, 'Black patent leather stiletto heels.', 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=500&auto=format&fit=crop&q=60', 'female'),
('Leather Handbag', 'female_fashion', 28000, 'Designer-inspired genuine leather tote bag.', 'https://images.unsplash.com/photo-1584916201218-f4242ceb4809?w=500&auto=format&fit=crop&q=60', 'female'),
('Silk Blouse', 'female_fashion', 11500, 'Smooth and luxurious silk blouse in emerald green.', 'https://images.unsplash.com/photo-1588117305388-c2631a279f82?w=500&auto=format&fit=crop&q=60', 'female'),
('Pleated Midi Skirt', 'female_fashion', 9500, 'Pink pleated midi skirt for a chic look.', 'https://images.unsplash.com/photo-1583496661160-c588c4f52636?w=500&auto=format&fit=crop&q=60', 'female'),
('Denim Shorts', 'female_fashion', 7000, 'Distressed denim shorts for casual summer style.', 'https://images.unsplash.com/photo-1591369822096-1142517ab0ec?w=500&auto=format&fit=crop&q=60', 'female'),
('Cardigan Sweater', 'female_fashion', 13000, 'Cozy knit cardigan in mustard yellow.', 'https://images.unsplash.com/photo-1620799140188-3b2a02fd9a77?w=500&auto=format&fit=crop&q=60', 'female'),
('Ankle Boots', 'female_fashion', 25000, 'Brown suede ankle boots with block heel.', 'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?w=500&auto=format&fit=crop&q=60', 'female'),
('Cocktail Dress', 'female_fashion', 18000, 'Little black dress for cocktail parties.', 'https://images.unsplash.com/photo-1566160983997-7e9b0680cb4e?w=500&auto=format&fit=crop&q=60', 'female'),
('Yoga Leggings', 'female_fashion', 8500, 'Stretchable high-performance leggings for workouts.', 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=500&auto=format&fit=crop&q=60', 'female'),
('Trench Coat', 'female_fashion', 32000, 'Classic beige trench coat for rainy days.', 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=500&auto=format&fit=crop&q=60', 'female'),
('Crop Top', 'female_fashion', 5000, 'White ribbed crop top.', 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=500&auto=format&fit=crop&q=60', 'female'),
('Wide-Leg Trousers', 'female_fashion', 15000, 'Elegant black wide-leg trousers for office wear.', 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=500&auto=format&fit=crop&q=60', 'female'),
('Crossbody Bag', 'female_fashion', 16000, 'Compact and stylish crossbody bag with gold chain.', 'https://images.unsplash.com/photo-1591561954557-26941169b49e?w=500&auto=format&fit=crop&q=60', 'female'),
('Maxi Skirt', 'female_fashion', 11000, 'Flowy bohemian style maxi skirt.', 'https://images.unsplash.com/photo-1519014816548-bf5fe059e98b?w=500&auto=format&fit=crop&q=60', 'female'),
('Off-Shoulder Top', 'female_fashion', 7500, 'Ruffled off-shoulder top in pastel blue.', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=500&auto=format&fit=crop&q=60', 'female'),
('Puffer Jacket', 'female_fashion', 29000, 'Insulated puffer jacket for cold weather.', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&auto=format&fit=crop&q=60', 'female'),
('Ballet Flats', 'female_fashion', 9000, 'Comfortable nude ballet flats for everyday use.', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&auto=format&fit=crop&q=60', 'female');

-- 20 Fragrances
INSERT INTO products (name, category, price, description, image_url, gender) VALUES
('Bleu de Ocean', 'fragrance', 35000, 'A fresh, aquatic masculine scent perfect for daily wear.', 'https://images.unsplash.com/photo-1523293115678-d2906198d3b4?w=500&auto=format&fit=crop&q=60', 'male'),
('Rose Elegance', 'fragrance', 28000, 'A soft, romantic rose perfume with notes of vanilla.', 'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=500&auto=format&fit=crop&q=60', 'female'),
('Oud Wood Intense', 'fragrance', 55000, 'A rich, woody unisex fragrance with a hint of spice.', 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Citrus Burst', 'fragrance', 21000, 'Energizing citrus blend with lemon and bergamot.', 'https://images.unsplash.com/photo-1615529182904-14819c35db37?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Midnight Musk', 'fragrance', 42000, 'A seductive musk scent for evening occasions.', 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&auto=format&fit=crop&q=60', 'male'),
('Floral Jasmine', 'fragrance', 25000, 'Delicate jasmine blossoms infused with white tea.', 'https://images.unsplash.com/photo-1590736704728-f4730bb30770?w=500&auto=format&fit=crop&q=60', 'female'),
('Spicy Leather', 'fragrance', 48000, 'Bold leather and warm spices for the confident man.', 'https://images.unsplash.com/photo-1587017539504-67cfaf3ed065?w=500&auto=format&fit=crop&q=60', 'male'),
('Vanilla Bloom', 'fragrance', 24000, 'Sweet vanilla bean with a touch of orchid.', 'https://images.unsplash.com/photo-1605367302473-b3c996614144?w=500&auto=format&fit=crop&q=60', 'female'),
('Ocean Breeze', 'fragrance', 19000, 'Light and airy body mist reminiscent of the sea.', 'https://images.unsplash.com/photo-1595532542520-22c6085a53f0?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Amber Gold', 'fragrance', 52000, 'Luxurious amber and sandalwood perfume.', 'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Berry Bliss', 'fragrance', 22000, 'Fruity fragrance with notes of raspberry and blackberry.', 'https://images.unsplash.com/photo-1615529182904-14819c35db37?w=500&auto=format&fit=crop&q=60', 'female'),
('Vetiver Fresh', 'fragrance', 31000, 'Earthy vetiver mixed with crisp green apple.', 'https://images.unsplash.com/photo-1523293115678-d2906198d3b4?w=500&auto=format&fit=crop&q=60', 'male'),
('Patchouli Dark', 'fragrance', 38000, 'Deep and mysterious patchouli essence.', 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Peony Dreams', 'fragrance', 27000, 'Soft and powdery peony fragrance.', 'https://images.unsplash.com/photo-1590736704728-f4730bb30770?w=500&auto=format&fit=crop&q=60', 'female'),
('Aqua Di Sport', 'fragrance', 29000, 'Invigorating sport cologne for active men.', 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&auto=format&fit=crop&q=60', 'male'),
('Cherry Blossom', 'fragrance', 23000, 'Springtime cherry blossom perfume.', 'https://images.unsplash.com/photo-1595532542520-22c6085a53f0?w=500&auto=format&fit=crop&q=60', 'female'),
('Smoked Tobacco', 'fragrance', 46000, 'Warm tobacco leaves and honey.', 'https://images.unsplash.com/photo-1587017539504-67cfaf3ed065?w=500&auto=format&fit=crop&q=60', 'male'),
('Coconut Island', 'fragrance', 20000, 'Tropical coconut and pineapple scent.', 'https://images.unsplash.com/photo-1605367302473-b3c996614144?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Lavender Fields', 'fragrance', 26000, 'Calming lavender and chamomile blend.', 'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=500&auto=format&fit=crop&q=60', 'unisex'),
('Classic Noir', 'fragrance', 65000, 'Premium exclusive black edition perfume.', 'https://images.unsplash.com/photo-1523293115678-d2906198d3b4?w=500&auto=format&fit=crop&q=60', 'unisex');
