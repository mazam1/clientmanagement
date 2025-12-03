# Product Requirements Document (PRD)
## Client Management Dashboard

**Version:** 1.0  
**Date:** December 2, 2025  
**Document Type:** Product Requirements Document  
**Target Audience:** AI Agents, Development Teams, Stakeholders

---

## 1. Executive Summary

### 1.1 Product Overview
A full-stack web application for small service businesses (tutoring, consulting, freelance) to manage clients, track appointments/sessions, and generate invoices.

### 1.2 Business Objectives
- Streamline client relationship management
- Automate session tracking and invoicing
- Provide real-time business insights through analytics
- Reduce administrative overhead by 60%

### 1.3 Success Metrics
- User can create a client profile in < 30 seconds
- Invoice generation in < 15 seconds
- 100% data persistence across sessions
- Mobile-responsive on all viewport sizes (320px+)

---

## 2. Technical Architecture

### 2.1 Technology Stack
| Layer | Technology | Purpose |
|-------|------------|---------|
| **Backend Framework** | Laravel 12.x | Application logic, routing, ORM |
| **Database** | MySQL 8.0+ | Relational data storage |
| **Frontend** | Blade Templates | Server-side rendering |
| **Styling** | Tailwind CSS 3.x | Utility-first CSS framework |
| **JavaScript** | Vanilla JS (ES6+) | Client-side interactivity |
| **Architecture** | 3-Tier (MVC + Service) | Separation of concerns |
| **RBAC Package** | spatie/laravel-permission | Role-based access control |

### 2.2 Architecture Layers
```
┌─────────────────┐
│   VIEW LAYER    │ → Blade Templates + Tailwind CSS
├─────────────────┤
│ CONTROLLER      │ → Request handling, response formatting
├─────────────────┤
│ SERVICE LAYER   │ → Business logic, data transformation
├─────────────────┤
│  MODEL LAYER    │ → Eloquent ORM, database interaction
└─────────────────┘
```

**Layer Responsibilities:**
- **View:** UI rendering, Blade components, JavaScript interactions
- **Controller:** HTTP request/response, validation, authorization
- **Service:** Business logic, calculations, data aggregation
- **Model:** Database queries, relationships, accessors/mutators

### 2.3 Database Schema Requirements

#### Tables
1. **clients**
   - id (primary key)
   - name (string, required, indexed)
   - email (string, nullable, unique)
   - phone (string, nullable)
   - status (enum: active, inactive, archived)
   - created_at, updated_at
   - soft_deletes

2. **sessions**
   - id (primary key)
   - client_id (foreign key → clients)
   - session_date (datetime, required, indexed)
   - duration_minutes (integer, required)
   - notes (text, nullable)
   - created_at, updated_at

3. **invoices**
   - id (primary key)
   - client_id (foreign key → clients)
   - invoice_number (string, unique, indexed)
   - session_ids (json, array of session IDs)
   - subtotal (decimal 10,2)
   - tax_amount (decimal 10,2)
   - total_amount (decimal 10,2)
   - payment_status (enum: unpaid, partial, paid)
   - payment_date (datetime, nullable)
   - issued_at (datetime, required)
   - created_at, updated_at

#### Indexes
- clients.name (for search performance)
- clients.status (for filtering)
- sessions.session_date (for dashboard queries)
- invoices.invoice_number (unique lookup)
- invoices.payment_status (for reporting)

#### RBAC Tables (Spatie Permissions)
4. **roles**
   - id (primary key)
   - name (string, unique)
   - guard_name (string, default: 'web')
   - created_at, updated_at

5. **permissions**
   - id (primary key)
   - name (string, unique)
   - guard_name (string, default: 'web')
   - created_at, updated_at

6. **model_has_permissions**
   - permission_id (foreign key → permissions)
   - model_type (string, e.g., 'App\Models\User')
   - model_id (bigint, foreign key → users.id)
   - Composite primary key: (permission_id, model_id, model_type)

7. **model_has_roles**
   - role_id (foreign key → roles)
   - model_type (string)
   - model_id (bigint, foreign key → users.id)
   - Composite primary key: (role_id, model_id, model_type)

8. **role_has_permissions**
   - permission_id (foreign key → permissions)
   - role_id (foreign key → roles)
   - Composite primary key: (permission_id, role_id)

---

## 2.4 Admin Panel Layout Specification

### 2.4.1 Overall Layout Structure

**Layout Type:** Sidebar + Top Navigation (Standard Admin Dashboard)

```
┌─────────────────────────────────────────────────────────────┐
│  Topbar (Header)                                      │ User │
├──────────┬──────────────────────────────────────────────────┤
│          │  Main Content Area                               │
│          │                                                  │
│  Sidebar │  ┌────────────────────────────────────────────┐ │
│          │  │  Page Title                                │ │
│  (Fixed) │  │  Breadcrumbs                               │ │
│          │  └────────────────────────────────────────────┘ │
│          │                                                  │
│          │  [Content Cards/Tables/Forms]                    │
│          │                                                  │
│          │                                                  │
└──────────┴──────────────────────────────────────────────────┘
```

**Component Breakdown:**
1. **Sidebar (Left)** - 260px width on desktop, collapsible to 64px (icon-only)
2. **Topbar (Header)** - 64px height, full-width, fixed position
3. **Main Content** - Flexible width (100% - sidebar width), scrollable

### 2.4.2 Monochrome Color Palette

