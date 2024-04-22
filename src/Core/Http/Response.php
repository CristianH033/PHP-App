<?php

namespace NewsApp\Core\Http;

class Response
{
    protected int $statusCode;
    protected string $body;
    /** @var Header[] */
    protected array $headers;
    /** @var Cookie[] */
    protected array $cookies;

    public function __construct(int $statusCode = 200, array $headers = [], string $body = '', array $cookies = [])
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->parseHeaders($headers);
        $this->parseCookies($cookies);
    }

    private function parseHeaders(array $headers): void
    {
        $parsedHeaders = [];

        foreach ($headers as $header) {
            $currrentHeader = null;

            if (is_array($header)) {
                $currrentHeader = Header::parseFromKeyValueArray($header);
            }

            if (is_string($header)) {
                $currrentHeader = Header::parseFromHeaderString($header);
            }

            if ($currrentHeader !== null) {
                if ($currrentHeader->isCookie()) {
                    $this->cookies[] = Cookie::parseFromHeaderString($header);
                } else {
                    $parsedHeaders[] = $currrentHeader;
                }
            }
        }

        $this->headers = $parsedHeaders;
    }

    private function parseCookies(array $cookies): void
    {
        $parsedCookies = [];

        foreach ($cookies as $cookie) {
            if (is_array($cookie) && arrayKeysExists($cookie, ['name', 'value'])) {
                $parsedCookies[] = new Cookie(...$cookie);
            }
        }

        $this->cookies = $parsedCookies;
    }

    public static function make(int $statusCode = 200, array $headers = [], string $body = '', array $cookies = [])
    {
        return new static($statusCode, $headers, $body, $cookies);
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setHeaders(array $headers): self
    {
        $this->parseHeaders($headers);
        return $this;
    }

    public function addHeader(string $key, string $value): self
    {
        $header = Header::parseFromKeyValueArray([$key => $value]);

        $header && $this->headers[] = $header;

        return $this;
    }

    public function getHeaders(): array
    {
        return array_map(fn ($header) => $header->toString(), $this->headers);
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getJson(): ?array
    {
        return json_decode($this->body, true);
    }

    public function isJson(): bool
    {
        return $this->getHeaders()['Content-Type'] === 'application/json';
    }

    public function bodyIsJson(): bool
    {
        return json_decode($this->body, true) !== null;
    }

    public function setCookies(array $cookies): self
    {
        $this->parseCookies($cookies);
        return $this;
    }

    public function addCookie(
        $name,
        $value,
        $expires = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true,
        $sameSite = null
    ): self {
        $this->cookies[] = new Cookie(
            name: $name,
            value: $value,
            expires: $expires,
            path: $path,
            domain: $domain,
            secure: $secure,
            httpOnly: $httpOnly,
            sameSite: $sameSite
        );
        return $this;
    }

    public function getCookies()
    {
        return array_map(fn ($cookie) => $cookie->toArray(), $this->cookies);
    }

    public function json($data)
    {
        $this->addHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($data));
        return $this;
    }

    public function text(string $data)
    {
        $this->addHeader('Content-Type', 'text/plain');
        $this->setBody($data);
        return $this;
    }

    public function send()
    {
        ob_start();
        ob_clean();

        http_response_code($this->statusCode);

        foreach ($this->headers as $header) {
            $header->set();
        }

        foreach ($this->cookies as $cookie) {
            $cookie->set();
        }

        Cookie::make('X-NewsApp-Test-Cookie', 'X-NewsApp-Test-Cookie-Value')->set();

        // Enviar el contenido de la respuesta
        echo $this->body;
        ob_end_flush();
    }
}
