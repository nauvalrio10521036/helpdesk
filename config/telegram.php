<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | This is the token for your Telegram bot. You can get this from BotFather
    | on Telegram by creating a new bot.
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Telegram Chat ID
    |--------------------------------------------------------------------------
    |
    | This is the chat ID where the bot will send messages.
    |
    */

    'chat_id' => env('TELEGRAM_CHAT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Suricata Log Path
    |--------------------------------------------------------------------------
    |
    | Path to the Suricata log file for monitoring alerts.
    |
    */

    'suricata_log_path' => env('SURICATA_LOG_PATH', 'Z:\\eve.json'),

];
