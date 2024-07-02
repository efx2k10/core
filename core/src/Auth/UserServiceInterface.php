<?php

namespace Efx\Core\Auth;

interface UserServiceInterface
{
    public function findByEmail(string $email): ?AuthUserInterface;
}