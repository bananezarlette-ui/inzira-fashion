# 👗 Inzira Fashion — E-Commerce Web Application

> Rwanda's premier online fashion store, built as a full-stack web application.

![PHP](https://img.shields.io/badge/PHP-8.0-blue) ![MySQL](https://img.shields.io/badge/MySQL-8.0-orange) ![Docker](https://img.shields.io/badge/Docker-✓-blue) ![CI/CD](https://img.shields.io/badge/CI%2FCD-GitHub%20Actions-green) ![Live](https://img.shields.io/badge/Live-Online-brightgreen)

## 🌐 Live Demo
- **Store:** http://inzirafashion.site.je
- **Admin Panel:** http://inzirafashion.site.je/admin/index.php

## 📁 GitHub Repository
- https://github.com/bananezarlette-ui/inzira-fashion

## 🛠️ Technologies
| Technology | Purpose |
|---|---|
| HTML5 | Structure & markup |
| CSS3 | Styling (pure custom CSS — no frameworks) |
| JavaScript | Cart interactivity, form validation |
| PHP 8.0 | Backend logic & routing |
| MySQL | Relational database |
| Git & GitHub | Version control |
| GitHub Actions | CI/CD pipeline |
| Docker | Containerization |
| XAMPP | Local development environment |

## 🚀 Quick Start (XAMPP)

1. Clone the repo into your XAMPP `htdocs`:
```bash
git clone https://github.com/bananezarlette-ui/inzira-fashion.git
cd C:\xampp\htdocs\inzira-fashion
```
2. Import the database:
   - Open **phpMyAdmin** → create database `inzira_fashion`
   - Import `database.sql`
3. Visit `http://localhost/inzira-fashion`

## 🐳 Docker Setup

```bash
# Start all services (app + MySQL)
docker compose up -d

# Visit the store
open http://localhost:8080

# Visit admin panel
open http://localhost:8080/admin/index.php

# Stop
docker compose down
```

## 👤 Default Admin Credentials
| Field | Value |
|---|---|
| Email | admin@inzirafashion.rw |
| Password | Admin@1234 |

## 📁 Project Structure
```
inzira-fashion/
├── index.php                   # Homepage
├── database.sql                # Database schema + seed data
├── Dockerfile                  # Docker image definition
├── docker-compose.yml          # Multi-service orchestration
├── .htaccess                   # Apache config & security headers
├── .github/workflows/
│   └── ci-cd.yml               # GitHub Actions CI/CD pipeline
├── assets/
│   ├── css/style.css           # All custom styles
│   └── js/
│       ├── cart.js             # Cart logic (localStorage)
│       └── app.js              # Utilities & form validation
├── config/
│   ├── database.php            # DB connection (auto-detects local/live)
│   ├── session.php             # Auth helpers
│   └── app.php                 # Constants & base() helper
├── includes/
│   ├── header.php              # Shared navbar + cart sidebar
│   └── footer.php              # Shared footer
├── pages/
│   ├── products.php            # Product listing + search + filter
│   ├── product-detail.php      # Single product view
│   ├── checkout.php            # Checkout form
│   ├── order-confirmation.php  # Order success page
│   ├── login.php               # Login
│   ├── register.php            # Register
│   ├── logout.php              # Logout
│   └── my-orders.php           # Order history
├── api/
│   └── orders.php              # REST endpoint for order creation
└── admin/
    ├── index.php               # Admin dashboard
    ├── admin.css               # Admin styles
    ├── includes/sidebar.php    # Admin sidebar
    └── pages/
        ├── products.php        # CRUD products
        ├── orders.php          # View & update orders
        ├── users.php           # View users
        ├── categories.php      # Manage categories
        ├── order-detail.php    # Order detail view
        └── update-order.php    # Update order status
```

## ✨ Features
- **Responsive UI** — works on mobile, tablet, desktop
- **Product catalog** — listing, detail, categories, search, filter, sort
- **Shopping cart** — add/remove/update quantity, persisted in localStorage
- **Checkout** — customer info form with JS validation, REST API order creation
- **User auth** — register, login, logout, session management, bcrypt password hashing
- **My Orders** — authenticated users can view their order history
- **Admin panel** — manage products, orders (status updates), users, categories
- **Docker** — single `docker compose up` runs the full stack
- **CI/CD** — GitHub Actions: syntax check → Docker build → deploy on push to main
- **Security** — bcrypt hashing, input sanitization, security headers, role-based access

## 🔁 CI/CD Pipeline
The pipeline runs automatically on every push to `main`:
1. ✅ **PHP Syntax Check** — validates all PHP files
2. 🐳 **Docker Build** — builds and tests the Docker image
3. 🚀 **Deploy** — deploys to production server via SSH

## 🔐 GitHub Actions Secrets Required
| Secret | Description |
|---|---|
| `DOCKER_USERNAME` | Docker Hub username |
| `DOCKER_PASSWORD` | Docker Hub password/token |
| `SSH_HOST` | Production server IP |
| `SSH_USER` | SSH username |
| `SSH_KEY` | SSH private key |

## 👩‍💻 Author
**Bananeza Arlette**
Built for the E-Commerce and Web Application course project.
Instructor: Eric Maniraguha
Kigali, Rwanda — July 2026
