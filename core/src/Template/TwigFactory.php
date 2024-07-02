<?php

namespace Efx\Core\Template;

use Efx\Core\Auth\SessionAuthInterface;
use Efx\Core\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private string               $viewsPath,
        private SessionInterface     $session,
        private SessionAuthInterface $auth,
    )
    {

    }

    public function create(): Environment
    {
        $loader = new FilesystemLoader($this->viewsPath);

        $twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));
        $twig->addFunction(new TwigFunction('auth', [$this, 'getAuth']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function getAuth(): SessionAuthInterface
    {
        return $this->auth;
    }

}