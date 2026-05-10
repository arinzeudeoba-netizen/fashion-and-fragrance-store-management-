<?php
require_once 'config/database.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle Add to Cart
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if(!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login to add items to your cart.'); window.location.href='login.php';</script>";
        exit;
    }
    
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];
    
    // Check if exists
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch();
    
    if($existing) {
        $new_qty = $existing['quantity'] + $quantity;
        $update = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->execute([$new_qty, $existing['id']]);
    } else {
        $insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->execute([$user_id, $product_id, $quantity]);
    }
    echo "<script>alert('Product added to cart!'); window.location.href='index.php';</script>";
}

// Fetch products
$stmt = $pdo->query("SELECT * FROM products ORDER BY category");
$products = $stmt->fetchAll();

$men = array_filter($products, fn($p) => $p['category'] === 'male_fashion');
$women = array_filter($products, fn($p) => $p['category'] === 'female_fashion');
$fragrances = array_filter($products, fn($p) => $p['category'] === 'fragrance');

// HTML Output starts here
include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="container hero-content fade-in-up">
        <span class="hero-badge">New Collection 2026</span>
        <h1>Redefine Your<br>Elegance.</h1>
        <p>Discover a curated selection of premium fashion and signature fragrances designed for the modern connoisseur.</p>
        <a href="#shop" class="btn btn-primary btn-hero">Explore Collection <i class="fas fa-arrow-down"></i></a>
    </div>
</section>

<div class="container shop-container" id="shop">
    <div class="shop-header fade-in">
        <h2 class="section-title">The Collection</h2>
        <div class="filter-group">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="male_fashion">Men</button>
            <button class="filter-btn" data-filter="female_fashion">Women</button>
            <button class="filter-btn" data-filter="fragrance">Fragrance</button>
        </div>
    </div>

    <div class="products-container">
        <?php 
        // We will loop all products together for the frontend filtering
        foreach($products as $p): 
        ?>
        <div class="product-card fade-in" data-category="<?= htmlspecialchars($p['category']) ?>">
            <div class="product-img-wrapper">
                <img src="<?= htmlspecialchars($p['image_url']) ?>&auto=format" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" class="product-img">
                <div class="badges">
                    <?php if($p['category'] === 'fragrance'): ?>
                        <span class="badge badge-dark">Premium</span>
                    <?php else: ?>
                        <span class="badge badge-light">In Stock</span>
                    <?php endif; ?>
                </div>
                <div class="product-overlay">
                    <form method="POST" class="add-to-cart-form" data-product-id="<?= $p['id'] ?>" data-product-name="<?= htmlspecialchars($p['name']) ?>" data-product-price="<?= $p['price'] ?>" data-product-image="<?= htmlspecialchars($p['image_url']) ?>">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="add_to_cart" value="1">
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
                    <span class="product-gender"><?= ucfirst($p['gender']) ?></span>
                </div>
                <h3 class="product-name" title="<?= htmlspecialchars($p['name']) ?>"><?= htmlspecialchars($p['name']) ?></h3>
                <div class="product-price">₦<?= number_format($p['price'], 2) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
