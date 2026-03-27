# Zuber - Professional Cab Booking & Management System

A comprehensive, multi-portal solution for cab booking, driver management, and business operations. Built with a modern tech stack focusing on performance, UX, and security.

---

## 🏗️ System Architecture

The project is organized into three distinct but integrated modules:

### 1. Customer Portal (Root)
*   **Ride Hub:** Interactive booking engine with real-time fare estimates.
*   **Booking History:** Track past and upcoming trips.
*   **Profile Management:** Secure user account and payment method storage.
*   **Modern Branding:** High-end typography and premium design aesthetics.

### 2. Admin Panel (`/admin`)
*   **Operations Hub:** Full control over the entire ecosystem.
*   **Vetting System:** Multi-step approval process for new driver applications.
*   **Fleet Control:** Manage vehicle inventory, types (Sedan, SUV, Luxury), and maintenance status.
*   **Live Metrics:** Real-time dashboards for bookings and revenue.
*   **Audit Tools:** Detailed view of driver documentation (Aadhaar, License) via AJAX modals.

### 3. Driver Portal (`/driver`) - **NEW**
*   **Workplace Dashboard:** Real-time polling for new ride requests.
*   **Integrated Navigation:** Live route mapping from pickup to destination using Leaflet.js.
*   **Availability Toggle:** Simple "Online/Offline" switch to manage work session.
*   **Earnings Tracker:** Detailed trip history with lifetime revenue calculation.
*   **Profile Hub:** Quick view of KYC verification status and vehicle information.

---

## 🚀 Specialized Technical Features

### 📡 Fixed Country Code Integration
*   **UI/UX:** Implemented a non-editable, unselectable `+91` prefix overlay on the mobile input field.
*   **Integrity:** Uses CSS padding and pointer-event logic to ensure users can only interact with the 10-digit portion of the field.
*   **Automation:** JavaScript automatically prepends the prefix during data sanitization, ensuring the database always stores numbers in the standardized `+91XXXXXXXXXX` format.

### 🗺️ Live Routing Hub
*   **Engine:** Powered by Leaflet.js and Leaflet Routing Machine.
*   **UX:** Dynamic route generation upon ride acceptance, providing drivers with instant visual cues for their next trip.

### 🍱 Clean API Layer
*   Modular PHP API scripts in the `/api` folder handle all core operations (Booking, Status updates, User profiles) with JSON responses and error handling.

---

## 🛠️ Installation & Setup

1.  **Server Environment:** Optimized for XAMPP/WAMP (PHP 7.4+ and MySQL).
2.  **Database Migration:** Execute the `database.sql` file in your MySQL environment. This includes all tables for `users`, `drivers`, `cabs`, and `bookings` with proper foreign key relationships.
3.  **Connection:** Ensure `api/db_connect.php` is configured with your local credentials.
4.  **Admin Setup:** Use the credentials in `admin/README.md` to access the operation panel.

---

## 📂 Key Directory Map
*   `/admin` - Management interface and setup tools.
*   `/api` - Unified backend logic for all portals.
*   `/driver` - Dedicated driver partner environment.
*   `/layout` - Global frontend components.

---

© 2026 Zuber Project Team.
Krushna
