# Uranus

Uranus is an open-source Laravel backend for a social chat system. It includes email OTP authentication, user profiles, friendships, private conversations, realtime presence/chat events, Firebase push notifications, and a fast Blade-based admin dashboard.

The admin dashboard is intentionally privacy-aware: it can list conversations and operational metadata, but it does not display message bodies or attachment details. This keeps the project ready for future end-to-end encrypted message content.

## Features

- Email OTP authentication with Laravel Sanctum API tokens.
- Profile completion and profile update endpoints.
- User discovery and user profile lookup.
- Friendship flow: send, accept, reject, cancel, remove, block, unblock.
- Conversation management between two users.
- Message records with text, image, file, and audio metadata support.
- Delivery and seen status endpoints.
- Typing indicators and realtime events.
- Presence endpoints for online/offline state.
- Private Pusher channels for conversations.
- Presence channel for online users.
- Firebase Cloud Messaging device-token storage.
- Push notifications for common chat and friendship events.
- Admin dashboard with separate admin auth.
- Admin CRUD for dashboard operators.
- Admin user management with profile, presence, friendship, message, and device counts.
- Admin conversation listing with metadata only, never message content.
- Admin friendship listing and status management.

## Tech Stack

- PHP 8.1+
- Laravel 10
- Laravel Sanctum
- MySQL or another Laravel-supported database
- Pusher-compatible broadcasting
- Firebase Cloud Messaging through `kreait/firebase-php`
- Blade, Vite, and small vanilla JavaScript for the dashboard

## Requirements

- PHP 8.1 or newer
- Composer
- Node.js and npm
- MySQL, MariaDB, or another configured database
- A mail service for OTP emails
- Optional: Pusher credentials for realtime broadcasting
- Optional: Firebase service-account credentials for push notifications

## Installation

Clone the repository:

```bash
git clone <repository-url>
cd Uranus
```

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel app key:

```bash
php artisan key:generate
```

## Environment Setup

Update `.env` for your local machine.

Basic app settings:

```env
APP_NAME=Uranus
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uranus
DB_USERNAME=root
DB_PASSWORD=
```

Mail settings are required for OTP emails:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@uranus.local"
MAIL_FROM_NAME="${APP_NAME}"
```

Admin seed settings:

```env
ADMIN_NAME="Uranus Admin"
ADMIN_EMAIL=admin@uranus.local
ADMIN_PASSWORD=
```

If `ADMIN_PASSWORD` is empty, `AdminSeeder` generates a random password and prints it once in the terminal.

Broadcasting settings:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

Firebase settings:

```env
FIREBASE_PROJECT=
FIREBASE_CREDENTIALS=
```

If `FIREBASE_CREDENTIALS` is empty, the app uses:

```text
storage/app/firebase-credentials.json
```

Do not commit real `.env` files, Pusher secrets, Firebase credentials, or generated admin passwords.

## Database Setup

Run migrations:

```bash
php artisan migrate
```

Seed the demo data and admin account:

```bash
php artisan db:seed
```

Seed only the admin account:

```bash
php artisan db:seed --class=AdminSeeder
```

Seed only the Uranus demo users, friendships, conversations, and messages:

```bash
php artisan db:seed --class=UranusDemoSeeder
```

For a fresh local database reset:

```bash
php artisan migrate:fresh --seed
```

## Running the Project

Start the Laravel server:

```bash
php artisan serve
```

Start Vite for dashboard assets during development:

```bash
npm run dev
```

Build frontend assets for production:

```bash
npm run build
```

Open the admin dashboard:

```text
http://localhost:8000/admin/login
```

## Admin Dashboard

The dashboard is available under `/admin`.

Dashboard areas:

- `/admin/login` for admin authentication.
- `/admin` for system home and stats.
- `/admin/admins` for admin account management.
- `/admin/users` for mobile user management.
- `/admin/conversations` for conversation metadata and actions.
- `/admin/friendships` for friendship graph status management.

Conversation screens never show message bodies, attachment paths, or attachment names.

## API Overview

Public auth endpoints:

- `POST /api/auth/otp`
- `POST /api/auth/otp/verify`

Authenticated API areas:

- `GET /api/profile/me`
- `POST /api/profile/complete`
- `POST /api/profile/update`
- `GET /api/home`
- `GET /api/users`
- `GET /api/users/{user}`
- `GET /api/friends`
- `GET /api/friends/requests`
- `GET /api/friends/blocked`
- `POST /api/friends/request`
- `POST /api/friends/accept`
- `POST /api/friends/reject`
- `POST /api/friends/cancel`
- `DELETE /api/friends/remove`
- `POST /api/friends/block`
- `POST /api/friends/unblock`
- `GET /api/conversations`
- `POST /api/conversations`
- `GET /api/conversations/{conversation}/messages`
- `POST /api/conversations/{conversation}/messages`
- `PUT /api/conversations/{conversation}/messages/{message}`
- `DELETE /api/conversations/{conversation}/messages/{message}`
- `POST /api/conversations/{conversation}/messages/{message}/delivered`
- `POST /api/conversations/{conversation}/messages/{message}/seen`
- `POST /api/conversations/{conversation}/typing`
- `POST /api/presence/online`
- `POST /api/presence/offline`
- `POST /api/devices/fcm-token`
- `DELETE /api/devices/fcm-token`

For the full Flutter-facing contract, see `API_ENDPOINTS.md` and `FLUTTER_NOTIFICATIONS_GUIDE.md`.

## Realtime Channels

The backend uses authenticated broadcasting routes for private and presence channels.

Common channels:

- `private-conversations.{conversation_id}`
- `presence-online`

Common events:

- `message.sent`
- `message.edited`
- `message.deleted`
- `typing.changed`
- `message.delivered`
- `message.seen`
- `presence.changed`

Keep `PUSHER_APP_SECRET` backend-only. Client applications should only receive the public Pusher key, host, port, scheme, and cluster values.

## Useful Commands

List routes:

```bash
php artisan route:list
php artisan route:list --path=api
php artisan route:list --path=admin
```

Check migration status:

```bash
php artisan migrate:status
```

Clear cached framework files:

```bash
php artisan optimize:clear
```

Cache Blade views:

```bash
php artisan view:cache
```

Clear Blade view cache:

```bash
php artisan view:clear
```

Run tests:

```bash
php artisan test
```

Run PHP syntax check on a file:

```bash
php -l path/to/file.php
```

Build assets:

```bash
npm run build
```