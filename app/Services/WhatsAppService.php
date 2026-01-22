<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendTemplate(string $toE164, string $templateName, string $lang, array $bodyParams = []): array
    {
        $token = config('whatsapp.token');
        $phoneNumberId = config('whatsapp.phone_number_id');
        $version = config('whatsapp.api_version');

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $components = [];
        if (!empty($bodyParams)) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(fn($v) => ['type' => 'text', 'text' => (string)$v], $bodyParams),
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $toE164,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => $lang],
                'components' => $components,
            ],
        ];

        $res = Http::withToken($token)
            ->acceptJson()
            ->post($url, $payload);

        if (!$res->successful()) {
            // Devuelve error detallado para debug
            return [
                'ok' => false,
                'status' => $res->status(),
                'error' => $res->json(),
            ];
        }

        return [
            'ok' => true,
            'data' => $res->json(),
        ];
    }

    // Convierte "4151-616161" => "5024151616161" (E.164 sin +)
    public function normalizeGtPhoneToE164(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone ?? '');
        if (!$digits) return null;

        // Si ya viene con 502...
        if (str_starts_with($digits, '502') && strlen($digits) >= 11) {
            return $digits;
        }

        // Guatemala: normalmente 8 dígitos
        if (strlen($digits) === 8) {
            return '502' . $digits;
        }

        // Si viene con +502 ya lo limpiamos arriba; si viene raro, lo mandamos tal cual
        // (puedes endurecer validación si quieres)
        return $digits;
    }
}
