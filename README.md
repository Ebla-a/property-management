# 🏠 Property Management System
A comprehensive and integrated platform for browsing, booking, and managing properties, with robust features for both tenants and property administrators.



## 📸 Screenshots
![websit](screenshots/website.jpg)
![AboutUs](screenshots/about%20us.PNG)
![Admin Dashboard](screenshots/Home.PNG)
![Property Listing](screenshots/properties.PNG)
![Amenities](screenshots/amenities.PNG)
![Booking Management](screenshots/bookings.PNG)
![Booking Report Management](screenshots/bookings%20report.PNG)
![Property Report Management](screenshots/properties%20report.PNG)

## 📚 Table of Contents
- [🏠 Property Management System](#-property-management-system)
- [📸 Screenshots](#-screenshots)
- [📚 Table of Contents](#-table-of-contents)
- [🚀 Project Overview](#-project-overview)
- [⚙️ Requirements](#️-requirements)
- [🛠 Installation & Setup](#-installation--setup)
  - [1. Clone the Repository](#1-clone-the-repository)
  - [2. Install Dependencies](#2-install-dependencies)
  - [3. Configure Environment](#3-configure-environment)
  - [4. Set Up the Database](#4-set-up-the-database)
  - [5. Run the Application](#5-run-the-application)
- [👥 System Roles](#-system-roles)
- [🗄 Database Structure](#-database-structure)
  - [users](#users)
  - [properties](#properties)
  - [bookings](#bookings)
  - [reviews](#reviews)
- [🔗 Interfaces & Routes](#-interfaces--routes)
- [📚 API Documentation](#-api-documentation)
  - [🔐 Authentication](#-authentication)
  - [🏠 Properties](#-properties-1)
  - [📅 Bookings](#-bookings-1)
  - [⭐ Reviews](#-reviews-1)
- [🧾 Sample Responses](#-sample-responses)
  - [Property Response:](#property-response)
  - [Booking Response:](#booking-response)
- [🔑 Sample Credentials](#-sample-credentials)
  - [👨‍💼 Admin](#-admin)
  - [👨‍💻 Employee](#-employee)
  - [👤 Client](#-client)
- [📞 Support \& Contributions](#-support--contributions)
- [🏆 Acknowledgments](#-acknowledgments)
  - [🎉 Special Thanks](#-special-thanks)
  - [Mentors](#mentors)
  - [Development Team](#development-team)

## 🚀 Project Overview
The Property Management System is a full-stack Laravel application designed to:

✅ **Allow user registration and authentication**  
🏠 **Enable property browsing with detailed information**  
📅 **Let users book properties and track booking status**  
📊 **Provide admin dashboard for property and user management**  
🔐 **Enforce role-based access control for admins, employees, and clients**

## ⚙️ Requirements
| Component | Version |
|-----------|---------|
| PHP | ≥ 8.2 |
| Composer | Latest |
| Laravel | 12.x |
| Database | MySQL |
| Node.js | Latest (v18+) |

## 🛠 Installation & Setup
### 1. Clone the Repository
```bash
git clone https://github.com/Ebla-a/property-management.git
cd property-management
```

### 2. Install Dependencies
```bash
composer install      # PHP dependencies
npm install           # JS dependencies
```

### 3. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set Up the Database
```bash
php artisan migrate --seed
```

### 5. Run the Application
```bash
php artisan serve     # Start Laravel dev server
npm run dev           # Compile frontend assets with Vite
```

## 👥 System Roles
| Role | Permissions | Dashboard Route |
|------|-------------|-----------------|
| Admin | Full system access | `/admin` |
| Employee | Manage bookings & properties | `/employee` |
| Client | View properties, make bookings | `/dashboard` |

## 🗄 Database Structure
### users
`id, name, email, password, role, created_at, updated_at`

### properties
`id, title, description, price, location, images, status, created_at`

### bookings
`id, user_id, property_id, booking_date, status, notes, created_at`

### reviews
`id, user_id, property_id, booking_id, rating, comment, created_at`

## 🔗 Interfaces & Routes
| Page | Route | Description |
|------|-------|-------------|
| Admin Dashboard | `/admin` | Full system management |
| Employee Panel | `/employee` | Manage properties and bookings |
| Property Browsing | `/properties` | Public property interface |
| Login Page | `/login` | Login for all roles |

## 📚 API Documentation
### 🔐 Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | User login |
| POST | `/api/logout` | Logout (with token) |

### 🏠 Properties
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/properties` | List all properties (paginated) |
| GET | `/api/properties/{id}` | Fetch property details |
| POST | `/api/properties` | Create new property (admin only) |
| PUT | `/api/properties/{id}` | Update property (admin only) |
| DELETE | `/api/properties/{id}` | Delete property (admin only) |

### 📅 Bookings
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/bookings` | List bookings (role-based filtering) |
| POST | `/api/bookings` | Create new booking (client) |
| GET | `/api/bookings/{id}` | Get booking details |
| PUT | `/api/bookings/{id}/status` | Update status (employee/admin) |
| DELETE | `/api/bookings/{id}` | Cancel booking (employee/client/admin) |

### ⭐ Reviews
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/properties/{id}/reviews` | Get all reviews for a property |
| POST | `/api/reviews` | Submit a review (client) |

## 🧾 Sample Responses
### Property Response:
```json
{
    "data": {
        "id": 3,
        "title": "Hintz Rue Apartment",
        "type": null,
        "city": "Malloryside",
        "neighborhood": null,
        "address": "59578 Nakia Path Suite 684\nLake Destin, IL 55277",
        "rooms": 1,
        "area": null,
        "price": "521.00",
        "status": "hidden",
        "is_furnished": false,
        "description": null,
        "main_image": null,
        "amenities": [],
        "created_at": "2026-01-11 20:29:21"
    }
}
```

### Booking Response:
```json
   "data": [
        {
            "id": 46,
            "status": "pending",
            "scheduled_at": "2026-02-14 15:30",
            "notes": null,
            "property": {
                "id": 2,
                "title": "Katharina Glens Apartment",
                "city": "South Maurine"
            },
            "customer": null,
            "employee": null
        },
]
```

## 🔑 Sample Credentials
### 👨‍💼 Admin
**Email:** admin@example.com  
**Password:** password123

### 👨‍💻 Employee
**Email:** employee@property.com  
**Password:** 12345678


## 📞 Support & Contributions
If you find bugs, need help, or would like to contribute:

1. **Open an issue** on the [GitHub repo](https://github.com/Ebla-a/property-management.git)
2. **Fork and submit a pull request**
3. **Contact the team** for feedback or collaboration

## 🏆 Acknowledgments
### 🎉 Special Thanks
**Focal X Agency**  
For their commitment to student growth and learning opportunities.

### Mentors
**Mr. Hashim Othman**  
- Technical guidance
- Concept clarification
- Inspirational mentorship

**Mr. Ayham Ibrahim**  
- Support throughout development

**Supervisors**

**Ms Nourhan almohammed** & **Ms Muna alrays**
Technical support and daily follow-up

**Focal X Team**  
For building and supporting this educational journey

ceo and founder **Alaa Darwish**

### Development Team
| Role | Name |
|------|------|
| Lead Developer | Ebla zyab ali |
| Assistant Developer | Hasan Dayoub |
|  full stack | Wajd Heshme |
| Backend Developer | Amin Ali |
| Database Specialist | Enas Alhmoy|
| Backend Developer | Abdullah Shuraitah |

---

*Thank you to all educators, mentors, and contributors who made this project a reality.*
---

## 🙋 My contribution (Hasan Dayoub)

This repository is my fork of the team project [Ebla-a/property-management](https://github.com/Ebla-a/property-management). I worked on the `hasan` branch.

Merged pull requests in the team repository: [#7](https://github.com/Ebla-a/property-management/pull/7), [#8](https://github.com/Ebla-a/property-management/pull/8), [#15](https://github.com/Ebla-a/property-management/pull/15), [#27](https://github.com/Ebla-a/property-management/pull/27), [#34](https://github.com/Ebla-a/property-management/pull/34), [#40](https://github.com/Ebla-a/property-management/pull/40), [#71](https://github.com/Ebla-a/property-management/pull/71), [#86](https://github.com/Ebla-a/property-management/pull/86).

Areas I worked on:

- **Employee-to-customer communication:** booking messages (`BookingMessage` model and migration, `BookingCommunicationService`)
- **Reviews and ratings:** `ReviewService`, `RatingService` and `ReviewResource`
- **Admin and accounts:** `AdminService` and the user activation (`is_active`) migration
- **Reports:** property reports (`PropertiesReportController`) and optimizing the auth and review controllers

### Endpoints I added

| Method | Path | Purpose | PR |
|---|---|---|---|
| POST | `/ratings` | Customer adds a rating / review | [#7](https://github.com/Ebla-a/property-management/pull/7), [#8](https://github.com/Ebla-a/property-management/pull/8) |
| POST | `/logout` | Log out (Sanctum token) | [#15](https://github.com/Ebla-a/property-management/pull/15), [#40](https://github.com/Ebla-a/property-management/pull/40) |
| POST | `/employees` | Admin creates an employee account | [#15](https://github.com/Ebla-a/property-management/pull/15), [#27](https://github.com/Ebla-a/property-management/pull/27) |
| PATCH | `/users/{id}/role` | Admin changes a user's role | [#15](https://github.com/Ebla-a/property-management/pull/15) |
| PATCH | `/users/{userId}/status` | Admin activates / deactivates an account | [#15](https://github.com/Ebla-a/property-management/pull/15) |
| PATCH | `/change-password` | Admin changes their password | [#15](https://github.com/Ebla-a/property-management/pull/15) |
| GET, POST | `/bookings/{booking}/messages` | Employee-to-customer messages on a booking (`BookingMessageController`) | [#40](https://github.com/Ebla-a/property-management/pull/40) |
| GET | `/reports/properties` | Admin property report | [#34](https://github.com/Ebla-a/property-management/pull/34), [#86](https://github.com/Ebla-a/property-management/pull/86) |
| GET | `/reports/bookings` | Admin bookings report | [#34](https://github.com/Ebla-a/property-management/pull/34) |
