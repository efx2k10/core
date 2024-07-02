<?php

namespace APP\Controllers;

use APP\Forms\User\RegisterForm;
use APP\Services\UserService;
use Efx\Core\Auth\SessionAuthInterface;
use Efx\Core\Controller\AbstractController;
use Efx\Core\Http\Redirect;
use Efx\Core\Http\Response;

class RegisterController extends AbstractController
{

    public function __construct(
        private UserService          $userService,
        private SessionAuthInterface $auth,
    )
    {
    }

    public function index(): Response
    {

        return $this->render('user.register', [
            'action' => '/register',
            'input' => [
                'name' => $this->request->input('name'),
                'email' => $this->request->input('email'),
                'password' => $this->request->input('password'),
                'passwordConfirm' => $this->request->input('passwordConfirm')
            ]
        ]);
    }

    public function register(): Response
    {
        $form = new RegisterForm($this->userService);
        $form->setFields(
            $this->request->input('name'),
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('passwordConfirm')
        );

        $form->validate();

        foreach ($form->getErrors() as $error) {
            $this->request->getSession()->setFlash('error', $error);
        }


        if ($form->hasErrors())
            return $this->index();


        $id = $form->save();

        if ($id) {
            $this->request->getSession()->setFlash('success', 'пользователь успешно зарегистрирован');
            $user = $this->userService->find($id);
            $this->auth->login($user);
            return new Redirect('/login');
        }


        $this->request->getSession()->setFlash('error', 'ошибка регистрации пользователя');

        return new Redirect('/');


    }

}