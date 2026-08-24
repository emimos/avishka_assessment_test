# Customer Support Ticket System

This is a Customer Support Ticket System built with Laravel and Tailwind CSS. It enables customers to submit support tickets, check real-time status updates using a unique reference code, and communicate with support agents via follow-up replies.

---

## Key Features

###  Customer Portal
- **Ticket Submission**: Instant ticket creation with validation for name, email, phone number, and issue details.
- **Reference Code Generator**: Generates unique tracking codes (e.g., `TK-8F92-A37B`).
- **Ticket Status Lookup**: Real-time status checking with complete reply history.
- **Customer Follow-up Replies**: Customers can post follow-up replies directly from their ticket status screen.
- **Automated Acknowledgement Emails**: Dispatches an email notification containing the reference number upon ticket creation.

###  Agent Dashboard
- **Secure Agent Authentication**: Protected routes and session management for support agents.
- **Dashboard Stats**: Real-time counters for Total, New, Pending, and Replied tickets.
- **Live On-Type Search**: Debounced live filtering across customer names, emails, phone numbers, and reference codes.
- **Status Filter Tabs**: Quick filtering for *All*, *New*, *Pending*, and *Replied* tickets.
- **Interactive Ticket Modal**: View ticket details, read past conversation timeline, and post replies seamlessly.
- **Email Notifications on Reply**: Sends an automated email to the customer whenever an agent responds.

### Responsive & Modern UI
- Responsive navigation bar with a  drawer menu.
- Styled using Tailwind CSS with glassmorphism card components and dynamic status badges.

---

## System Requirements & Prerequisites

Ensure your system meets the following requirements before installation:

- **PHP**: `^8.3`
- **Composer**: `^2.0`
- **Node.js**: `^18.0` & **npm**
- **Database**: MySQL `^8.0` / MariaDB `^10.4` (or SQLite)
- **Web Server**: Apache / Nginx / Built-in PHP CLI Server

---

## Installation Guide

Follow these step-by-step instructions to set up the project locally:

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/test_coding_application.git
cd test_coding_application
```

### 2. Install PHP & Node Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
Copy the `.env.example` file to create your `.env` configuration file:
```bash
cp .env.example .env
```

Generate the Laravel application key:
```bash
php artisan key:generate
```

### 4. Database Setup
Update your database configuration inside the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=dbhost
DB_PORT=dbport
DB_DATABASE=dbname
DB_USERNAME=dbusername
DB_PASSWORD=dbpassword
```

Run database migrations and seed default data (creates default agent account):
```bash
php artisan migrate --seed
```

---

## 📧 Mail Setup Configuration

SupportPro sends automated emails for ticket creation acknowledgements and agent replies.

### Configuring SMTP (Production / Staging)
Configure your SMTP provider details in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="support@yourdomain.com"
MAIL_FROM_NAME="SupportPro Ticket Hub"
```

### Local Testing Options

#### Option A: Log Driver (Development without SMTP)
If you don't have an active SMTP server, write emails to `storage/logs/laravel.log`:
```env
MAIL_MAILER=log
```

#### Option B: Mailtrap / Mailpit (Recommended for Testing)
Use [Mailtrap](https://mailtrap.io/) or [Mailpit](https://github.com/axllent/mailpit) to capture sent emails visually during local testing.

---

## Default Agent Credentials

After running `php artisan migrate --seed`, use the default credentials below to log into the Agent Dashboard at `/agent/login`:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Support Agent** | `agent@support.com` | `password123` |

---

## 🏃 Running the Application locally

Start the Laravel development server and Vite asset bundler:

```bash
# Terminal 1: PHP Server
php artisan serve

# Terminal 2: Vite Dev Server (for Tailwind / Assets)
npm run dev
```

Visit the application in your browser:
- **Customer Support Hub**: `http://localhost:8000/`
- **Agent Login**: `http://localhost:8000/agent/login`
- **Agent Dashboard**: `http://localhost:8000/agent/dashboard`

---

##  Testing

Run the automated test suite using Artisan:
```bash
php artisan test
```

---

## Project Architecture

```
test_coding_application/
├── app/
│   ├── Http/Controllers/
│   │   ├── AgentDashboardController.php  # Handles agent dashboard & replies
│   │   ├── AuthController.php            # Agent login & logout
│   │   └── GuestTicketController.php     # Public ticket submission & status lookup
│   ├── Mail/
│   │   ├── TicketAcknowledgementMail.php # Email sent on ticket creation
│   │   └── TicketRepliedMail.php         # Email sent when agent replies
│   ├── Models/
│   │   ├── Ticket.php                    # Ticket Eloquent Model
│   │   └── TicketReply.php               # Ticket Reply Eloquent Model
│   └── Services/
│       ├── AgentService.php              # Ticket filtering & agent reply logic
│       └── TicketService.php             # Ticket creation & customer reply logic
├── database/
│   ├── migrations/                       # Database schema migrations
│   └── seeders/DatabaseSeeder.php        # Initial agent account seeder
├── resources/views/
│   ├── agent/                            # Agent dashboard & auth blade views
│   ├── emails/                           # Email notification templates
│   ├── layouts/app.blade.php             # Main layout & navigation bar
│   └── welcome.blade.php                 # Customer Portal main page
└── routes/web.php                        # Application route definitions
```

---
