// Tab Navigation
document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const tabName = tab.getAttribute('data-tab');
        switchTab(tabName);
    });
});

function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.nav-tab').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    document.getElementById(tabName).classList.add('active');

    // Add active class to clicked button
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    
    // Refresh displays when switching tabs
    if (tabName === 'gallery') {
        displayFriendsPhotoPosts();
    } else if (tabName === 'messages-friends') {
        displayFriendsWishes();
    }
}

function padNumber(num) {
    return num < 10 ? '0' + num : num;
}

// Countdown Timer
function updateCountdown() {
    // Set birthday date - you can customize this
    const birthdayDate = new Date().getFullYear();
    const birthday = new Date(birthdayDate, 3, 27); // April 27 (month is 0-indexed)
    
    // If birthday has passed, use next year
    if (new Date() > birthday) {
        birthday.setFullYear(birthdayDate + 1);
    }

    const now = new Date().getTime();
    const distance = birthday.getTime() - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('days').textContent = padNumber(days);
    document.getElementById('hours').textContent = padNumber(hours);
    document.getElementById('minutes').textContent = padNumber(minutes);
    document.getElementById('seconds').textContent = padNumber(seconds);

    if (distance < 0) {
        document.querySelector('.countdown-timer').innerHTML = `
            <div style="grid-column: 1/-1; color: #8B4EA8; font-size: 1.5em; font-weight: bold;">
                🎉 Happy Birthday Joy! 🎉
            </div>
        `;
    }
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();

// Display current date
function updateDate() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date().toLocaleDateString('en-US', options);
    document.getElementById('dateDisplay').textContent = `Today: ${today}`;
}
updateDate();

// Birthday Song
function playSong() {
    const song = document.getElementById('birthdaySong');
    if (song.paused) {
        song.play();
    } else {
        song.pause();
        song.currentTime = 0;
    }
}

// Confetti Effect
function triggerConfetti() {
    const container = document.getElementById('confetti-container');
    
    for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.backgroundColor = getRandomColor();
        confetti.style.animationDelay = Math.random() * 0.3 + 's';
        confetti.style.animationDuration = (Math.random() * 2 + 2.5) + 's';
        
        container.appendChild(confetti);

        setTimeout(() => {
            confetti.remove();
        }, 3000);
    }
}

function getRandomColor() {
    const colors = ['#FFD700', '#8B4EA8', '#C9A1D8', '#FF69B4', '#FF6B6B', '#4ECDC4'];
    return colors[Math.floor(Math.random() * colors.length)];
}

function generateGiftIdea() {
    const ideas = [
        'A beautiful purple bouquet with a heartfelt note',
        'A small memory box filled with meaningful notes from friends',
        'A sweet cake or dessert decorated with flowers and candles',
        'A set of luxury candles with calming scents like lavender and vanilla',
        'A handmade coupon book with offers like "coffee date" or "movie night"',
        'A custom portrait painting of Joy with a nature theme',
        'A spa gift package with bath bombs, face masks, and relaxation items',
        'A beautiful jewelry piece (bracelet, necklace) with a meaningful charm or inscription',
        'A personalized video montage with birthday messages from friends',
        'A cookbook focused on her favorite cuisine to enjoy cooking together',
        'A potted flowering plant (orchids, roses, or tulips) for her home',
        'A gift basket filled with her favorite snacks, teas, and treats',
        'A journaling art set for creative expression and reflection',
        'A beautiful welcome sign or wall art with an inspiring quote for her home',
        'A custom puzzle with a cherished photo of Joy and friends',
        'A class or workshop ticket (art, music, cooking) to explore new interests',
        'A handwritten letter of appreciation shared by friends and family',
        'A purple-themed picnic basket for outdoor gatherings and adventures',
        'A personalized greeting card signed by all her friends',
        'A memory scrapbook documenting special moments and milestones',
        'A gift of time - planning a surprise birthday outing or adventure',
        'A cozy blanket or throw in her favorite purple shade',
        'A subscription to a favorite magazine or streaming service',
        'A set of gourmet chocolates or her favorite candies',
        'A personalized mug with a fun quote or her name',
        'A beautiful scarf or accessory in coordinating colors',
        'A plant care kit for her green thumb',
        'A fun board game or puzzle to enjoy with friends',
        'A donation made in her name to a favorite charity',
        'A beautiful photo album for collecting memories'
    ];
    const idea = ideas[Math.floor(Math.random() * ideas.length)];
    document.getElementById('giftIdea').textContent = idea;
}

