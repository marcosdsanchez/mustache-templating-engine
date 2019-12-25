<?php

declare(strict_types=1);

namespace MustacheTemplatingEngine;

use Mustache_Engine;
use Mustache_Template;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;

final class MustacheEngine implements EngineInterface
{
    private $mustache;
    private $parser;

    public function __construct(Mustache_Engine $mustache, TemplateNameParserInterface $parser)
    {
        $this->mustache = $mustache;
        $this->parser   = $parser;
    }

    public function render($name, array $parameters = array()): string
    {
        return $this->load($name)->render($parameters);
    }

    public function exists($name): bool
    {
        try {
            $this->load($name);
        } catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    public function supports($name): bool
    {
        if ($name instanceof Mustache_Template) {
            return true;
        }

        $template = $this->parser->parse($name);

        return 'mustache' === $template->get('engine');
    }

    private function load($name): Mustache_Template
    {
        if ($name instanceof Mustache_Template) {
            return $name;
        }

        return $this->mustache->loadTemplate($name);
    }
}