**Primary Palette (Grayscale):**
```css
/* Light Mode */
--color-bg-primary: #FFFFFF      /* Background - Pure white */
--color-bg-secondary: #F8F9FA    /* Cards, containers - Near white */
--color-bg-tertiary: #F1F3F5     /* Hover states, subtle backgrounds */

--color-border-light: #E9ECEF    /* Dividers, borders */
--color-border-medium: #DEE2E6   /* Input borders */
--color-border-dark: #CED4DA     /* Active borders */

--color-text-primary: #212529    /* Headings, primary text - Near black */
--color-text-secondary: #6C757D  /* Body text, labels */
--color-text-tertiary: #ADB5BD   /* Muted text, placeholders */

--color-sidebar-bg: #1A1D1F      /* Sidebar background - Charcoal black */
--color-sidebar-text: #E9ECEF    /* Sidebar text - Light grey */
--color-sidebar-text-muted: #868E96  /* Inactive sidebar items */
--color-sidebar-hover: #2C3034   /* Sidebar hover state */
--color-sidebar-active: #343A40  /* Active sidebar item */

/* Dark Mode */
--dark-bg-primary: #121212       /* Background - True black */
--dark-bg-secondary: #1E1E1E     /* Cards - Dark grey */
--dark-bg-tertiary: #2A2A2A      /* Hover states */

--dark-border-light: #333333     /* Dividers */
--dark-border-medium: #404040    /* Borders */
--dark-border-dark: #4D4D4D      /* Active borders */

--dark-text-primary: #FFFFFF     /* Headings - White */
--dark-text-secondary: #CCCCCC   /* Body text */
--dark-text-tertiary: #999999    /* Muted text */

--dark-sidebar-bg: #0A0A0A       /* Sidebar - Darker black */
--dark-sidebar-text: #FFFFFF     /* Sidebar text */
--dark-sidebar-text-muted: #808080
--dark-sidebar-hover: #1A1A1A
--dark-sidebar-active: #252525
```

**Accent Colors (Strategic Use Only):**
```css
/* Use sparingly for calls-to-action, notifications, data visualization */
--accent-primary: #0066FF        /* Primary actions (blue) */
--accent-success: #00C853        /* Success states (green) */
--accent-warning: #FFB300        /* Warnings (amber) */
--accent-danger: #FF1744         /* Errors, deletions (red) */
--accent-info: #00B8D4           /* Information (cyan) */
```

### 2.4.3 Sidebar Specifications

**Structure:**
- **Logo Area:** 260px × 64px, centered, top of sidebar
- **Navigation Menu:** Vertical list, icon + text
- **Footer:** User profile, logout button at bottom

**Navigation Item States:**
- **Default:** `color-sidebar-text`, no background
- **Hover:** `color-sidebar-hover` background, 4px left border accent
- **Active:** `color-sidebar-active` background, `accent-primary` left border (4px)
- **Icon Size:** 20px × 20px, 12px margin-right
- **Text:** 14px font size, medium weight
- **Padding:** 12px vertical, 16px horizontal

**Collapse Behavior:**
- **Expanded:** 260px width, show icon + text
- **Collapsed:** 64px width, icon-only (centered)
- **Toggle Button:** Top-right of sidebar, hamburger icon
- **Transition:** 200ms ease-in-out

**Navigation Modules (RBAC Controlled):**
```
├── Dashboard         (permission: view-dashboard)
├── Clients           (permission: view-clients)
├── Sessions          (permission: view-sessions)
├── Invoices          (permission: view-invoices)
├── Reports           (permission: view-reports)
├── Settings          (permission: view-settings)
└── User Management   (role: super-admin | admin)
```

### 2.4.4 Topbar (Header) Specifications

**Left Section:**
- Breadcrumbs navigation (Home > Clients > Edit Client)
- Font size: 13px, color: `text-secondary`
- Separator: `/` or `>` in `text-tertiary`

**Right Section:**
- **Search Bar:** 300px width, 36px height, rounded, icon-left
- **Notifications Icon:** Bell icon with badge count
- **Theme Toggle:** Sun/Moon icon for dark/light mode
- **User Dropdown:** Avatar (32px circle) + name + chevron

**Styling:**
- Background: `bg-primary` (white in light mode)
- Bottom border: 1px solid `border-light`
- Box shadow: 0 2px 4px rgba(0,0,0,0.04)
- Padding: 12px 24px

### 2.4.5 Main Content Area

**Layout Constraints:**
- Max width: 1400px (centered for large screens)
- Padding: 24px on all sides
- Background: `bg-secondary` (light grey for contrast)

**Page Header:**
- **Page Title:** H1, 28px, font-weight: 600, `text-primary`
- **Subtitle/Description:** 14px, `text-secondary`, margin-top: 4px
- **Action Buttons:** Right-aligned, primary button style
- **Margin Bottom:** 24px separator line

**Card Design:**
- Background: `bg-primary` (white cards on grey background)
- Border: 1px solid `border-light`
- Border radius: 8px
- Padding: 20px
- Box shadow: 0 1px 3px rgba(0,0,0,0.08)
- Margin bottom: 16px between cards

**Table Design:**
- **Header Row:** Background `bg-tertiary`, text-transform: uppercase, 11px, `text-secondary`, font-weight: 600
- **Body Rows:** 14px, `text-primary`, 48px height, border-bottom: 1px solid `border-light`
- **Hover State:** Background `bg-tertiary`, cursor: pointer
- **Striped:** Optional, alternate rows with `bg-secondary`

**Typography Hierarchy:**
```
H1 (Page Title):     28px, weight: 600, letter-spacing: -0.5px
H2 (Section):        22px, weight: 600
H3 (Card Title):     18px, weight: 600
H4 (Subsection):     16px, weight: 600
Body Text:           14px, weight: 400, line-height: 1.6
Small Text:          12px, weight: 400
Buttons:             14px, weight: 500
Labels:              13px, weight: 500, text-transform: uppercase
```

### 2.4.6 Component Specifications

