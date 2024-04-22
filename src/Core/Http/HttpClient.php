<?php

namespace NewsApp\Core\Http;

class HttpClient
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_PATCH = 'PATCH';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private $defaultOptions = [
        'method' => self::METHOD_GET,
        'timeout' => 30,
        'ignore_errors' => true,
        'follow_location' => true,
        'max_redirects' => 5,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        'header' => [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
            // 'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'close',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Content-Length' => '0',
        ],
        'content' => [],
        'cookies' => [],
        // 'proxy' => 'http://proxy.example.com:8080', 
        // 'auth' => ['username', 'password'], 
    ];

    private $options = [];

    private $url = '';

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->defaultOptions, $options);
    }

    public static function make(array $options = [])
    {
        return new self($options);
    }

    public function addHeader(string $key, string $value)
    {
        $this->options['header'][$key] = $value;
        return $this;
    }

    public function addHeaders(array $headers)
    {
        $this->options['header'] = array_merge($this->options['header'], $headers);
        return $this;
    }

    public function getHeaders()
    {
        return $this->options['header'];
    }

    public function addCookie(string $key, string $value, $domain = null)
    {
        $this->options['cookies'][$key] = [
            'value' => $value,
            'domain' => $domain
        ];

        return $this;
    }

    public function addCookies(array $cookies)
    {
        $this->options['cookies'] = array_merge($this->options['cookies'], $cookies);
        return $this;
    }

    public function getCookies()
    {
        return $this->options['cookies'];
    }

    public function addToBody(string $key, string $value)
    {
        $this->options['content'][$key] = $value;
        return $this;
    }

    public function get(string $url)
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_GET;

        return $this;
    }

    public function post(string $url, array $data = [])
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_POST;
        $this->options['content'] = array_merge($this->options['content'], $data);
        return $this;
    }

    public function put(string $url, array $data = [])
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_PUT;
        $this->options['content'] = array_merge($this->options['content'], $data);
        return $this;
    }

    public function delete(string $url)
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_DELETE;
        return $this;
    }

    public function patch(string $url, array $data = [])
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_PATCH;
        $this->options['content'] = array_merge($this->options['content'], $data);
        return $this;
    }

    public function head(string $url)
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_HEAD;
        return $this;
    }

    public function options(string $url)
    {
        $this->url = $url;
        $this->options['method'] = self::METHOD_OPTIONS;
        return $this;
    }

    public function json()
    {
        $this->addHeader('Content-Type', 'application/json');
        $this->addHeader('Accept', 'application/json');
        return $this;
    }

    private function buildUrl()
    {
        $url = $this->url;
        $defaultSchema = 'http://';

        if (strpos($this->url, '://') === false) {
            $url = $defaultSchema . $url;
        }

        $parsedUrl = parse_url($url);
        $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . ($parsedUrl['path'] ?? '/');

        switch ($this->options['method']) {
            case self::METHOD_GET:
                $url .=  empty($this->options['content']) ? '' : '?' . http_build_query($this->options['content']);
                break;
            default:
                break;
        }

        return $url;
    }

    private function buildHeaders()
    {
        $this->addHeader('Content-Length', strlen($this->buildContentData()));

        $headers = [];
        foreach ($this->getHeaders() as $key => $value) {
            $headers[] = "$key: $value";
        }

        $this->buildCookiesString() && $headers[] = "Cookie: " . $this->buildCookiesString();

        return $headers;
    }

    private function buildHeadersString()
    {
        return implode("\r\n", $this->buildHeaders());
    }

    private function buildCookies()
    {
        $cookies = [];
        foreach ($this->getCookies() as $key => $data) {
            $cookies[] = "$key=" . urlencode($data['value']) . ($data['domain'] ? "; domain=" . $data['domain'] : '');
        }

        return $cookies;
    }

    private function buildCookiesString()
    {
        return implode('; ', $this->buildCookies());
    }

    private function buildContentData()
    {
        switch ($this->options['method']) {
            case self::METHOD_POST:
            case self::METHOD_PATCH:
            case self::METHOD_PUT:
                if ($this->getHeaders()['Content-Type'] === 'application/json') {
                    return json_encode($this->options['content']);
                }

                return http_build_query($this->options['content']);
            default:
                return '';
        }
    }

    private function buildContext()
    {
        $allowedContextKeys = [
            'method',
            'header',
            'user_agent',
            'content',
            'proxy',
            'request_fulluri',
            'follow_location',
            'max_redirects',
            'protocol_version',
            'timeout',
            'ignore_errors',
        ];

        $httpOptions = array_merge(
            $this->options,
            [
                'method' => $this->options['method'],
                'header' =>  $this->buildHeadersString(),
                'content' => $this->buildContentData(),
            ]
        );

        $httpOptions = array_filter($httpOptions, function ($value) {
            return !empty($value);
        });

        $httpOptions = array_intersect_key($httpOptions, array_flip($allowedContextKeys));

        $options = [
            'http' => $httpOptions,
        ];

        $context = stream_context_create($options);

        return $context;
    }

    private function extractStatusCode(array $responseHeaders)
    {
        if (isset($responseHeaders[0])) {
            if (strpos($responseHeaders[0], 'HTTP') === 0) {
                return substr($responseHeaders[0], 9, 3);
            }
        }

        return 200;
    }

    public function send()
    {
        $url = $this->buildUrl();
        $context = $this->buildContext();

        $responseBody = file_get_contents(
            $url,
            false,
            $context
        );

        $responseHeaders = $http_response_header;

        if ($responseBody === false) {
            return response(500, [], 'Could not fetch from ' . $url);
        }

        $response = new Response(
            statusCode: $this->extractStatusCode($responseHeaders),
            headers: $responseHeaders,
            body: $responseBody
        );

        return $response;
    }
}
