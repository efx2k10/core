<?php

namespace APP\Services;

use APP\Models\User;
use Efx\Core\Auth\AuthUserInterface;
use Efx\Core\Auth\UserServiceInterface;
use Efx\Core\Dbal\ModelService;
use Efx\Core\Http\Exceptions\NotFoundException;

class UserService implements UserServiceInterface
{
    public function __construct(
        private ModelService $service,
    )
    {
    }

    public function save(User $user): int
    {
        $this->service->getConnection()->createQueryBuilder()
            ->insert('users')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password' => ':password',

            ])
            ->setParameters([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            ])
            ->executeQuery();

        return $this->service->save($user);
    }

    public function find(int $id): ?AuthUserInterface
    {
        $builder = $this->service->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $user = $builder->fetchAssociative();

        if (empty($user)) return null;

        return User::create(
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            id: $user['id'],
            createdAt: new \DateTimeImmutable($user['created_at'])
        );
    }


    public function findOrFail(int $id): ?AuthUserInterface
    {
        $user = $this->find($id);

        if (is_null($user)) throw new NotFoundException();

        return $user;
    }


    public function findByEmail(string $email): ?AuthUserInterface
    {
        $builder = $this->service->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        $user = $builder->fetchAssociative();

        if (empty($user)) return null;

        return User::create(
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            id: $user['id'],
            createdAt: new \DateTimeImmutable($user['created_at'])
        );
    }
}