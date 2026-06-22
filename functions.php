

<?php
require_once('constant.php');

function sendWhatsAppOTP($recipient)
{
   
    $accessToken   = ACCESS_TOKEN;
    $url           = WP_URL;

    // ── Validate inputs ──────────────────────────────────────────────────────
    if (empty($recipient) || !preg_match('/^\d{10,15}$/', $recipient)) {
        return [
            'success'   => false,
            'http_code' => 0,
            'data'      => [],
            'error'     => 'Invalid recipient phone number.',
        ];
    }

    if (empty(OTP_CODE)) {
        return [
            'success'   => false,
            'http_code' => 0,
            'data'      => [],
            'error'     => 'OTP code cannot be empty.',
        ];
    }

    // ── Build payload ────────────────────────────────────────────────────────
    $payload = [
        "messaging_product" => "whatsapp",
        "to"                => $recipient,
        "type"              => "template",
        "template"          => [
            "name"       => "otp_verification",
            "language"   => ["code" => "en"],
            "components" => [
                [
                    "type"       => "body",
                    "parameters" => [
                        ["type" => "text", "text" => OTP_CODE]
                    ]
                ],
                [
                    "type"       => "button",
                    "sub_type"   => "url",
                    "index"      => "0",
                    "parameters" => [
                        ["type" => "text", "text" => BUTTON_VALUE]
                    ]
                ]
            ]
        ]
    ];

    // ── cURL request ─────────────────────────────────────────────────────────
    $ch = curl_init($url);

    if ($ch === false) {
        return [
            'success'   => false,
            'http_code' => 0,
            'data'      => [],
            'error'     => 'Failed to initialize cURL.',
        ];
    }

    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // ── cURL failed ──────────────────────────────────────────────────────────
    if ($response === false) {
        error_log("sendWhatsAppOTP cURL Error: $curlError | Recipient: $recipient");
        return [
            'success'   => false,
            'http_code' => $httpCode,
            'data'      => [],
            'error'     => $curlError,
        ];
    }

    // ── Parse response ───────────────────────────────────────────────────────
    $data = json_decode($response, true);
    //echo "<pre>"; print_r($data); die;

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("sendWhatsAppOTP JSON Error: " . json_last_error_msg() . " | Raw: $response");
        return [
            'success'   => false,
            'http_code' => $httpCode,
            'data'      => [],
            'error'     => 'Invalid JSON response from API.',
        ];
    }

    // ── HTTP error ───────────────────────────────────────────────────────────
    if ($httpCode < 200 || $httpCode >= 300) {
        $apiError = $data['error']['message'] ?? 'Unknown API error';
        error_log("sendWhatsAppOTP HTTP $httpCode: $apiError | Recipient: $recipient");
        return [
            'success'   => false,
            'http_code' => $httpCode,
            'data'      => $data,
            'error'     => $apiError,
        ];
    }

    // ── Success ──────────────────────────────────────────────────────────────
    return [
        'success'   => true,
        'http_code' => $httpCode,
        'data'      => $data,
        'error'     => '',
        'otp'      => OTP_CODE
    ];
}