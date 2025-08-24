# Layout System Documentation

This directory contains the layout files for the Matrimony application. The layouts provide a consistent structure and styling across all pages using Tailwind CSS.

## Available Layouts

### 1. Main Layout (`app.blade.php`)

The main layout that provides the basic HTML structure, navigation header, and footer for all pages.

**Features:**
- Responsive navigation header
- Tailwind CSS integration
- Common fonts and meta tags
- Footer with copyright information
- Support for custom styles and scripts

**Usage:**
```php
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
    <!-- Your page content here -->
@endsection

@push('styles')
    <!-- Additional CSS styles -->
@endpush

@push('scripts')
    <!-- Additional JavaScript -->
@endpush
```

### 2. Admin Layout (`admin.blade.php`)

A specialized layout for admin pages that extends the main layout and adds admin-specific features.

**Features:**
- Extends the main layout
- Fixed sidebar navigation
- Admin-specific navigation items
- Responsive design for mobile devices
- Consistent admin header with user info

**Usage:**
```php
@extends('layouts.admin')

@section('title', 'Admin Page Title')

@section('page-title', 'Page Title')
@section('page-description', 'Page description')

@section('admin-content')
    <!-- Your admin page content here -->
@endsection
```

## Layout Structure

```
layouts/
├── app.blade.php          # Main layout for all pages
├── admin.blade.php        # Admin-specific layout
└── README.md             # This documentation
```

## Key Features

### Tailwind CSS Integration
- All layouts include Tailwind CSS via CDN
- Custom CSS classes for gradients and effects
- Responsive design utilities
- Consistent color scheme

### Navigation
- **Main Layout**: Simple header with logo and admin login link
- **Admin Layout**: Full sidebar navigation with admin menu items

### Responsive Design
- Mobile-first approach
- Responsive navigation
- Adaptive layouts for different screen sizes

### Custom Styling
- Gradient backgrounds
- Glass morphism effects
- Consistent shadows and borders
- Hover effects and transitions

## Creating New Pages

### Regular Pages
```php
@extends('layouts.app')

@section('title', 'Your Page Title')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Your content here -->
    </div>
</div>
@endsection
```

### Admin Pages
```php
@extends('layouts.admin')

@section('title', 'Admin Page')

@section('page-title', 'Page Title')
@section('page-description', 'Page description')

@section('admin-content')
<div class="space-y-6">
    <!-- Your admin content here -->
</div>
@endsection
```

## Customization

### Adding Custom Styles
Use the `@push('styles')` directive to add page-specific CSS:

```php
@push('styles')
<style>
    .custom-class {
        /* Your custom styles */
    }
</style>
@endpush
```

### Adding Custom Scripts
Use the `@push('scripts')` directive to add page-specific JavaScript:

```php
@push('scripts')
<script>
    // Your custom JavaScript
</script>
@endpush
```

### Modifying Layouts
- **Main Layout**: Edit `app.blade.php` for site-wide changes
- **Admin Layout**: Edit `admin.blade.php` for admin-specific changes
- **Navigation**: Update the navigation arrays in respective layout files

## Best Practices

1. **Always extend a layout** instead of creating standalone HTML files
2. **Use semantic HTML** within your content sections
3. **Leverage Tailwind CSS** classes for consistent styling
4. **Keep content sections focused** on page-specific content
5. **Use the appropriate layout** (main vs admin) for your page type
6. **Test responsiveness** on different screen sizes

## Examples

See the following files for examples:
- `welcome.blade.php` - Uses main layout
- `admin/login.blade.php` - Uses main layout (login page)
- `admin/dashboard.blade.php` - Uses admin layout
- `user/profile.blade.php` - Uses main layout (user page) 