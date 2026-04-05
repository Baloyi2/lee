# 🎂 Happy Birthday Joy - App Guide

## Features

### 🎯 Three Main Sections:

1. **Photo Gallery** 📸
   - Display precious memories
   - Purple-themed placeholder images
   - Ready for your custom photos

3. **Wishes & Messages** 💌
   - Friends and family can write birthday wishes
   - Messages are saved locally
   - Shows name, message, and date
   - Can delete wishes

3. **Special Moments** ✨
   - **Spiritual Blessings**: Faith-based messages
   - **Facts About Joy**: Special qualities and characteristics
   - **Birthday Jokes**: Interactive jokes (click to reveal answers)
   - **Photo Upload**: Add your own photos

## 🚀 How to Use

### View the App:
- Open XAMPP Control Panel
- Start Apache
- Navigate to: `http://localhost/lee%20joy/`
- **Choose your role and log in:**

#### Login Credentials:
- **👑 Admin**: Username: `greater` | Password: `joy2026`
- **🎂 Joy**: Username: `joy` | Password: `birthday2026`
- **🎉 Guest**: Username: `guest` | Password: `happybirthday`

#### Role Features:
- **Admin**: Full access to countdown and gallery sections
- **Joy**: Personalized birthday experience with countdown, gallery, and exclusive "Special" section with personal content, spiritual blessings, facts, jokes, and birthday blessings
- **Guest**: Exclusive access to wishes section with gift ideas generator and photo sharing features

> If you open the app locally on Apache, `.htaccess` ensures `index.php` is used as the default page.

### Customize the App:

#### Change Birthday Date:
Open `script.js` and find this line (around line 41):
```javascript
const birthday = new Date(birthdayDate, 3, 5); // April 5 (month is 0-indexed)
```
Change the numbers:
- First number (3) = month (0=Jan, 1=Feb, 2=Mar, 3=Apr, etc.)
- Second number (5) = day

#### Add Your Own Photos:
1. Go to "Special" tab
2. Click "Click to Add Photos"
3. Select images from your computer
4. Photos appear in the gallery

#### Add Birthday Wishes:
1. Go to "Wishes" tab
2. Write your message
3. Enter your name
4. Click "Send Wish"
5. Wishes are saved automatically

#### Change Colors/Theme:
Open `styles.css` and modify these color variables at the top:
```css
--primary-purple: #8B4EA8;
--light-purple: #C9A1D8;
```

Change hex codes to your preferred colors.

#### Update Special Information:
Open `index.html` and find the "Special Moments" section. Edit:
- Spiritual Blessings messages
- Facts about Joy
- Birthday jokes
- Any other text

## 📱 Mobile Friendly
The app is fully responsive - works perfectly on:
- Smartphones
- Tablets
- Laptops
- Desktop computers

## 💾 Data Storage
- **Wishes**: Stored locally in browser (persists between visits)
- **Photos**: Stored in session (refreshing the page may clear them)
- No server needed!

## 🎵 Birthday Song
The app includes a placeholder audio link. To use a custom song:
1. Upload your MP3 file to the folder
2. Open `index.html`
3. Find this line: `<audio id="birthdaySong" src="https://..."`
4. Replace with: `<audio id="birthdaySong" src="your-song.mp3"`

## 🎨 Emoji Guide
- 🎂 Birthday/Cake
- 💜 Love/Special
- ✨ Sparkles/Magical
- 🎉 Celebration
- 🙏 Spiritual/Faith
- 💌 Messages
- 📸 Photos
- 😄 Fun/Jokes

## 📁 Files Included
- `index.php` - Main app page protected by login
- `login.php` - Login form for authorized access
- `logout.php` - Log out and return to login page
- `styles.css` - Beautiful purple theme
- `script.js` - All interactivity
- `README.md` - This guide

## 🎯 Quick Tips
- Click joke cards to reveal punchlines
- Use Confetti button for celebrations
- Add messages from multiple people
- Customize everything to make it personal!

---

Made with 💜 for Precious Lethabo Mateta (Joy)

Enjoy the celebration! 🎊
