<?php
// login.php
require_once 'config/database.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}

// Only output HTML after processing
include 'includes/header.php';
?>
<div class="auth-wrapper fade-in">
    <div class="auth-card">
        <div class="auth-header">
            <i class="far fa-user-circle auth-icon"></i>
            <h2>Welcome Back</h2>
            <p>Sign in to access your luxury account</p>
        </div>
        
        <?php if($error): ?>
            <div class="toast-alert error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="modern-form">
            <div class="floating-group">
                <input type="email" name="email" id="email" required placeholder=" ">
                <label for="email">Email Address</label>
            </div>
            <div class="floating-group password-group">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Password</label>
                <button type="button" class="toggle-password" tabindex="-1"><i class="far fa-eye"></i></button>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block btn-large mt-20">Sign In</button>
        </form>
        
        <div class="auth-footer">
            <p>New to F&F? <a href="register.php" class="text-gold hover-underline">Create an account</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
