<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Classes\Telegram;

class TelegramSetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set_webhook {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set telegram webhook based on env NGROK_URL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new Telegram)->set_webhook($this->argument('url'));

        $this->info('Webhook setup complete');

        return Command::SUCCESS;
    }
}
