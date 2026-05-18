<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class TransactionParserService
{
    /**
     * Parse natural language message into transaction data
     *
     * Examples:
     * "Lapor pengeluaran 23000 beli makan"
     * "Keluar 15000 kopi"
     * "Masuk 500000 gaji"
     * "Pengeluaran 50000 bensin"
     */
    public function parse(string $text): ?array
    {
        $text = strtolower(trim($text));

        // Determine type
        $type = $this->detectType($text);
        if (!$type) return null;

        // Extract amount
        $amount = $this->extractAmount($text);
        if (!$amount) return null;

        // Extract description (remaining text after removing keywords and amount)
        $description = $this->extractDescription($text, $type, $amount);

        // Find matching category
        $category = $this->guessCategory($description, $type);

        return [
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'category_id' => $category?->id,
            'category_name' => $category?->name,
        ];
    }

    private function detectType(string $text): ?string
    {
        $expenseKeywords = ['pengeluaran', 'keluar', 'beli', 'bayar', 'spending', 'expense', 'out', 'lapor'];
        $incomeKeywords = ['pemasukan', 'masuk', 'gaji', 'terima', 'income', 'in', 'dapat'];

        foreach ($expenseKeywords as $kw) {
            if (str_contains($text, $kw)) return 'expense';
        }
        foreach ($incomeKeywords as $kw) {
            if (str_contains($text, $kw)) return 'income';
        }

        return null;
    }

    private function extractAmount(string $text): ?float
    {
        // Match patterns: 23000, 23.000, 23,000, 23k, 23rb, 23ribu, 23 jt, 23juta
        $patterns = [
            '/(\d+)\s*(?:juta|jt)\b/i' => fn($m) => $m[1] * 1000000,
            '/(\d+)\s*(?:ribu|rb|k)\b/i' => fn($m) => $m[1] * 1000,
            '/(\d{1,3}(?:[.,]\d{3})+(?:[.,]\d+)?)/' => fn($m) => (float) str_replace(['.', ','], ['', '.'], $m[1]),
            '/(\d{4,})/' => fn($m) => (float) $m[1],
        ];

        foreach ($patterns as $pattern => $callback) {
            if (preg_match($pattern, $text, $matches)) {
                return $callback($matches);
            }
        }

        return null;
    }

    private function extractDescription(string $text, string $type, float $amount): string
    {
        $desc = $text;

        // Remove type keywords
        $keywords = ['lapor', 'pengeluaran', 'pemasukan', 'keluar', 'masuk', 'beli', 'bayar', 'terima', 'dapat', 'spending', 'expense', 'income', 'out', 'in'];
        foreach ($keywords as $kw) {
            $desc = preg_replace('/\b' . $kw . '\b/i', '', $desc);
        }

        // Remove amount patterns
        $desc = preg_replace('/\d+\s*(?:juta|jt|ribu|rb|k)\b/i', '', $desc);
        $desc = preg_replace('/\d{1,3}(?:[.,]\d{3})+(?:[.,]\d+)?/', '', $desc);
        $desc = preg_replace('/\d{4,}/', '', $desc);

        $desc = trim(preg_replace('/\s+/', ' ', $desc));

        return $desc ?: 'Tanpa keterangan';
    }

    private function guessCategory(string $description, string $type): ?Category
    {
        $keywords = [
            'Makanan & Minuman' => ['makan', 'minum', 'kopi', 'nasi', 'warteg', 'resto', 'cafe', 'jajan', 'snack', 'burger', 'pizza'],
            'Transportasi' => ['bensin', 'transport', 'ojol', 'grab', 'gojek', 'taksi', 'bus', 'kereta', 'parkir', 'tol'],
            'Belanja' => ['belanja', 'shopping', 'tokopedia', 'shopee', 'lazada', 'baju', 'sepatu'],
            'Tagihan & Utilitas' => ['listrik', 'air', 'pulsa', 'internet', 'wifi', 'tagihan', 'pln', 'token'],
            'Hiburan' => ['nonton', 'film', 'game', 'hiburan', 'konser', 'netflix', 'spotify'],
            'Kesehatan' => ['dokter', 'obat', 'kesehatan', 'rumah sakit', 'apotek', 'vitamin'],
            'Pendidikan' => ['buku', 'kursus', 'sekolah', 'kuliah', 'pendidikan', 'kelas'],
            'Gaji' => ['gaji', 'salary', 'payroll'],
            'Freelance' => ['freelance', 'project', 'kontrak'],
            'Investasi' => ['investasi', 'saham', 'crypto', 'reksadana'],
        ];

        foreach ($keywords as $categoryName => $words) {
            foreach ($words as $word) {
                if (str_contains($description, $word)) {
                    return Category::where('name', $categoryName)
                        ->where('type', $type)
                        ->first();
                }
            }
        }

        // Fallback to "Lainnya"
        return Category::where('name', 'Lainnya')
            ->where('type', $type)
            ->first();
    }
}