**Buttons:**
```css
/* Primary Button */
background: --color-text-primary (black)
color: white
padding: 10px 20px
border-radius: 6px
font-size: 14px
hover: opacity 0.85

/* Secondary Button */
background: transparent
border: 1px solid --border-medium
color: --text-primary
hover: background --bg-tertiary

/* Ghost Button */
background: transparent
color: --text-secondary
hover: background --bg-tertiary
```

**Form Inputs:**
```css
height: 40px
padding: 8px 12px
border: 1px solid --border-medium
border-radius: 6px
font-size: 14px
focus: border-color --accent-primary, box-shadow 0 0 0 3px rgba(0,102,255,0.1)
```

**Badges/Status Indicators:**
```css
/* Use monochrome variations */
Active:     background: #000, color: #FFF
Inactive:   background: #6C757D, color: #FFF
Archived:   background: #ADB5BD, color: #FFF
Paid:       background: #00C853, color: #FFF (accent exception)
Unpaid:     background: #CED4DA, color: #495057
```

**Modals:**
```css
Backdrop: rgba(0, 0, 0, 0.5)
Container: --bg-primary, max-width 600px, border-radius 12px
Header: border-bottom 1px solid --border-light, padding 20px
Body: padding 20px
Footer: border-top 1px solid --border-light, padding 16px, buttons right-aligned
```

### 2.4.7 Responsive Breakpoints

**Desktop (1024px+):**
- Sidebar: Visible, 260px width
- Content: Full layout
- Tables: All columns visible

**Tablet (768px - 1023px):**
- Sidebar: Collapsible, overlay on mobile toggle
- Content: Full width when sidebar collapsed
- Tables: Horizontal scroll or hide non-essential columns

**Mobile (< 768px):**
- Sidebar: Hidden by default, slide-in overlay when toggled
- Topbar: Simplified (hamburger + logo + user menu)
- Content: Full width, 16px padding
- Tables: Card view (stack rows vertically)
- Stat cards: Stack vertically (1 column)

### 2.4.8 Design Principles

1. **Minimalism:** Clean, uncluttered layouts with ample white space
2. **Clarity:** Clear visual hierarchy using typography and spacing
3. **Consistency:** Uniform spacing scale (4px, 8px, 12px, 16px, 24px, 32px, 48px)
4. **Accessibility:** 4.5:1 minimum contrast ratio, focus indicators
5. **Performance:** CSS-only animations, optimized assets
6. **Progressive Disclosure:** Show essential info first, details on demand

---

## 2.5 Role-Based Access Control (RBAC)

### 2.5.1 RBAC Overview

**Package:** `spatie/laravel-permission` (v6.x)

**Core Concepts:**
- **Roles:** Groups of permissions (e.g., Super Admin, Admin, Manager, Staff)
- **Permissions:** Granular access rights (e.g., view-clients, edit-invoices)
- **Super Admin:** Special role with ALL permissions, regardless of explicit assignments
- **Route-Level Protection:** Middleware applied to routes, NOT in controllers

### 2.5.2 Predefined Roles

| Role | Description | Permission Strategy |
|------|-------------|--------------------|
| **Super Admin** | Full system access | Implicit all permissions via Gate::before |
| **Admin** | Manage users, all modules | All module permissions except user management |
| **Manager** | View/edit clients, sessions, invoices | view-*, create-*, edit-* (no delete) |
| **Staff** | View-only access to clients and sessions | view-clients, view-sessions |

### 2.5.3 Permission Naming Convention

**Format:** `{action}-{module}`

**Actions:**
- `view` - Read/list records
- `create` - Create new records
- `edit` - Update existing records
- `delete` - Delete/soft-delete records
- `export` - Export data (CSV, PDF)
- `manage` - Full CRUD access

**Modules:**
- `dashboard`
- `clients`
- `sessions`
- `invoices`
- `reports`
- `settings`
- `users`
- `roles`

**Examples:**
```
view-clients
create-clients
edit-clients
delete-clients
export-invoices
manage-users
view-dashboard
```

### 2.5.4 Complete Permission Matrix

| Permission | Super Admin | Admin | Manager | Staff |
|-----------|-------------|-------|---------|-------|
| `view-dashboard` | ✅ | ✅ | ✅ | ✅ |
| `view-clients` | ✅ | ✅ | ✅ | ✅ |
| `create-clients` | ✅ | ✅ | ✅ | ❌ |
| `edit-clients` | ✅ | ✅ | ✅ | ❌ |
| `delete-clients` | ✅ | ✅ | ❌ | ❌ |
| `view-sessions` | ✅ | ✅ | ✅ | ✅ |
| `create-sessions` | ✅ | ✅ | ✅ | ❌ |
| `edit-sessions` | ✅ | ✅ | ✅ | ❌ |
| `delete-sessions` | ✅ | ✅ | ❌ | ❌ |
| `view-invoices` | ✅ | ✅ | ✅ | ❌ |
| `create-invoices` | ✅ | ✅ | ✅ | ❌ |
| `edit-invoices` | ✅ | ✅ | ✅ | ❌ |
| `delete-invoices` | ✅ | ✅ | ❌ | ❌ |
| `export-invoices` | ✅ | ✅ | ✅ | ❌ |
| `view-reports` | ✅ | ✅ | ✅ | ❌ |
| `view-settings` | ✅ | ✅ | ❌ | ❌ |
| `edit-settings` | ✅ | ✅ | ❌ | ❌ |
| `manage-users` | ✅ | ✅ | ❌ | ❌ |
| `manage-roles` | ✅ | ❌ | ❌ | ❌ |

> **Note:** Super Admin automatically has ALL permissions via `Gate::before()` configuration, even if not explicitly assigned.

### 2.5.5 Route-Level Middleware Implementation

**CRITICAL RULE:** All permission checks MUST be implemented at the route level using middleware. Controllers should NOT contain permission checks.

