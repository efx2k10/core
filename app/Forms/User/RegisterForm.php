<?php

namespace APP\Forms\User;

use APP\Models\User;
use APP\Services\UserService;

class RegisterForm
{

    private string $name;
    private string $email;
    private string $password;
    private string $confirmPassword;

    private array $errors = [];

    public function __construct(
        private UserService $userService
    )
    {
    }

    public function setFields(
        string $name,
        string $email,
        string $password,
        string $confirmPassword
    ): void
    {
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = trim($password);
        $this->confirmPassword = trim($confirmPassword);
    }

    public function save(): int
    {
        $user = User::create(
            $this->name,
            $this->email,
            $this->password
        );

        return $this->userService->save($user);
    }


    public function validate(): array
    {
        $this->errors = [];

        if (empty($this->name) || strlen($this->name) < 3 || strlen($this->name) > 64)
            $this->errors['name'] = 'Name is too short';

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL))
            $this->errors['email'] = 'Email is invalid';

        // добавить проверку на уникальность почты

        if (empty($this->password) || strlen($this->password) < 6)
            $this->errors['password'] = 'Password is too short';

        if (empty($this->confirmPassword) || $this->password !==
            $this->confirmPassword) $this->errors['confirmPassword'] = 'Confirm password is invalid';

        return $this->errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}