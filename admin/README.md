# Cab Booking Management System - Professional Admin Panel

A comprehensive, original admin interface designed for the Cab Booking System. Built with PHP, MySQL, and Modern CSS.

## 🚀 Key Features

### 1. Dashboard
- **Summary Cards:** Real-time totals for Bookings, Pending Requests, Completed Trips, Clients, Verified Drivers, and Available Cabs.
- **Recent Activity:** Quick view of the latest 10 bookings.

### 2. Booking Management
- **Centralized View:** Filter all bookings by status (Pending, Confirmed, Completed, Cancelled).
- **Lifecycle Control:** A professional admin-driven workflow:
  - **Approval:** Admin reviews pending rides and **Assigns available Drivers & Cabs**.
  - **Ongoing:** Once confirmed, the driver becomes "On Trip" and the cab becomes "Busy".
  - **Completion:** Admin manually marks trips as "Completed" to release resources back to the pool.

### 3. Driver Management
- **Vetting System:** Review new driver applications.
- **Approval Logic:** Approve or Reject drivers based on their licenses.
- **Active Controls:** Activate or Deactivate driver accounts at any time.
- **Status Tracking:** Monitor if a driver is Available, On Trip, or Offline.

### 4. Cab & Fleet Management
- **Inventory:** Full CRUD for the vehicle fleet.
- **Details:** Manage registration numbers, models, **Cab Type** (Sedan, SUV, etc.), and **Seating Capacity**.
- **Maintenance:** Set cab status to Available, Busy, or Maintenance.

### 5. Client Management
- **Customer List:** Full database of registered users.
- **Security:** Ability to block users from the system.

### 6. Authentication
- **Secure Login:** Session-based authentication using Bcrypt hashing.
- **Role-Based:** Dedicated `admins` table for system security.

## 🛠️ Installation & Setup
1. **Ensure XAMPP is running.**
2. **Setup Database:** Visit [http://localhost/cab_app/admin/setup.php](http://localhost/cab_app/admin/setup.php) to automatically create all tables and default accounts.
3. **Login:** Access the panel at [http://localhost/cab_app/admin/login.php](http://localhost/cab_app/admin/login.php).
   - **User:** `admin`
   - **Pass:** `Password@123`

## 📂 Project Architecture
- `admin/` - Root admin directory.
- `admin/config/db.php` - Database connection.
- `admin/inc/` - UI components (sidebar, header).
- `admin/assets/` - Professional styling and CSS.
- `admin/setup.php` - Automated installer.