**Middleware Types:**
1. `role:{role_name}` - Check if user has specific role
2. `permission:{permission_name}` - Check if user has specific permission
3. `role_or_permission:{role|permission}` - Check if user has role OR permission

**Route Protection Examples:**

```php
// routes/web.php

// Dashboard - Requires authentication only
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view-dashboard')
        ->name('dashboard');
});

// Clients Module
Route::middleware(['auth'])->prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [ClientController::class, 'index'])
        ->middleware('permission:view-clients')
        ->name('index');
    
    Route::get('/create', [ClientController::class, 'create'])
        ->middleware('permission:create-clients')
        ->name('create');
    
    Route::post('/', [ClientController::class, 'store'])
        ->middleware('permission:create-clients')
        ->name('store');
    
    Route::get('/{client}', [ClientController::class, 'show'])
        ->middleware('permission:view-clients')
        ->name('show');
    
    Route::get('/{client}/edit', [ClientController::class, 'edit'])
        ->middleware('permission:edit-clients')
        ->name('edit');
    
    Route::put('/{client}', [ClientController::class, 'update'])
        ->middleware('permission:edit-clients')
        ->name('update');
    
    Route::delete('/{client}', [ClientController::class, 'destroy'])
        ->middleware('permission:delete-clients')
        ->name('destroy');
    
    Route::get('/export/csv', [ClientController::class, 'export'])
        ->middleware('permission:export-clients')
        ->name('export');
});

// Sessions Module
Route::middleware(['auth', 'permission:view-sessions'])->prefix('sessions')->name('sessions.')->group(function () {
    Route::get('/', [SessionController::class, 'index'])->name('index');
    Route::get('/{session}', [SessionController::class, 'show'])->name('show');
    
    Route::middleware('permission:create-sessions')->group(function () {
        Route::get('/create', [SessionController::class, 'create'])->name('create');
        Route::post('/', [SessionController::class, 'store'])->name('store');
    });
    
    Route::middleware('permission:edit-sessions')->group(function () {
        Route::get('/{session}/edit', [SessionController::class, 'edit'])->name('edit');
        Route::put('/{session}', [SessionController::class, 'update'])->name('update');
    });
    
    Route::delete('/{session}', [SessionController::class, 'destroy'])
        ->middleware('permission:delete-sessions')
        ->name('destroy');
});

// Invoices Module
Route::middleware(['auth', 'permission:view-invoices'])->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
    Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print'); // Public or view-invoices
    
    Route::middleware('permission:create-invoices')->group(function () {
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
    });
    
    Route::middleware('permission:edit-invoices')->group(function () {
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
    });
    
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])
        ->middleware('permission:delete-invoices')
        ->name('destroy');
    
    Route::get('/export/csv', [InvoiceController::class, 'export'])
        ->middleware('permission:export-invoices')
        ->name('export');
});

// User Management (Admins and Super Admins only)
Route::middleware(['auth', 'permission:manage-users'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

// Role Management (Super Admins only)
Route::middleware(['auth', 'role:Super Admin'])->prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});

// Multiple permissions (OR logic)
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('permission:view-reports|export-invoices')
    ->name('reports.index');

// Role OR Permission
Route::get('/settings', [SettingController::class, 'index'])
    ->middleware('role_or_permission:Admin|view-settings')
    ->name('settings.index');
```

### 2.5.6 Super Admin Configuration

**Implementation Location:** `app/Providers/AppServiceProvider.php` (Laravel 11+)

**Configuration:**
```php
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Implicitly grant Super Admin role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
```

**Key Points:**
- Super Admin bypasses ALL permission checks
- Returns `true` to grant access, `null` to continue normal authorization flow
- Works with `@can`, `auth()->user()->can()`, and middleware checks
- Super Admin role name is case-sensitive: use exactly `'Super Admin'`

### 2.5.7 Sidebar Menu Filtering (View Layer)

**Blade Template Example:**
```blade
<!-- resources/views/layouts/partials/sidebar.blade.php -->
<aside class="sidebar">
    <nav class="sidebar-menu">
        <!-- Dashboard - Always visible to authenticated users -->
        <a href="{{ route('dashboard') }}" class="menu-item">
            <i class="icon-dashboard"></i>
            <span>Dashboard</span>
        </a>
        
        @can('view-clients')
        <a href="{{ route('clients.index') }}" class="menu-item">
            <i class="icon-users"></i>
            <span>Clients</span>
        </a>
        @endcan
        
        @can('view-sessions')
        <a href="{{ route('sessions.index') }}" class="menu-item">
            <i class="icon-calendar"></i>
            <span>Sessions</span>
        </a>
        @endcan
        
        @can('view-invoices')
        <a href="{{ route('invoices.index') }}" class="menu-item">
            <i class="icon-file-text"></i>
            <span>Invoices</span>
        </a>
        @endcan
        
        @can('view-reports')
        <a href="{{ route('reports.index') }}" class="menu-item">
            <i class="icon-bar-chart"></i>
            <span>Reports</span>
        </a>
        @endcan
        
        @can('view-settings')
        <a href="{{ route('settings.index') }}" class="menu-item">
            <i class="icon-settings"></i>
            <span>Settings</span>
        </a>
        @endcan
        
        @can('manage-users')
        <a href="{{ route('users.index') }}" class="menu-item">
            <i class="icon-user-check"></i>
            <span>User Management</span>
        </a>
        @endcan
        
        @role('Super Admin')
        <a href="{{ route('roles.index') }}" class="menu-item">
            <i class="icon-shield"></i>
            <span>Roles & Permissions</span>
        </a>
        @endrole
    </nav>
</aside>
```

### 2.5.8 Conditional UI Elements

