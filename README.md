# 💰 Money Tracker — Laravel 13 + Telegram Bot

Aplikasi money tracking pribadi dengan integrasi Telegram Bot dan dashboard web.

## ✨ Fitur

- **Telegram Bot**: Catat transaksi via chat (natural language)
- **Dashboard Web**: Visualisasi grafik harian, mingguan, bulanan
- **CRUD Transaksi**: Tambah, edit, hapus transaksi
- **Kategori**: Kelola kategori pemasukan & pengeluaran
- **Laporan**: Export PDF & CSV
- **Dark Theme**: UI modern dengan indigo accent
- **Responsive**: Mobile-friendly

## 🚀 Quick Start

### 1. Clone & Install

```bash
git clone <repo-url> money-tracker
cd money-tracker
composer install
npm install
```

### 2. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_URL=http://localhost:8000
DB_DATABASE=/path/to/database.sqlite
TELEGRAM_BOT_TOKEN=your_bot_token_here
```

### 3. Database

```bash
touch database/database.sqlite
php artisan migrate --seed
```

### 4. Run

```bash
php artisan serve
npm run dev
```

### 5. Setup Telegram Bot

1. Buat bot di @BotFather → dapatkan token
2. Set token di `.env` → `TELEGRAM_BOT_TOKEN`
3. Set webhook:
```bash
php artisan telegram:webhook --set
```
Atau via dashboard: **Pengaturan** → masukkan bot token → klik "Set Webhook"

## 📱 Telegram Bot Commands

| Command | Deskripsi |
|---------|-----------|
| `/start` | Mulai & registrasi |
| `/lapor` | Catat transaksi |
| `/riwayat` | 10 transaksi terakhir |
| `/laporan` | Laporan hari ini |
| `/bulanini` | Laporan bulan ini |
| `/kategori` | Daftar kategori |
| `/help` | Bantuan |

### Natural Language
Langsung ketik tanpa command:
```
keluar 23000 beli makan
masuk 500000 gaji
pengeluaran 15000 kopi
```

## 📊 Screenshots

### Dashboard
- Summary cards (saldo, pemasukan, pengeluaran)
- Grafik tren 7 hari
- Pie chart kategori
- Transaksi terakhir

### Laporan
- Filter harian/mingguan/bulanan
- Grafik batang
- Export PDF & CSV

## 🛠️ Tech Stack

- **Backend**: Laravel 11
- **Database**: SQLite (default) / MySQL
- **Frontend**: Blade + Tailwind CSS + Alpine.js + Chart.js
- **Telegram**: irazasyed/telegram-bot-sdk
- **PDF**: barryvdh/laravel-dompdf

## 📁 Struktur Folder

```
app/
├── Http/Controllers/    # Web & API controllers
├── Models/              # Eloquent models
├── Services/            # Business logic
├── Telegram/            # Bot commands & conversations
├── Policies/            # Authorization
database/
├── migrations/          # DB schema
├── seeders/             # Default data
resources/
├── views/               # Blade templates
routes/
├── web.php              # Web routes
├── api.php              # API routes
```

## 🔧 Konfigurasi

### Budget Bulanan
Set budget di **Pengaturan** → akan muncul notifikasi di bot saat mendekati limit.

### Multi-User
Setiap user punya data terpisah. Registrasi via web atau Telegram.

## 📝 License

MIT
