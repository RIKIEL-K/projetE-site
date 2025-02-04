# Laravel E-Commerce Application with PayPal Integration

## Overview
This project is a **Laravel-based e-commerce application** hosted on **XAMPP**. It features **product management** for both administrators and users and integrates the **PayPal API** for secure transactions.

## Features
### Admin Panel
- Full control over the online store (add, edit, delete products)
- Access via **secure credentials**
- Order and transaction management

### User Panel
- Browse and purchase products
- Secure checkout using **PayPal API**
- View order history

## Technologies Used
- **Laravel** (PHP Framework)
- **MySQL** (Database via XAMPP)
- **PayPal API** (Payment Integration)
- **Blade Templates** (Frontend Rendering)
- **Bootstrap** (UI Design)

## Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/projetE-site.git
   ```
2. Navigate to the project directory:
   ```sh
   cd laravel-paypal-shop
   ```
3. Install dependencies:
   ```sh
   composer install
   ```
4. Configure `.env` file:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
5. Set up the database:
   ```sh
   php artisan migrate --seed
   ```
6. Start the server:
   ```sh
   php artisan serve
   ```

## PayPal API Configuration
Update the `.env` file with your **PayPal Client ID** and **Secret**:
```env
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret
PAYPAL_MODE=sandbox # Change to 'live' for production
```
## First Admin User Setup
To create the first administrator user, uncomment lines **54 to 62** in the `login` function of `AuthController.php`. After running the application and logging in with the created user, **recomment these lines** to prevent further unintended user creation.

## Usage
- **Admin**: Access the dashboard with predefined credentials.
- **Users**: Browse products, add to cart, and checkout via PayPal.

## License
This project is open-source.