**Blade Directives:**
```blade
<!-- Show Create button only if user has permission -->
@can('create-clients')
<button type="button" onclick="openCreateModal()" class="btn btn-primary">
    Create Client
</button>
@endcan

<!-- Show Edit/Delete actions in table -->
<td class="actions">
    @can('edit-clients')
    <a href="{{ route('clients.edit', $client) }}" class="btn-icon">
        <i class="icon-edit"></i>
    </a>
    @endcan
    
    @can('delete-clients')
    <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-icon btn-danger" onclick="return confirm('Are you sure?')">
            <i class="icon-trash"></i>
        </button>
    </form>
    @endcan
</td>

<!-- Check multiple permissions (OR logic) -->
@canany(['edit-clients', 'delete-clients'])
<div class="actions-menu">
    <!-- Action buttons -->
</div>
@endcanany

<!-- Check role -->
@role('Super Admin')
<div class="admin-tools">
    <!-- Super Admin only tools -->
</div>
@endrole

<!-- Check role or permission -->
@hasanyrole('Super Admin|Admin')
<a href="{{ route('users.index') }}">Manage Users</a>
@endhasanyrole
```

### 2.5.9 Permission Seeder

**File:** `database/seeders/RolePermissionSeeder.php`

**Seeding Strategy:**
```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create permissions
        $permissions = [
            'view-dashboard',
            'view-clients', 'create-clients', 'edit-clients', 'delete-clients', 'export-clients',
            'view-sessions', 'create-sessions', 'edit-sessions', 'delete-sessions',
            'view-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices', 'export-invoices',
            'view-reports',
            'view-settings', 'edit-settings',
            'manage-users',
            'manage-roles',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'Super Admin']);
        // Super Admin gets permissions via Gate::before, but can optionally assign all
        // $superAdmin->givePermissionTo(Permission::all());
        
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view-dashboard',
            'view-clients', 'create-clients', 'edit-clients', 'delete-clients', 'export-clients',
            'view-sessions', 'create-sessions', 'edit-sessions', 'delete-sessions',
            'view-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices', 'export-invoices',
            'view-reports',
            'view-settings', 'edit-settings',
            'manage-users',
        ]);
        
        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view-dashboard',
            'view-clients', 'create-clients', 'edit-clients',
            'view-sessions', 'create-sessions', 'edit-sessions',
            'view-invoices', 'create-invoices', 'edit-invoices', 'export-invoices',
            'view-reports',
        ]);
        
        $staff = Role::create(['name' => 'Staff']);
        $staff->givePermissionTo([
            'view-dashboard',
            'view-clients',
            'view-sessions',
        ]);
        
        // Create default Super Admin user
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);
        $superAdminUser->assignRole('Super Admin');
    }
}
```

### 2.5.10 Handling Unauthorized Access

**403 Error Page:** `resources/views/errors/403.blade.php`

**Redirect Logic:** Configure in `app/Exceptions/Handler.php`

```php
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

public function register(): void
{
    $this->renderable(function (AuthorizationException $e, $request) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.',
            ], 403);
        }
        
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access that page.');
    });
}
```

### 2.5.11 Testing RBAC

**Feature Test Example:**
```php
use App\Models\User;
use Spatie\Permission\Models\Role;

public function test_staff_cannot_create_clients()
{
    $staff = User::factory()->create();
    $staff->assignRole('Staff');
    
    $this->actingAs($staff)
        ->get(route('clients.create'))
        ->assertStatus(403);
}

public function test_super_admin_has_all_permissions()
{
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('Super Admin');
    
    $this->actingAs($superAdmin)
        ->get(route('roles.index'))
        ->assertStatus(200);
    
    $this->assertTrue($superAdmin->can('manage-roles'));
    $this->assertTrue($superAdmin->can('delete-clients')); // Any permission
}
```

---

## 3. Feature Requirements

### 3.1 Client Management

#### FR-CM-001: Client List View
**Priority:** P0 (Critical)  
**User Story:** As a business owner, I want to see all my clients in a table so I can quickly access their information.

**Acceptance Criteria:**
- Display table with columns: Name, Email, Phone, Status, Actions
- Show pagination (15 clients per page)
- Status indicator with visual badges (active=green, inactive=gray, archived=orange)
- Click on row to navigate to client detail page
- Empty state when no clients exist with "Add First Client" CTA

**Technical Requirements:**
- Use Laravel pagination: `$clients->paginate(15)`
- Blade component: `resources/views/clients/index.blade.php`
- Service: `ClientService::getAllClients($perPage, $filters)`
- Implement eager loading for performance: `Client::with('sessions', 'invoices')`

---

#### FR-CM-002: Add/Edit Client Functionality
**Priority:** P0 (Critical)  
**User Story:** As a business owner, I want to create and update client records so I can maintain accurate information.

**Acceptance Criteria:**
- Modal popup for create/edit (not separate page)
- Form fields: Name (required), Email (optional), Phone (optional), Status (dropdown)
- Client-side validation before submission
- Server-side validation with Laravel Form Requests
- Success toast notification on save
- Error messages inline with form fields
- Auto-focus on Name field when modal opens

**Technical Requirements:**
- Blade component: `resources/views/components/client-modal.blade.php`
- Controller: `ClientController@store`, `ClientController@update`
- Service: `ClientService::createClient($data)`, `ClientService::updateClient($id, $data)`
- Form Request: `app/Http/Requests/StoreClientRequest.php`
- Validation rules:
  ```
  name: required|string|max:255
  email: nullable|email|unique:clients,email,{id}
  phone: nullable|string|max:20
  status: required|in:active,inactive,archived
  ```

---

### 3.2 Session/Appointment Tracking

#### FR-ST-001: Session List View
**Priority:** P0 (Critical)  
**User Story:** As a business owner, I want to view all sessions for a client so I can track our engagement history.

**Acceptance Criteria:**
- Nested table/list under client detail page
- Display: Session Date, Duration, Notes (truncated), Actions
- Sort by session_date descending (newest first)
- Show total sessions count
- Show total billable hours

