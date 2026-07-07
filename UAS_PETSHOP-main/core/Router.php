<?php

class Router
{
    protected string $controller = 'ProductController';
    protected string $method     = 'index';
    protected array  $params     = [];

    public function dispatch(): void
    {
        $url = $this->parseUrl();

        if (!empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            $controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                $this->notFound();
                return;
            }
        }

        require_once BASE_PATH . '/app/controllers/' . $this->controller . '.php';
        $controller = new $this->controller();

        if (!empty($url[1])) {
            $methodName = strtolower($url[1]);

            if (method_exists($controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            } else {
                $this->notFound();
                return;
            }
        }

        $this->params = !empty($url) ? array_values($url) : [];
        call_user_func_array([$controller, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
        <html lang="id">
        <head>
        <meta charset="UTF-8">
        <title>404 - Halaman Tidak Ditemukan</title>
        <style>
            body { font-family: sans-serif; text-align: center; padding: 80px; color: #333; }
            h1   { font-size: 72px; margin: 0; color: #e74c3c; }
            h2   { font-size: 24px; margin: 10px 0; }
            a    { color: #3498db; text-decoration: none; }
        </style>
        </head>
        <body>
        <h1>404</h1>
        <h2>Halaman tidak ditemukan</h2>
        <p><a href="' . BASE_URL . '">← Kembali ke Beranda</a></p>
        </body>
        </html>';
    }
}