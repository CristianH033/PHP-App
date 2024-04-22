<?php

namespace NewsApp\Core\Http;

class Cookie
{
    private string $name;
    private string $value;
    private ?int $expires;
    private string $path;
    private ?string $domain;
    private bool $secure;
    private bool $httpOnly;
    private string $sameSite;

    public function __construct(
        string $name,
        mixed $value,
        ?int $expires = null,
        string $path = '/',
        ?string $domain = null,
        bool $secure = false,
        bool $httpOnly = true,
        string $sameSite = 'Lax'
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->sameSite = $sameSite;
    }

    public static function make(string $name, mixed $value, ?int $expires = null, string $path = '/', ?string $domain = null, bool $secure = false, bool $httpOnly = true, string $sameSite = 'Lax'): self
    {
        return new self($name, $value, $expires, $path, $domain, $secure, $httpOnly, $sameSite);
    }

    public static function parseFromHeaderString(string $headerString): ?self
    {

        if (strpos(strtolower($headerString), 'set-cookie:') !== 0) {
            return null;
        }

        $parts = explode(';', $headerString);
        $nameParts = explode('=', trim($parts[0]), 2);

        $name = trim($nameParts[0], ' Set-Cookie:');
        $value = trim($nameParts[1]);
        $expires = null;
        $path = '/';
        $domain = null;
        $secure = false;
        $httpOnly = false;
        $sameSite = 'Lax';

        foreach ($parts as $part) {
            $part = trim($part);
            if (strtolower(substr($part, 0, 8)) === 'expires=') {
                $expires = strtotime(substr($part, 8));
            } elseif (strtolower(substr($part, 0, 5)) === 'path=') {
                $path = substr($part, 5);
            } elseif (strtolower(substr($part, 0, 7)) === 'domain=') {
                $domain = substr($part, 7);
            } elseif (strtolower($part) === 'secure') {
                $secure = true;
            } elseif (strtolower($part) === 'httponly') {
                $httpOnly = true;
            } elseif (strtolower(substr($part, 0, 9)) === 'samesite=') {
                $sameSite = substr($part, 9);
            }
        }

        return new static($name, $value, $expires, $path, $domain, $secure, $httpOnly, $sameSite);
    }

    public function set()
    {
        setcookie(
            $this->name,
            $this->value,
            [
                'expires' => $this->expires,
                'path' => $this->path,
                'domain' => $this->domain,
                'secure' => $this->secure,
                'httponly' => $this->httpOnly,
                'samesite' => $this->sameSite
            ]
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'expires' => $this->expires,
            'path' => $this->path,
            'domain' => $this->domain,
            'secure' => $this->secure,
            'httpOnly' => $this->httpOnly,
            'sameSite' => $this->sameSite
        ];
    }

    public function toString(): string
    {
        $cookie = $this->name . '=' . $this->value;

        if ($this->expires !== null) {
            $cookie .= '; Expires=' . gmdate('D, d M Y H:i:s T', $this->expires);
        }

        $cookie .= '; Path=' . $this->path;

        if ($this->domain !== null) {
            $cookie .= '; Domain=' . $this->domain;
        }

        if ($this->secure) {
            $cookie .= '; Secure';
        }

        if ($this->httpOnly) {
            $cookie .= '; HttpOnly';
        }

        $cookie .= '; SameSite=' . $this->sameSite;

        return $cookie;
    }

    public function toHeaderString(): string
    {
        return 'Set-Cookie: ' . $this->toString();
    }

    public function __toString()
    {
        $this->toString();
    }
}
