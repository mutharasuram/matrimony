# Matrimony Admin Panel

This document provides instructions for setting up and using the admin panel for the Matrimony application.

## Features

- **Secure Admin Login**: Beautiful login page with authentication
- **Dashboard**: Overview with statistics and recent activity
- **User Management**: View and manage users
- **Profile Management**: View and manage user profiles
- **Product Management**: View and manage products
- **Responsive Design**: Modern UI with Tailwind CSS

## Setup Instructions

### 1. Run Migrations

First, run the database migrations to add the admin column:

```bash
php artisan migrate
```

### 2. Create Admin User

You can create an admin user in two ways:

#### Option A: Using Artisan Command
```bash
php artisan admin:create admin@example.com your_password --name="Admin Name"
```

#### Option B: Using Database Seeder
```bash
php artisan db:seed --class=AdminSeeder
```

This will create an admin user with:
- Email: `admin@matrimony.com`
- Password: `admin123`

### 3. Access Admin Panel

Navigate to the admin login page:
```
http://your-domain.com/admin/login
```

## Admin Panel Routes

- **Login Page**: `/admin/login`
- **Dashboard**: `/admin/dashboard`
- **Logout**: POST `/admin/logout`

## Security Features

- **Admin Middleware**: Protects admin routes
- **Authentication**: Requires valid admin credentials
- **Session Management**: Secure session handling
- **CSRF Protection**: Built-in CSRF protection

## Dashboard Features

### Statistics Cards
- Total Users
- Total Profiles
- Total Products

### Recent Activity
- Recent Users
- Recent Profiles

### Quick Actions
- View All Users
- View All Profiles
- View Products
- Generate Reports

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AdminController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   └── User.php (updated with is_admin field)
└── Console/
    └── Commands/
        └── CreateAdminCommand.php

resources/
└── views/
    └── admin/
        ├── login.blade.php
        └── dashboard.blade.php

routes/
└── web.php (updated with admin routes)

database/
├── migrations/
│   └── 2025_01_27_000000_add_is_admin_to_users_table.php
└── seeders/
    ├── AdminSeeder.php
    └── DatabaseSeeder.php (updated)
```

## Customization

### Adding New Admin Features

1. **Create Controller Methods**: Add new methods to `AdminController.php`
2. **Add Routes**: Update `routes/web.php` with new admin routes
3. **Create Views**: Add new Blade templates in `resources/views/admin/`
4. **Update Middleware**: Modify `AdminMiddleware.php` if needed

### Styling

The admin panel uses Tailwind CSS. You can customize the styling by:
- Modifying the CSS classes in the Blade templates
- Adding custom CSS in the `<style>` tags
- Using Tailwind's utility classes

### Database Schema

The admin functionality adds an `is_admin` boolean column to the users table:

```sql
ALTER TABLE users ADD COLUMN is_admin BOOLEAN DEFAULT FALSE;
```

## Troubleshooting

### Common Issues

1. **"You do not have admin privileges"**
   - Ensure the user has `is_admin = true` in the database
   - Check if the user exists and is properly authenticated

2. **Migration fails**
   - Make sure you're running the latest migrations
   - Check if the `is_admin` column already exists

3. **Login not working**
   - Verify the user credentials
   - Check if the user has admin privileges
   - Ensure the database connection is working

### Debug Commands

```bash
# Check if admin user exists
php artisan tinker
>>> App\Models\User::where('is_admin', true)->get();

# Create admin user manually
php artisan admin:create admin@test.com password123

# Clear cache
php artisan cache:clear
php artisan config:clear
```

## Security Notes

- Change the default admin password after first login
- Use strong passwords for admin accounts
- Consider implementing two-factor authentication
- Regularly backup the database
- Monitor admin access logs

## Support

For issues or questions about the admin panel, please check:
1. Laravel documentation
2. Tailwind CSS documentation
3. Application logs in `storage/logs/` 