function showBlessing() {
    const blessings = [
        'May peace, joy, and every blessing fill your day.',
        'May your heart be filled with hope, your spirit with strength, and your year with grace.',
        'May your birthday be as beautiful and kind as you are, Joy.',
        'May every step you take be guided by love and positivity in the year ahead.'
    ];
    const blessing = blessings[Math.floor(Math.random() * blessings.length)];
    document.getElementById('blessingText').textContent = blessing;
}

function toggleEnvelope(card) {
    card.classList.toggle('open');
}

function closeEnvelope(event) {
    event.stopPropagation();
    const card = event.target.closest('.envelope-card');
    card.classList.remove('open');
}

// Wishes System (Local Storage)
function addWish() {
    const text = document.getElementById('wishText').value.trim();
    const name = document.getElementById('wishName').value.trim();

    if (text && name) {
        // Get existing wishes
        let wishes = JSON.parse(localStorage.getItem('wishes')) || [];
        
        // Add new wish
        wishes.push({
            name: name,
            text: text,
            date: new Date().toLocaleDateString()
        });

        // Save to localStorage
        localStorage.setItem('wishes', JSON.stringify(wishes));

        // Clear inputs
        document.getElementById('wishText').value = '';
        document.getElementById('wishName').value = '';

        // Refresh display
        displayWishes();

        // Show success feedback
        showNotification('Wish added! 💜');
    } else {
        showNotification('Please fill in all fields!', 'error');
    }
}

function displayWishes() {
    const wishes = JSON.parse(localStorage.getItem('wishes')) || [];
    const display = document.getElementById('wishesDisplay');

    if (wishes.length === 0) {
        display.innerHTML = `
            <div style="text-align: center; color: white; padding: 40px 20px;">
                <p style="font-size: 1.1em; margin-bottom: 20px;">No wishes yet... Be the first! 💌</p>
            </div>
        `;
        return;
    }

    display.innerHTML = wishes.map((wish, index) => `
        <div class="wish-card">
            <div class="wish-author">💌 ${escapeHtml(wish.name)}</div>
            <div class="wish-text">${escapeHtml(wish.text)}</div>
            <div style="font-size: 0.8em; color: #999; margin-top: 8px;">${wish.date}</div>
            <button onclick="deleteWish(${index})" style="margin-top: 8px; padding: 5px 10px; background: #ffebee; color: #c62828; border: none; border-radius: 5px; cursor: pointer; font-size: 0.85em;">Delete</button>
        </div>
    `).join('');
}

function deleteWish(index) {
    let wishes = JSON.parse(localStorage.getItem('wishes')) || [];
    wishes.splice(index, 1);
    localStorage.setItem('wishes', JSON.stringify(wishes));
    displayWishes();
    showNotification('Wish deleted');
}

// Photo Upload
let uploadedPhotos = [];

function previewPhoto(event) {
    const files = event.target.files;
    if (files.length === 0) return;

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            uploadedPhotos.push(e.target.result);
            displayPhotos();
        };
        reader.readAsDataURL(file);
    });
}

function displayPhotos() {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = uploadedPhotos.map((photo, index) => `
        <div class="preview-item">
            <img src="${photo}" alt="Photo ${index + 1}">
            <button onclick="deletePhoto(${index})" style="position: absolute; top: 5px; right: 5px; background: rgba(255,255,255,0.9); border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; font-size: 0.8em;">Delete</button>
        </div>
    `).join('');
}

function deletePhoto(index) {
    uploadedPhotos.splice(index, 1);
    displayPhotos();
    document.getElementById('photoInput').value = '';
}

// Notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === 'error' ? '#FF6B6B' : '#8B4EA8'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideUp 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        notification.style.transition = 'all 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 2500);
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Photo Upload with Wishes
function uploadPhotoWithWish() {
    const name = document.getElementById('wishName').value.trim();
    const photoInput = document.getElementById('photoInput');
    
    if (!name) {
        showNotification('Please enter your name in the Birthday Wishes section first', 'error');
        return;
    }
    
    if (!photoInput.files.length) {
        showNotification('Please select a photo', 'error');
        return;
    }
    
    const file = photoInput.files[0];
    const reader = new FileReader();
    
    reader.onload = (e) => {
        const photoPost = {
            id: Date.now(),
            name: escapeHtml(name),
            message: '',
            photo: e.target.result,
            timestamp: new Date().toISOString(),
            date: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
        };
        
        let photoPosts = JSON.parse(localStorage.getItem('photoPosts')) || [];
        photoPosts.push(photoPost);
        localStorage.setItem('photoPosts', JSON.stringify(photoPosts));
        
        // Clear form
        document.getElementById('wishName').value = '';
        photoInput.value = '';
        document.getElementById('photoPreview').innerHTML = '';
        
        showNotification('Photo posted successfully!');
        displayMyPhotoPosts();
        displayFriendsPhotoPosts();
    };
    
    reader.readAsDataURL(file);
}

function displayMyPhotoPosts() {
    const container = document.getElementById('myPhotoPosts');
    if (!container) return;
    
    let photoPosts = JSON.parse(localStorage.getItem('photoPosts')) || [];
    const fiveDaysMs = 5 * 24 * 60 * 60 * 1000;
    const now = new Date().getTime();
    
    const myPosts = photoPosts.filter(post => {
        const postTime = new Date(post.timestamp).getTime();
        const age = now - postTime;
        return age <= fiveDaysMs;
    });
    
    if (myPosts.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">No posts yet</p>';
        return;
    }
    
    container.innerHTML = '<h4 style="margin: 20px 0 15px 0;">My Posts (Editable within 5 days)</h4>' + myPosts.map((post, index) => {
        const postTime = new Date(post.timestamp).getTime();
        const age = now - postTime;
        const daysLeft = Math.ceil((fiveDaysMs - age) / (24 * 60 * 60 * 1000));
        
        return `
            <div class="my-post-card" style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <div>
                        <strong>${post.name}</strong> • <small>${post.date}</small>
                        <div style="font-size: 0.9em; color: #FFD700; margin-top: 3px;">Days left to edit: ${daysLeft}</div>
                    </div>
                </div>
                <img src="${post.photo}" style="width: 100%; max-height: 250px; border-radius: 8px; object-fit: contain; margin: 10px 0;">
                ${post.message ? `<p style="margin: 10px 0; color: #f4f2ff;">${post.message}</p>` : ''}
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-secondary" onclick="editPhotoPost(${post.id})" style="padding: 8px 12px; font-size: 0.9em;">Edit</button>
                    <button class="btn btn-secondary" onclick="deletePhotoPost(${post.id})" style="padding: 8px 12px; font-size: 0.9em;">Delete</button>
                </div>
            </div>
        `;
    }).join('');
}

function displayFriendsWishes() {
    const container = document.getElementById('friendsWishes');
    if (!container) return;
    
    // Get all wishes from localStorage
    const storedWishes = localStorage.getItem('wishes');
    console.log('Raw localStorage wishes:', storedWishes);
    
    let wishes = [];
    try {
        wishes = storedWishes ? JSON.parse(storedWishes) : [];
    } catch (e) {
        console.error('Error parsing wishes:', e);
    }
    
    console.log('Parsed wishes array:', wishes);
    
    // Update debug info
    const debugEl = document.getElementById('debugWishesCount');
    if (debugEl) {
        if (wishes.length === 0) {
            debugEl.textContent = 'No wishes found in localStorage';
        } else {
            debugEl.textContent = `Found ${wishes.length} message(s) from friends`;
        }
    }
    
    if (wishes.length === 0) {
        container.innerHTML = '';
        return;
    }
    
    // Display wishes
    container.innerHTML = wishes.map((wish, idx) => `
        <div style="background: rgba(139, 78, 168, 0.1); padding: 15px; border-radius: 10px; margin-bottom: 15px; border-left: 4px solid #FFD700;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <strong style="color: #FFD700;">${wish.name}</strong> • <small>${wish.date}</small>
                </div>
            </div>
            <p style="margin: 10px 0; color: #f4f2ff; font-style: italic;">"${wish.text}"</p>
        </div>
    `).join('');
}