**Technical Requirements:**
- Embedded in `resources/views/clients/show.blade.php`
- Service: `SessionService::getClientSessions($clientId)`
- Calculate total hours: `$sessions->sum('duration_minutes') / 60`

---

#### FR-ST-002: Add/Edit Session
**Priority:** P0 (Critical)  
**User Story:** As a business owner, I want to log session details so I can bill accurately and maintain records.

**Acceptance Criteria:**
- Modal form with fields: Session Date (datetime picker), Duration (integer, minutes), Notes (textarea)
- Prevent future dates for session_date
- Duration must be positive integer
- Notes character limit: 1000
- Autosave draft to localStorage every 30 seconds

**Technical Requirements:**
- Blade component: `resources/views/components/session-modal.blade.php`
- Controller: `SessionController@store`, `SessionController@update`
- Service: `SessionService::createSession($clientId, $data)`
- JavaScript: Flatpickr or native datetime-local input
- Validation rules:
  ```
  session_date: required|date|before_or_equal:today
  duration_minutes: required|integer|min:1|max:1440
  notes: nullable|string|max:1000
  ```

---

### 3.3 Invoice Generation

#### FR-IG-001: Generate Invoice
**Priority:** P0 (Critical)  
**User Story:** As a business owner, I want to create invoices from sessions so I can bill clients accurately.

**Acceptance Criteria:**
- Select multiple sessions to include in invoice
- Auto-generate unique invoice number (format: INV-YYYYMMDD-XXXX)
- Calculate subtotal from session durations × hourly rate
- Apply tax rate (configurable, default 0%)
- Show payment status dropdown: Unpaid, Partial, Paid
- Save as draft or finalize invoice

**Technical Requirements:**
- Blade: `resources/views/invoices/create.blade.php`
- Controller: `InvoiceController@store`
- Service: `InvoiceService::generateInvoice($clientId, $sessionIds, $data)`
- Invoice number generation:
  ```php
  $prefix = 'INV-' . date('Ymd');
  $count = Invoice::whereDate('created_at', today())->count() + 1;
  $invoiceNumber = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
  ```
- Store session IDs as JSON array

---

#### FR-IG-002: Invoice Detail View
**Priority:** P0 (Critical)  
**User Story:** As a business owner, I want to view complete invoice details so I can review before sending to clients.

**Acceptance Criteria:**
- Display invoice header: Invoice #, Client Name, Issue Date
- Itemized session list with Date, Duration, Rate, Amount
- Subtotal, Tax, Total in bold
- Payment status badge
- Action buttons: Print, Download PDF, Mark as Paid, Delete

**Technical Requirements:**
- Blade: `resources/views/invoices/show.blade.php`
- Service: `InvoiceService::getInvoiceDetails($id)`
- Eager load: `Invoice::with('client', 'sessions')`

---

#### FR-IG-003: Print/Export Invoice
**Priority:** P1 (High)  
**User Story:** As a business owner, I want to export invoices so I can send them to clients.

**Acceptance Criteria:**
- Print-friendly CSS (hide navigation, optimize layout)
- Export to PDF using browser print dialog
- PDF filename: `Invoice-{invoice_number}.pdf`
- Include business logo/branding

**Technical Requirements:**
- Blade: `resources/views/invoices/print.blade.php`
- CSS: `@media print` styles in Tailwind config
- Route: `GET /invoices/{id}/print`

---

### 3.4 Search & Filter

#### FR-SF-001: Client Search
**Priority:** P1 (High)  
**User Story:** As a business owner, I want to search clients by name so I can quickly find specific records.

**Acceptance Criteria:**
- Search input at top of client list
- Real-time search (debounced 300ms)
- Search by name, email, phone (partial match)
- Show "No results found" message
- Keep filters applied during search

**Technical Requirements:**
- JavaScript: Debounce search input
- Controller: `ClientController@index` with search parameter
- Service: `ClientService::searchClients($query, $filters)`
- Query:
  ```php
  Client::where('name', 'LIKE', "%{$query}%")
        ->orWhere('email', 'LIKE', "%{$query}%")
        ->orWhere('phone', 'LIKE', "%{$query}%")
  ```

---

#### FR-SF-002: Filter by Status
**Priority:** P1 (High)  
**User Story:** As a business owner, I want to filter clients by status so I can focus on active relationships.

**Acceptance Criteria:**
- Dropdown filter: All, Active, Inactive, Archived
- Update URL with filter parameter (supports bookmarking)
- Show filter count badge (e.g., "Active (12)")
- Combine with search query

**Technical Requirements:**
- Route: `GET /clients?status={status}&search={query}`
- Service: Apply `where('status', $status)` when filter is set
- Maintain filter state in URL parameters

---

### 3.5 Dashboard Analytics

#### FR-DA-001: Dashboard Stats
**Priority:** P1 (High)  
**User Story:** As a business owner, I want to see key metrics at a glance so I can monitor business health.

**Acceptance Criteria:**
- Display 4 stat cards:
  1. Total Revenue (sum of paid invoices)
  2. Active Clients (status=active count)
  3. Upcoming Sessions (next 7 days)
  4. Unpaid Invoices (payment_status != paid)
- Show trend indicators (% change vs last period)
- Update in real-time when data changes
- Loading skeletons while fetching data

**Technical Requirements:**
- Blade: `resources/views/dashboard.blade.php`
- Service: `DashboardService::getStats()`
- Queries:
  ```php
  totalRevenue: Invoice::where('payment_status', 'paid')->sum('total_amount')
  activeClients: Client::where('status', 'active')->count()
  upcomingSessions: Session::whereBetween('session_date', [now(), now()->addDays(7)])->count()
  unpaidInvoices: Invoice::where('payment_status', '!=', 'paid')->count()
  ```

