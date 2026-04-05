<?php
session_start();

if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';
$registered_guests = [];
$guests_file = 'registered_guests.php';

// Load registered guests
if (file_exists($guests_file)) {
    include $guests_file;
}

$validCredentials = [
    'joy' => ['username' => 'joy', 'password' => 'birthday2026'],
    'guest' => ['username' => 'guest', 'password' => 'happybirthday', 'email' => 'guest@birthday.app']
];

// Validation Functions
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

function isStrongPassword($password) {
    return strlen($password) >= 8;
}

function emailDomainLooksReal($email) {
    $domain = strtolower(substr(strrchr($email, '@'), 1));
    if ($domain === '' || $domain === false) {
        return false;
    }

    if (function_exists('checkdnsrr')) {
        if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A') || checkdnsrr($domain, 'AAAA')) {
            return true;
        }
    }

    if (function_exists('getmxrr')) {
        $mx_hosts = [];
        if (getmxrr($domain, $mx_hosts) && !empty($mx_hosts)) {
            return true;
        }
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? 'login');
    
    if ($action === 'register') {
        // Registration logic with validations
        $reg_name = trim($_POST['reg_name'] ?? '');
        $reg_email = trim($_POST['reg_email'] ?? '');
        $reg_username = trim($_POST['reg_username'] ?? '');
        $reg_password = trim($_POST['reg_password'] ?? '');
        $reg_confirm = trim($_POST['reg_confirm'] ?? '');

        // Validation checks
        if (empty($reg_name) || empty($reg_email) || empty($reg_username) || empty($reg_password) || empty($reg_confirm)) {
            $error = 'All fields are required.';
        } elseif (strlen($reg_name) < 2) {
            $error = 'Name must be at least 2 characters long.';
        } elseif (!isValidEmail($reg_email)) {
            $error = 'Please enter a valid email address.';
        } elseif (!emailDomainLooksReal($reg_email)) {
            $error = 'This email domain does not appear to exist. Please use a real email address.';
        } elseif (!isValidUsername($reg_username)) {
            $error = 'Username must be 3 to 20 characters and use only letters, numbers, or underscore.';
        } elseif ($reg_password !== $reg_confirm) {
            $error = 'Passwords do not match.';
        } elseif (!isStrongPassword($reg_password)) {
            $error = 'Password must be at least 8 characters long.';
        } elseif (isset($registered_guests[$reg_username])) {
            $error = 'Username already taken. Please choose another.';
        } else {
            // Check if email is already registered
            $email_exists = false;
            foreach ($registered_guests as $guest) {
                if (strtolower($guest['email']) === strtolower($reg_email)) {
                    $email_exists = true;
                    break;
                }
            }
            
            if ($email_exists) {
                $error = 'Email already registered. Please use a different email or log in.';
            } else {
                $registered_guests[$reg_username] = [
                    'name' => $reg_name,
                    'email' => strtolower($reg_email),
                    'password' => password_hash($reg_password, PASSWORD_DEFAULT)
                ];

                $file_content = "<?php\n\$registered_guests = " . var_export($registered_guests, true) . ";\n?>";
                file_put_contents($guests_file, $file_content);

                $success = 'Account created successfully. You can now log in with your username or email.';
            }
        }
    } else {
        // Login logic with username or email
        $login_input = trim($_POST['login_input'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? '');

        if (empty($login_input) || empty($password) || empty($role)) {
            $error = 'Please enter all required fields.';
        } else {
            $authenticated = false;
            $login_username = '';

            // Check Joy credentials
            if ($role !== 'guest' && isset($validCredentials[$role])) {
                if (($login_input === $validCredentials[$role]['username'] || 
                     $login_input === $validCredentials[$role]['email']) &&
                    $password === $validCredentials[$role]['password']) {
                    $authenticated = true;
                    $login_username = $validCredentials[$role]['username'];
                }
            }
            // Check registered guest credentials by username or email
            elseif ($role === 'guest') {
                foreach ($registered_guests as $username => $guest) {
                    if (($login_input === $username || 
                         strtolower($login_input) === strtolower($guest['email'])) &&
                        password_verify($password, $guest['password'])) {
                        $authenticated = true;
                        $login_username = $username;
                        break;
                    }
                }
                
                // Also check default guest credentials
                if (!$authenticated && 
                    $login_input === $validCredentials['guest']['username'] &&
                    $password === $validCredentials['guest']['password']) {
                    $authenticated = true;
                    $login_username = $validCredentials['guest']['username'];
                }
            }

            if ($authenticated) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $login_username;
                $_SESSION['role'] = $role;
                header('Location: index.php');
                exit;
            }

            $error = 'Invalid login credentials. Please check your username, email, and password.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#8B4EA8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>Login - Joy's Birthday App</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at top, #4d1d83 0%, #1d0632 60%, #10021b 100%);
            padding: 20px;
        }
        .login-card {
            width: min(480px, 95vw);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 30px;
            padding: 0;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.35);
            color: white;
            overflow: hidden;
        }
        .login-header {
            padding: 30px 30px 20px;
            text-align: center;
        }
        .login-header h1 {
            margin: 0 0 8px 0;
            font-size: 2rem;
        }
        .login-header p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }
        .tab-buttons {
            display: flex;
            gap: 0;
            padding: 0 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        .tab-button {
            flex: 1;
            padding: 14px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -1px;
        }
        .tab-button.active {
            color: white;
            border-bottom-color: #8b4ea8;
        }
        .tab-button:hover {
            color: rgba(255, 255, 255, 0.8);
        }
        .login-content {
            padding: 0 30px 30px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .login-card input {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.08);
            color: white;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);
            box-sizing: border-box;
            font-size: 1rem;
        }
        .login-card input::placeholder {
            color: rgba(255, 255, 255, 0.62);
        }
        input[name="verify_code"] {
            text-align: center;
            font-size: 1.1rem;
            letter-spacing: 3px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .login-card button[type="submit"] {
            width: 100%;
            padding: 14px 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #8b4ea8, #d49ae8);
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 12px 22px rgba(139, 78, 168, 0.32);
            transition: all 0.3s ease;
        }
        .login-card button[type="submit"]:hover {
            background: linear-gradient(135deg, #9957b5, #e0aef5);
            box-shadow: 0 16px 28px rgba(139, 78, 168, 0.4);
        }
        .error {
            padding: 12px 14px;
            margin-bottom: 16px;
            background: rgba(255, 107, 107, 0.15);
            border-radius: 12px;
            color: #ffb3c6;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }
        .success {
            padding: 12px 14px;
            margin-bottom: 16px;
            background: rgba(107, 255, 178, 0.15);
            border-radius: 12px;
            color: #a8ffb3;
            border: 1px solid rgba(107, 255, 178, 0.3);
        }
        .role-selection {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }
        .role-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .role-option:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .role-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #8b4ea8;
        }
        .role-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }
        .login-hints {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .login-hints p {
            margin: 6px 0;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }
        .hint-title {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>Joy's Birthday App 🎂</h1>
            <p>Join the celebration</p>
        </div>

        <div class="tab-buttons">
            <button class="tab-button active" onclick="switchTab('login')">Login</button>
            <button class="tab-button" onclick="switchTab('register')">Register as Guest</button>
        </div>

        <div class="login-content">
            <!-- Login Tab -->
            <div id="login" class="tab-content active">
                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <form method="post" action="login.php">
                    <input type="hidden" name="action" value="login">
                    <div class="role-selection">
                        <label class="role-option">
                            <input type="radio" name="role" value="joy" required>
                            <span class="role-label">Joy 🎂</span>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="guest" required>
                            <span class="role-label">Guest 🎉</span>
                        </label>
                    </div>
                    <input type="text" name="login_input" placeholder="Username or Email" required autocomplete="username">
                    <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                    <button type="submit">Log In</button>
                </form>
            </div>

            <!-- Register Tab -->
            <div id="register" class="tab-content">
                <?php if ($success): ?>
                    <div class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($error && isset($_POST['action']) && $_POST['action'] === 'register'): ?>
                    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                
                <form method="post" action="login.php">
                    <input type="hidden" name="action" value="register">
                    <input type="text" name="reg_name" placeholder="Full Name (min 2 characters)" required minlength="2">
                    <input type="email" name="reg_email" placeholder="Email Address" required>
                    <input type="text" name="reg_username" placeholder="Username (3-20 chars: letters, numbers, _)" required pattern="[a-zA-Z0-9_]{3,20}">
                    <input type="password" name="reg_password" placeholder="Password (min 8 chars)" required minlength="8">
                    <input type="password" name="reg_confirm" placeholder="Confirm Password" required minlength="8">
                    <button type="submit">Create Account</button>
                </form>
                <div class="login-hints">
                    <div class="hint-title">Registration Requirements:</div>
                    <p>Name: at least 2 characters</p>
                    <p>Email: valid email format</p>
                    <p>Email domain: should exist</p>
                    <p>Username: 3 to 20 characters using letters, numbers, or underscore</p>
                    <p>Password: at least 8 characters</p>
                    <p>Each username and email can only be used once</p>
                    <p style="margin-top: 10px; font-size: 0.75rem; color: rgba(255,255,255,0.5);">🎉 You can log in immediately after creating your account.</p>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
