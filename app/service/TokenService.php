<?php
namespace app\service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

class TokenService
{
    protected $secret;
    protected $accessTtl;
    protected $refreshTtl;

    public function __construct()
    {
        $this->secret     = env('JWT_SECRET', 'openclaw_ai_admin_jwt_secret_key_2026');
        $this->accessTtl  = env('JWT_ACCESS_TTL', 7200);
        $this->refreshTtl = env('JWT_REFRESH_TTL', 604800);
    }

    /**
     * 生成访问令牌
     */
    public function generateAccessToken(int $userId): string
    {
        $now = time();
        $payload = [
            'iss' => 'openclaw_admin',
            'aud' => 'openclaw_admin',
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $this->accessTtl,
            'type' => 'access',
            'uid'  => $userId,
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    /**
     * 生成刷新令牌
     */
    public function generateRefreshToken(int $userId): string
    {
        $now = time();
        $payload = [
            'iss' => 'openclaw_admin',
            'aud' => 'openclaw_admin',
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $this->refreshTtl,
            'type' => 'refresh',
            'uid'  => $userId,
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    /**
     * 验证并解码令牌
     */
    public function verifyToken(string $token, string $type = 'access'): ?object
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            if ($decoded->type !== $type) {
                return null;
            }
            return $decoded;
        } catch (ExpiredException $e) {
            return null;
        } catch (BeforeValidException $e) {
            return null;
        } catch (SignatureInvalidException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
