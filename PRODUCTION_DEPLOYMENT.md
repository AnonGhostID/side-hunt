# Production Deployment Guide

## CSS Loading Issue Resolution

This guide documents the fixes applied to resolve production CSS loading issues where pages showed unstyled HTML.

### Issues Fixed

1. **Missing Dependencies**: npm packages were not installed
2. **Incorrect CSS File**: `resources/css/app.css` contained @vite directive instead of actual CSS
3. **Inconsistent Vite Configuration**: Mix of CSS and SCSS files with incomplete entry points
4. **Template Issues**: Duplicate and inconsistent @vite directives in Blade templates
5. **CDN vs Local Assets**: Some templates used CDN, others used local assets

### Production Deployment Steps

1. **Install Node.js Dependencies**
   ```bash
   npm install
   ```

2. **Build Production Assets**
   ```bash
   npm run build
   ```
   **Important**: The `public/build/` directory is in `.gitignore` so assets must be built on the server.

3. **Install PHP Dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

4. **Configure Environment**
   ```bash
   cp .env.example .env
   # Edit .env file:
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Cache Configuration**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Asset Structure

After running `npm run build`, the following assets are generated:

- **Tailwind CSS**: `public/build/assets/app-[hash].css` (~14KB)
- **Bootstrap SCSS**: `public/build/assets/app-[hash].css` (~301KB)  
- **JavaScript**: `public/build/assets/app-[hash].js` (~115KB)
- **Font Assets**: Bootstrap icons fonts
- **Manifest**: `public/build/manifest.json` (maps source files to built files)

### Template Updates

All layout files now include both CSS entry points:
```blade
@vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
```

### File Structure

```
resources/
├── css/
│   └── app.css (Tailwind directives + Bootstrap imports)
├── sass/
│   ├── app.scss (Bootstrap + icons)
│   └── _variables.scss
└── js/
    └── app.js (Bootstrap JS imports)

vite.config.js (configured for both CSS and SCSS)
tailwind.config.js (Tailwind configuration)
```

### Development vs Production

- **Development**: Run `npm run dev` (requires LARAVEL_BYPASS_ENV_CHECK=1 in CI)
- **Production**: Run `npm run build` to generate static assets

### Verification

To verify the production build is working:
1. Check `public/build/manifest.json` exists
2. Verify CSS and JS files exist in `public/build/assets/`
3. Test pages load with proper styling
4. Management panel (/management) should use Vite-compiled Tailwind instead of CDN

### Troubleshooting

- **No CSS loading**: Ensure `npm run build` completed successfully
- **Old CDN styles**: Check templates are using @vite directive not CDN links
- **Missing assets**: Verify manifest.json exists and references correct files
- **Permissions**: Ensure web server can read `public/build/` directory
- **Build errors**: Check for SCSS syntax issues or missing dependencies