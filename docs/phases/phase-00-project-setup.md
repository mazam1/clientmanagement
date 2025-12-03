# Phase 0: Project Setup & Infrastructure

**Duration:** 1-2 days  
**Prerequisites:** None  
**Complexity:** Low  
**Risk Level:** Low

---

## Overview

Establish the foundational Laravel 12 application with all required dependencies, configure Tailwind CSS with custom monochrome theme, and set up the development environment.

### **Objectives**

- ✅ Install fresh Laravel 12 application
- ✅ Configure MySQL database connection
- ✅ Install and configure Tailwind CSS with monochrome theme
- ✅ Install spatie/laravel-permission package
- ✅ Set up basic project structure
- ✅ Configure development tools (Vite, npm)

---

## Prerequisites

### **System Requirements**

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- npm 9.x or higher
- MySQL 8.0 or higher
- Git (for version control)

### **Environment Checklist**

- [ ] PHP extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON
- [ ] MySQL server running and accessible
- [ ] Composer globally installed
- [ ] Node.js and npm installed
- [ ] Text editor/IDE configured

---

## Deliverables

1. **Laravel Application**
   - Fresh Laravel 11.x installation
   - `.env` configured with database credentials
   - Application key generated

2. **Database Configuration**
   - MySQL database created
   - Connection verified

3. **Frontend Setup**
   - Tailwind CSS 3.x installed
   - Custom monochrome theme configured
   - Vite build process working

4. **RBAC Package**
   - spatie/laravel-permission installed
   - Config published
   - Migrations ready

5. **Project Structure**
   - Standard Laravel directories
   - Custom folders: `app/Services`, `docs`, `docs/phases`

---

## Task Checklist

### **Task 1: Install Laravel**

```bash
# If starting fresh (delete existing files if needed)
composer create-project laravel/laravel client-management "11.*"
cd client-management
```

**Validation:**
```bash
php artisan --version
# Should output: Laravel Framework 11.x.x
```

---

### **Task 2: Configure Environment**

**Steps:**
1. Copy `.env.example` to `.env` (if not auto-created)
2. Generate application key:
   ```bash
   php artisan key:generate
   ```

3. Configure database in `.env`:
   ```env
   APP_NAME="Client Management Dashboard"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000
   APP_TIMEZONE=UTC
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=clientmanagement
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```

4. Create database:
   ```sql
   CREATE DATABASE clientmanagement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

**Validation:**
```bash
php artisan migrate:status
# Should connect to database without errors
```

---

### **Task 3: Install Tailwind CSS**

**Steps:**

1. Install dependencies:
   ```bash
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init -p
   ```

2. Update `tailwind.config.js`:
   ```javascript
   /** @type {import('tailwindcss').Config} */
   export default {
     content: [
       "./resources/**/*.blade.php",
       "./resources/**/*.js",
       "./resources/**/*.vue",
     ],
     darkMode: 'class', // Enable class-based dark mode
     theme: {
       extend: {
         colors: {
           // Light Mode
           'bg-primary': '#FFFFFF',
           'bg-secondary': '#F8F9FA',
           'bg-tertiary': '#F1F3F5',
           'border-light': '#E9ECEF',
           'border-medium': '#DEE2E6',
           'border-dark': '#CED4DA',
           'text-primary': '#212529',
           'text-secondary': '#6C757D',
           'text-tertiary': '#ADB5BD',
           'sidebar-bg': '#1A1D1F',
           'sidebar-text': '#E9ECEF',
           'sidebar-text-muted': '#868E96',
           'sidebar-hover': '#2C3034',
           'sidebar-active': '#343A40',
           
           // Dark Mode (use with dark: prefix)
           'dark-bg-primary': '#121212',
           'dark-bg-secondary': '#1E1E1E',
           'dark-bg-tertiary': '#2A2A2A',
           'dark-border-light': '#333333',
           'dark-border-medium': '#404040',
           'dark-border-dark': '#4D4D4D',
           'dark-text-primary': '#FFFFFF',
           'dark-text-secondary': '#CCCCCC',
           'dark-text-tertiary': '#999999',
           'dark-sidebar-bg': '#0A0A0A',
           'dark-sidebar-text': '#FFFFFF',
           'dark-sidebar-text-muted': '#808080',
           'dark-sidebar-hover': '#1A1A1A',
           'dark-sidebar-active': '#252525',
           
           // Accents
           'accent-primary': '#0066FF',
           'accent-success': '#00C853',
           'accent-warning': '#FFB300',
           'accent-danger': '#FF1744',
           'accent-info': '#00B8D4',
         },
       },
     },
     plugins: [],
   }
   ```

3. Create `resources/css/app.css`:
   ```css
   @tailwind base;
   @tailwind components;
   @tailwind utilities;
   
   /* Custom base styles */
   @layer base {
     body {
       @apply bg-bg-secondary text-text-primary;
     }
     
     .dark body {
       @apply bg-dark-bg-primary text-dark-text-primary;
     }
   }
   ```

4. Update `resources/views/welcome.blade.php` to include Vite assets:
   ```blade
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>{{ config('app.name') }}</title>
       @vite(['resources/css/app.css', 'resources/js/app.js'])
   </head>
   <body>
       <h1 class="text-3xl font-bold text-text-primary">
           Welcome to Client Management Dashboard
       </h1>
   </body>
   </html>
   ```

**Validation:**
```bash
npm run dev
# Visit http://localhost:8000 - should see styled text
```

---

### **Task 4: Install spatie/laravel-permission**

**Steps:**

1. Install package:
   ```bash
   composer require spatie/laravel-permission
   ```

2. Publish configuration:
   ```bash
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

4. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

**Validation:**
```bash
php artisan migrate:status
# Should show 5 new permission tables migrated
```

---

### **Task 5: Create Custom Directories**

**Steps:**

```bash
# Create Services directory
mkdir app/Services

# Create docs/phases directory (if not exists)
mkdir -p docs/phases

# Create component directories
mkdir -p resources/views/components
mkdir -p resources/views/layouts
mkdir -p resources/views/layouts/partials
```

**Directory Structure:**
```
app/
├── Services/          (Business logic layer)
resources/
├── views/
│   ├── components/    (Reusable Blade components)
│   ├── layouts/       (Layout templates)
│   │   └── partials/  (Sidebar, topbar, etc.)
docs/
└── phases/            (Implementation phase docs)
```

---

### **Task 6: Configure Package.json Scripts**

Update `package.json` to include build scripts:

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "watch": "vite build --watch"
  }
}
```

**Validation:**
```bash
npm run build
# Should compile assets successfully
```

---

### **Task 7: Configure Git Ignore**

Ensure `.gitignore` includes:

```gitignore
/node_modules
/public/hot
/public/storage
/public/build
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/.idea
/.vscode
```

---

### **Task 8: Initial Commit**

```bash
git init
git add .
git commit -m "Phase 0: Initial Laravel 11 setup with Tailwind CSS and spatie/permissions"
```

---

## Acceptance Criteria

Before proceeding to Phase 1, validate:

- [ ] Laravel 11.x installed and `php artisan --version` works
- [ ] Application key generated in `.env`
- [ ] MySQL database created and connection verified
- [ ] `php artisan migrate` runs successfully (default + permission tables)
- [ ] Tailwind CSS compiles without errors (`npm run dev`)
- [ ] Welcome page loads at `http://localhost:8000` with styled content
- [ ] spatie/laravel-permission config published
- [ ] Custom directories created (`app/Services`, `docs/phases`)
- [ ] No errors in browser console or terminal
- [ ] Git repository initialized with initial commit

---

## Testing Commands

Run these commands to validate the phase:

```bash
# Test PHP version and extensions
php -v
php -m | grep -E 'pdo|mbstring|openssl'

# Test Composer
composer --version

# Test Laravel installation
php artisan --version
php artisan list

# Test database connection
php artisan migrate:status

# Test Node.js and npm
node -v
npm -v

# Test Tailwind compilation
npm run build

# Test application serves
php artisan serve
# Visit http://localhost:8000
```

---

## Troubleshooting

### **Issue: Database Connection Failed**

**Solution:**
- Verify MySQL is running: `mysql -u root -p`
- Check `.env` DB credentials match MySQL user
- Ensure database exists: `SHOW DATABASES;`

### **Issue: npm install fails**

**Solution:**
- Clear npm cache: `npm cache clean --force`
- Delete `node_modules` and `package-lock.json`
- Re-run `npm install`

### **Issue: Vite not compiling**

**Solution:**
- Check Node.js version: `node -v` (should be 18+)
- Remove `node_modules` and reinstall
- Check `vite.config.js` is present

### **Issue: Permission migrations not found**

**Solution:**
- Re-publish: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations" --force`
- Run: `php artisan migrate`

---

## Dependencies for Next Phase

**Phase 1 (Authentication & RBAC) requires:**
- ✅ Laravel installed with spatie/permissions
- ✅ Database connection working
- ✅ Permission tables migrated
- ✅ Tailwind CSS configured

---

## Estimated Time Breakdown

| Task | Estimated Time |
|------|----------------|
| Install Laravel | 15 minutes |
| Configure environment | 20 minutes |
| Install Tailwind CSS | 30 minutes |
| Install spatie/permissions | 15 minutes |
| Create directories | 10 minutes |
| Configure scripts | 10 minutes |
| Testing & validation | 20 minutes |
| **Total** | **~2 hours** |

---

## Phase Completion Sign-off

**Completed By:** [AI Agent Name]  
**Completion Date:** [YYYY-MM-DD]  
**Status:** ⬜ Not Started | ⬜ In Progress | ⬜ Complete  
**Notes:** 

---

**Next Phase:** [Phase 1: Authentication & RBAC Foundation](./phase-01-auth-rbac.md)
