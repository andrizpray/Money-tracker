<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
use App\Services\ReportService;
use App\Services\TransactionParserService;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramBotController extends Controller
{
    private Api $telegram;

    public function __construct(
        private TransactionParserService $parser,
        private ReportService $report
    ) {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    /**
     * Handle incoming webhook from Telegram
     */
    public function webhook(Request $request)
    {
        $update = $request->all();
        
        if (!isset($update['message'])) {
            return response()->json(['status' => 'ok']);
        }

        $message = $update['message'];
        $text = $message['text'] ?? '';
        $chatId = $message['chat']['id'];
        $userId = $message['from']['id'] ?? null;

        // Handle commands
        if (str_starts_with($text, '/')) {
            return $this->handleCommand($text, $chatId, $userId, $message['from'] ?? []);
        }

        // Handle plain text - natural language parsing
        return $this->handlePlainText($text, $chatId, $userId);
    }

    /**
     * Handle commands
     */
    private function handleCommand(string $text, int $chatId, ?int $userId, array $from): \Illuminate\Http\JsonResponse
    {
        $command = trim(explode(' ', $text)[0], '/');

        return match($command) {
            'start' => $this->cmdStart($chatId, $from),
            'help' => $this->cmdHelp($chatId),
            'lapor' => $this->cmdLapor($chatId, $userId),
            'riwayat' => $this->cmdRiwayat($chatId, $userId),
            'laporan' => $this->cmdLaporan($chatId, $userId),
            'bulanini' => $this->cmdBulanIni($chatId, $userId),
            'kategori' => $this->cmdKategori($chatId, $userId),
            default => $this->sendMessage($chatId, "❓ Command tidak dikenali.\n\nKetik /help untuk bantuan."),
        };
    }

    /**
     * /start command
     */
    private function cmdStart(int $chatId, array $from): \Illuminate\Http\JsonResponse
    {
        $user = User::where('telegram_id', $from['id'] ?? 0)->first();

        if (!$user) {
            $user = User::create([
                'name' => ($from['first_name'] ?? 'User') . ($from['last_name'] ?? '' ? ' ' . $from['last_name'] : ''),
                'email' => 'tg_' . ($from['id'] ?? 0) . '@money.id',
                'password' => bcrypt(str()->random(32)),
                'telegram_id' => $from['id'] ?? 0,
                'telegram_username' => $from['username'] ?? '',
                'currency' => 'IDR',
            ]);

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
        $welcome .= "Bot ini membantu kamu mencatat pemasukan &amp; pengeluaran.\n\n";
        $welcome .= "━━━━━━━━━━━━━━━━━━━━\n";
        $welcome .= "📌 <b>Cara Pakai:</b>\n\n";
        $welcome .= "1️⃣ Ketik pesan langsung:\n";
        $welcome .= "   <code>keluar 23000 makan siang</code>\n";
        $welcome .= "   <code>masuk 500000 gaji</code>\n\n";
        $welcome .= "2️⃣ Atau gunakan command:\n";
        $welcome .= "   /lapor — Catat transaksi\n";
        $welcome .= "   /riwayat — Lihat transaksi terakhir\n";
        $welcome .= "   /laporan — Laporan hari ini\n";
        $welcome .= "   /bulanini — Laporan bulan ini\n";
        $welcome .= "   /help — Bantuan lengkap\n";
        $welcome .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $welcome .= "💡 <b>Tip:</b> Langsung ketik aja!\n";
        $welcome .= "Contoh: <code>keluar 15000 kopi</code>";

        $this->sendMessage($chatId, $welcome, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * /help command
     */
    private function cmdHelp(int $chatId): \Illuminate\Http\JsonResponse
    {
        $help = "📖 <b>Bantuan</b>\n\n";
        $help .= "/start — Mulai bot\n";
        $help .= "/help — Bantuan ini\n";
        $help .= "/lapor — Catat transaksi\n";
        $help .= "/riwayat — 10 transaksi terakhir\n";
        $help .= "/laporan — Ringkasan hari ini\n";
        $help .= "/bulanini — Laporan bulan ini\n";
        $help .= "/kategori — Daftar kategori\n\n";
        $help .= "<b>Cara cepat:</b>\n";
        $help .= "<code>keluar 23000 makan siang</code>";

        $this->sendMessage($chatId, $help, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * /lapor command
     */
    private function cmdLapor(int $chatId, ?int $userId): \Illuminate\Http\JsonResponse
    {
        if (!$userId) {
            $this->sendMessage($chatId, "⚠️ Gagal mendapatkan ID Telegram.");
            return response()->json(['status' => 'ok']);
        }

        $user = User::where('telegram_id', $userId)->first();
        if (!$user) {
            $this->sendMessage($chatId, "⚠️ Kamu belum terdaftar. Ketik /start dulu ya!");
            return response()->json(['status' => 'ok']);
        }

        $help = "📝 <b>Cara Lapor:</b>\n\n";
        $help .= "Ketik langsung tanpa command:\n";
        $help .= "<code>keluar 23000 makan siang</code>\n";
        $help .= "<code>masuk 500000 gaji</code>\n\n";
        $help .= "<b>Format:</b>\n";
        $help .= "keluar/masuk + jumlah + deskripsi\n\n";
        $help .= "<b>Kategori:</b>\n";
        $cats = $user->categories()->where('type', 'expense')->get();
        foreach ($cats as $cat) {
            $help .= "{$cat->icon} {$cat->name}\n";
        }

        $this->sendMessage($chatId, $help, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * /riwayat command
     */
    private function cmdRiwayat(int $chatId, ?int $userId): \Illuminate\Http\JsonResponse
    {
        if (!$userId) return response()->json(['status' => 'ok']);

        $user = User::where('telegram_id', $userId)->first();
        if (!$user) {
            $this->sendMessage($chatId, "⚠️ Kamu belum terdaftar. Ketik /start dulu!");
            return response()->json(['status' => 'ok']);
        }

        $txs = $user->transactions()->with('category')->latest()->take(10)->get();

        if ($txs->isEmpty()) {
            $this->sendMessage($chatId, "📭 Belum ada transaksi. Yuk catat yang pertama!");
            return response()->json(['status' => 'ok']);
        }

        $msg = "📋 <b>10 Transaksi Terakhir</b>\n\n";
        foreach ($txs as $tx) {
            $emoji = $tx->type === 'income' ? '📈' : '📉';
            $cat = $tx->category ? " {$tx->category->icon}" : '';
            $msg .= "{$emoji} Rp" . number_format($tx->amount, 0, ',', '.') . "{$cat}\n";
            $msg .= "   📝 {$tx->description}\n";
            $msg .= "   🕒 {$tx->created_at->format('d/m H:i')}\n\n";
        }

        $this->sendMessage($chatId, $msg, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * /laporan command
     */
    private function cmdLaporan(int $chatId, ?int $userId): \Illuminate\Http\JsonResponse
    {
        if (!$userId) return response()->json(['status' => 'ok']);

        $user = User::where('telegram_id', $userId)->first();
        if (!$user) {
            $this->sendMessage($chatId, "⚠️ Kamu belum terdaftar. Ketik /start dulu!");
            return response()->json(['status' => 'ok']);
        }

        $today = now()->startOfDay();
        $income = $user->transactions()->whereDate('created_at', $today)->where('type', 'income')->sum('amount');
        $expense = $user->transactions()->whereDate('created_at', $today)->where('type', 'expense')->sum('amount');

        $msg = "📊 <b>Ringkasan Hari Ini</b>\n";
        $msg .= now()->format('d M Y') . "\n\n";
        $msg .= "📈 Masuk: Rp" . number_format($income, 0, ',', '.') . "\n";
        $msg .= "📉 Keluar: Rp" . number_format($expense, 0, ',', '.') . "\n";
        $msg .= "━━━━━━━━━━━━━━━\n";
        $msg .= "💵 Saldo: Rp" . number_format($income - $expense, 0, ',', '.') . "\n";

        $this->sendMessage($chatId, $msg, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * /bulanini command
     */
    private function cmdBulanIni(int $chatId, ?int $userId): \Illuminate\Http\JsonResponse
    {
        if (!$userId) return response()->json(['status' => 'ok']);

        $user = User::where('telegram_id', $userId)->first();
        if (!$user) {
            $this->sendMessage($chatId, "⚠️ Kamu belum terdaftar. Ketik /start dulu!");
            return response()->json(['status' => 'ok']);
        }

        $start = now()->startOfMonth();
        $income = $user->transactions()->whereDate('created_at', '>=', $start)->where('type', 'income')->sum('amount');
        $expense = $user->transactions()->whereDate('created_at', '>=', $start)->where('type', 'expense')->sum('amount');
        $count = $user->transactions()->whereDate('created_at', '>=', $start)->count();

        $msg = "📊 <b>Laporan Bulan " . now()->format('F Y') . "</b>\n\n";
        $msg .= "📈 Total Masuk: Rp" . number_format($income, 0, ',', '.') . "\n";
        $msg .= "📉 Total Keluar: Rp" . number_format($expense, 0, ',', '.') . "\n";
        $msg .= "━━━━━━━━━━━━━━━\n";
        $msg .= "💵 Saldo: Rp" . number_format($income - $expense, 0, ',', '.') . "\n\n";
        $msg .= "📝 Total: {$count} transaksi";

        $this->sendMessage($chatId, $msg, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * /kategori command
     */
    private function cmdKategori(int $chatId, ?int $userId): \Illuminate\Http\JsonResponse
    {
        if (!$userId) return response()->json(['status' => 'ok']);

        $user = User::where('telegram_id', $userId)->first();
        if (!$user) {
            $this->sendMessage($chatId, "⚠️ Kamu belum terdaftar. Ketik /start dulu!");
            return response()->json(['status' => 'ok']);
        }

        $exp = $user->categories()->where('type', 'expense')->get();
        $inc = $user->categories()->where('type', 'income')->get();

        $msg = "📂 <b>Kategori</b>\n\n📉 <b>Pengeluaran:</b>\n";
        foreach ($exp as $c) { $msg .= "{$c->icon} {$c->name}\n"; }
        $msg .= "\n📈 <b>Pemasukan:</b>\n";
        foreach ($inc as $c) { $msg .= "{$c->icon} {$c->name}\n"; }

        $this->sendMessage($chatId, $msg, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle plain text (natural language parsing)
     */
    private function handlePlainText(string $text, int $chatId, ?int $userId): \Illuminate\Http\JsonResponse
    {
        if (!$userId) return response()->json(['status' => 'ok']);

        $user = User::where('telegram_id', $userId)->first();
        if (!$user) {
            $this->sendMessage($chatId, "⚠️ Kamu belum terdaftar. Ketik /start dulu!");
            return response()->json(['status' => 'ok']);
        }

        $parsed = $this->parser->parse($text);

        if (!$parsed) {
            $this->sendMessage($chatId, "⚠️ Format tidak dikenali.\n\nContoh: <code>keluar 23000 beli makan</code>\n\nKetik /help.");
            return response()->json(['status' => 'ok']);
        }

        $tx = $user->transactions()->create([
            'category_id' => $parsed['category_id'],
            'type' => $parsed['type'],
            'amount' => $parsed['amount'],
            'description' => $parsed['description'],
            'transaction_date' => now(),
        ]);

        $emoji = $tx->type === 'income' ? '📈' : '📉';
        $typeLabel = $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran';

        $resp = "✅ <b>Transaksi dicatat!</b>\n\n";
        $resp .= "🕒 {$tx->created_at->format('d/m/Y H:i')}\n";
        $resp .= "{$emoji} {$typeLabel}\n";
        $resp .= "💰 Rp" . number_format($tx->amount, 0, ',', '.') . "\n";
        $resp .= "📝 {$tx->description}\n";
        if ($tx->category) $resp .= "📂 {$tx->category->icon} {$tx->category->name}\n";

        $todayInc = $user->transactions()->whereDate('created_at', now()->startOfDay())->where('type', 'income')->sum('amount');
        $todayExp = $user->transactions()->whereDate('created_at', now()->startOfDay())->where('type', 'expense')->sum('amount');

        $resp .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $resp .= "📊 <b>Hari Ini:</b>\n";
        $resp .= "📈 Rp" . number_format($todayInc, 0, ',', '.') . "\n";
        $resp .= "📉 Rp" . number_format($todayExp, 0, ',', '.') . "\n";
        $resp .= "💵 Rp" . number_format($todayInc - $todayExp, 0, ',', '.');

        $this->sendMessage($chatId, $resp, 'HTML');
        return response()->json(['status' => 'ok']);
    }

    /**
     * Send message helper
     */
    private function sendMessage(int $chatId, string $text, string $parseMode = 'HTML')
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode,
        ]);
    }
}
