<?php

namespace NewsApp\Core\Http;

class Response
{
    protected $statusCode;
    protected $headers;
    protected $body;
    protected $cookies;

    public function __construct($statusCode = 200, $headers = [], $body = '', $cookies = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->cookies = $cookies;
    }

    public static function make($statusCode = 200, $headers = [], $body = '', $cookies = [])
    {
        return new static($statusCode, $headers, $body, $cookies);
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getJson()
    {
        return json_decode($this->body, true);
    }

    public function isJson()
    {
        return $this->getHeaders()['Content-Type'] === 'application/json';
    }

    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    public function addCookie(
        $name,
        $value,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true
    ) {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httpOnly' => $httpOnly
        ];
        return $this;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    public function json($data)
    {
        $this->setHeaders(['Content-Type' => 'application/json']);
        $this->setBody(json_encode($data));
        return $this;
    }

    public function text(string $data)
    {
        $this->setHeaders(['Content-Type' => 'text/plain']);
        $this->setBody($data);
        return $this;
    }

    public function send()
    {
        // Establecer el código de estado HTTP
        http_response_code($this->statusCode);

        // Establecer los encabezados
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        // Establecer las cookies
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httpOnly']
            );
        }

        // Enviar el contenido de la respuesta
        ob_start();
        echo $this->body;
        ob_end_flush();
    }
}
