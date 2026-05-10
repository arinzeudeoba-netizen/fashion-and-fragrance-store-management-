<?php
require_once 'config/database.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to view your cart.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Remove
if(isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);
    header("Location: cart.php");
    exit;
}

// Handle Update Quantity
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if(isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach($_POST['qty'] as $cart_id => $qty) {
            if($qty > 0) {
                $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$qty, $cart_id, $user_id]);
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// Fetch Cart Items
$stmt = $pdo->prepare("
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image_url 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
$total = 0;

// HTML Output starts here
include 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Your Shopping Cart</h1>
        <p class="subtitle">Review your items before proceeding to checkout</p>
    </div>
</div>

<div class="container section-padding">
    <?php if(empty($cart_items)): ?>
        <div class="empty-state fade-in">
            <div class="empty-icon-wrapper">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2>Your cart is currently empty</h2>
            <p>Explore our exclusive collections and find something you love.</p>
            <a href="index.php" class="btn btn-primary btn-large mt-20">Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="POST" action="" class="cart-form fade-in">
            <div class="cart-layout">
                <div class="cart-main">
                    <div class="cart-items-wrapper">
                        <?php foreach($cart_items as $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <div class="cart-item-row">
                            <div class="item-image">
                                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Product">
                            </div>
                            <div class="item-details">
                                <h3 class="item-name"><?= htmlspecialchars($item['name']) ?></h3>
                                <div class="item-price-unit">₦<?= number_format($item['price'], 2) ?></div>
                            </div>
                            <div class="item-quantity">
                                <div class="qty-stepper">
                                    <button type="button" class="qty-btn minus"><i class="fas fa-minus"></i></button>
                                    <input type="number" name="qty[<?= $item['cart_id'] ?>]" value="<?= $item['quantity'] ?>" min="1" max="20" class="qty-input" readonly>
                                    <button type="button" class="qty-btn plus"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="item-subtotal">
                                ₦<?= number_format($subtotal, 2) ?>
                            </div>
                            <div class="item-remove">
                                <a href="cart.php?remove=<?= $item['cart_id'] ?>" class="btn-icon-remove" title="Remove item"><i class="far fa-trash-alt"></i></a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="cart-actions-row">
                        <a href="index.php" class="btn btn-text"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                        <button type="submit" name="update_cart" class="btn btn-outline"><i class="fas fa-sync-alt"></i> Update Cart</button>
                    </div>
                </div>
                
                <div class="cart-sidebar">
                    <div class="summary-card">
                        <h3>Order Summary</h3>
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>₦<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="summary-line">
                            <span>Delivery</span>
                            <span class="text-success">Free Delivery</span>
                        </div>
                        <div class="summary-line total-line">
                            <span>Total</span>
                            <span>₦<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="checkout-btn-wrapper">
                            <a href="checkout.php" class="btn btn-primary btn-block btn-large">Proceed to Checkout</a>
                        </div>
                        <div class="secure-checkout">
                            <i class="fas fa-shield-alt"></i> Secure Checkout
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