---

#### FR-DA-002: Recent Activity Feed
**Priority:** P2 (Medium)  
**User Story:** As a business owner, I want to see recent activities so I can track what's happening in my business.

**Acceptance Criteria:**
- Show last 10 activities (created clients, sessions, invoices)
- Display: Icon, Activity description, Timestamp (relative: "2 hours ago")
- Link to related record
- Auto-refresh every 60 seconds

**Technical Requirements:**
- Create `activities` table or query unions
- Service: `DashboardService::getRecentActivity($limit)`
- Use Carbon for relative timestamps

---

### 3.6 Data Persistence

#### FR-DP-001: LocalStorage Caching
**Priority:** P1 (High)  
**User Story:** As a user, I want my data to persist after refresh so I don't lose my work.

**Acceptance Criteria:**
- Cache dashboard stats in localStorage (TTL: 5 minutes)
- Save form drafts to localStorage
- Restore drafts when reopening forms
- Clear cache on logout
- Show toast: "Draft restored" when applicable

**Technical Requirements:**
- JavaScript module: `resources/js/storage.js`
- Methods: `saveToLocal(key, data)`, `getFromLocal(key)`, `clearLocal(key)`
- Store with timestamp for TTL validation

---

### 3.7 Export Functionality

#### FR-EX-001: CSV Export
**Priority:** P2 (Medium)  
**User Story:** As a business owner, I want to export data to CSV so I can analyze it in Excel.

**Acceptance Criteria:**
- Export buttons on Client List and Invoice List
- CSV columns match table columns
- Filename: `clients-{date}.csv`, `invoices-{date}.csv`
- Include headers row
- Handle special characters and commas in data

**Technical Requirements:**
- Controller: `ClientController@export`, `InvoiceController@export`
- Use Laravel Excel package or native fputcsv
- Response headers:
  ```php
  Content-Type: text/csv
  Content-Disposition: attachment; filename="clients-{date}.csv"
  ```

---

### 3.8 UI/UX Features

#### FR-UI-001: Dark/Light Mode Toggle
**Priority:** P1 (High)  
**User Story:** As a user, I want to switch between dark and light themes so I can work comfortably.

**Acceptance Criteria:**
- Toggle switch in navigation bar
- Persist preference in localStorage
- Smooth transition animation (200ms)
- Apply to all pages consistently
- Default to user's system preference

**Technical Requirements:**
- Tailwind dark mode config: `darkMode: 'class'`
- JavaScript: Toggle `.dark` class on `<html>`
- CSS variables for theme colors
- localStorage key: `theme` (values: 'light', 'dark', 'auto')

---

#### FR-UI-002: Responsive Design
**Priority:** P0 (Critical)  
**User Story:** As a mobile user, I want the dashboard to work on my phone so I can manage clients on the go.

**Acceptance Criteria:**
- Breakpoints: Mobile (320px+), Tablet (768px+), Desktop (1024px+)
- Mobile: Stack cards vertically, hamburger menu
- Tablet: 2-column grid, sidebar collapse
- Desktop: Full layout with sidebar
- Touch-friendly buttons (min 44px height)
- Test on iOS Safari, Chrome Android

**Technical Requirements:**
- Tailwind responsive classes: `sm:`, `md:`, `lg:`, `xl:`
- Meta viewport tag: `<meta name="viewport" content="width=device-width, initial-scale=1">`
- Mobile-first CSS approach

---

## 4. Non-Functional Requirements

### 4.1 Performance
- Page load time: < 2 seconds (LCP)
- API response time: < 500ms (p95)
- Database queries: < 100ms per query
- Support 100 concurrent users

### 4.2 Security
- CSRF protection on all forms (Laravel default)
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade escaping)
- Input validation on all endpoints
- HTTPS enforced in production

### 4.3 Browser Support
- Chrome 90+ ✅
- Firefox 88+ ✅
- Safari 14+ ✅
- Edge 90+ ✅
- IE 11 ❌ (not supported)

### 4.4 Accessibility
- WCAG 2.1 Level AA compliance
- Keyboard navigation support
- Screen reader compatible (ARIA labels)
- Color contrast ratio 4.5:1 minimum
- Focus indicators on interactive elements

---

## 5. Development Guidelines

### 5.1 Naming Conventions

#### Routes
```
GET    /clients                 → clients.index
POST   /clients                 → clients.store
GET    /clients/{id}            → clients.show
PUT    /clients/{id}            → clients.update
DELETE /clients/{id}            → clients.destroy
```

#### File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ClientController.php
│   │   ├── SessionController.php
│   │   └── InvoiceController.php
│   └── Requests/
│       ├── StoreClientRequest.php
│       └── UpdateClientRequest.php
├── Models/
│   ├── Client.php
│   ├── Session.php
│   └── Invoice.php
└── Services/
    ├── ClientService.php
    ├── SessionService.php
    ├── InvoiceService.php
    └── DashboardService.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── components/
│   │   ├── client-modal.blade.php
│   │   ├── session-modal.blade.php
│   │   └── stat-card.blade.php
│   ├── clients/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── sessions/
│   │   └── index.blade.php
│   ├── invoices/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── print.blade.php
│   └── dashboard.blade.php
└── js/
    ├── app.js
    ├── storage.js
    ├── theme.js
    └── components/
        ├── modal.js
        └── search.js
```

### 5.2 Service Layer Pattern

**Purpose:** Isolate business logic from controllers

**Example:**
```php
// app/Services/ClientService.php
class ClientService
{
    public function getAllClients($perPage = 15, $filters = [])
    {
        $query = Client::query();
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $query->where('name', 'LIKE', "%{$filters['search']}%");
        }
        
