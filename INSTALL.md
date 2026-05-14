# RT 9 Sambiroto - Installation Guide

## 🚀 Quick Start (5 Menit)

### Step 1: Download Files
```bash
git clone https://github.com/eouxt-art/rt9sambiroto.git
cd rt9sambiroto
```

### Step 2: Create Folders
```bash
# Buat folder upload (jika belum ada)
mkdir uploads
mkdir uploads/artikel
mkdir uploads/gallery
mkdir uploads/temp
chmod 755 uploads
chmod 755 application/logs
```

### Step 3: Import Database
1. Buka **phpMyAdmin** di `http://localhost/phpmyadmin`
2. Login dengan credentials Anda
3. Buat database baru atau gunakan yang ada
4. Pilih **Import** tab
5. Upload file `database/rt9sambiroto.sql`
6. Klik **Go**

### Step 4: Configure Database
Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost',           // Host MySQL
    'username' => 'root',                // Username MySQL Anda
    'password' => '',                    // Password MySQL Anda
    'database' => 'rt9sambiroto',        // Nama database
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

### Step 5: Configure Base URL
Edit file `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/rt9sambiroto/';
// Atau untuk hosting:
// $config['base_url'] = 'http://yourdomain.com/';
```

### Step 6: Run di Browser
```
http://localhost/rt9sambiroto
```

### Step 7: Login
```
Username: admin
Password: admin123
```

---

## 🏢 Instalasi ke Hosting/Server

### Via cPanel File Manager

1. **Create Database**
   - Masuk cPanel → MySQL Databases
   - Buat database baru: `rt9sambiroto`
   - Buat user & password
   - Add user ke database dengan semua privileges

2. **Import Database**
   - Buka phpMyAdmin di cPanel
   - Select database yang baru dibuat
   - Import file `database/rt9sambiroto.sql`

3. **Upload Files**
   - Login FTP/cPanel File Manager
   - Upload semua folder & file ke public_html atau subdirectory
   - Pastikan struktur folder tetap sama

4. **Set Permissions**
   - Upload folder: chmod 755
   - application/logs: chmod 755
   - index.php: chmod 644

5. **Edit Konfigurasi**
   - Edit `application/config/database.php`
   - Update hostname, username, password, database
   - Edit `application/config/config.php`
   - Update base_url ke domain Anda

6. **Test**
   - Buka http://yourdomain.com di browser
   - Login dengan admin/admin123

### Via FTP Client (Filezilla)

1. Download Filezilla dari https://filezilla-project.org/
2. Buat connection baru dengan FTP credentials dari hosting
3. Drag-drop semua folder dari local ke server
4. Edit file konfigurasi via Filezilla
5. Ubah permission folder upload & logs ke 755
6. Import database via cPanel phpMyAdmin
7. Test di browser

---

## 🔧 Konfigurasi Hosting Populer

### Hosting Niagahoster
```php
// application/config/database.php
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'niagaXXXX_user';     // Sesuaikan
$db['default']['password'] = 'passwordXXXX';       // Sesuaikan
$db['default']['database'] = 'niagaXXXX_db';       // Sesuaikan
```

### Hosting DomaiNesia
```php
// Sama seperti Niagahoster, ikuti format dari email hosting
```

### Hosting Bersama
```php
// Gunakan hostname dari email hosting Anda
// Biasanya di kolom: Database Host, Database User, Database Password
```

---

## 📝 Ganti Password Admin

Setelah login pertama kali:

1. Buka **Admin Dashboard**
2. Klik menu **Data User**
3. Klik icon **Edit** pada user admin
4. Isi field **Password Baru**
5. Klik **Simpan**
6. Logout dan login dengan password baru

---

## ⚠️ Troubleshooting

### Error: "Database Connection Error"

**Solusi:**
```php
// Cek apakah database sudah diimport
// Cek username & password MySQL
// Pastikan server MySQL berjalan
// Cek hostname (biasanya localhost)
```

### Error: "Page Not Found" / 404

**Solusi:**
```apache
# Pastikan .htaccess sudah ada di root
# Enable mod_rewrite di server
# Atau ubah config untuk tidak menggunakan .htaccess
```

Edit `application/config/config.php`:
```php
$config['index_page'] = 'index.php';  // Ubah jika diperlukan
$config['uri_protocol'] = 'REQUEST_URI';  // Ganti ke AUTO jika tidak jalan
```

### Error: "Permission Denied" pada Upload

**Solusi:**
```bash
# Set permission folder
chmod 755 uploads
chmod 755 application/logs
```

Di cPanel: Klik File Manager → Select folder → Change Permissions → 755

### Error: "Call to undefined function"

**Solusi:**
- Cek apakah library sudah di-autoload
- Edit `application/config/autoload.php`
- Tambahkan library yang missing

### Halaman Admin Blank / Database Kosong

**Solusi:**
- Cek apakah database sudah diimport dengan benar
- Cek error di `application/logs/` folder
- Buka Chrome DevTools (F12) → Console untuk error JS

---

## 🔐 Security Tips

1. **Ubah Password Admin**
   - Segera ubah password default
   - Gunakan password yang kuat

2. **Disable Error Display**
   Edit `application/config/config.php`:
   ```php
   $config['log_threshold'] = 1;  // Production
   define('ENVIRONMENT', 'production');
   ```

3. **Update Base URL**
   - Jangan gunakan `http://`, gunakan `https://`
   - Pastikan base_url sesuai domain

4. **Backup Regular**
   - Backup database secara berkala
   - Backup file uploads

5. **File Permissions**
   - application/logs: 755
   - uploads: 755
   - application/config: 644

---

## 📞 Support

Jika ada masalah:
1. Cek error di `application/logs/`
2. Cek file `database/rt9sambiroto.sql` sudah diimport
3. Cek konfigurasi database sudah benar
4. Cek folder permissions sudah 755
5. Contact hosting support jika masih error

---

**Good Luck! 🎉**
