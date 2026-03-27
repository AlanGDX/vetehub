<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;

class JwtService
{
    public function issueToken(User $user, bool $remember = false): string
    {
        $issuedAt = Carbon::now()->timestamp;
        $ttl = $remember
            ? (int) config('jwt.remember_ttl_minutes', 43200)
            : (int) config('jwt.ttl_minutes', 1440);

        $payload = [
            'iss' => (string) config('jwt.issuer', config('app.url')),
            'sub' => $user->id,
            'iat' => $issuedAt,
            'exp' => Carbon::now()->addMinutes($ttl)->timestamp,
            'ver' => (int) ($user->token_version ?? 0),
            'jti' => (string) Str::uuid(),
        ];

        return $this->encode($payload);
    }

    public function userFromRequest(Request $request): ?User
    {
        $token = $request->cookie($this->cookieName()) ?: $request->bearerToken();

        if (! $token) {
            return null;
        }

        return $this->userFromToken($token);
    }

    public function userFromToken(string $token): ?User
    {
        $payload = $this->decode($token);

        if (! $payload || ! isset($payload['sub'], $payload['exp'], $payload['ver'])) {
            return null;
        }

        if ((int) $payload['exp'] < Carbon::now()->timestamp) {
            return null;
        }

        $user = User::find($payload['sub']);

        if (! $user) {
            return null;
        }

        if ((int) ($user->token_version ?? 0) !== (int) $payload['ver']) {
            return null;
        }

        return $user;
    }

    public function makeAuthCookie(User $user, bool $remember = false): Cookie
    {
        $minutes = $remember
            ? (int) config('jwt.remember_ttl_minutes', 43200)
            : (int) config('jwt.ttl_minutes', 1440);

        return cookie(
            $this->cookieName(),
            $this->issueToken($user, $remember),
            $minutes,
            '/',
            null,
            (bool) config('jwt.cookie_secure', false),
            true,
            false,
            (string) config('jwt.cookie_samesite', 'lax')
        );
    }

    public function expireAuthCookie(): Cookie
    {
        return Cookie::create(
            $this->cookieName(),
            null,
            Carbon::now()->subMinutes(5),
            '/',
            null,
            (bool) config('jwt.cookie_secure', false),
            true,
            false,
            (string) config('jwt.cookie_samesite', 'lax')
        );
    }

    private function encode(array $payload): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR)),
            $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR)),
        ];

        $signature = hash_hmac('sha256', implode('.', $segments), $this->secret(), true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private function decode(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $this->secret(), true)
        );

        if (! hash_equals($expectedSignature, $encodedSignature)) {
            return null;
        }

        $payloadJson = $this->base64UrlDecode($encodedPayload);

        if ($payloadJson === false) {
            return null;
        }

        $payload = json_decode($payloadJson, true);

        return is_array($payload) ? $payload : null;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string|false
    {
        $remainder = strlen($data) % 4;

        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    private function secret(): string
    {
        $secret = (string) config('jwt.secret', '');

        if (str_starts_with($secret, 'base64:')) {
            $decoded = base64_decode(substr($secret, 7), true);

            if ($decoded !== false) {
                return $decoded;
            }
        }

        return $secret;
    }

    private function cookieName(): string
    {
        return (string) config('jwt.cookie_name', 'vetehub_token');
    }
}
