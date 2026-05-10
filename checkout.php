<?php
require_once 'config/database.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to checkout.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch Cart to check if empty and get total
$stmt = $pdo->prepare("SELECT c.quantity, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if(empty($cart_items) && !isset($_POST['name'])) {
    header("Location: cart.php");
    exit;
}

$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

$success = false;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // In a real app, we would insert an order here
    
    $del = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    if($del->execute([$user_id])) {
        $success = true;
    }
}

// HTML Output starts here
include 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <p class="subtitle">Complete your luxury purchase</p>
    </div>
</div>

<div class="container section-padding">
    <?php if($success): ?>
        <div class="success-container fade-in">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Order Confirmed!</h2>
            <p>Thank you for shopping with F&F. Your luxury items will be delivered soon.</p>
            <div class="order-meta">
                <p><strong>Delivery Address:</strong> <?= htmlspecialchars($_POST['address']) ?></p>
            </div>
            <a href="index.php" class="btn btn-primary btn-large mt-20">Continue Shopping</a>
        </div>
        <script>
            // Trigger confetti
            document.addEventListener("DOMContentLoaded", function() {
                var duration = 3 * 1000;
                var animationEnd = Date.now() + duration;
                var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                var interval = setInterval(function() {
                    var timeLeft = animationEnd - Date.now();

                    if (timeLeft <= 0) {
                        return clearInterval(interval);
                    }

                    var particleCount = 50 * (timeLeft / duration);
                    confetti(Object.assign({}, defaults, { particleCount,
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                    }));
                    confetti(Object.assign({}, defaults, { particleCount,
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                    }));
                }, 250);
            });
        </script>
    <?php else: ?>
        <div class="checkout-layout fade-in">
            <div class="checkout-form-section">
                <div class="glass-card">
                    <h3 class="card-title">Delivery Details</h3>
                    <form method="POST" action="" class="modern-form" id="checkout-form">
                        <div class="floating-group">
                            <input type="text" name="name" id="name" required placeholder=" " value="<?= htmlspecialchars($_SESSION['fullname']) ?>">
                            <label for="name">Full Name</label>
                        </div>
                        <div class="floating-group">
                            <input type="tel" name="phone" id="phone" required placeholder=" ">
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="floating-group">
                            <textarea name="address" id="address" required placeholder=" " rows="4"></textarea>
                            <label for="address">Full Delivery Address</label>
                        </div>
                        
                        <div class="payment-info mt-20">
                            <i class="fas fa-info-circle text-gold"></i> Payment will be collected securely on delivery.
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-large mt-20" id="btn-place-order">
                            <span>Place Order</span>
                            <span class="btn-price">₦<?= number_format($total, 2) ?></span>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="checkout-summary-section">
                <div class="glass-card sticky-sidebar">
                    <h3 class="card-title">Order Summary</h3>
                    <div class="checkout-items">
                        <?php foreach($cart_items as $item): ?>
                            <div class="checkout-item">
                                <div class="ci-img">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Product">
                                    <span class="ci-qty"><?= $item['quantity'] ?></span>
                                </div>
                                <div class="ci-info">
                                    <h4><?= htmlspecialchars($item['name']) ?></h4>
                                    <div class="ci-price">₦<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-totals mt-20">
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>₦<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="summary-line">
                            <span>Shipping</span>
                            <span class="text-success">Free</span>
                        </div>
                        <div class="summary-line total-line">
                            <span>Total</span>
                            <span>₦<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
