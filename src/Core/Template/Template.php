<?php

namespace NewsApp\Core\Template;

use Exception;

class Template
{
    const DEFAULT_SECTION_NAME = 'content';

    protected $engine;
    protected $name;
    protected $data;
    protected $layoutName;
    protected $layoutData;
    protected $sections;
    protected $sectionName;

    public function __construct(Engine $engine, string $name, array $data = [])
    {
        $this->engine = $engine;
        $this->name = $name;
        $this->data = $data;
        $this->layoutData = [];
        $this->sections = [];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function addData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function layout(string $name, array $data = [])
    {
        $this->layoutName = $name;
        $this->layoutData = $data;
    }

    public function section($name, $default = null)
    {
        if (!isset($this->sections[$name])) {
            return $default;
        }

        return $this->sections[$name];
    }

    public function startSection($name)
    {
        if ($name === self::DEFAULT_SECTION_NAME) {
            throw new Exception(
                'The section name "' . self::DEFAULT_SECTION_NAME . '" is reserved.'
            );
        }

        if ($this->sectionName) {
            throw new Exception('You cannot nest sections within other sections.');
        }

        $this->sectionName = $name;

        ob_start();
    }

    public function stopSection()
    {
        if (is_null($this->sectionName)) {
            throw new Exception(
                'You must start a section before you can stop it.'
            );
        }

        if (!isset($this->sections[$this->sectionName])) {
            $this->sections[$this->sectionName] = '';
        }

        $this->sections[$this->sectionName] = ob_get_clean();
        $this->sectionName = null;
    }

    public function insert($name, array $data = array())
    {
        echo $this->engine->render($name, $data);
    }

    public function render(array $data = [])
    {
        $this->addData($data);

        $templateFile = $this->engine->getTemplateFile($this->name);

        try {
            $level = ob_get_level();
            ob_start();

            (function () {
                extract($this->getData());
                include func_get_arg(0);
            })($templateFile);

            $content = ob_get_clean();

            if (isset($this->layoutName)) {
                $layout = $this->engine->make($this->layoutName);
                $layout->sections = array_merge($this->sections, array(self::DEFAULT_SECTION_NAME => $content));
                $content = $layout->render($this->layoutData);
            }

            return $content;
        } catch (\Throwable $th) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $th;
        }
    }

    public function escape($string)
    {
        static $flags;

        if (!isset($flags)) {
            $flags = ENT_QUOTES | (defined('ENT_SUBSTITUTE') ? ENT_SUBSTITUTE : 0);
        }

        return htmlspecialchars($string ?? '', $flags, 'UTF-8');
    }
}