function displayFriendsPhotoPosts() {
    const container = document.getElementById('friendsPhotoPosts');
    
    if (!container) {
        console.log('ERROR: friendsPhotoPosts container not found');
        return;
    }
    
    // Get all photos from localStorage
    const storedData = localStorage.getItem('photoPosts');
    console.log('Raw localStorage photoPosts:', storedData);
    
    let photoPosts = [];
    try {
        photoPosts = storedData ? JSON.parse(storedData) : [];
    } catch (e) {
        console.error('Error parsing photoPosts:', e);
    }
    
    console.log('Parsed photoPosts array:', photoPosts);
    
    if (photoPosts.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: #999; padding: 20px;">No photos shared yet. Be the first to share!</p>';
        return;
    }
    
    // Sort by most recent first
    photoPosts.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
    
    container.innerHTML = photoPosts.map((post, idx) => {
        console.log(`Rendering post ${idx}:`, post);
        return `
        <div class="friend-post-card" style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px solid rgba(255,105,180,0.3);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <strong style="color: #FFD700;">${post.name}</strong> • <small>${post.date}</small>
                </div>
            </div>
            <img src="${post.photo}" style="width: 100%; max-height: 300px; border-radius: 8px; object-fit: contain; margin: 10px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
            ${post.message ? `<p style="margin: 10px 0; color: #f4f2ff; font-style: italic;">"${post.message}"</p>` : ''}
        </div>
    `}).join('');
}

function editPhotoPost(postId) {
    showNotification('Edit feature coming soon!');
    // Future: Implement edit functionality
}

function deletePhotoPost(postId) {
    const fiveDaysMs = 5 * 24 * 60 * 60 * 1000;
    const now = new Date().getTime();
    
    let photoPosts = JSON.parse(localStorage.getItem('photoPosts')) || [];
    const post = photoPosts.find(p => p.id === postId);
    
    if (!post) return;
    
    const postTime = new Date(post.timestamp).getTime();
    const age = now - postTime;
    
    if (age > fiveDaysMs) {
        showNotification('This post is older than 5 days and cannot be deleted', 'error');
        return;
    }
    
    if (confirm('Are you sure you want to delete this post?')) {
        photoPosts = photoPosts.filter(p => p.id !== postId);
        localStorage.setItem('photoPosts', JSON.stringify(photoPosts));
        showNotification('Post deleted successfully');
        displayMyPhotoPosts();
        displayFriendsPhotoPosts();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    // Display content
    displayWishes();
    displayMyPhotoPosts();
    displayFriendsPhotoPosts();
    displayFriendsWishes();
    
    // Load uploaded photos from session
    const savedPhotos = sessionStorage.getItem('uploadedPhotos');
    if (savedPhotos) {
        uploadedPhotos = JSON.parse(savedPhotos);
        displayPhotos();
    }

    // Prevent zoom on double-tap for iOS
    let lastTouchEnd = 0;
    document.addEventListener('touchend', (event) => {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Improve touch targets
    const touchTargets = document.querySelectorAll('.btn, .nav-tab, .card, .envelope-card');
    touchTargets.forEach(target => {
        target.addEventListener('touchstart', () => {
            target.style.transform = 'scale(0.98)';
        });
        target.addEventListener('touchend', () => {
            setTimeout(() => {
                target.style.transform = '';
            }, 150);
        });
    });

    // Handle orientation changes
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 100);
    });

    // Improve form inputs on mobile
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            setTimeout(() => {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        });
    });
});

// Save photos to session storage
window.addEventListener('beforeunload', () => {
    sessionStorage.setItem('uploadedPhotos', JSON.stringify(uploadedPhotos));
});

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && e.ctrlKey) {
        const activeTab = document.querySelector('.tab-content.active');
        if (activeTab.id === 'messages') {
            addWish();
        }
    }
});
