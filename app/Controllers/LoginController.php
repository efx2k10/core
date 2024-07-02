<?php

namespace APP\Controllers;

use Efx\Core\Auth\SessionAuthInterface;
use Efx\Core\Controller\AbstractController;
use Efx\Core\Http\Redirect;
use Efx\Core\Http\Response;

class LoginController extends AbstractController
{

    public function __construct(
        private SessionAuthInterface $sessionAuth,
    )
    {
    }

    public function index(): Response
    {
        return $this->render('user.login', [
            'action' => '/login',
            'input' => [
                'email' => $this->request->input('email'),
                'password' => $this->request->input('password'),
            ]
        ]);
    }

    public function login(): Response
    {

        $auth = $this->sessionAuth->auth(
            $this->request->input('email'),
            $this->request->input('password')
        );


        if (!$auth) {
            $this->request->getSession()->setFlash('error', 'не правильный логин или пароль');
            return $this->index();
        }


        $this->request->getSession()->setFlash('success', 'успешный вход');

        return new Redirect('/dashboard');


    }


    public function logout(): Response
    {
        $this->sessionAuth->logout();
        return new Redirect('/');

    }

}