<?php

namespace APP\Services;

use APP\Models\Post;
use Efx\Core\Dbal\ModelService;
use Efx\Core\Http\Exceptions\NotFoundException;

class PostService
{
    public function __construct(
        private ModelService $service,
    )
    {
    }

    public function save(Post $post): int
    {
        $this->service->getConnection()->createQueryBuilder()
            ->insert('posts')
            ->values([
                'title' => ':title',
                'content' => ':content',
            ])
            ->setParameters([
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
            ])
            ->executeQuery();

        return $this->service->save($post);
    }

    public function find(int $id): ?Post
    {
        $builder = $this->service->getConnection()->createQueryBuilder()
            ->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $post = $builder->fetchAssociative();

        if (empty($post)) return null;


        return Post::create(
            title: $post['title'],
            content: $post['content'],
            id: $post['id'],
            createdAt: new \DateTimeImmutable($post['created_at'])
        );

    }


    public function findOrFail(int $id): ?Post
    {
        $post = $this->find($id);

        if (is_null($post)) throw new NotFoundException();

        return $post;
    }
}