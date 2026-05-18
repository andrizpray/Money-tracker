<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Services\TransactionParserService;
use Telegram\Bot\Commands\Command;

class LaporCommand extends Command
{
    protected string $name = 'lapor';
    protected string $description = 'Catat transaksi baru';

    public function __construct(
        private TransactionParserService $parser
    ) {}

    public function handle(): void
    {
        $telegramUser = $this->getUpdate()->getMessage()->getFrom();
        $text = trim($this->getUpdate()->getMessage()->getText());
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();

        // Remove command prefix
        $text = preg_replace('/^\/\w+\s*/', '', $text);

        $user = User::where('telegram_id', $telegramUser->getId())->first();

        if (!$user) {
            $this->replyWithMessage([
                'text' => "⚠️ Kamu belum terdaftar. Ketik /start dulu ya!",
            ]);
            return;
        }

        // If no text after command, show help
        if (empty($text)) {
            $help = "📝 <b>Format Laporan:</b>\n\n";
            $help .= "Langsung ketik aja:\n";
            $help .= "├ <code>Lapor pengeluaran 23000 beli makan</code>\n";
            $help .= "├ <code>Keluar 15000 kopi</code>\n";
            $help .= "├ <code>Masuk 500000 gaji januari</code>\n";
            $help .= "└ <code>Pengeluaran 50000 bensin</code>\n\n";
            $help .= "💡 <b>Keyword:</b>\n";
            $help .= "📉 Pengeluaran: keluar, beli, bayar, pengeluaran\n";
            $help .= "📈 Pemasukan: masuk, gaji, terima, pemasukan\n\n";
            $help .= "💰 <b>Format angka:</b> 23000, 23k, 23rb, 1.5jt";

            $this->replyWithMessage([
                'text' => $help,
                'parse_mode' => 'HTML',
            ]);
            return;
        }

        // Try to parse the message
        $parsed = $this->parser->parse($text);

        if (!$parsed) {
            $this->replyWithMessage([
                'text' => "⚠️ Format tidak dikenali.\n\nContoh: <code>Lapor pengeluaran 23000 beli makan</code>\n\nKetik /lapor untuk bantuan.",
                'parse_mode' => 'HTML',
            ]);
            return;
        }

        // Create transaction
        $transaction = $user->transactions()->create([
            'category_id' => $parsed['category_id'],
            'type' => $parsed['type'],
            'amount' => $parsed['amount'],
            'description' => $parsed['description'],
            'transaction_date' => now(),
        ]);

        $typeEmoji = $transaction->type === 'income' ? '📈' : '📉';
        $typeLabel = $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran';

        $response = "✅ <b>Transaksi berhasil dicatat!</b>\n\n";
        $response .= "🕒 Waktu: " . $transaction->created_at->format('d/m/Y H:i') . "\n";
        $response .= "{$typeEmoji} Tipe: {$typeLabel}\n";
        $response .= "💰 Jumlah: Rp" . number_format($transaction->amount, 0, ',', '.') . "\n";
        $response .= "📝 Deskripsi: {$transaction->description}\n";

        if ($transaction->category) {
            $response .= "📂 Kategori: {$transaction->category->icon} {$transaction->category->name}\n";
        }

        // Show today's summary
        $todayIncome = $user->transactions()->today()->income()->sum('amount');
        $todayExpense = $user->transactions()->today()->expense()->sum('amount');

        $response .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $response .= "📊 <b>Ringkasan Hari Ini:</b>\n";
        $response .= "📈 Masuk: Rp" . number_format($todayIncome, 0, ',', '.') . "\n";
        $response .= "📉 Keluar: Rp" . number_format($todayExpense, 0, ',', '.') . "\n";
        $response .= "💵 Saldo: Rp" . number_format($todayIncome - $todayExpense, 0, ',', '.');

        $this->replyWithMessage([
            'text' => $response,
            'parse_mode' => 'HTML',
        ]);
    }
}
