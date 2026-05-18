# 💰 Money Tracker – Aplikasi Pencatat Keuangan Pribadi

Aplikasi web pencatat pemasukan & pengeluaran pribadi berbasis **Laravel 13**, terintegrasi dengan **Telegram Bot** untuk input cepat, dan dilengkapi **dashboard visualisasi** (grafik harian, mingguan, bulanan) serta laporan ekspor PDF/CSV.

> ✅ **Live**: [https://money.eatrade-journal.site](https://money.eatrade-journal.site)  
> 🤖 **Bot**: [@DrzMT_bot](https://t.me/DrzMT_bot)  
> 🌐 **Domain**: `money.eatrade-journal.site` (SSL via Let's Encrypt + Cloudflare DNS)

---

## 📋 Fitur Utama

### Telegram Bot – Input Cepat
Kirim pesan bebas ke bot, tidak perlu perintah rumit:
```
Lapor pengeluaran 23000 beli makan
Masuk 500000 gaji januari
Keluar 15000 kopi
```
Bot otomatis parse dan catat transaksi.

**Command yang tersedia:**
- `/start` – Registrasi & welcome
- `/lapor` – Input transaksi interaktif (guided)
- `/riwayat` – 10 transaksi terakhir
- `/laporan` – Ringkasan hari ini
- `/bulanini` – Laporan bulan berjalan
- `/kategori` – Kelola kategori
- `/help` – Bantuan

### Dashboard Web
- **Ringkasan cepat** – Saldo, pemasukan & pengeluaran hari ini
- **Grafik mingguan** – Tren 7 hari terakhir (bar chart)
- **Pie chart kategori** – Distribusi pengeluaran per kategori
- **Laporan lengkap** – Filter harian/mingguan/bulanan
- **Ekspor** – PDF & CSV

### Tech Stack
| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 13 |
| Database | SQLite (`database/database.sqlite`) |
| Frontend | Blade + Tailwind CSS + Alpine.js |
| Charting | Chart.js (CDN) |
| Telegram | `irazasyed/telegram-bot-sdk` |
| PDF Export | `barryvdh/laravel-dompdf` |
| Web Server | Nginx + PHP-FPM 8.3 |

---

## 🚀 Setup Development

```bash
# Clone & masuk
git clone https://github.com/andrizpray/Money-tracker.git
cd Money-tracker

# Install PHP deps
composer install

# Install JS deps
npm install

# Setup env
cp .env.example .env
php artisan key:generate

# Edit .env – isi TELEGRAM_BOT_TOKEN & TELEGRAM_WEBHOOK_URL
nano .env

# Migrasi & seeder
php artisan migrate:fresh --seed

# Jalankan
php artisan serve
npm run dev
```

**Login default:** `user@money.id` / `password`

### Telegram Webhook (Dev)
Gunakan **ngrok** untuk expose webhook:
```bash
ngrok http 8000
# Copy HTTPS URL → setWebhook via BotFather
```

---

## 🌐 Setup Production (Ubuntu + Nginx)

```bash
# 1. Clone ke /var/www
sudo git clone https://github.com/andrizpray/Money-tracker.git /var/www/money-tracker
sudo chown -R $USER:$USER /var/www/money-tracker

# 2. Install deps
cd /var/www/money-tracker
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 3. Setup env & migrasi
cp .env.example .env
php artisan key:generate
php artisan migrate --force

# 4. Izin folder
sudo chown -R www-data:www-data storage bootstrap/cache

# 5. Nginx config – see section below

# 6. Set webhook
curl -F "url=https://money.eatrade-journal.site/api/telegram/webhook" \
     https://api.telegram.org/bot<TOKEN>/setWebhook
```

### Nginx Config
```nginx
server {
    listen 80;
    server_name money.eatrade-journal.site;
    location /.well-known/acme-challenge/ { root /var/www/certbot; }
    location / { return 301 https://$host$request_uri; }
}

server {
    listen 443 ssl http2;
    server_name money.eatrade-journal.site;

    ssl_certificate /etc/letsencrypt/live/money.eatrade-journal.site/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/money.eatrade-journal.site/privkey.pem;

    root /var/www/money-tracker/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\. { deny all; }
}
```

---

## 🔐 Environment Variables

| Variabel | Keterangan |
|----------|------------|
| `APP_KEY` | `php artisan key:generate` |
| `TELEGRAM_BOT_TOKEN` | Dari @BotFather |
| `TELEGRAM_WEBHOOK_URL` | `https://domain.com/api/telegram/webhook` |
| `TELEGRAM_BOT_USERNAME` | Username bot (tanpa @) |
| `APP_URL` | URL aplikasi (mis. `https://money.eatrade-journal.site`) |

---

## 📁 Struktur Utama

```
app/
├── Http/Controllers/
│   ├── TelegramBotController.php   ← Logika bot + natural language parsing
│   ├── ReportController.php        ← Laporan & ekspor PDF/CSV
│   ├── DashboardController.php     ← Dashboard & grafik
│   └── TransactionController.php  ← CRUD transaksi
├── Services/
│   ├── ReportService.php          ← Perhitungan laporan
│   └── TransactionParserService.php ← Parser pesan Telegram
└── Models/
    ├── User.php, Transaction.php, Category.php
routes/
├── api.php     ← Webhook Telegram (tanpa session)
└── web.php     ← Dashboard (dengan auth)
```

---

## 📄 Lisensi

MIT License – bebas digunakan untuk proyek pribadi maupun komersial.

**Made with ❤️ by Andriz**  
*(Deployed & maintained with ❤️ by Hermes Agent)*
