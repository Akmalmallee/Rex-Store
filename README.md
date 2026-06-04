# Rex Fashion - Premium Fashion E-Commerce

Modern, premium fashion e-commerce website built with **Laravel 13**, **Tailwind CSS**, and **MySQL**.

## Features

### User Features
- Modern landing page with hero, categories, featured products, new arrivals, promo banners
- Product shop with search, filter (category, price, size), sort (newest, cheapest, popular)
- Product detail with image gallery, size/color selector, reviews
- Shopping cart with AJAX add/remove/update
- Checkout with address, courier, payment method selection
- Payment proof upload (Bank Transfer, Dana, OVO, GoPay, COD)
- Order history with status tracking
- PDF invoice download
- Wishlist
- User profile management
- Authentication (Laravel Breeze)

### Admin Features
- Dashboard with sales analytics, revenue chart (Chart.js), recent orders, best sellers
- Product CRUD with multiple image upload, sizes, colors, soft delete
- Category CRUD
- Brand CRUD
- User management with role assignment
- Order management with status updates
- Payment management (approve/reject)
- Coupon management
- Banner management
- Promo management
- Reviews management
- Sales reports
- Website settings

### Technical Features
- Laravel 13 + Blade Template
- Tailwind CSS 3 with custom design system
- MySQL with Eloquent ORM
- Full CRUD operations
- Role-based middleware (Admin/User)
- Responsive design (mobile, tablet, desktop)
- Dark modern aesthetic with gold accents
- Chart.js for analytics
- dompdf for PDF invoice generation
- CSRF, XSS, SQL injection protection
- Soft deletes on products, categories, brands

## Requirements

- PHP >= 8.3
- Composer
- MySQL
- Node.js >= 18
- NPM

## Installation

1. Clone repository:
```bash
git clone <repository-url>
cd Rex-Fashion
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rex_fashion
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations with seeders:
```bash
php artisan migrate:fresh --seed
```

8. Build frontend assets:
```bash
npm run build
```

9. Start development server:
```bash
php artisan serve
```

## Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@rex.com | password |
| User | user@test.com | password |

## Database Structure

17 tables with full Eloquent relationships:
- `users` - User accounts with role (admin/user)
- `categories` - Product categories (Baju, Celana, Jacket, Topi)
- `brands` - Product brands
- `products` - Product catalog with pricing, stock, ratings
- `product_images` - Multiple product images
- `product_sizes` - Product size variants (S, M, L, XL, XXL)
- `product_colors` - Product color variants
- `carts` - Shopping carts per user
- `cart_items` - Cart line items
- `orders` - Customer orders with status workflow
- `order_items` - Order line items
- `payments` - Payment records with proof uploads
- `reviews` - Product reviews with star ratings
- `wishlists` - User wishlists
- `coupons` - Discount coupons
- `banners` - Homepage banners
- `promos` - Promotion campaigns

## Dummy Data

The seeder creates:
- 5 users (1 admin, 1 test, 3 random)
- 4 categories (Baju, Celana, Jacket, Topi) with 10 products each = 40 products
- 5 brands
- 40+ product images
- Product sizes and colors for each product
- 30 reviews
- 3 banners
- 2 promos
- 3 coupons (WELCOME10, SALE50K, FREESHIP)

## Design System

- **Primary**: Black (#000000)
- **Accent**: Gold (#C8A951)
- **Background**: White / Dark (#0a0a0a, #1a1a1a)
- **Cards**: `rounded-xl shadow-lg hover:shadow-xl`
- **Buttons**: `rounded-lg` with hover scale effects
- **Navbar**: Fixed with glassmorphism effect
- **Typography**: Instrument Sans font

## Routes

### User Routes
| Route | Page |
|-------|------|
| `/` | Home page |
| `/shop` | Product shop with filters |
| `/product/{slug}` | Product detail |
| `/cart` | Shopping cart |
| `/checkout` | Checkout |
| `/orders` | Order history |
| `/wishlist` | Wishlist |
| `/profile` | User profile |

### Admin Routes (prefix: `/admin`)
| Route | Page |
|-------|------|
| `/admin/dashboard` | Analytics dashboard |
| `/admin/products` | Product management |
| `/admin/categories` | Category management |
| `/admin/brands` | Brand management |
| `/admin/orders` | Order management |
| `/admin/payments` | Payment management |
| `/admin/users` | User management |
| `/admin/coupons` | Coupon management |
| `/admin/banners` | Banner management |
| `/admin/promos` | Promo management |
| `/admin/reviews` | Review management |
| `/admin/reports` | Sales reports |
| `/admin/settings` | Website settings |

## License

This project is open-sourced software licensed under the MIT license.
