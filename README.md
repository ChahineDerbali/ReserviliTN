# Reservili

Reservili is a PHP-based web application for managing events and reservations. It allows users to browse, search, and reserve events, while administrators can create, edit, and delete events. The project uses MySQL for data storage and features user authentication with role-based access.

## Features

- **User Authentication**
  - Sign up, log in, and log out
  - Session-based user roles (user/admin)

- **Event Management**
  - Browse and search events by category or name
  - Reserve and cancel reservations for events
  - View personal reservations

- **Admin Dashboard**
  - Create, edit, and delete events
  - Manage all reservations

- **Database**
  - MySQL database for users, events, and reservations

- **UI & Styling**
  - Responsive design with custom CSS

- **Static Pages**
  - About and Contact pages


## Getting Started

1. **Clone the repository**
2. **Set up the MySQL database** using the provided schema (see `includes/db.php` for connection details)
3. **Configure database credentials** in `includes/db.php`
4. **Run the application** on a local PHP server (e.g., XAMPP, WAMP, or `php -S localhost:8000`)

