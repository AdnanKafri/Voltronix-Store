# ⚡ Voltronix Digital Store

A modern, bilingual (EN/AR) digital marketplace built with Laravel 11, featuring comprehensive SEO optimization, automated sitemap generation, and professional admin dashboard.

## 🚀 Key Features

- **Bilingual Support**: Full English/Arabic support with RTL/LTR layouts
- **SEO Optimized**: Automated sitemap generation, hreflang tags, structured data
- **Modern UI**: Responsive design with Voltronix branding and animations
- **Admin Dashboard**: Complete admin panel with user management, orders, products
- **E-commerce Ready**: Cart system, checkout process, order management
- **Analytics Integration**: Google Analytics 4 and Tag Manager support

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities


## 🔍 SEO Automation System

### Automated Sitemap Generation

The Voltronix Digital Store features a fully automated sitemap generation system that keeps your SEO fresh and up-to-date.

#### ⏰ Schedule Configuration
- **Frequency**: Daily at 3:00 AM (production only)
- **Environment**: Only runs in production environment
- **Location**: Sitemap saved to `/public/sitemap.xml`
- **Logging**: All operations logged to `storage/logs/laravel.log`

#### 🛠️ Manual Commands
{{ ... }}
```bash
# Generate sitemap manually with statistics
php artisan sitemap:generate --stats

# Force regeneration (overwrite existing)
php artisan sitemap:generate --force

# Test the scheduler system
php artisan scheduler:test

# Check automation status and recent activity
php artisan sitemap:status

# Run scheduler manually (for testing)
php artisan schedule:run
```

#### 📊 Sitemap Features
- **Bilingual Support**: Proper hreflang alternates for EN/AR
- **Dynamic Content**: Automatically includes all active products and categories
- **SEO Optimized**: Proper priority, changefreq, and lastmod dates
- **Performance**: Optimized XML structure with minimal file size

#### 🚫 Disabling Automation

To disable automatic sitemap generation, modify `routes/console.php`:

```php
// Comment out or remove this block
/*
app(Schedule::class)->command('sitemap:generate --force')
    ->dailyAt('03:00')
    ->when(function () {
        return app()->environment('production');
    });
*/
```

#### 🔧 Production Setup

Add this to your server's crontab for Laravel scheduler:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

#### 📧 Email Notifications (Optional)

To enable email notifications for sitemap generation:

1. **Configure Environment Variables**:
```env
ADMIN_EMAIL=admin@voltronix.com
SITEMAP_EMAIL_NOTIFICATIONS=true
```

2. **Enable Notifications**: Uncomment the notification blocks in `routes/console.php`

3. **Email Features**:
- **Success Emails**: Include generation statistics, file size, execution time
- **Failure Emails**: Include error details and troubleshooting steps
- **Professional Templates**: Voltronix-branded email design
- **Queued Processing**: Non-blocking email delivery

#### 📝 Log Messages

Success logs will appear as:
```
[Sitemap Scheduler] Sitemap regenerated successfully at 2024-10-19 03:00:00
[Sitemap Service] Sitemap generated successfully
```

Failure logs will appear as:
```
[Sitemap Scheduler] Sitemap regeneration failed at 2024-10-19 03:00:00
[Sitemap Service] Sitemap generation failed
```

## 🔐 Google OAuth Integration

### Overview
The Voltronix Digital Store includes a complete Google OAuth login system using Laravel Socialite, providing users with a seamless single sign-on experience.

### ✅ Features Implemented
- **Complete OAuth Flow**: Redirect to Google → User consent → Callback handling
- **User Account Linking**: Automatic account creation or linking for existing users
- **Graceful Error Handling**: User-friendly messages when OAuth is not configured
- **Bilingual Support**: Full EN/AR translation support for all OAuth messages
- **Security Features**: Proper validation, session management, and error logging
- **Avatar Integration**: Google profile pictures with fallback to initials-based avatars

### 🛠️ Setup Instructions

#### 1. Google Cloud Console Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable the **Google+ API** (or People API)
4. Go to **Credentials** → **Create Credentials** → **OAuth 2.0 Client IDs**
5. Set **Application Type** to "Web application"
6. Add **Authorized Redirect URIs**:
   - Local: `http://localhost:8000/auth/google/callback`
   - Production: `https://yourdomain.com/auth/google/callback`

#### 2. Environment Configuration
Add these variables to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=/auth/google/callback
```

#### 3. Verification
Run the configuration checker:
```bash
php artisan google:check
```

### 🔗 Routes Available
- **Login Redirect**: `/auth/google` - Redirects to Google OAuth
- **Callback Handler**: `/auth/google/callback` - Handles Google response
- **Disconnect**: `POST /auth/google/disconnect` - Unlink Google account (authenticated users)

### 🎨 UI Integration
The Google login button is automatically available on:
- Login page (`/login`) - Professional glassmorphism design
- Consistent with Voltronix branding
- RTL/LTR support for Arabic users
- Hover animations and accessibility features

### 🛡️ Security Features
- **Missing Config Handling**: Shows user-friendly message when credentials are not set
- **Account Protection**: Prevents disconnecting Google if it's the only login method
- **Session Security**: Proper session regeneration and CSRF protection
- **Error Logging**: Comprehensive logging for debugging and monitoring
- **Input Validation**: Validates all Google user data before account creation

### 🌐 Bilingual Support
**English Messages**:
- Welcome back notifications
- Error handling messages
- Configuration status messages

**Arabic Messages**:
- Complete RTL layout support
- Culturally appropriate translations
- Proper icon positioning

### 🔧 Technical Implementation
**Database Changes**:
- Added `google_id` column to users table (nullable, unique, indexed)
- Added `avatar` column for Google profile pictures
- Migration: `2025_10_19_200741_add_google_fields_to_users_table.php`

**New Files Created**:
- `app/Http/Controllers/Auth/GoogleController.php` - OAuth controller
- `app/Console/Commands/CheckGoogleOAuth.php` - Configuration checker
- Enhanced User model with Google OAuth methods

**Configuration Files**:
- `config/services.php` - Google OAuth service configuration
- Routes added to `routes/web.php`
- Translations in `lang/en/app.php` and `lang/ar/app.php`

### 🚀 Production Deployment
1. **Update Redirect URI**: Change to your production domain in Google Console
2. **Environment Variables**: Set production Google credentials in `.env`
3. **HTTPS Required**: Google OAuth requires HTTPS in production
4. **Domain Verification**: Verify your domain in Google Console if required

### 🧪 Testing
**Without Credentials** (Development):
- Google login button shows user-friendly "not configured" message
- No errors or broken functionality
- Users can still login with email/password

**With Credentials** (Production):
- Full OAuth flow works seamlessly
- Account creation and linking functions properly
- Avatar images load correctly
- Disconnect functionality works as expected

### 📊 User Experience Flow
1. **New User**: Click Google login → Google consent → Account created → Logged in
2. **Existing User**: Click Google login → Google consent → Account linked → Logged in
3. **Return User**: Click Google login → Instant login (if previously linked)
4. **Account Management**: Users can disconnect Google account from profile settings

### 🔍 Monitoring & Debugging
- **Configuration Check**: `php artisan google:check`
- **Error Logs**: Check `storage/logs/laravel.log` for OAuth errors
- **User Feedback**: Clear error messages shown to users
- **Admin Monitoring**: OAuth attempts logged for security monitoring

The Google OAuth system is production-ready and will work instantly once Google credentials are added to the environment configuration.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
