<?php

return [

    'telegram' => [
        'bots' => [
            'money_tracker_bot' => [
                'token' => env('TELEGRAM_BOT_TOKEN'),
                'certificate_path' => env('TELEGRAM_CERTIFICATE_PATH', ''),
                'webhook_url' => env('TELEGRAM_WEBHOOK_URL', ''),
            ],
        ],
        'default' => 'money_tracker_bot',
        'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),
        'http_client_handler' => null,
        'base_bot_url' => 'https://api.telegram.org/bot',
        'resolve_command_dependencies' => true,
        'register_trusted_commands' => false,
        'commands' => [
            App\Telegram\Commands\StartCommand::class,
            App\Telegram\Commands\HelpCommand::class,
            App\Telegram\Commands\LaporCommand::class,
            App\Telegram\Commands\RiwayatCommand::class,
            App\Telegram\Commands\LaporanCommand::class,
            App\Telegram\Commands\BulanIniCommand::class,
            App\Telegram\Commands\KategoriCommand::class,
            App\Telegram\Commands\BatalkanCommand::class,
        ],
    ],

];
