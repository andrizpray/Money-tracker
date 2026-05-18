<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Mulai menggunakan Money Tracker Bot';

    public function handle(): void
    {
        $telegramUser = $this->getUpdate()->getMessage()->getFrom();
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();

        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if (!$user) {
            $user = User::create([
                'name' => $telegramUser->getFirstName() . ($telegramUser->getLastName() ? ' ' . $telegramUser->getLastName() : ''),
                'email' => 'tg_' . $telegramUser->getId() . '@money.id',
                'password' => bcrypt(str()->random(32)),
                'telegram_id' => $telegramUser->getId(),
                'telegram_username' => $telegramUser->getUsername(),
                'currency' => 'IDR',
            ]);

            // Create default categories for new user
            $defaultCategories = [
                ['name' => 'Makanan & Minuman', 'type' => 'expense', 'icon' => '🍔', 'color' => '#ef4444'],
                ['name' => 'Transportasi', 'type' => 'expense', 'icon' => '🚗', 'color' => '#f59e0b'],
                ['name' => 'Belanja', 'type' => 'expense', 'icon' => '🛒', 'color' => '#ec4899'],
                ['name' => 'Tagihan & Utilitas', 'type' => 'expense', 'icon' => '💡', 'color' => '#8b5cf6'],
                ['name' => 'Hiburan', 'type' => 'expense', 'icon' => '🎮', 'color' => '#06b6d4'],
                ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => '💊', 'color' => '#10b981'],
                ['name' => 'Pendidikan', 'type' => 'expense', 'icon' => '📚', 'color' => '#6366f1'],
                ['name' => 'Lainnya', 'type' => 'expense', 'icon' => '📦', 'color' => '#64748b'],
                ['name' => 'Gaji', 'type' => 'income', 'icon' => '💰', 'color' => '#22c55e'],
                ['name' => 'Freelance', 'type' => 'income', 'icon' => '💻', 'color' => '#14b8a6'],
                ['name' => 'Investasi', 'type' => 'income', 'icon' => '📈', 'color' => '#3b82f6'],
                ['name' => 'Lainnya', 'type' => 'income', 'icon' => '💵', 'color' => '#a855f7'],
            ];

            foreach ($defaultCategories as $cat) {
                $user->categories()->create(array_merge($cat, ['is_default' => true]));
            }
        }

        $welcome = "👋 <b>Selamat datang di Money Tracker Bot!</b>\n\n";
        $welcome .= "Hai, {$user->name}! 👋\n\n";
        $welcome .= "Bot ini membantu kamu mencatat pemasukan & pengeluaran dengan mudah.\n\n";
        $welcome .= "━━━━━━━━━━━━━━━━━━━━\n";
        $welcome .= "📌 <b>Cara Pakai:</b>\n\n";
        $welcome .= "1️⃣ Ketik pesan langsung:\n";
        $welcome .= "   <code>Lapor pengeluaran 23000 beli makan</code>\n";
        $welcome .= "   <code>Masuk 500000 gaji</code>\n\n";
        $welcome .= "2️⃣ Atau gunakan command:\n";
        $welcome .= "   /lapor — Catat transaksi\n";
        $welcome .= "   /riwayat — Lihat transaksi terakhir\n";
        $welcome .= "   /laporan — Laporan hari ini\n";
        $welcome .= "   /bulanini — Laporan bulan ini\n";
        $welcome .= "   /kategori — Kelola kategori\n";
        $welcome .= "   /help — Bantuan lengkap\n";
        $welcome .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $welcome .= "💡 <b>Tip:</b> Langsung ketik aja, gak perlu command!\n";
        $welcome .= "Contoh: <code>keluar 15000 kopi</code>";

        $this->replyWithMessage([
            'text' => $welcome,
            'parse_mode' => 'HTML',
        ]);
    }
}
