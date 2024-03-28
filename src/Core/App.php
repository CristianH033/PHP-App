<?php

namespace NewsApp\Core;

class App
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function run()
    {
        $scheme = $this->request->getScheme();
        $httpMethod = $this->request->getMethod();
        $host = $this->request->getHost();
        $path = $this->request->getPath();
        $queryParams = $this->request->getQueryParams();
        $ip = $this->request->getIp();

        header('Content-Type: text/plain');

        echo "Scheme: {$scheme}" . PHP_EOL;
        echo "HTTP Method: {$httpMethod}" . PHP_EOL;
        echo "Host: {$host}" . PHP_EOL;
        echo "Path: {$path}" . PHP_EOL;
        echo "Query Params: " . json_encode($queryParams) . PHP_EOL;
        echo "IP: {$ip}" . PHP_EOL;
    }
}
