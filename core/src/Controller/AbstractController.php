<?php

namespace Efx\Core\Controller;


use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    protected Request $request;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function render(string $view, array $params = [], Response $response = null): Response
    {
        $view_path = str_replace('.', '/', $view);
        $content = $this->container->get('twig')->render($view_path . '.html.twig', $params);

        $response ??= new Response();

        $response->setContent($content);

        return $response;

    }


}