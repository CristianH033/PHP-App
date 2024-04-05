<?php

namespace NewsApp\Core;

class TemplateEngine
{
    protected $directory;

    public function __construct(string $directory)
    {
        $this->directory = $this->directory($directory);
    }

    protected function directory(string $directory): string
    {
        return is_dir($directory) ? $directory : throw new \Exception('Directory not found');
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }

    private function getFilePath(string $name): string
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $name . '.php';
    }

    public function templateExists(string $name): bool
    {
        $file = $this->getFilePath($name);

        return file_exists($file);
    }

    public function getTemplateFile(string $name): string
    {
        if (!$this->templateExists($name)) {
            throw new \Exception('Template not found: ' . $name);
        }

        $file = $this->getFilePath($name);

        return $file;
    }

    public function make($name, array $data = [])
    {
        $template = new Template($this, $name);
        $template->addData($data);
        return $template;
    }

    public function render(string $name, array $data = [])
    {
        return Cache::function(fn () => $this->make($name)->render($data));
    }
}
