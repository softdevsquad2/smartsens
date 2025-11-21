<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WaGatewayService
{
    public static function send($nomor, $pesan)
    {
        $url = config('app.wa_gateway_url', env('WA_GATEWAY_URL'));

        try {
            $response = Http::get($url, [
                'nomor' => $nomor,
                'pesan' => $pesan
            ]);

            return $response->body();
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
