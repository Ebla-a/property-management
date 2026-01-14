# 🏠 Property Management System

A comprehensive Laravel-based platform for managing property listings, bookings, and user interactions with three distinct system roles (Admin, Employee, Client)



## 📸 Screenshots
*(Replace these with your actual screenshot paths after uploading images)*
![Admin Dashboard](/screenshots/Hoom.png)
![Property Listing](/screenshots/properties.png)
![Booking Management](/screenshots/amenities.png)
![Booking Management](/screenshots/bookings.png)
![Booking Repoert Management](/screenshots/bookings repoert.png) 
![Property Repoert Management](/screenshots/properties repoert.png) 

## 📚 Table of Contents
- [🚀 Project Overview](#-project-overview)
- [⚙️ Tech Stack](#-tech-stack)
- [👥 System Roles & Permissions](#-system-roles--permissions)
- [🛠 Installation & Setup](#-installation--setup)
- [🗄 Database Structure](#-database-structure)
- [🔗 Key Features & User Flows](#-key-features--user-flows)
- [📡 API Endpoints](#-api-endpoints)
- [🎨 UI/UX Details](#-uiux-details)
- [🔑 Sample Credentials](#-sample-credentials)
- [📞 Support & Contact](#-support--contact)

## 🚀 Project Overview
A full-stack property management system built with **Laravel, Blade, and Tailwind CSS** that enables:
- ✅ **Three-tier role system** (Admin, Employee, Client)
- 🏠 **Property browsing and detailed views** for clients
- 📅 **Booking system** with status tracking (Pending → Completed)
- ⭐ **Review system** for completed bookings
- 👔 **Employee dashboard** for managing bookings
- ⚙️ **Admin panel** for full system control

## ⚙️ Tech Stack
| Component | Technology |
|-----------|------------|
| **Backend Framework** | Laravel |
| **Frontend Template** | Blade |
| **CSS Framework** | Tailwind CSS |
| **Database** | MySQL |
| **Authentication** | Laravel Sanctum/Breeze |
| **Version Control** | Git/GitHub |

## 👥 System Roles & Permissions
| Role | Dashboard Route | Key Permissions |
|------|----------------|-----------------|
| **👑 Admin** | `/admin` | • Manage ALL system data<br>• CRUD for users, properties<br>• Full access to all bookings<br>• System configuration |
| **👔 Employee** | `/employee` | • View and manage bookings<br>• Update booking statuses<br>• View property listings<br>• Contact clients |
| **👤 Client** | `/dashboard` | • Register & login<br>• Browse all properties<br>• View property details<br>• Create bookings<br>• Submit reviews for completed bookings |

## 🛠 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Ebla-a/property-management.git
cd property-management

### 2. Install Dependencies
composer install
npm install

### 3. Configure Environment
cp .env.example .env
php artisan key:generate

Edit .env file with your database credentials:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=property_management
DB_USERNAME=root
DB_PASSWORD=

### 4. Set Up Database
php artisan migrate
php artisan db:seed  

### 5. Run the Application
php artisan serve     # Start Laravel dev server
npm run dev           # Compile frontend assets with Vite

🗄 Database Structure
Core Tables
users (id, name, email, password, role, created_at, updated_at)
properties (id, title, description, price, location, images, status, created_at)
bookings (id, user_id, property_id, booking_date, status, notes, created_at)
reviews (id, user_id, property_id, booking_id, rating, comment, created_at)

Relationships
User has many Bookings and Reviews

Property has many Bookings and Reviews

Booking belongs to User and Property

Review belongs to User, Property, and Booking

🔗 Key Features & User Flows

👤 Client Journey

1.Register/Login → Create account or sign in

2.Browse Properties → View all available properties

3.Property Details → See full details, images, pricing

4.Create Booking → Book a property (status: Pending)

5.Track Booking → View booking status updates

6.Submit Review → After booking status becomes "Completed"

👔 Employee Workflow

View  His Bookings → See pending, confirmed, completed bookings

Update Status → Change booking status (Pending → Confirmed → Completed)

Manage Client Info → View client details for each booking

👑 Admin Capabilities

Full CRUD operations on all tables

User role management

System analytics and reporting

Content management (properties, pages, etc.)


📡 API Endpoints

🔐 Authentication

POST    /api/register     # Register new user
POST    /api/login        # User login
POST    /api/logout       # User logout
GET     /api/user         # Get authenticated user

🏠 Properties
GET     /api/properties              # List all properties
GET     /api/properties/{id}         # Get property details
POST    /api/properties              # Create property (Admin only)
PUT     /api/properties/{id}         # Update property (Admin only)
DELETE  /api/properties/{id}         # Delete property (Admin only)

📅 Bookings
GET     /api/bookings                # List bookings (role-based filtering)
POST    /api/bookings                # Create new booking (Client)
GET     /api/bookings/{id}           # Get booking details
PUT     /api/bookings/{id}/status    # Update status (Employee/Admin)
DELETE  /api/bookings/{id}           # Cancel booking (Employee/Client/Admin)

⭐ Reviews
GET     /api/properties/{id}/reviews  # Get property reviews
POST    /api/reviews                  # Submit review (Client)

🎨 UI/UX Details
Design System: Tailwind CSS   

Responsive Layout: Mobile-first approach

Blade Components: Reusable partials for consistency

Color Scheme: Professional blues and neutral tones

Icons: Heroicons or FontAwesome

🔑 Sample Credentials

👑 Admin Account
Email: admin@example.com
Password: password123

👔 Employee Account
Email: employee@property.com
Password: employee123

👤 Client Account
Email: client@example.com
Password: client123

📞 Support & Contact
If you find bugs, need help, or would like to contribute:

1.Open an issue on the GitHub repo
2.Fork and submit a pull request
3.Contact the team for feedback or collaboration




