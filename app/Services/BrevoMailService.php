<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Illuminate\Support\Facades\Log;

class BrevoMailService
{
    protected $apiKey;
    protected $client;
    protected $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
        $this->client = HttpClient::create();
    }

    /**
     * Enviar correo a través de la API de Brevo
     * 
     * @param array $data
     * @return array
     */
    public function sendEmail(array $data)
    {
        if (empty($this->apiKey)) {
            throw new \Exception('BREVO_API_KEY no está configurada en el archivo .env');
        }

        try {
            // Preparar payload
            $payload = [
                'sender' => [
                    'name' => $data['from_name'] ?? env('BREVO_FROM_NAME', 'VeteHub - Sistema de Citas'),
                    'email' => $data['from_email'] ?? env('BREVO_FROM_EMAIL', env('MAIL_FROM_ADDRESS', 'noreply@example.com')),
                ],
                'to' => $data['to'],
                'subject' => $data['subject'],
                'htmlContent' => $data['html_content'],
                'textContent' => $data['text_content'] ?? strip_tags($data['html_content']),
            ];

            // Agregar replyTo si se especifica
            if (!empty($data['reply_to'])) {
                $payload['replyTo'] = $data['reply_to'];
            }

            $response = $this->client->request('POST', $this->apiUrl, [
                'headers' => [
                    'api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->toArray();

            if ($statusCode === 201) {
                Log::info('Correo enviado exitosamente vía Brevo API', [
                    'message_id' => $content['messageId'] ?? null,
                    'to' => $data['to'],
                ]);

                return [
                    'success' => true,
                    'message_id' => $content['messageId'] ?? null,
                    'data' => $content,
                ];
            }

            return [
                'success' => false,
                'error' => 'Error desconocido',
                'status_code' => $statusCode,
            ];

        } catch (TransportExceptionInterface $e) {
            Log::error('Error de transporte al enviar correo vía Brevo API', [
                'error' => $e->getMessage(),
                'to' => $data['to'] ?? null,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('Error al enviar correo vía Brevo API', [
                'error' => $e->getMessage(),
                'to' => $data['to'] ?? null,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Enviar notificación de recordatorio de cita
     * 
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlContent
     * @param string|null $textContent
     * @param array|null $replyTo Array con 'email' y 'name' para responder
     * @return array
     */
    public function sendAppointmentReminder(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlContent,
        ?string $textContent = null,
        ?array $replyTo = null
    ) {
        $emailData = [
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName,
                ]
            ],
            'subject' => $subject,
            'html_content' => $htmlContent,
            'text_content' => $textContent,
        ];

        // Agregar replyTo si se especifica
        if ($replyTo !== null) {
            $emailData['reply_to'] = $replyTo;
        }

        return $this->sendEmail($emailData);
    }

    /**
     * Verificar si la API Key está configurada
     * 
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->apiKey) && strlen($this->apiKey) > 10;
    }

    /**
     * Probar la conexión con la API de Brevo
     * 
     * @return array
     */
    public function testConnection()
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'error' => 'API Key no configurada',
            ];
        }

        try {
            // Llamada a la API para obtener información de la cuenta
            $response = $this->client->request('GET', 'https://api.brevo.com/v3/account', [
                'headers' => [
                    'api-key' => $this->apiKey,
                    'accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();
                return [
                    'success' => true,
                    'account' => $data['email'] ?? 'Cuenta verificada',
                ];
            }

            return [
                'success' => false,
                'error' => 'Código de estado: ' . $response->getStatusCode(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
