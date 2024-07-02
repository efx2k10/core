<?php

namespace Efx\Core\Auth;

interface SessionAuthInterface
{
    public function auth(string $email, string $password): bool;

    public function login(AuthUserInterface $user);

    public function logout();

    public function getUser(): AuthUserInterface;

    public function check(): bool;
}