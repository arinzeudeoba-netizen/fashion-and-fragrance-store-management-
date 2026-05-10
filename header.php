<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cart_count = 0;
$offcanvas_cart_items = [];
$offcanvas_total = 0;

if(isset($_SESSION['user_id'])){
    require_once 'config/database.php';
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $res = $stmt->fetch();
    $cart_count = $res['total'] ? $res['total'] : 0;
    
    // Fetch items for off-canvas
    $stmt_oc = $pdo->prepare("
        SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image_url 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt_oc->execute([$_SESSION['user_id']]);
    $offcanvas_cart_items = $stmt_oc->fetchAll();
    foreach($offcanvas_cart_items as $oc_item) {
        $offcanvas_total += $oc_item['price'] * $oc_item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F&F Fashion and Fragrance</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Confetti JS for Checkout -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div class="top-bar-left">
                <i class="fas fa-map-marker-alt"></i> SABURIA NO11, DEI-DEI
            </div>
            <div class="top-bar-right">
                <a href="https://wa.me/2348074001039" target="_blank" class="hover-gold"><i class="fab fa-whatsapp"></i> +2348074001039</a>
                <a href="mailto:arinzeudeoba@gmail.com" class="hover-gold"><i class="fas fa-envelope"></i> arinzeudeoba@gmail.com</a>
                <a href="https://instagram.com/arin_ze67" target="_blank" class="hover-gold"><i class="fab fa-instagram"></i> @arin_ze67</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="header" id="main-header">
        <div class="container header-inner">
            <div class="logo-wrapper">
                <a href="index.php" class="logo">F&F<span>.</span></a>
            </div>
            
            <nav class="nav" id="main-nav">
                <ul class="nav-list">
                    <li><a href="index.php#men" class="nav-link" data-filter="male_fashion">Men</a></li>
                    <li><a href="index.php#women" class="nav-link" data-filter="female_fashion">Women</a></li>
                    <li><a href="index.php#fragrance" class="nav-link" data-filter="fragrance">Fragrance</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <!-- User Auth -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="icon-btn" aria-label="User Account">
                            <i class="far fa-user"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-header">Hello, <?= htmlspecialchars(explode(' ', $_SESSION['fullname'])[0]) ?></div>
                            <a href="cart.php"><i class="fas fa-shopping-bag"></i> My Cart</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="icon-btn" aria-label="Login"><i class="far fa-user"></i></a>
                <?php endif; ?>

                <!-- Cart Toggle -->
                <button class="icon-btn cart-toggle" aria-label="View Cart">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-badge" id="cart-badge-count"><?= $cart_count ?></span>
                </button>

                <!-- Mobile Menu Toggle -->
                <button class="icon-btn mobile-menu-btn" aria-label="Menu">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </div>
        </div>
    </header>

    <!-- Off-Canvas Cart -->
    <div class="offcanvas-cart" id="offcanvas-cart">
        <div class="offcanvas-header">
            <h3>Your Cart (<span class="offcanvas-count"><?= $cart_count ?></span>)</h3>
            <button class="close-cart"><i class="fas fa-times"></i></button>
        </div>
        <div class="offcanvas-body">
            <?php if(empty($offcanvas_cart_items)): ?>
                <div class="offcanvas-empty">
                    <i class="fas fa-shopping-bag empty-icon"></i>
                    <p>Your cart is empty.</p>
                    <a href="index.php" class="btn btn-primary close-cart-link">Shop Now</a>
                </div>
            <?php else: ?>
                <div class="offcanvas-items">
                    <?php foreach($offcanvas_cart_items as $item): ?>
                        <div class="offcanvas-item">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Product">
                            <div class="offcanvas-item-details">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <div class="offcanvas-price">₦<?= number_format($item['price'], 2) ?></div>
                                <div class="offcanvas-qty">Qty: <?= $item['quantity'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="offcanvas-footer">
            <div class="offcanvas-total">
                <span>Subtotal:</span>
                <span>₦<?= number_format($offcanvas_total, 2) ?></span>
            </div>
            <a href="cart.php" class="btn btn-outline btn-block mb-10">View Cart</a>
            <a href="checkout.php" class="btn btn-primary btn-block">Checkout</a>
        </div>
    </div>
    <div class="offcanvas-overlay"></div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <main class="main-content">
