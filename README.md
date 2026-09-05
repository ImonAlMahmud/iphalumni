<div align="center">

<img src="public/storage/logo.png" alt="IPH Alumni Association Logo" width="120" height="120" style="border-radius: 50%;" />

# IPH Alumni Association Portal
### আইপিএইচ এলামনাই এসোসিয়েশন

**প্রতিষ্ঠিত ২০২৫ · এক অ্যালামনাই পরিবার**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

[🌐 Live Site](https://iphalumni.dev.cv) · [📖 Documentation](#table-of-contents) · [🐛 Report Bug](https://github.com/ImonAlMahmud/iphalumni/issues) · [✨ Request Feature](https://github.com/ImonAlMahmud/iphalumni/issues)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Deployment](#-deployment)
- [User Roles & Permissions](#-user-roles--permissions)
- [Module Reference](#-module-reference)
- [API & Routes Reference](#-api--routes-reference)
- [Payment Integration](#-payment-integration)
- [Email System](#-email-system)
- [Security](#-security)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)

---

## 🎯 Overview

**IPH Alumni Association Portal** হলো ইনস্টিটিউট অব পাবলিক হেলথ (IPH), ঢাকার প্রাক্তন শিক্ষার্থীদের জন্য একটি সম্পূর্ণ ডিজিটাল প্ল্যাটফর্ম। এই পোর্টালটি অ্যালামনাইদের একত্রিত করে, যোগাযোগ সহজ করে এবং সাংগঠনিক কার্যক্রম পরিচালনায় সহায়তা করে।

### মূল লক্ষ্য
- প্রাক্তন শিক্ষার্থীদের একটি কেন্দ্রীভূত ডিরেক্টরিতে একত্রিত করা
- ডিজিটাল সদস্যপদ কার্ড ও QR ভেরিফিকেশন ব্যবস্থা চালু করা
- ইভেন্ট, নিউজ, গ্যালারি ও সাফল্যের গল্প প্রকাশ করা
- অনলাইন পেমেন্টের মাধ্যমে সদস্যপদ ফি গ্রহণ করা
- অ্যাসোসিয়েশনের আর্থিক স্বচ্ছতা নিশ্চিত করা

---

## ✨ Features

### 👥 অ্যালামনাই পোর্টাল (Members)
| ফিচার | বিবরণ |
|-------|--------|
| 🔐 রেজিস্ট্রেশন ও লগইন | ইমেইল OTP ভেরিফিকেশনসহ নিরাপদ রেজিস্ট্রেশন |
| 🆔 ডিজিটাল আইডি কার্ড | QR Code সহ SVG/PNG ফরম্যাটে ডাউনলোডযোগ্য ID কার্ড |
| 👤 প্রোফাইল ম্যানেজমেন্ট | ছবি, শিক্ষা, চাকরির বিবরণ, সোশ্যাল লিংক আপডেট |
| 🎓 সদস্যপদ আবেদন | General, Life, Honorary সদস্যপদ ক্যাটাগরি |
| 💳 অনলাইন পেমেন্ট | UddoktaPay (bKash, Nagad, Rocket, Card) ইন্টিগ্রেশন |
| 💼 চাকরির সার্কুলার | চাকরি পোস্ট করুন, আবেদন করুন ও ট্র্যাক করুন |
| 📖 সাফল্যের গল্প | নিজের অর্জন শেয়ার করুন |
| 💰 আর্থিক বিবরণ | ফান্ড, ব্যয়, বাজেট ট্র্যাকিং |
| 🔔 নোটিফিকেশন | ইন-অ্যাপ নোটিফিকেশন সিস্টেম |
| 📇 যোগাযোগ অনুরোধ | অন্য অ্যালামনাইয়ের সাথে যোগাযোগের অনুরোধ |

### 🌐 পাবলিক পেজ (Public)
| পেজ | বিবরণ |
|-----|--------|
| 🏠 হোম পেজ | ভিডিও হিরো সেকশন, স্ট্যাটিস্টিক্স, ফিচার্ড অ্যালামনাই |
| 📂 অ্যালামনাই ডিরেক্টরি | সার্চ ও ফিল্টার সহ সম্পূর্ণ সদস্য তালিকা |
| 📰 নিউজ | সংগঠনের সর্বশেষ সংবাদ |
| 📅 ইভেন্ট | আসন্ন ও অতীত ইভেন্ট |
| 🖼️ গ্যালারি | অ্যালবাম ভিত্তিক ছবির গ্যালারি |
| 🏆 সাফল্যের গল্প | অনুপ্রেরণামূলক অ্যালামনাই স্টোরি |
| 💼 চাকরির তালিকা | সর্বশেষ চাকরির সার্কুলার |
| 📋 সংবিধান | সংগঠনের সংবিধান (PDF ডাউনলোড) |
| 🤝 মেন্টরশিপ | মেন্টর-মেন্টি সংযোগ |
| ✅ QR ভেরিফিকেশন | মেম্বারশিপ কার্ড যাচাই পেজ |

### 🔧 অ্যাডমিন প্যানেল
| মডিউল | বিবরণ |
|--------|--------|
| 📊 ড্যাশবোর্ড | সামগ্রিক পরিসংখ্যান ও অ্যাক্টিভিটি |
| 👩‍💼 অ্যালামনাই ম্যানেজমেন্ট | অনুমোদন, প্রত্যাখ্যান, স্ট্যাটাস পরিবর্তন |
| 🎓 সদস্যপদ | পেমেন্ট অনুমোদন, Honorary মেম্বার যুক্ত করা |
| 📰 কন্টেন্ট ম্যানেজমেন্ট | নিউজ, ইভেন্ট, গ্যালারি, স্টোরি |
| 👥 কমিটি ম্যানেজমেন্ট | কমিটির সদস্য যুক্ত ও সম্পাদনা |
| 🎓 শিক্ষার্থী রেফারেন্স | ডাটাবেজ আপলোড ও ম্যাপিং |
| 📤 ইমেইল ব্রডকাস্ট | সকল সদস্যকে ইমেইল পাঠানো |
| 📈 রিপোর্ট | অ্যালামনাই, মেম্বারশিপ, ডোনেশন রিপোর্ট |
| ⚙️ সেটিংস | SMTP, পেমেন্ট গেটওয়ে, লোগো কনফিগারেশন |
| 🃏 ID কার্ড বাল্ক এক্সপোর্ট | সকল অ্যালামনাইয়ের আইডি কার্ড ZIP ডাউনলোড |
| 📧 ইমেইল টেমপ্লেট | Welcome, Approval ইত্যাদি টেমপ্লেট ব্যবস্থাপনা |

---

## 🛠️ Tech Stack

### Backend
| টেকনোলজি | ভার্সন | ব্যবহার |
|-----------|--------|--------|
| **PHP** | ^8.2 | কোর ল্যাঙ্গুয়েজ |
| **Laravel** | ^11.31 | ফ্রেমওয়ার্ক |
| **MySQL** | 8.0 | প্রধান ডাটাবেজ |
| **Laravel Tinker** | ^2.9 | REPL / ডিবাগিং |

### Frontend
| টেকনোলজি | ব্যবহার |
|-----------|--------|
| **HTML5 / CSS3** | স্ট্রাকচার ও স্টাইলিং |
| **Vanilla JavaScript** | ইন্টারেক্টিভিটি |
| **Vite** | অ্যাসেট বান্ডলিং |
| **Google Fonts (Inter, Noto Sans Bengali)** | টাইপোগ্রাফি |
| **QRCode.js** | QR কোড জেনারেশন |

### Services & Integrations
| সার্ভিস | উদ্দেশ্য |
|---------|---------|
| **UddoktaPay** | বাংলাদেশি পেমেন্ট গেটওয়ে (bKash, Nagad, Rocket, Card) |
| **SMTP / Mail** | ইমেইল নোটিফিকেশন |
| **QR Code** | মেম্বারশিপ কার্ড ভেরিফিকেশন |

---

## 📁 Project Structure

```
alumni/
├── app/
│   ├── Helpers/
│   │   └── LegacyHelpers.php           # গ্লোবাল হেল্পার ফাংশন
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # অ্যাডমিন প্যানেল কন্ট্রোলার
│   │   │   │   ├── AlumniController.php
│   │   │   │   ├── CommitteeController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── EmailTemplatesController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   ├── MembershipController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   ├── StoriesController.php
│   │   │   │   └── StudentsController.php
│   │   │   ├── Payment/
│   │   │   │   └── UddoktaPayController.php
│   │   │   ├── Portal/                 # অ্যালামনাই পোর্টাল কন্ট্রোলার
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── FinancialController.php
│   │   │   │   ├── JobController.php
│   │   │   │   ├── MembershipController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   └── StoryController.php
│   │   │   ├── Webhook/
│   │   │   │   └── DeployController.php
│   │   │   ├── AuthController.php      # লগইন, রেজিস্ট্রেশন, পাসওয়ার্ড রিসেট
│   │   │   ├── BaseController.php
│   │   │   ├── DirectoryController.php # অ্যালামনাই ডিরেক্টরি
│   │   │   ├── DonationController.php
│   │   │   ├── EventController.php
│   │   │   ├── GalleryController.php
│   │   │   ├── HomeController.php      # পাবলিক হোম পেজ
│   │   │   ├── JobController.php
│   │   │   ├── NewsController.php
│   │   │   └── StoriesController.php
│   │   └── Middleware/
│   │       ├── AuthMiddleware.php      # অ্যালামনাই লগইন চেক
│   │       └── AdminMiddleware.php     # অ্যাডমিন রোল চেক
│   ├── Models/
│   │   ├── AlumniProfile.php
│   │   ├── Setting.php
│   │   └── User.php
│   └── Services/
│       ├── AuditLogger.php             # অ্যাক্টিভিটি লগিং
│       ├── IdCardSvgService.php        # SVG আইডি কার্ড জেনারেশন
│       ├── MailService.php             # ইমেইল পাঠানো
│       ├── UddoktaPayService.php       # পেমেন্ট গেটওয়ে
│       └── UploadService.php           # ফাইল আপলোড
├── database/
│   └── migrations/                     # ডাটাবেজ মাইগ্রেশন
├── public/
│   ├── storage/                        # আপলোড করা ফাইল
│   │   ├── logo.png
│   │   ├── Index_Hero_video.mp4        # হিরো ভিডিও
│   │   └── avatars/, documents/...
│   └── deploy.php                      # শেয়ার্ড হোস্টিং ডিপ্লয় স্ক্রিপ্ট
├── resources/
│   └── views/
│       ├── admin/                      # অ্যাডমিন প্যানেল ভিউ
│       ├── auth/                       # লগইন, রেজিস্ট্রেশন ভিউ
│       ├── home/                       # পাবলিক পেজ
│       ├── partials/                   # নেভবার, ফুটার
│       └── portal/                     # পোর্টাল পেজ
├── routes/
│   └── web.php                         # সব রুট ডেফিনিশন
├── database_backup.sql                 # সম্পূর্ণ ডাটাবেজ ব্যাকআপ
└── scripts/
    └── local_direct_deploy.ps1         # লোকাল ডিপ্লয় স্ক্রিপ্ট
```

---

## 🗄️ Database Schema

প্রকল্পটিতে মোট **43টি টেবিল** রয়েছে:

### Core Tables
| টেবিল | বিবরণ |
|-------|--------|
| `users` | লগইন তথ্য, রোল, স্ট্যাটাস |
| `alumni_profiles` | বিস্তারিত অ্যালামনাই প্রোফাইল |
| `alumni_education` | শিক্ষা বিবরণ |
| `alumni_employment` | কর্মসংস্থান তথ্য |
| `alumni_skills` | দক্ষতা তালিকা |
| `students_reference` | অটো-ভেরিফিকেশনের জন্য শিক্ষার্থী রেফারেন্স ডাটা |

### Membership & Payment
| টেবিল | বিবরণ |
|-------|--------|
| `memberships` | সদস্যপদ রেকর্ড |
| `membership_types` | সদস্যপদ ক্যাটাগরি ও ফি |
| `membership_payments` | পেমেন্ট লেনদেন |
| `donations` | ডোনেশন রেকর্ড |

### Content Tables
| টেবিল | বিবরণ |
|-------|--------|
| `events` | ইভেন্ট তথ্য |
| `event_registrations` | ইভেন্ট নিবন্ধন |
| `event_expenses` | ইভেন্ট খরচ |
| `news` | সংবাদ নিবন্ধ |
| `gallery_albums` | গ্যালারি অ্যালবাম |
| `gallery_photos` | গ্যালারি ছবি |
| `success_stories` | সাফল্যের গল্প |
| `committees` | কমিটি তালিকা |
| `committee_members` | কমিটি সদস্য |

### Financial Tables
| টেবিল | বিবরণ |
|-------|--------|
| `association_funds` | ফান্ড সংগ্রহ রেকর্ড |
| `association_expenses` | ব্যয়ের রেকর্ড |
| `yearly_budgets` | বার্ষিক বাজেট |

### Job & Communication
| টেবিল | বিবরণ |
|-------|--------|
| `jobs` | চাকরির সার্কুলার |
| `job_applications` | চাকরির আবেদন |
| `job_alert_subscriptions` | চাকরির অ্যালার্ট সাবস্ক্রিপশন |
| `contact_requests` | যোগাযোগ অনুরোধ |
| `contact_messages` | যোগাযোগ ফর্মের বার্তা |
| `notifications` | ইন-অ্যাপ নোটিফিকেশন |
| `email_broadcasts` | ইমেইল ব্রডকাস্ট লগ |

### System Tables
| টেবিল | বিবরণ |
|-------|--------|
| `settings` | সিস্টেম সেটিংস (Key-Value) |
| `audit_logs` | ব্যবহারকারীর অ্যাক্টিভিটি লগ |
| `activity_logs` | সিস্টেম অ্যাক্টিভিটি লগ |
| `approval_history` | অনুমোদনের ইতিহাস |
| `universities` | বিশ্ববিদ্যালয়ের তালিকা |
| `organizations` | সংগঠনের তালিকা |
| `page_sections` | ডাইনামিক পেজ সেকশন |
| `faqs` | সচরাচর জিজ্ঞাসিত প্রশ্ন |
| `downloads` | ডাউনলোডযোগ্য ফাইল |
| `mentorship_requests` | মেন্টরশিপ অনুরোধ |
| `skills` | দক্ষতার মাস্টার তালিকা |
| `custom_fields` | কাস্টম ফিল্ড সংজ্ঞা |
| `custom_field_values` | কাস্টম ফিল্ড মান |
| `notice_signatories` | নোটিশ স্বাক্ষরকারী |

---

## 🚀 Installation

### পূর্বশর্ত
- PHP >= 8.2 (extensions: `pdo_mysql`, `mbstring`, `gd`, `fileinfo`, `zip`)
- MySQL >= 8.0
- Composer >= 2.x
- Node.js >= 18.x

### ধাপ ১: রিপোজিটরি ক্লোন করুন
```bash
git clone https://github.com/ImonAlMahmud/iphalumni.git
cd iphalumni
```

### ধাপ ২: PHP নির্ভরতা ইনস্টল করুন
```bash
composer install
```

### ধাপ ৩: Environment ফাইল সেটআপ করুন
```bash
cp .env.example .env
php artisan key:generate
```

`.env` ফাইলে নিচের তথ্য আপডেট করুন:
```ini
APP_NAME="IPH Alumni Association"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pmarkbdc_alumni
DB_USERNAME=root
DB_PASSWORD=
```

### ধাপ ৪: ডাটাবেজ ইম্পোর্ট করুন
```bash
# MySQL-এ ডাটাবেজ তৈরি করুন
mysql -u root -e "CREATE DATABASE pmarkbdc_alumni;"

# ব্যাকআপ ইম্পোর্ট করুন
mysql -u root pmarkbdc_alumni < database_backup.sql
```

### ধাপ ৫: স্টোরেজ লিংক তৈরি করুন
```bash
php artisan storage:link
```

### ধাপ ৬: Frontend অ্যাসেট বিল্ড করুন
```bash
npm install
npm run build
```

### ধাপ ৭: অ্যাপ্লিকেশন চালু করুন
```bash
php artisan serve
```

এখন `http://localhost:8000` এ ব্রাউজ করুন।

---

## ⚙️ Configuration

### মেইল (SMTP) কনফিগারেশন
`.env` ফাইলে:
```ini
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="IPH Alumni Association"
```

অথবা অ্যাডমিন প্যানেলের **Settings > SMTP** থেকে সরাসরি কনফিগার করুন।

### UddoktaPay পেমেন্ট কনফিগারেশন
অ্যাডমিন প্যানেলের **Settings > Payment Gateway**:
- API Key
- API URL (Sandbox/Live)
- Mode: `sandbox` বা `live`

অথবা `.env`-এ:
```ini
UDDOKTAPAY_API_KEY=your_api_key
UDDOKTAPAY_API_URL=https://pay.uddoktapay.com/api
UDDOKTAPAY_MODE=live
```

### Session কনফিগারেশন
```ini
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
```

---

## 🚢 Deployment

### শেয়ার্ড হোস্টিং (cPanel/Plesk)

এই প্রকল্পটি `exec()` ডিসেবল থাকা শেয়ার্ড হোস্টিং-এ কাজ করার জন্য বিশেষভাবে অপ্টিমাইজ করা হয়েছে।

#### ডিপ্লয় প্রক্রিয়া:
```powershell
# লোকাল মেশিন থেকে (PowerShell)
.\scripts\local_direct_deploy.ps1
```

এই স্ক্রিপ্টটি:
1. কোডবেস ZIP করে
2. `public/deploy.php` এ আপলোড করে
3. সার্ভারে ZIP এক্সট্র্যাক্ট করে
4. লগ ফাইল চেক করে

#### প্রোডাকশন `.env` সেটআপ:
```ini
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### GitHub Webhook স্বয়ংক্রিয় ডিপ্লয়মেন্ট
```
Route: POST /webhook/deploy
```
GitHub Webhook সেটআপ করলে `git push` করার সাথে সাথে সার্ভার আপডেট হবে।

---

## 👤 User Roles & Permissions

| রোল | অ্যাক্সেস |
|-----|----------|
| `alumni` | পোর্টাল ড্যাশবোর্ড, প্রোফাইল, জব, স্টোরি, মেম্বারশিপ |
| `editor` | অ্যাডমিন প্যানেল (লিমিটেড) — কন্টেন্ট ম্যানেজমেন্ট |
| `admin` | অ্যাডমিন প্যানেল (সকল) — অ্যালামনাই অনুমোদন বাদে সেটিংস |
| `super_admin` | সম্পূর্ণ অ্যাক্সেস |

### Registration Flow
```
রেজিস্ট্রেশন ফর্ম
    ↓ ইমেইল OTP ভেরিফিকেশন
    ↓ স্বয়ংক্রিয় ভেরিফিকেশন (students_reference ডাটাবেজ থেকে)
    ↓ ✅ পাওয়া গেলে → status: active (লগইন করতে পারবেন)
    ↓ ❌ না পাওয়া গেলে → status: pending (অ্যাডমিন অনুমোদনের অপেক্ষা)
```

---

## 📦 Module Reference

### Authentication Module
- **লগইন:** Email + Password ভিত্তিক
- **রেজিস্ট্রেশন:** ইমেইল OTP + প্রমাণ ডকুমেন্ট আপলোড
- **পাসওয়ার্ড রিসেট:** ইমেইল লিংক (১ ঘন্টা মেয়াদ)
- **Session:** Laravel Auth Guard

### ID Card Module (`IdCardSvgService`)
- ফ্রন্ট ও ব্যাক সাইড SVG জেনারেশন
- QR Code এমবেড (membership verification URL)
- ব্যক্তিগত ছবি ও স্বাক্ষর এমবেড
- বাল্ক ZIP ডাউনলোড (অ্যাডমিন থেকে)

### Payment Module (`UddoktaPayService`)
- Sandbox ও Live মোড
- Checkout ও Webhook ভেরিফিকেশন
- পেমেন্ট সফল হলে স্বয়ংক্রিয় মেম্বারশিপ অ্যাক্টিভেশন

### Email Module (`MailService`)
- Template-based HTML ইমেইল
- Broadcast (সকল সদস্যকে)
- Transactional (welcome, approval, reset, OTP)
- SMTP কনফিগারেশন DB বা `.env` থেকে লোড

### File Upload Module (`UploadService`)
- Avatar (JPG, PNG, WebP — max 2MB)
- Documents (PDF, JPG, PNG — max 5MB)
- Gallery Photos (একাধিক একসাথে)
- Storage: `storage/app/public/`

---

## 🗺️ API & Routes Reference

### Public Routes
| Method | URL | বিবরণ |
|--------|-----|--------|
| GET | `/` | হোম পেজ |
| GET | `/directory` | অ্যালামনাই ডিরেক্টরি |
| GET | `/directory/{id}` | অ্যালামনাই প্রোফাইল বিবরণ |
| GET | `/events` | ইভেন্ট তালিকা |
| GET | `/news` | নিউজ তালিকা |
| GET | `/gallery` | গ্যালারি |
| GET | `/jobs` | চাকরির তালিকা |
| GET | `/stories` | সাফল্যের গল্প |
| GET | `/verify/{code}` | QR মেম্বারশিপ ভেরিফিকেশন |
| POST | `/contact` | যোগাযোগ ফর্ম |

### Auth Routes
| Method | URL | বিবরণ |
|--------|-----|--------|
| GET/POST | `/login` | লগইন |
| GET/POST | `/register` | রেজিস্ট্রেশন |
| GET/POST | `/forgot-password` | পাসওয়ার্ড ভুলে গেছি |
| GET/POST | `/reset-password` | পাসওয়ার্ড রিসেট |
| GET | `/logout` | লগআউট |

### Portal Routes (auth.alumni required)
| Method | URL | বিবরণ |
|--------|-----|--------|
| GET | `/portal` | ড্যাশবোর্ড |
| GET | `/portal/id-card` | ডিজিটাল আইডি কার্ড |
| GET/POST | `/portal/profile` | প্রোফাইল দেখুন ও আপডেট করুন |
| GET | `/portal/membership` | সদস্যপদ |
| GET/POST | `/portal/jobs` | চাকরির ম্যানেজমেন্ট |
| GET/POST | `/portal/stories` | সাফল্যের গল্প |
| GET | `/portal/financials` | আর্থিক বিবরণ |

### Admin Routes (auth.admin required)
| Method | URL | বিবরণ |
|--------|-----|--------|
| GET | `/admin` | ড্যাশবোর্ড |
| GET | `/admin/alumni` | অ্যালামনাই তালিকা |
| GET | `/admin/alumni/export/excel` | Excel ডাউনলোড |
| GET | `/admin/alumni/export/cards-svg` | আইডি কার্ড ZIP |
| GET | `/admin/membership` | সদস্যপদ ম্যানেজমেন্ট |
| GET | `/admin/reports` | রিপোর্ট |
| GET | `/admin/settings` | সেটিংস |
| GET | `/admin/email-templates` | ইমেইল টেমপ্লেট |

---

## 💳 Payment Integration

### UddoktaPay
বাংলাদেশের সবচেয়ে জনপ্রিয় পেমেন্ট গেটওয়েগুলির মধ্যে একটি। নিম্নলিখিত পেমেন্ট পদ্ধতি সমর্থিত:

| পেমেন্ট পদ্ধতি | |
|----------------|--|
| 📱 bKash | ✅ |
| 📱 Nagad | ✅ |
| 📱 Rocket | ✅ |
| 💳 Visa/Mastercard | ✅ |
| 🏦 Internet Banking | ✅ |

#### Payment Flow:
```
সদস্যপদ আবেদন
    ↓
UddoktaPay Checkout পেজে রিডাইরেক্ট
    ↓
পেমেন্ট সম্পন্ন
    ↓
Webhook (/webhook/uddoktapay) — পেমেন্ট ভেরিফিকেশন
    ↓
সদস্যপদ অ্যাক্টিভ + ইমেইল নোটিফিকেশন
```

---

## 📧 Email System

### টেমপ্লেট তালিকা
| টেমপ্লেট | কখন পাঠানো হয় |
|-----------|---------------|
| `new_member_welcome` | রেজিস্ট্রেশন সম্পন্ন হলে |
| `membership_approved` | সদস্যপদ অনুমোদিত হলে |
| `membership_payment_confirm` | পেমেন্ট সফল হলে |
| `profile_approved` | প্রোফাইল অনুমোদিত হলে |
| Email OTP | ইমেইল ভেরিফিকেশনের সময় |
| Password Reset | পাসওয়ার্ড রিসেটের সময় |

### ইমেইল টেমপ্লেট কাস্টমাইজ
অ্যাডমিন প্যানেলের **Admin > Email Templates** থেকে বিষয়বস্তু পরিবর্তন করা যায়।

---

## 🔐 Security

### বর্তমানে সক্রিয় নিরাপত্তা ব্যবস্থা
- ✅ CSRF Protection (Laravel default)
- ✅ bcrypt Password Hashing (`Hash::make()`)
- ✅ Session Regeneration on Login
- ✅ Session Invalidation on Logout
- ✅ SQL Injection Protection (Laravel Query Builder)
- ✅ XSS Prevention (`e()` helper)
- ✅ Role-based Access Control
- ✅ Audit Logging
- ✅ Password Reset Token Expiry (1 ঘন্টা)

### প্রস্তাবিত নিরাপত্তা উন্নতি
বিস্তারিত সিকিউরিটি গাইডের জন্য দেখুন: [Security Recommendations](SECURITY.md)

মূল সুপারিশ:
1. **Login Rate Limiting** — Brute Force সুরক্ষা
2. **`APP_DEBUG=false`** প্রোডাকশনে
3. **Session Encryption** চালু করুন
4. **deploy.php** IP restrict করুন

---

## 📸 Screenshots

| পেজ | বিবরণ |
|-----|--------|
| 🏠 হোম পেজ | ভিডিও হিরো + ফিচার্ড অ্যালামনাই |
| 🆔 আইডি কার্ড | QR Code সহ ডিজিটাল মেম্বারশিপ কার্ড |
| 📂 ডিরেক্টরি | সার্চ ও ফিল্টার সহ অ্যালামনাই তালিকা |
| 🔧 অ্যাডমিন | ড্যাশবোর্ড ও ম্যানেজমেন্ট প্যানেল |

---

## 🤝 Contributing

### ডেভেলপমেন্ট শুরু করুন

```bash
# ডেভ সার্ভার চালু করুন
php artisan serve

# ফ্রন্টএন্ড ওয়াচ মোড
npm run dev

# টেস্ট চালান
php artisan test
```

### কোড স্ট্যান্ডার্ড
```bash
# PHP কোড ফরম্যাট
./vendor/bin/pint

# সিকিউরিটি অডিট
composer audit
npm audit
```

### ডাটাবেজ ব্যাকআপ আপডেট
```bash
c:\xampp\mysql\bin\mysqldump.exe -u root pmarkbdc_alumni > database_backup.sql
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Developer

**IPH Alumni Association Portal** — Developed for ইনস্টিটিউট অব পাবলিক হেলথ (IPH), ঢাকা।

---

<div align="center">

**এক অ্যালামনাই পরিবার। একসাথে এগিয়ে যাই।**

আইপিএইচ এলামনাই এসোসিয়েশন · প্রতিষ্ঠিত ২০২৫

</div>
