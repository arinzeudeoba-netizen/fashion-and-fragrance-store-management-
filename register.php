<?php
// register.php
require_once 'config/database.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            if($insert->execute([$fullname, $email, $hashed])) {
                $success = "Registration successful! You can now <a href='login.php' class='text-gold'>login here</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

// Only output HTML after processing
include 'includes/header.php';
?>
<div class="auth-wrapper fade-in">
    <div class="auth-card">
        <div class="auth-header">
            <i class="far fa-id-card auth-icon"></i>
            <h2>Create Account</h2>
            <p>Join the F&F exclusive community</p>
        </div>
        
        <?php if($error): ?>
            <div class="toast-alert error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="toast-alert success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
        <?php else: ?>
            <form method="POST" action="" class="modern-form">
                <div class="floating-group">
                    <input type="text" name="fullname" id="fullname" required placeholder=" ">
                    <label for="fullname">Full Name</label>
                </div>
                <div class="floating-group">
                    <input type="email" name="email" id="email" required placeholder=" ">
                    <label for="email">Email Address</label>
                </div>
                <div class="floating-group password-group">
                    <input type="password" name="password" id="password" required placeholder=" " minlength="6">
                    <label for="password">Password</label>
                    <button type="button" class="toggle-password" tabindex="-1"><i class="far fa-eye"></i></button>
                </div>
                <div class="floating-group password-group">
                    <input type="password" name="confirm_password" id="confirm_password" required placeholder=" " minlength="6">
                    <label for="confirm_password">Confirm Password</label>
                    <button type="button" class="toggle-password" tabindex="-1"><i class="far fa-eye"></i></button>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block btn-large mt-20">Register</button>
            </form>
        <?php endif; ?>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php" class="text-gold hover-underline">Sign in</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
