<?php
session_start();
require 'database.php';

if (isset($_POST['login'])){
    $errMsg = '';

    // Gets Data from the form 
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"]: null;

    if (empty($username) && empty($email)) {
    $errMsg = 'Enter Username or Email';
    }
    if (empty($password)) {
        $errMsg = 'Enter Password';
    }

    if ($errMsg == '') {
        try {
            $stmt = $connct->prepare('SELECT id, username, password, email FROM pdo WHERE username = :username OR email = :email');
            if ($username) {
                $stmt = $connct->prepare('SELECT id, username, password, email FROM pdo WHERE username = :username');
                $stmt->execute([':username' => $username]);
            } elseif ($email) {
                $stmt = $connct->prepare('SELECT id, username, password, email FROM pdo WHERE email = :email');
                $stmt->execute([':email' => $email]);
            }
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data == false){
                $errMsg = "User $username not found";
            }
            else {
                if(password_verify($password, $data['password'])) {
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['id'] = $data['id'];

                    header('Location: Homescreen.php');
                    exit;
                }
                else 
                    $errMsg = 'Password Incorrect';
            }
        }
        catch (PDOException $e) {
            $errMsg = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto:wght@400;500&display=swap">
    <link rel="stylesheet" href="design.css">
</head>
<body>
    <div class="wrapper">
                <div class="greeting-container" id="greeting">
                    <div class="greet">Hello</div>
                </div>

                <div class="login-container hidden" id="login">
                    <div class="login-card">
                        <div class="card-left">
                            <div class="image-cover">
                                <img src="https://i.pinimg.com/736x/8b/cf/d3/8bcfd33920b22010b50a47cf86f00284.jpg" alt="image">
                            </div>
                        </div>

                        <div class="card-right">
                            <h1 class="title">Looksmax</h1>
                            <h2 class="welcome">Welcome to Looksmaxing</h2>
                            <?php 
                                if(isset($errMsg)){
                                    echo '<div style="color:#FF0000;text-align:center;font-size:17px;">' . htmlspecialchars($errMsg) . '</div>';
                                }
                            ?>
                            <form action="index.php" method="POST">
                                <div class="input-group">
                                    <label for="username">Username or Email</label>
                                    <input type="text" name="username" id="username" required>
                                </div>
                                <div class="input-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password">
                                </div>

                                <div class="actions">
                                    <button type="submit" class="btn">Sign In</button>
                                    <a href="#" class="forget-password">Forgot password?</a>
                                </div>

                                <div class="or-divider">or</div>
                                <div class="google-signin">
                                    <button type="button" class="btn google-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px" class="google-icon"><path fill="#4285F4" d="M44.5,20H24v8.5h11.7c-1.1,3.2-3.6,5.7-6.7,6.9l6.7,5.2c3.9-3.6,6.3-8.8,6.3-14.6C44.7,24.9,44.6,22.4,44.5,20z"/><path fill="#34A853" d="M24,44c5.9,0,10.8-1.9,14.4-5.1l-6.7-5.2c-2,1.3-4.6,2-7.7,2c-5.9,0-10.8-4-12.6-9.4l-7.2,5.6C8.9,39.7,15.9,44,24,44z"/><path fill="#FBBC05" d="M11.4,26.3c-0.5-1.3-0.8-2.7-0.8-4.3s0.3-3,0.8-4.3l-7.2-5.6C2.3,15.6,1,19.1,1,22.8s1.3,7.2,3.2,10.7L11.4,26.3z"/><path fill="#EA4335" d="M24,9.5c3.2,0,6.1,1.1,8.4,3.2l6.3-6.3C34.8,2.9,29.9,1,24,1c-8.1,0-15.1,4.3-19.2,10.7l7.2,5.6C13.2,13.5,18.1,9.5,24,9.5z"/><path fill="none" d="M0,0h48v48H0V0z"/></svg>
                                        Sign in with Google
                                    </button>
                                </div>

                                <div class="create-account">
                                    <p>New to Looksmaxing? <a href="#">Create Account</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
    
    <script src="script.js"></script>
</body>
</html>