        return $query->paginate($perPage);
    }
}
```

### 5.3 API Response Format

**Success Response:**
```json
{
  "success": true,
  "data": { ... },
  "message": "Client created successfully"
}
```

**Error Response:**
```json
{
  "success": false,
  "errors": {
    "email": ["The email has already been taken."]
  },
  "message": "Validation failed"
}
```

### 5.4 JavaScript Module Pattern

**Example:**
```javascript
// resources/js/storage.js
const StorageManager = {
  save(key, data, ttl = 300000) {
    const item = {
      data: data,
      timestamp: Date.now(),
      ttl: ttl
    };
    localStorage.setItem(key, JSON.stringify(item));
  },
  
  get(key) {
    const item = JSON.parse(localStorage.getItem(key));
    if (!item) return null;
    
    if (Date.now() - item.timestamp > item.ttl) {
      localStorage.removeItem(key);
      return null;
    }
    
    return item.data;
  }
};
```

---

## 6. Database Migrations

### 6.1 Migration Sequence
1. `create_permission_tables` (Spatie package migration)
2. `create_clients_table`
3. `create_sessions_table`
4. `create_invoices_table`

### 6.2 Seeder Requirements
- Run `RolePermissionSeeder` first to create roles and permissions
- Seed 50 sample clients with varied statuses
- Generate 200 random sessions across clients
- Create 30 invoices with different payment statuses
- Create sample users with different roles (Super Admin, Admin, Manager, Staff)

---

## 7. Testing Requirements

### 7.1 Unit Tests
- Test all Service class methods
- Test Model relationships and scopes
- Target: 80% code coverage

### 7.2 Feature Tests
- Test all CRUD operations for Clients, Sessions, Invoices
- Test validation rules
- Test authorization policies
- Test CSV export functionality

### 7.3 Browser Tests (Laravel Dusk)
- Test modal open/close
- Test form submission flows
- Test search and filter interactions
- Test dark mode toggle

---

## 8. Deployment Checklist

### 8.1 Environment Variables
```env
APP_NAME="Client Management Dashboard"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clientmanagement
DB_USERNAME=root
DB_PASSWORD=

TIMEZONE=UTC
```

### 8.2 Production Optimizations
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build` for assets
- [ ] Enable OPcache in php.ini
- [ ] Set up database indexes
- [ ] Configure queue workers for background jobs

---

## 9. Future Enhancements (Out of Scope)

- Email notifications for upcoming sessions
- Recurring session templates
- Multi-currency support
- Payment gateway integration (Stripe, PayPal)
- Custom branding per invoice
- Client portal (self-service)
- Mobile application (iOS/Android)
- Advanced reporting and analytics

---

## 10. Glossary

| Term | Definition |
|------|------------|
| **Client** | A person or organization receiving services |
| **Session** | A single appointment or service interaction |
| **Invoice** | A billing document for rendered services |
| **Active Client** | Client with status = 'active' |
| **Billable Hours** | Total session duration in hours |
| **Payment Status** | Current state of invoice payment (unpaid, partial, paid) |
| **Service Layer** | Business logic abstraction between Controller and Model |

---

## 11. Acceptance Criteria Summary

**Minimum Viable Product (MVP) Must Include:**
- ✅ Complete CRUD for Clients, Sessions, Invoices
- ✅ Dashboard with 4 key metrics
- ✅ Search and filter functionality
- ✅ CSV export
- ✅ Print-friendly invoice view
- ✅ Dark/light mode toggle
- ✅ Mobile responsive (320px+)
- ✅ Data persistence via MySQL
- ✅ 3-tier architecture implementation

**Definition of Done:**
- All feature tests pass
- Code reviewed and approved
- Documentation updated
- Deployed to staging environment
- User acceptance testing completed
- Performance benchmarks met

---

## 12. AI Agent Development Instructions

### 12.1 Development Sequence
1. **Setup Phase**
   - Install Laravel 11 fresh
   - Install spatie/laravel-permission: `composer require spatie/laravel-permission`
   - Publish config: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
   - Configure Tailwind CSS with custom monochrome theme
   - Set up database connection
   - Run Spatie permission migrations: `php artisan migrate`
   - Create database migrations for clients, sessions, invoices
   - Configure super-admin in AppServiceProvider::boot() using Gate::before()

2. **Model Layer**
   - Create all Eloquent models
   - Define relationships
   - Add accessors/mutators
   - Create factories and seeders

3. **Service Layer**
   - Implement ClientService
   - Implement SessionService
   - Implement InvoiceService
   - Implement DashboardService

4. **Controller Layer**
   - Create resource controllers
   - Implement validation via Form Requests
   - Add authorization checks

5. **View Layer**
   - Build base layout (app.blade.php)
   - Create Blade components
   - Implement page templates
   - Add JavaScript interactivity

6. **Feature Implementation**
   - Implement search/filter
   - Add CSV export
   - Implement dark mode
   - Add localStorage caching

7. **Testing & Optimization**
   - Write feature tests
   - Optimize database queries
   - Audit accessibility
   - Performance testing

### 12.2 Code Standards
- Follow PSR-12 coding standards
- Use Laravel best practices
- Document complex logic with comments
- Write descriptive commit messages
- Use type hints for all method parameters

### 12.3 AI Agent Prompting Tips
- Reference specific FR (Feature Requirement) IDs when implementing features
- Follow the 3-tier architecture strictly (no business logic in controllers)
- Use service layer for all data operations
- Implement validation in Form Request classes
- Use Blade components for reusable UI elements
- Follow the file structure defined in Section 5.1

---

**Document Version Control:**
- v1.0 - Initial PRD creation (2025-12-02)
- v1.1 - Added monochrome admin layout specifications and RBAC implementation (2025-12-02)

**Approved By:** [Pending Stakeholder Review]  
**Next Review Date:** [After MVP Completion]
