<?php

namespace Efx\Core\Auth;

interface AuthUserInterface
{
    public function getId(): int;

    public function getEmail(): string;

    public function getPassword(): string;
}
