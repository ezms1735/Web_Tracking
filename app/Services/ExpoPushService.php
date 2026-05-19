<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExpoPushService
{
    public static function kirim(string $expoToken, string $judul, string $pesan, array $data = []): void
    {
        // Validasi format token
        if (!str_starts_with($expoToken, 'ExponentPushToken[')) {
            Log::warning('Expo push token tidak valid: ' . $expoToken);
            return;
        }

        try {
            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', [
                'to'    => $expoToken,
                'title' => $judul,
                'body'  => $pesan,
                'data'  => $data,
                'sound' => 'default',
                'channelId' => 'default',
                'priyority' => 'high',         
            ]);

            Log::info('Expo push response: ' . $response->body());
        } catch (\Throwable $e) {
            Log::error('Expo push error: ' . $e->getMessage());
        }
    }
}