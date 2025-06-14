# 📝 Laravel Todo List API

API sederhana untuk Todo List yang dibangun dengan Laravel 12, mengimplementasikan Clean Code dan Repository Pattern.

---

## 🚀 Fitur

- ✅ CRUD Todo (Create, Read, Update, Delete)
- ✅ Validasi due date tidak boleh di masa lalu
- ✅ Export ke file Excel + Summary
- ✅ Chart data summary dalam format JSON:
    - Status summary
    - Priority summary
    - Assignee summary
- ✅ Filtering lengkap pada endpoint Export dan Chart
- ✅ Struktur clean menggunakan Service + Repository Pattern
- ✅ Unit test dengan PHPUnit
- ✅ Postman collection JSON

---

## 📦 Struktur Project

```aiignore
app/
├── Http/
│ └── Controllers/
│ └── API/
│   └── TodoController.php
├── Models/
│ └── Todo.php
├── Repositories/
│ └── Interfaces/
│   │── TodoRepositoryInterface.php
│   └── TodoRepository.php
├── Services/
│ └── TodoService.php
```


---

## ⚙️ Instalasi

```bash
git clone https://github.com/iamelse/be-test-laravel-tn.git
cd be-test-laravel-tn
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed untuk migrasi dan seed data dummy
```

## 🧪 Menjalankan Test

```bash
php artisan test --filter=TodoApiTest
```
