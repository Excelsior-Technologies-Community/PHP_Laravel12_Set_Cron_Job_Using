# 🚀 Laravel 12 Category CRUD + Cron Job System  
### **Made with ❤️ by Hardik Panchal**

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-ff2d20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/Status-Active-success?style=for-the-badge" />
  <img src="https://img.shields.io/badge/Build-Passing-brightgreen?style=for-the-badge" />
</p>

---

# 📌 Overview

A fully-featured **Category CRUD + Cron Job** system built using **Laravel 12**, including:

- Category creation, editing, listing, deletion  
- Cron job executed every minute  
- Custom Artisan Command  
- Laravel Scheduler  
- Blade Views (Bootstrap UI)  
- Professional folder structure  
- Fully documented setup guide  

---

# ⭐ Features

- 📝 Full CRUD (Create, Read, Update, Delete)  
- ⏱ Cron Job (runs every minute)  
- 🛠 Custom Artisan Command  
- 📦 Clean MVC Structure  
- 🎨 Bootstrap Blade Templates  
- 🔧 Full Laravel Scheduler Setup  
- 🧹 Log Writing via Cron  
- 🗂 Pagination  

---

# 📁 Folder Structure

```
app/
├── Console/Commands/
│   └── CategoryCron.php
├── Http/Controllers/
│   ├── Controller.php
│   └── CategoryController.php
├── Models/
│   └── Category.php

resources/
└── views/categories/
       ├── index.blade.php
       ├── create.blade.php

routes/
└── web.php

database/
└── migrations/
```

---

# 📚 Table of Contents

- [Overview](#-overview)  
- [Features](#-features)  
- [Folder Structure](#-folder-structure)  
- [Installation](#-installation)  
- [Environment Setup](#-environment-setup)  
- [Migration](#-migration)  
- [Routes](#-routes)  
- [Controller](#-controller)  
- [Model](#-model)  
- [Blade Views](#-blade-views)  
- [Cron Job Setup](#-cron-job-setup)  
- [Run Application](#-run-application)  
- [Screenshots](#-screenshots)  
- [Credits](#-credits)

---

# ⚙ Installation

```bash
composer create-project laravel/laravel CronJobApp "12.*"
```

---

# 🔧 Environment Setup

Update `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=cron
DB_USERNAME=root
DB_PASSWORD=
```

---

# 🗄 Migration

Create migration:

```bash
php artisan make:migration create_categories_table --create=categories
```

Run migration:

```bash
php artisan migrate
```

---

# 🔌 Routes

```php
use App\Http\Controllers\CategoryController;

Route::resource('categories', CategoryController::class);
```

---

# 🎮 Controller (Important Methods)

### Display Categories

```php
public function index() {
    $categories = Category::latest()->paginate(10);
    return view('categories.index', compact('categories'));
}
```

### Store Category

```php
public function store(Request $request) {
    $request->validate(['name' => 'required']);
    Category::create($request->all());
    return redirect()->route('categories.index');
}
```

---

# 🧬 Model

```php
class Category extends Model
{
    protected $fillable = ['name'];
}
```

---

# 🖼 Blade Views

### 📌 index.blade.php  
- Shows list  
- Edit/Delete buttons  
- Pagination  

### 📌 create.blade.php  
- Add new category form  

---

# ⏱ Cron Job Setup

## 1️⃣ Create Artisan Command

`app/Console/Commands/CategoryCron.php`

```php
protected $signature = 'category:cron';

public function handle()
{
    \Log::info("Category Cron Executed at " . now());
}
```

---

## 2️⃣ Register Inside Kernel

`app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('category:cron')->everyMinute();
}
```

---

## 3️⃣ Linux Crontab Setup

Run:

```bash
crontab -e
```

Add:

```
* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1
```

---

## 4️⃣ Windows Scheduler Setup

- Open **Task Scheduler**  
- Create Task  
- Program: `php.exe`  
- Arguments: `artisan schedule:run`  
- Trigger: Every 1 minute  

---

# ▶ Run Application

```bash
php artisan serve
```

Visit:

```
http://localhost:8000/categories
```

---

# 📸 Windows Task Scheduler Screenshots

<img width="887" height="573" alt="image" src="https://github.com/user-attachments/assets/a7b1d124-01a4-41da-aefb-c45a308f6a3e" />


<img width="997" height="644" alt="image" src="https://github.com/user-attachments/assets/11276ba5-8e5a-490e-9b1b-02c46c041a42" />


<img width="839" height="647" alt="image" src="https://github.com/user-attachments/assets/8a40d775-0d3b-4f4b-9568-45d6f2bcbd09" />


<img width="851" height="505" alt="image" src="https://github.com/user-attachments/assets/883c1a23-0966-4859-8f48-333c6f7c17d0" />


<img width="865" height="573" alt="image" src="https://github.com/user-attachments/assets/f1a33b48-7fe8-444e-8625-7c258e7eada7" />


<img width="802" height="612" alt="image" src="https://github.com/user-attachments/assets/36175894-96e6-406f-8a59-09186b8a27f9" />


<img width="966" height="571" alt="image" src="https://github.com/user-attachments/assets/3a592d11-afcc-4d36-bb67-cf4ff3473bbd" />


<img width="975" height="741" alt="image" src="https://github.com/user-attachments/assets/9a95baec-eb1f-405c-b942-aa6f238b2f92" />


<img width="938" height="669" alt="image" src="https://github.com/user-attachments/assets/9d32b9f8-9300-4167-a482-8fbc95235d52" />











