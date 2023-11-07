<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class TestPageHandler implements RequestHandlerInterface
{
    private array $vendors;
    private TemplateRendererInterface $renderer;

    public function __construct()
    {
       
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {   
        $result = array(
            'data' => array(
                'id' => 1,
                'title' => 'Hello World!',
                'date' => '2012-01-01',
            ),
        );
        return new JsonResponse($result);
    }
    
}
