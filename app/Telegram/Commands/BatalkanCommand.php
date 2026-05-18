<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class BatalkanCommand extends Command
{
    protected string $name = 'batalkan';
    protected string $description = 'Batalkan operasi saat ini';

    public function handle(): void
    {
        $this->replyWithMessage([
            'text' => "❌ Operasi dibatalkan.\n\nKetik /help untuk melihat daftar perintah.",
        ]);
    }
}
