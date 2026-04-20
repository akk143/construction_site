# Lotus Skyline Construction - Property Management System

A modern, API-driven property listing and management platform with integrated Stripe payment processing for property deposits. Built with PHP, MySQL, and vanilla JavaScript.

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Database Setup](#-database-setup)
- [Stripe Configuration](#-stripe-configuration)
- [Webhook Setup](#-webhook-setup)
- [API Integration](#-api-integration)
- [Project Structure](#-project-structure)
- [Usage Guide](#-usage-guide)
- [Key Files Explanation](#-key-files-explanation)
- [Troubleshooting](#-troubleshooting)
- [Development Notes](#-development-notes)

---

## ✨ Features

### Property Management
- **API-Powered Property Listing** - Integrates with GitHub mock real estate API
- **Fallback System** - Automatically switches to local database if API is unavailable
- **Advanced Filtering** - Property type, location, price, area, and availability
- **Property Detail Pages** - Comprehensive property information with gallery
- **Status Tracking** - Available, Reserved, Sold Out status tracking
- **UTF-8 Character Support** - Handles international property locations

### Payment Processing
- **Stripe Integration** - Secure payment processing for property deposits
- **10% Deposit System** - Calculates 10% of property price as deposit
- **Multi-Currency Support** - MMK to USD conversion (configurable rate)
- **Receipt Management** - Automatic receipt generation and storage
- **Webhook Verification** - Secure Stripe webhook signature validation

### User Experience
- **Modern UI Design** - Clean, professional interface with gradient accents
- **Responsive Grid Layout** - Featured property hero + 2-column responsive grid
- **Smooth Animations** - Hover effects and transitions
- **Pagination System** - Efficient property browsing with ellipsis logic
- **Loading States** - User feedback during API calls

---

## 🛠 Technology Stack

### Backend
- **PHP 8.2.4** - Server-side scripting
- **MySQL** - Database management
- **MySQLi** - Database driver with UTF-8 support
- **cURL** - HTTP requests for API integration and Stripe

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with gradients and animations
- **Vanilla JavaScript** - No frameworks (lightweight and fast)
- **Bootstrap Icons 1.11.1** - Icon library

### External Services
- **Stripe** - Payment processing
- **GitHub Mock API** - Real estate property data
- **Picsum Photos** - Placeholder images

### Server Requirements
- Apache 2.4.56 with mod_perl
- OpenSSL 1.1.1t
- PHP 8.2.4 or higher
- MySQL 5.7 or higher
- XAMPP (or similar LAMP stack)

---

## 📦 Installation

### Prerequisites
- XAMPP/LAMP stack installed
- Composer (optional, for future dependencies)
- Git (for cloning the project)

### Step 1: Clone/Download Project

```bash
# Navigate to XAMPP htdocs directory
cd /Applications/XAMPP/xamppfiles/htdocs/phpbatch4

# Clone or extract the project
git clone <repository-url> P00199731_Pyone_Zon_Phu_ISP_V4
cd P00199731_Pyone_Zon_Phu_ISP_V4
```

### Step 2: Set Up Permissions

```bash
# Make directories writable
chmod -R 755 Admin/imgUpload/
chmod -R 755 User/
chmod -R 755 DB/
```

### Step 3: Start XAMPP Services

```bash
# On macOS
sudo /Applications/XAMPP/xamppfiles/apache2 start
sudo /Applications/XAMPP/xamppfiles/mysql/bin/mysqld_safe

# On Linux
sudo /opt/lampp/xampp start
```

### Step 4: Verify Installation

```bash
# Check PHP version
php -v

# Check if Apache is running
curl http://localhost

# Navigate to project
# http://localhost/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/User/index.php
```

---

## 🗄️ Database Setup

### Step 1: Create Database

```bash
# Connect to MySQL
mysql -u root -p

# In MySQL CLI
CREATE DATABASE construction_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE construction_db;
```

### Step 2: Import Database Schema

```bash
# Navigate to project root
cd /Applications/XAMPP/xamppfiles/htdocs/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4

# Import the SQL file
mysql -u root -p construction_db < construction_db.sql
```

### Step 3: Verify Database Tables

```sql
-- Connect to MySQL
mysql -u root -p construction_db

-- Check tables
SHOW TABLES;

-- Verify Property table structure
DESCRIBE Property;
DESCRIBE Property_type;
DESCRIBE Purchase_Property;
DESCRIBE Payment;
```

### Required Tables

**Property Table**
```sql
CREATE TABLE Property (
    property_ID INT PRIMARY KEY AUTO_INCREMENT,
    property_name VARCHAR(255),
    property_price DECIMAL(15,2),
    property_location VARCHAR(255),
    property_area DECIMAL(10,2),
    property_profile VARCHAR(255),
    property_status VARCHAR(50) DEFAULT 'Available',
    property_description LONGTEXT,
    pt_ID INT,
    no_of_bedroom INT,
    no_of_bathroom INT,
    built_year INT,
    listing_date DATE,
    KEY property_status (property_status)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
```

**Property_type Table**
```sql
CREATE TABLE Property_type (
    pt_ID INT PRIMARY KEY AUTO_INCREMENT,
    ptype VARCHAR(100)
);
```

**Purchase_Property Table**
```sql
CREATE TABLE Purchase_Property (
    pp_ID INT PRIMARY KEY AUTO_INCREMENT,
    property_ID INT,
    client_ID INT,
    deposit_amount DECIMAL(15,2),
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_ID) REFERENCES Property(property_ID)
);
```

**Payment Table**
```sql
CREATE TABLE Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    purchase_ID INT,
    stripe_session_id VARCHAR(255),
    amount_usd DECIMAL(10,2),
    amount_mmk DECIMAL(15,2),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (purchase_ID) REFERENCES Purchase_Property(pp_ID)
);
```

### UTF-8 Encoding Fix

If you encounter encoding issues with special characters:

```sql
-- Alter existing table to UTF-8
ALTER TABLE Property CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE Property_gallery CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
```

---

## 💳 Stripe Configuration

### Step 1: Create Stripe Account

1. Go to [Stripe Dashboard](https://dashboard.stripe.com)
2. Sign up for a Stripe account
3. Complete email verification
4. Verify your business information
5. Activate your account

### Step 2: Get API Keys

1. Go to **Developers** → **API Keys**
2. Copy:
   - **Publishable Key** (starts with `pk_`)
   - **Secret Key** (starts with `sk_`)
3. Keep these keys secure - never share them

### Step 3: Configure Keys

Edit `User/stripe_config.php`:

```php
<?php

define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_KEY_HERE');
define('STRIPE_SECRET_KEY', 'sk_test_YOUR_SECRET_KEY_HERE');
define('STRIPE_WEBHOOK_SECRET', 'whsec_YOUR_WEBHOOK_SECRET_HERE');

define('STRIPE_CURRENCY', 'usd');
define('MMK_TO_USD_RATE', 4000); // Adjust conversion rate as needed
define('STRIPE_COMPANY_NAME', 'Your Company Name');
define('STRIPE_STATEMENT_DESCRIPTOR', 'Your Company'); // Max 22 chars
```

### Step 4: Test Keys

Verify keys are correct:

```bash
# Test API connection
curl https://api.stripe.com/v1/account \
  -u sk_test_YOUR_SECRET_KEY:
```

**Test Credentials** (for sandbox testing):
- Card: `4242 4242 4242 4242`
- Expiry: Any future date (e.g., `12/25`)
- CVC: Any 3 digits (e.g., `123`)
- ZIP: Any valid ZIP code (e.g., `12345`)

---

## 🔐 Webhook Setup

Webhooks allow Stripe to notify your application about payment events in real-time.

### Step 1: Create Webhook Endpoint

1. Go to **Developers** → **Webhooks**
2. Click **Add Endpoint**
3. Enter endpoint URL: `https://yourdomain.com/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/User/stripe_webhook.php`
4. Select events to listen for:
   - ✅ `checkout.session.completed` (required)
   - ✅ `payment_intent.succeeded` (optional)
   - ✅ `charge.refunded` (optional)

### Step 2: Get Webhook Secret

1. Click on the endpoint you created
2. Scroll to **Signing secret**
3. Click **Reveal** to show the secret
4. Copy the secret (starts with `whsec_`)

### Step 3: Configure Webhook Secret

In `User/stripe_config.php`:

```php
define('STRIPE_WEBHOOK_SECRET', 'whsec_YOUR_WEBHOOK_SECRET_HERE');
```

### Step 4: Deploy to HTTPS

**Important**: Stripe webhooks require HTTPS in production. For local testing:

#### Option A: Use ngrok for Local Testing

```bash
# Install ngrok
# https://ngrok.com/download

# Start ngrok tunnel
ngrok http 80

# Output: Forwarding                    https://abc123.ngrok.io -> http://localhost:80

# Update Stripe webhook endpoint URL
# https://abc123.ngrok.io/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/User/stripe_webhook.php
```

#### Option B: Use Stripe CLI (Recommended)

```bash
# Install Stripe CLI
# https://stripe.com/docs/stripe-cli

# Forward webhook events to local endpoint
stripe listen --forward-to localhost/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/User/stripe_webhook.php

# Copy and save the webhook signing secret
# Update User/stripe_config.php with this secret
```

### Step 5: Verify Webhook Configuration

```bash
# Test webhook endpoint
curl -X POST http://localhost/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/User/stripe_webhook.php \
  -H "Content-Type: application/json" \
  -d '{"type":"test.message"}'

# Should return: {"error":"Invalid Stripe signature."}
# (This is expected for unsigned requests)
```

### Webhook Event Handling

The system handles these events in `User/stripe_webhook.php`:

| Event | Action |
|-------|--------|
| `checkout.session.completed` | Create purchase record and payment entry |
| `charge.refunded` | Log refund (can be extended for refund handling) |

### Webhook Security

The webhook endpoint verifies all requests using:

```php
function stripe_verify_signature($payload, $sigHeader, $secret) {
    // Stripe uses HMAC-SHA256 for signing
    // The signature header contains: t=timestamp,v1=signature
    // Prevents unauthorized webhook calls
}
```

---

## 🌐 API Integration

### Real Estate API Source

The system fetches property data from GitHub mock API:

```
https://raw.githubusercontent.com/anshumansinha1/real-estate-mock-api/master/db.json
```

### API Data Structure

```json
{
  "real-estate-data": {
    "listings": [
      {
        "property_id": 1,
        "property_name": "Sample Property",
        "property_type": "house",
        "address": "123 Main St",
        "city": "Yangon",
        "price": 500000,
        "square_footage": 2500,
        "year_built": 2020,
        "description": "Beautiful property...",
        "listing_date": "2024-01-15",
        "property_profile": "https://picsum.photos/seed/property1/500/350"
      }
    ]
  }
}
```

### How It Works

1. **API Request** (`DB/getProperties.php`):
   - Fetches data from GitHub API
   - Validates JSON response
   - Auto-inserts into local database

2. **Fallback System**:
   - If API unreachable, uses local database
   - Returns response with `source` flag (`api` or `local`)
   - Frontend shows notice when using local data

3. **Database Sync**:
   - Properties automatically inserted into `Property` table
   - Duplicate checking prevents duplicates
   - UTF-8 encoding ensures special characters work

### API Endpoint Usage

```bash
# Get properties with pagination
curl "http://localhost/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/DB/getProperties.php?page=1&perPage=3"

# Response:
{
  "success": true,
  "page": 1,
  "perPage": 3,
  "total": 105,
  "totalPages": 35,
  "properties": [
    {
      "property_ID": 1,
      "property_name": "...",
      "property_price": 500000,
      ...
    }
  ],
  "source": "api"
}
```

---

## 📁 Project Structure

```
P00199731_Pyone_Zon_Phu_ISP_V4/
├── README.md (this file)
├── index.php
├── construction_db.sql
├── skills-lock.json
│
├── Admin/
│   ├── index.php
│   ├── dashboard.php
│   ├── project.php
│   ├── property.php
│   ├── service.php
│   ├── app.js
│   ├── style.css
│   └── imgUpload/
│       ├── property_image/
│       ├── property_profile/
│       └── project_image/
│
├── User/
│   ├── index.php (Homepage)
│   ├── property.php (Property Listing)
│   ├── propertyDetail.php (Property Details)
│   ├── purchaseProperty.php (Checkout)
│   ├── stripe_config.php (⭐ KEY: Stripe Configuration)
│   ├── stripe_webhook.php (⭐ KEY: Webhook Handler)
│   ├── create_checkout_session.php (Stripe Session Creator)
│   ├── stripe_success.php (Success Page)
│   ├── stripe_cancel.php (Cancel Page)
│   ├── header.php
│   ├── footer.php
│   ├── user.css (Enhanced UI styles)
│   └── user.js
│
├── DB/
│   ├── connection.php (Database connection)
│   ├── getProperties.php (⭐ KEY: API + Fallback)
│   ├── insert.php
│   └── setup.php
│
└── img/
    └── main_logo.jpg
```

---

## 🚀 Usage Guide

### For End Users

#### 1. Browse Properties

1. Visit **Property** page
2. View featured property (full-width hero)
3. Browse 2-column grid below
4. Use pagination to view more properties

#### 2. View Property Details

1. Click **View Details & Purchase** button
2. View:
   - Property image gallery
   - Detailed specifications
   - "How it works" guide
   - Deposit amount in MMK and USD

#### 3. Purchase Property (Deposit)

1. Click **Process To Purchase** button
2. Review deposit amount:
   - 10% of property price
   - Converted to USD for Stripe
3. Fill in checkout form with:
   - Full name
   - Email address
   - Contact number
4. Click **Proceed to Stripe Payment**
5. Complete payment with card details
6. Receive confirmation and receipt

#### 4. Verify Purchase

1. Check email for receipt
2. View reserved property status
3. Property marked as "Reserved" for other users

---

### For Developers

#### 1. Add New Property Type

```sql
INSERT INTO Property_type (ptype) VALUES ('Villa');
```

#### 2. Add Property Manually

```sql
INSERT INTO Property (
  property_name, property_price, property_location, 
  property_area, property_type, pt_ID, no_of_bedroom, 
  no_of_bathroom, built_year
) VALUES (
  'New Property', 500000, 'Yangon', 2500, 'house', 1, 3, 2, 2024
);
```

#### 3. Test API Endpoint

```bash
# Get page 1 with 3 properties per page
curl "http://localhost/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/DB/getProperties.php?page=1&perPage=3"
```

#### 4. Test Stripe Webhook Locally

```bash
# Using Stripe CLI
stripe listen --forward-to localhost/phpbatch4/P00199731_Pyone_Zon_Phu_ISP_V4/User/stripe_webhook.php

# In another terminal, trigger test event
stripe trigger payment_intent.succeeded
```

#### 5. Debug Payment Issues

Check PHP error logs:

```bash
# macOS XAMPP
tail -f /Applications/XAMPP/xamppfiles/logs/php_error_log

# Linux XAMPP
tail -f /opt/lampp/logs/php_error_log
```

Check Stripe logs in Stripe Dashboard:
- **Developers** → **Events** (webhook events)
- **Developers** → **Logs** (API calls)

---

## 📄 Key Files Explanation

### `User/stripe_config.php`
**Purpose**: Centralized Stripe configuration

```php
// API Keys (from Stripe Dashboard)
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_...');
define('STRIPE_SECRET_KEY', 'sk_test_...');
define('STRIPE_WEBHOOK_SECRET', 'whsec_...');

// Conversion rate: 1 USD = 4000 MMK
define('MMK_TO_USD_RATE', 4000);

// Helper functions for API requests
function stripe_api_request($method, $url, $data = null) { ... }
function stripe_verify_signature($payload, $sigHeader, $secret) { ... }
```

### `User/stripe_webhook.php`
**Purpose**: Handles Stripe webhook events

```php
// Flow:
// 1. Receives webhook from Stripe
// 2. Verifies signature using STRIPE_WEBHOOK_SECRET
// 3. Parses event data
// 4. On checkout.session.completed:
//    - Extracts property_id, client_id, deposit_amount
//    - Creates purchase record in Purchase_Property table
//    - Creates payment record in Payment table
// 5. Logs all events for debugging
```

**Key Events Handled**:
- `checkout.session.completed` - Payment successful

### `DB/getProperties.php`
**Purpose**: API endpoint for property data

```php
// Flow:
// 1. Receives ?page=X&perPage=Y parameters
// 2. Tries to fetch from GitHub mock API
// 3. If API fails, falls back to local database
// 4. Paginates results
// 5. Returns JSON response with:
//    - Property data
//    - Page info
//    - source flag (api/local)
```

### `DB/connection.php`
**Purpose**: Database connection with UTF-8 support

```php
$dbconid = mysqli_connect("localhost", "root", "", "construction_db");
mysqli_set_charset($dbconid, "utf8"); // Handle special characters
```

### `User/propertyDetail.php`
**Purpose**: Display property details and handle purchases

```php
// Features:
// - Fetches property from database
// - Displays:
//   - Property images
//   - Detail cards (price, type, area, etc)
//   - Gallery section (3-4 images)
//   - "How it works" guide
// - Links to stripe_webhook for purchase tracking
// - Shows Reserved/Sold Out status
```

---

## 🔧 Troubleshooting

### Database Issues

**Problem**: "Database connection error"
```bash
# Solution: Verify MySQL is running
sudo /Applications/XAMPP/xamppfiles/mysql/bin/mysqld_safe

# Check connection
mysql -u root -p construction_db
```

**Problem**: "Unknown character set 'utf8mb4'"
```bash
# Use utf8 instead
CREATE DATABASE construction_db CHARACTER SET utf8;
```

**Problem**: Special characters showing as "?"
```sql
-- Fix: Alter table charset
ALTER TABLE Property CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
```

### Stripe Issues

**Problem**: "Invalid Stripe signature"
- Verify `STRIPE_WEBHOOK_SECRET` in `stripe_config.php`
- Ensure secret matches Stripe Dashboard
- Check webhook event logs in Stripe Dashboard

**Problem**: Checkout session not created
- Verify `STRIPE_SECRET_KEY` is correct
- Check PHP error logs for cURL errors
- Ensure SSL/HTTPS in production

**Problem**: Webhook not receiving events
- Verify endpoint URL in Stripe Dashboard
- Check webhook is not returning error status
- Use `stripe listen --events checkout.session.completed` locally
- Check logs in Stripe Dashboard **Developers** → **Webhooks**

### API Issues

**Problem**: "No properties available"
- Check GitHub API is accessible: `curl https://raw.githubusercontent.com/anshumansinha1/real-estate-mock-api/master/db.json`
- System falls back to local database automatically
- Check local database has properties: `SELECT COUNT(*) FROM Property;`

**Problem**: "JSON decode error"
- Verify database charset is UTF-8
- Check for invalid characters in property data
- Clear database and reimport SQL

### General Issues

**Problem**: Page shows "Loading properties..."
```bash
# Check browser console for errors (F12)
# Check PHP error logs
# Verify API endpoint: /DB/getProperties.php?page=1&perPage=3
```

**Problem**: Styles not loading
```bash
# Hard refresh browser (Cmd+Shift+R or Ctrl+Shift+R)
# Clear browser cache
# Verify user.css file exists
```

---

## 💡 Development Notes

### Code Standards

- **PHP**: PSR-12 coding standard
- **JavaScript**: ES6 vanilla (no frameworks)
- **CSS**: BEM naming convention for classes
- **Database**: Prepared statements for SQL injection prevention
- **Security**: Stripe webhook signature verification

### Best Practices Implemented

1. **Security**:
   - SQL prepared statements
   - Stripe webhook signature validation
   - HTML entity encoding for XSS prevention
   - Database charset UTF-8

2. **Performance**:
   - API fallback to local database
   - Pagination (3 properties per page)
   - Image lazy loading
   - CSS minification ready

3. **User Experience**:
   - Smooth animations and transitions
   - Loading states and error messages
   - Responsive design (mobile-first)
   - Accessibility (semantic HTML, ARIA ready)

### Future Enhancements

- [ ] Admin dashboard for property management
- [ ] User authentication and profiles
- [ ] Property search and filters
- [ ] Image upload for gallery
- [ ] Email notifications
- [ ] SMS alerts
- [ ] Property favorites/wishlist
- [ ] Advanced reporting
- [ ] Multi-language support
- [ ] Dark mode UI

### Testing Stripe Payments

```bash
# Using Stripe Test Cards
- Success: 4242 4242 4242 4242
- Decline: 4000 0000 0000 0002
- Requires Auth: 4000 2500 0000 3155
- Expired: 4000 0000 0000 0069

# Any future expiry date: 12/25
# Any 3-digit CVC: 123
# Any ZIP: 12345
```

### Common Customizations

**Change Deposit Percentage**:
```php
// In propertyDetail.php, change:
$depositAmount = intval(ceil($property_result['property_price'] * 0.10)); // 10%
// To:
$depositAmount = intval(ceil($property_result['property_price'] * 0.20)); // 20%
```

**Change Currency Conversion Rate**:
```php
// In stripe_config.php
define('MMK_TO_USD_RATE', 4000); // Change 4000 to your rate
```

**Change Pagination Items Per Page**:
```javascript
// In property.php, change:
const perPage = 3;
// To:
const perPage = 6;
```

---

## 📞 Support & Contact

For issues or questions:
1. Check **Troubleshooting** section above
2. Review Stripe Dashboard logs
3. Check PHP error logs
4. Verify database connection
5. Test API endpoint directly

---

## 📝 License

This project is built for educational and commercial use.

---

## 🎯 Quick Checklist

- [ ] Database created and imported
- [ ] PHP/MySQL connection working
- [ ] Stripe keys configured in `stripe_config.php`
- [ ] Webhook endpoint created in Stripe Dashboard
- [ ] Webhook secret added to `stripe_config.php`
- [ ] HTTPS/ngrok setup for local webhook testing
- [ ] Email configured (for receipt sending - if implemented)
- [ ] Properties visible on property listing page
- [ ] Test payment successful with test card
- [ ] Webhook events visible in Stripe Dashboard logs

---

**Version**: 1.0.0  
**Last Updated**: April 2026  
**Status**: Production Ready
