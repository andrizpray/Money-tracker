<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'Bantuan lengkap';

    public function handle(): void
    {
        $text = "📖 <b>Bantuan Money Tracker Bot</b>\n\n";

        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "🚀 <b>Cara Cepat:</b>\n";
        $text .= "Langsung ketik aja tanpa command:\n";
        $text .= "  <code>keluar 23000 beli makan</code>\n";
        $text .= "  <code>masuk 500000 gaji</code>\n";
        $text .= "  <code>beli 15000 kopi</code>\n\n";

        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "📋 <b>Daftar Command:</b>\n\n";
        $text .= "/start — Mulai bot & registrasi\n";
        $text .= "/lapor — Catat transaksi baru\n";
        $text .= "/riwayat — 10 transaksi terakhir\n";
        $text .= "/laporan — Laporan hari ini\n";
        $text .= "/bulanini — Laporan bulan ini\n";
        $text .= "/kategori — Daftar kategori\n";
        $text .= "/help — Bantuan ini\n\n";

        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "💰 <b>Format Angka:</b>\n";
        $text .= "  23000 → Rp23.000\n";
        $text .= "  23k / 23rb → Rp23.000\n";
        $text .= "  1.5jt → Rp1.500.000\n\n";

        $text .= "━━━━━━━━━━━━━━━━━━━━\n";
        $text .= "📉 <b>Keyword Pengeluaran:</b>\n";
        $text .= "  keluar, beli, bayar, pengeluaran\n\n";
        $text .= "📈 <b>Keyword Pemasukan:</b>\n";
        $text .= "  masuk, gaji, terima, pemasukan\n";

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
