<?php

declare(strict_types=1);

namespace GetLaminas\Security\Handler;

use GetLaminas\Security\Advisory;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function basename;
use function file_exists;
use function sprintf;

class AdvisoryHandler implements RequestHandlerInterface
{
    /** @var Advisory */
    private $advisory;

    /** @var Template\TemplateRendererInterface */
    private $template;

    public function __construct(Advisory $advisory, Template\TemplateRendererInterface $template)
    {
        $this->advisory = $advisory;
        $this->template = $template;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $advisory = $request->getAttribute('advisory', false);
        if (! $advisory) {
            return new HtmlResponse($this->template->render('error::404'));
        }

        $file = sprintf('data/advisories/%s.md', basename($advisory));
        if (! file_exists($file)) {
            return new HtmlResponse($this->template->render('error::404'));
        }

        $content             = $this->advisory->getFromFile($file);
        $content['layout']   = 'layout::default';
        $content['advisory'] = $advisory;

        return new HtmlResponse($this->template->render('security::advisory', $content));
    }
}
