E-Commerce Platform (Laravel)

This project is a RESTful API for an E-commerce platform built using Laravel. The platform allows users to manage products, orders, and user profiles. It includes role-based access control (RBAC) to ensure proper permissions for users (customers) and administrators.

Features
1. User Management:
Customer Role:
Customers can register, log in, and view their profiles.
Can view products and place orders.
Can update their own profile details.
Admin Role:
Admins have full control over product management (CRUD operations).
Can manage categories, including creating and deleting categories.
Can view all orders and update order statuses.
Can manage user roles and profiles.
2. Product Management:
CRUD Operations for Products:
Admins can create, read, update, and delete products.
Products have attributes like title, description, price, stock, and category.
Products can be assigned to categories.
Inventory management: Stock quantity is tracked and updated during order placement.
Categories:
Admin can create categories and assign products to specific categories.
3. Order Management:
Create Orders:
Customers can place orders with selected products and specify the quantity.
Multiple payment methods are supported (credit card, PayPal, cash on delivery).
Order Status Management:
Admin can update the status of an order (pending, completed, canceled).
Order Notifications:
Customers receive notifications about their order status (order confirmation).
4. Authentication & Authorization:
JWT Authentication:
Users must register and log in using their credentials.
Roles are assigned to users (customer or admin).
Middleware ensures that users with the correct roles access specific resources.
Middleware:
Middleware checks user roles for restricted access.
Admins can access the product and order management endpoints, while customers can only access their own orders and profiles.
5. Database Design:
Users: Manages customers and admins.
Products: Stores information about the products.
Categories: Stores product categories.
Orders: Stores customer orders and the order status.
Order Items: Stores the details of each product in an order (product ID, quantity, price).

Installation :
1. Clone the repository
    git clone <https://github.com/EsraaEid2/petra_task.git>
    cd <project_directory>

2. Install dependencies:
    composer install

3. Set up the environment file:

    Copy .env.example to .env and set the necessary environment variables, such as database connection and JWT secret key.
    cp .env.example .env
    php artisan key:generate


4. Run migrations:

    Run the database migrations to create the necessary tables
    php artisan migrate
5. Seed the database
    seed the database with sample data for products and users.    
    php artisan db:seed
6. Start the development server:
    php artisan serve
Now, the application should be up and running on http://localhost:8000.

API Endpoints

1. Product Management
GET /api/products: List all products.
GET /api/products/{id}: Get details of a specific product.
POST /api/products: Create a new product (admin only).
PUT /api/products/{id}: Update product details (admin only).
DELETE /api/products/{id}: Delete a product (admin only).
2. Order Management
POST /api/orders: Create a new order (customer only).
GET /api/orders: List all orders (admin only).
GET /api/orders/{id}: Get details of a specific order (admin and customer for their orders).
PUT /api/orders/{id}/status: Update the order status (admin only).
3. User Management
POST /api/auth/register: Register a new user (customer).
POST /api/auth/login: Login a user.
GET /api/users/profile: Get the logged-in user's profile.
PUT /api/users/profile: Update the logged-in user's profile.
4. Authentication
POST /api/auth/logout: Logout the current user.

Roles and Permissions

Customer:
Can register, log in, view products, and place orders.
Can view and update their profile.
Admin:
Can create, read, update, and delete products.
Can create and delete categories.
Can manage orders and update their statuses.
Can manage user profiles and roles

Middleware
Middleware is used to handle role-based access control. There are two main middlewares

auth:api: Ensures that the user is authenticated before performing any actions.
role:admin: Ensures that the user is an admin before performing actions like managing products or orders.