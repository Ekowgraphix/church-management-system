
# Church Management System - Auth Module

## Pages Included
1. Login Page (Role-based login)
2. Church Member Sign-Up Page
3. Member Dashboard

## Setup
1. Copy files into your Laravel project.
2. Add routes in `routes/web.php`:
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signupStore'])->name('signup.store');
Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard')->middleware('auth');
```
3. Ensure you have TailwindCSS configured.
4. Update `.env` mail settings for verification emails.

## Prompt for Windsurf
Use this prompt:
```
Combine the following three pages into a working Laravel + Blade mini module:
1. Login Page (with role selection)
2. Sign-Up Page (for Church Members)
3. Church Member Dashboard (after login)

- Use TailwindCSS for styling.
- Ensure that routes and controllers match: /login, /signup, /dashboard.
- Implement session-based role selection and redirect logic.
- Use example AuthController logic provided.
- Add flash messages for success and errors.
- Ensure the dashboard only loads for authenticated, verified members.
- Optimize for mobile and desktop views.
```
