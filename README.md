# 📚 SI Perpustakaan (Library Management System)

A comprehensive web-based library management system built with modern web technologies. This application streamlines library operations including book management, student records, lending transactions, and overdue fine calculations.

## ✨ Features

### 📖 Book Management
- Complete CRUD operations for books (Create, Read, Update, Delete with Soft Delete)
- Book cover image upload and management
- Multiple book copies (eksemplar) tracking
- Search and filter by title, author, and publication year
- Sort by various columns (code, title, author, year)
- Popular books tracking based on borrowing frequency
- Available books filtering

### 👥 Student Management
- Student registration and profile management
- Student borrowing history tracking
- NIS (Student ID) based identification

### 📋 Lending System
- Book lending transaction management
- Real-time availability checking
- Borrowing period tracking (7-day limit)
- Multiple book copies management per title

### 💰 Return & Fine Management
- Automated return processing
- Late return detection (7-day grace period)
- Automatic fine calculation (Rp 1,000 per day)
- Fine payment tracking
- Comprehensive lending reports with statistics

### 👤 User & Authentication
- Secure user authentication with Laravel Breeze
- Multi-user management
- Profile management
- Role-based access control

### 📊 Dashboard & Analytics
- Overview of library statistics
- Popular books carousel
- Available books showcase
- Quick access to all major functions

## 🛠️ Tech Stack

### Backend
- **PHP 8.2+** - Modern PHP with latest features
- **Laravel 12.x** - Latest Laravel framework
- **MySQL** - Relational database management
- **Laravel Eloquent ORM** - Database abstraction layer
- **Laravel Breeze** - Authentication scaffolding

### Frontend
- **Blade Templates** - Laravel's templating engine
- **Tailwind CSS 3.x** - Utility-first CSS framework
- **Alpine.js 3.x** - Lightweight JavaScript framework
- **Vite 6.x** - Modern frontend build tool
- **Axios** - HTTP client for API requests

### Development Tools
- **Composer** - PHP dependency management
- **NPM** - JavaScript package management
- **Laravel Pint** - PHP code style fixer
- **Laravel Sail** - Docker development environment
- **Laravel Pail** - Real-time log viewer
- **PHPUnit** - Testing framework
- **Faker** - Test data generation
- **Concurrently** - Run multiple dev servers

## Deployment Checklist

Before pushing to production:

1. Run production build:
   ```bash
   npm run build
   ```

2. Commit built assets:
   ```bash
   git add public/build
   git commit -m "Build assets for production v1.x.x"
   ```

3. Push to repository:
   ```bash
   git push origin main
   ```

4. On server, pull changes:
   ```bash
   git pull origin main
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL >= 8.0
- NPM or Yarn

## 🚀 Installation

### 1. Clone the repository
```bash
git clone https://github.com/hafidzmulia-its/SIPerpustakaan.git
cd SIPerpustakaan
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Install JavaScript dependencies
```bash
npm install
```

### 4. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=si_perpustakaan
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run migrations and seeders
```bash
php artisan migrate --seed
```

This will create all necessary tables and populate them with sample data:
- Users
- Books (Buku)
- Book copies (Eksemplar Buku)
- Students (Siswa)
- Lending transactions (Peminjaman)

### 7. Storage link
```bash
php artisan storage:link
```

### 8. Start development servers

**Option 1: Using composer script (recommended)**
```bash
composer run dev
```
This will start all services concurrently:
- Laravel development server (port 8000)
- Queue worker
- Log viewer (Pail)
- Vite dev server

**Option 2: Manual start**
```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Vite
npm run dev
```

## 🌐 Access the Application

Once the servers are running, access the application at:
- **Application**: http://localhost:8000
- **Vite Dev Server**: http://localhost:5173

## 📁 Project Structure

```
SIPerpustakaan/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── BukuController.php           # Books management
│   │       ├── EksemplarBukuController.php  # Book copies
│   │       ├── SiswaController.php          # Students
│   │       ├── PeminjamanController.php     # Lending
│   │       ├── PengembalianController.php   # Returns
│   │       ├── HomeController.php           # Homepage
│   │       └── UserController.php           # User management
│   └── Models/
│       ├── Buku.php                         # Book model
│       ├── EksemplarBuku.php                # Book copy model
│       ├── Siswa.php                        # Student model
│       ├── Peminjaman.php                   # Lending model
│       └── User.php                         # User model
├── database/
│   ├── migrations/                          # Database migrations
│   └── seeders/                             # Sample data seeders
├── resources/
│   ├── views/                               # Blade templates
│   ├── css/                                 # Stylesheets
│   └── js/                                  # JavaScript files
└── routes/
    └── web.php                              # Application routes
```

## 🗄️ Database Schema

### Main Tables
- **bukus** - Book catalog (kode_buku, judul, pengarang, tahun_terbit, cover)
- **eksemplar_bukus** - Book copies (nomor_eksemplar, kode_buku)
- **siswas** - Students (nis, nama, kelas)
- **peminjamen** - Lending transactions (nis, nomor_eksemplar, tanggal_peminjaman, tanggal_pengembalian, jumlah_denda)
- **users** - System users (authentication)

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📝 Key Features Details

### Fine Calculation System
- **Borrowing Period**: 7 days
- **Late Fee**: Rp 1,000 per day
- Automatic calculation on return
- Tracks payment status

### Book Availability Tracking
- Real-time availability check
- Tracks each book copy independently
- Shows available vs total copies
- Popular books based on lending frequency

### Soft Delete Implementation
- Books can be soft deleted
- Maintains data integrity
- Can be restored if needed

## 🔒 Security Features

- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection through Blade templates
- Secure authentication with Laravel Breeze

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Developer

**Hafidz Mulia**
- GitHub: [@hafidzmulia-its](https://github.com/hafidzmulia-its)

## 📧 Support

For support, please open an issue in the GitHub repository or contact the developer.

---

Built with ❤️ using Laravel 12 and modern web technologies
