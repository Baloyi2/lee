<?php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
$username = htmlspecialchars($_SESSION['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8');
$userRole = htmlspecialchars($_SESSION['role'] ?? 'guest', ENT_QUOTES, 'UTF-8');

// Define role-specific content
$roleTitles = [
   'admin' => 'Admin Dashboard 👑',
    'joy' => 'Your Special Day, Joy! 🎂',
    'guest' => 'Happy Birthday Joy! 🎉'
];

$roleGreetings = [
    'admin' => 'Welcome back, Admin! 👑',
    'joy' => 'Happy Birthday, Precious Joy! 💖',
    'guest' => 'Welcome to Joy\'s Birthday Celebration! 🎈'
];

$pageTitle = $roleTitles[$userRole] ?? 'Happy Birthday Joy! 🎉';
$greeting = $roleGreetings[$userRole] ?? 'Welcome!';
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
    <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎂</text></svg>">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="confetti-container"></div>

    <header class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                <span class="eyebrow">Birthday Celebration</span>
                <h1><?php echo $greeting; ?></h1>
                <p class="hero-subtitle">A warm celebration for Precious Lethabo Mateta, filled with memories, love, and blessings.</p>
                <div class="hero-actions">
                    <?php if ($userRole === 'joy'): ?>
                    <button class="btn btn-play" onclick="playSong()">🎵 Play "Birthday Song" by Eric Lloyd</button>
                    <?php endif; ?>
                    <button class="btn btn-confetti" onclick="triggerConfetti()">✨ Celebrate!</button>
                    <a href="logout.php" class="btn btn-secondary">Log out</a>
                </div>
                <p class="hero-login-note">Logged in as <strong><?= $username ?></strong> (<?php echo ucfirst($userRole); ?>)</p>
            </div>
            <div class="hero-card">
                <div class="hero-card-top">
                    <div class="hero-avatar">J</div>
                    <div class="hero-profile-text">
                        <p class="hero-name">Precious Lethabo Mateta</p>
                        <p class="hero-role">Beloved friend • April 27 birthday</p>
                    </div>
                </div>
                <div class="countdown-container">
                    <div class="countdown-header">
                        <p class="countdown-label">Birthday countdown</p>
                        <p class="birthday-date">April 27</p>
                    </div>
                    <div class="countdown-timer">
                        <div class="time-unit">
                            <span class="time-value" id="days">0</span>
                            <span class="time-label">Days</span>
                        </div>
                        <div class="time-unit">
                            <span class="time-value" id="hours">0</span>
                            <span class="time-label">Hours</span>
                        </div>
                        <div class="time-unit">
                            <span class="time-value" id="minutes">0</span>
                            <span class="time-label">Minutes</span>
                        </div>
                        <div class="time-unit">
                            <span class="time-value" id="seconds">0</span>
                            <span class="time-label">Seconds</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation -->
    <nav class="nav-tabs">
        <?php if ($userRole !== 'guest'): ?>
        <button class="nav-tab active" data-tab="gallery">Gallery</button>
        <?php endif; ?>
        <?php if ($userRole === 'joy'): ?>
        <button class="nav-tab" data-tab="messages-friends">Messages from Friends</button>
        <button class="nav-tab" data-tab="special">Special</button>
        <?php endif; ?>
    </nav>

    <?php if ($userRole !== 'guest'): ?>
    <!-- Photo Gallery Section -->
    <section id="gallery" class="tab-content active">
        <h2>Precious Memories 📸</h2>
        <div class="gallery-container">
            <div class="gallery-item">
                <img src="1.jpeg" alt="Precious Memory 1" loading="lazy">
                <p>Your favorite photo</p>
            </div>
            <div class="gallery-item">
                <img src="2.jpeg" alt="Faith & Grace" loading="lazy">
                <p>God's blessing in your life</p>
            </div>
            <div class="gallery-item">
                <img src="3.jpeg" alt="Cherished Moments" loading="lazy">
                <p>Beautiful memories with loved ones</p>
            </div>
            <div class="gallery-item">
                <img src="4.jpeg" alt="New Beginnings" loading="lazy">
                <p>Another year of blessings</p>
            </div>
        </div>
        
        <?php if ($userRole === 'joy'): ?>
        <div style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 40px;">
            <h3>📸 Friends' Photos</h3>
            <div id="friendsPhotoPosts" class="friends-posts">
                <!-- Guest photos will appear here -->
            </div>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <?php if ($userRole === 'guest'): ?>
    <!-- Messages & Wishes Section -->
    <section id="messages" class="tab-content active">
        <h2>Birthday Wishes 💖</h2>
        
        <div class="wish-input">
            <textarea id="wishText" placeholder="Write a special message for Joy..." maxlength="500"></textarea>
            <input type="text" id="wishName" placeholder="Your name (required)" maxlength="50" required>
            <button class="btn btn-primary" onclick="addWish()">Send Wish 💌</button>
        </div>

        <div class="wishes-display" id="wishesDisplay">
            <!-- Wishes will appear here -->
        </div>

        <div class="guest-features">
            <h3>🎁 Gift Ideas for Joy</h3>
            <div class="category-actions">
                <button class="btn btn-secondary" onclick="generateGiftIdea()">Get a gift idea</button>
                <p id="giftIdea" class="special-note">Need inspiration for Joy? Tap the button to reveal a thoughtful suggestion.</p>
            </div>
        </div>

        <div class="guest-features">
            <h3>📸 Share Your Photos and Wishes</h3>
            <p class="photo-note">Upload a photo. Your post can be updated or deleted within 5 days.</p>
            <div class="photo-upload">
                <input type="file" id="photoInput" accept="image/*" onchange="previewPhoto(event)">
                <label for="photoInput" class="upload-label">📷 Click to Add Photos</label>
                <button class="btn btn-primary" onclick="uploadPhotoWithWish()">📤 Share Photo</button>
                <div id="photoPreview" class="photo-preview"></div>
            </div>
            <div id="myPhotoPosts" class="my-posts"></div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($userRole === 'joy'): ?>
    <!-- Messages from Friends Section -->
    <section id="messages-friends" class="tab-content">
        <h2>💌 Messages from Friends</h2>
        <div id="friendsWishes" style="">
            <!-- Guest text wishes will appear here -->
        </div>
        <div style="margin-top: 20px; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px; font-size: 0.9em; color: #999;">
            <strong>Debug Info:</strong> <span id="debugWishesCount">Loading...</span>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($userRole === 'joy'): ?>
    <!-- Special Section -->
    <section id="special" class="tab-content">
        <h2>✨ All About Joy ✨</h2>
        
        <?php if ($userRole === 'admin'): ?>
        <div class="special-category admin-section">
            <h3>👑 Admin Dashboard</h3>
            <div class="category-content">
                <div class="card admin-card">
                    <h4>📊 App Statistics</h4>
                    <p>Total visits: <strong>Many hearts touched</strong></p>
                    <p>Wishes received: <strong>Countless blessings</strong></p>
                    <p>Birthday songs played: <strong>Endless celebrations</strong></p>
                </div>
                <div class="card admin-card">
                    <h4>⚙️ Management Tools</h4>
                    <p>Monitor app usage and user engagement</p>
                    <button class="btn btn-secondary" onclick="alert('Admin features coming soon!')">View Analytics</button>
                </div>
            </div>
        </div>
        <?php elseif ($userRole === 'joy'): ?>
        <div class="special-category joy-section">
            <h3>💖 Personal Messages for You, Joy</h3>
            <div class="category-content">
                <div class="card joy-card">
                    <p>🎂 <strong>Happy Birthday, Precious Joy!</strong> May this day be filled with as much joy as you bring to others.</p>
                </div>
                <div class="card joy-card">
                    <p>🙏 Your kindness and positivity inspire everyone around you. Keep shining your light!</p>
                </div>
                <div class="card joy-card">
                    <p>💖 You are loved, cherished, and truly special. This app was made just for you! 🎉</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="special-category">
            <h3>🙏 Warm Blessings</h3>
            <div class="category-content">
                <div class="card">
                    <p>"Trust in yourself with all your heart" - The foundation of Joy's strength</p>
                </div>
                <div class="card">
                    <p>May this year be filled with abundant grace and happiness</p>
                </div>
                <div class="card">
                    <p>Your kindness and service is a blessing to many</p>
                </div>
            </div>
        </div>

        <div class="special-category">
            <h3>💎 Special Facts About Joy</h3>
            <div class="category-content">
                <div class="card">📌 You bring joy wherever you go (that's why you're called Joy!)</div>
                <div class="card">💖 Purple is your color - a symbol of royalty and grace</div>
                <div class="card">🙌 Your kindness inspires others around you</div>
                <div class="card">✨ You make the world a better place</div>
            </div>
        </div>

        <div class="special-category">
            <h3>😄 Birthday Jokes and Laughs</h3>
            <div class="category-content">
                <div class="card" onclick="this.classList.toggle('revealed')">
                    <strong>Why do we put candles on top of cakes?</strong>
                    <p class="joke-answer">Because it's too hard to light them from the bottom! 🎂</p>
                </div>
                <div class="card" onclick="this.classList.toggle('revealed')">
                    <strong>What's special about birthdays?</strong>
                    <p class="joke-answer">They're the one day a year where you can eat cake for breakfast! 🍰</p>
                </div>
                <div class="card" onclick="this.classList.toggle('revealed')">
                    <strong>How do you know if it's Joy's birthday?</strong>
                    <p class="joke-answer">Everyone around her smiles more! 😊</p>
                </div>
            </div>
        </div>

        <div class="special-category">
            <h3>🌟 Birthday Blessing</h3>
            <div class="category-actions">
                <button class="btn btn-secondary" onclick="showBlessing()">Show blessing</button>
                <p id="blessingText" class="special-note">A warm birthday blessing will appear here.</p>
            </div>
        </div>

    </section>
    <?php endif; ?>

    <?php if ($userRole === 'joy'): ?>
    <!-- Audio Element -->
    <audio id="birthdaySong" src="The Birthday Song.mp3"></audio>
    <?php endif; ?>

    <footer class="footer">
        <p>Made with 💖 for a special birthday celebration</p>
        <p class="footer-date" id="dateDisplay"></p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
