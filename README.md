#  YouCode Arena

**YouCode Arena** is a centralized web platform developed with **Laravel** for managing competitive events such as e-sports tournaments, hackathons, and physical competitions.

It digitizes the entire tournament lifecycle — from registration to bracket tracking — while ensuring data integrity and enforcing participation rules.

---

##  Table of Contents

- Overview
- Core Objectives
- Technology Stack
- RBAC & User Roles
- Key Features
- Business Rules
- Installation Guide
- Project Structure
- Future Roadmap (V2)
- Developer

---

## 🔍 Overview

YouCode Arena replaces informal tools like Discord and WhatsApp with a centralized, secure, and automated platform.

It provides:
- Tournament creation & management
- Team formation & invitations
- Real-time bracket tracking
- Community interaction

---

##  Core Objectives

| Objective         | Description |
|------------------|------------|
| Centralization   | Manage all events from a single platform |
| Automation       | Automate registrations, quotas, and validation |
| Reliability      | Ensure data integrity and prevent duplicates |

---

##  Technology Stack

| Layer        | Technologies |
|-------------|-------------|
| Backend     | Laravel 11 (PHP 8.2+) |
| Database    | MySQL |
| Frontend    | Blade, Tailwind CSS (Dark Mode) |
| DevOps      | Docker (Laravel Sail), Git Flow |
| Security    | Bcrypt / Argon2, CSRF protection, XSS & SQL injection prevention |

---

##  RBAC & User Roles

| Role | Permissions |
|------|-----------|
| Admin | Manage users, assign roles, delete tournaments |
| Organizer | Create tournaments, manage lifecycle, validate matches |
| Competitor | Join tournaments, create teams, track stats |
| Jury | View competitor data (read-only) |

---

##  Key Features

### 1. Competition Hub
- Social feed for announcements
- Category filtering
- Comment system

### 2. Tournament Management
- Real-time quota control
- Challenge vs Match system
- Visual bracket system (Round of 16 → Final)

### 3. Team System
- Team creation (Duo / Squad)
- Email invitations
- Automatic team dissolution if a member leaves

### 4. Competitor Dashboard
- Win rate tracking
- Match history
- Active tournaments overview

---

##  Business Rules

1. Maximum participant limit enforced
2. One registration per user per tournament
3. No registration after event date
4. Only complete teams can participate

---

##  Installation Guide

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

### Setup Steps

```bash
# Clone repository
git clone https://github.com/Oussama-Ait-Youss/YOUCODE-ARENA-FILL_ROUGE.git
cd youcode-arena

# Install dependencies
composer install
npm install
npm run build

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database then run:
php artisan migrate --seed

# Storage link
php artisan storage:link

# Run server
php artisan serve
```

---

##  Project Structure

```
youcode-arena/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Policies/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   └── css/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
├── .env.example
└── README.md
```

---

## Future Roadmap (V2)

- Gamification system (points & leaderboard)
- Automatic bracket generator
- Email & push notifications
- Tournament history archive

---

##  Developer

**Oussama Ait Youss**  
YouCode - UM6P / Youssoufia
