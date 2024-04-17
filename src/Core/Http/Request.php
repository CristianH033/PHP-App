<?php

namespace NewsApp\Core\Http;

class Request
{
    private string $httpConnection;
    private string $acceptEncoding;
    private string $acceptLanguage;
    private string $accept;
    private string $userAgent;
    private string $host;
    private string $serverName;
    private string $serverPort;
    private string $serverAddr;
    private string $remotePort;
    private string $remoteAddr;
    private string $serverProtocol;
    private string $requestUri;
    private string $contentLength;
    private string $contentType;
    private string $requestMethod;
    private string $requestScheme;
    private string $queryString;
    private array $headers;
    private string $body;
    private array $query;

    public function __construct()
    {
        $this->httpConnection = $_SERVER['HTTP_CONNECTION'];
        $this->acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'];
        $this->acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $this->accept = $_SERVER['HTTP_ACCEPT'];
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->host = $_SERVER['HTTP_HOST'];
        $this->serverName = $_SERVER['SERVER_NAME'];
        $this->serverPort = $_SERVER['SERVER_PORT'];
        $this->serverAddr = $_SERVER['SERVER_ADDR'];
        $this->remotePort = $_SERVER['REMOTE_PORT'];
        $this->remoteAddr = $_SERVER['REMOTE_ADDR'];
        $this->requestScheme = $_SERVER['REQUEST_SCHEME'];
        $this->serverProtocol = $_SERVER['SERVER_PROTOCOL'];
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->contentLength = $_SERVER['CONTENT_LENGTH'];
        $this->contentType = $_SERVER['CONTENT_TYPE'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->queryString = $_SERVER['QUERY_STRING'];
        $this->requestScheme = $_SERVER['REQUEST_SCHEME'];
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
        $this->query = $_GET;
    }

    public function getHttpConnection()
    {
        return $this->httpConnection;
    }

    public function getAcceptEncoding()
    {
        return $this->acceptEncoding;
    }

    public function getAcceptLanguage()
    {
        return $this->acceptLanguage;
    }

    public function getAccept()
    {
        return $this->accept;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function getServerName()
    {
        return $this->serverName;
    }

    public function getServerAddr()
    {
        return $this->serverAddr;
    }

    public function getRemotePort()
    {
        return $this->remotePort;
    }

    public function getRemoteAddr()
    {
        return $this->remoteAddr;
    }

    public function getServerProtocol()
    {
        return $this->serverProtocol;
    }

    public function getContentLength()
    {
        return $this->contentLength;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getUri(): string
    {
        return $this->requestUri;
    }

    public function getScheme(): string
    {
        return $this->requestScheme;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->serverPort;
    }

    public function getBaseUrl(): string
    {
        $scheme = $this->getScheme();
        $host = $this->getHost();
        $port = match ($this->getPort()) {
            '80' => '',
            '443' => '',
            default => ':' . $this->getPort(),
        };

        return $scheme . '://' . $host . $port;
    }

    public function getFullUrl(): string
    {
        return $this->getBaseUrl() . $this->getUri();
    }

    public function getPath(): string
    {
        $path = explode('?', $this->getUri())[0];
        return $path;
    }

    public function getMethod(): string
    {
        return $this->requestMethod;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    public function getQueryParams(): array
    {
        return $this->query;
    }

    public function getIp(): string
    {
        return $this->getRemoteAddr();
    }

    public function get(string $key, ?string $default = null): ?string
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        if (isset($_REQUEST[$key])) {
            if (!isset($_SESSION[$key])) {
                return $_REQUEST[$key];
            }
        }

        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }

        if (isset($this->query[$key])) {
            return $this->query[$key];
        }

        return $default;
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isPut(): bool
    {
        return $this->getMethod() === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->getMethod() === 'DELETE';
    }
}
