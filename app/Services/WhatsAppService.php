<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendAttendanceNotification($phone, $nama, $tipe, $jam, $tanggal, $photoPath = null)
    {
        try {
            $message = "*Notifikasi Absensi Siswa*\n\n";
            $message .= "Nama: *{$nama}*\n";
            $message .= "Jenis: *" . ucfirst($tipe) . "*\n";
            $message .= "Jam: *{$jam}*\n";
            $message .= "Tanggal: *{$tanggal}*\n";
            $message .= "Status: *Berhasil direkam di sistem.*";

            // Kirim ke BOT kamu
            try {
                $response = Http::timeout(10)->post(env('WHATSAPP_BOT_URL'), [
                    'token'   => env('WHATSAPP_BOT_TOKEN'),
                    'phone'   => $phone,
                    'message' => $message,
                    'image'   => $photoPath ? url($photoPath) : null
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => 'Notification sent',
                    ];
                }

                return [
                    'success' => false,
                    'message' => $response->body(),
                ];
            } catch (\Exception $httpException) {
                return [
                    'success' => false,
                    'message' => 'HTTP Error: ' . $httpException->getMessage(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
