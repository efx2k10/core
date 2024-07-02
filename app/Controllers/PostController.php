<?php

namespace APP\Controllers;

use APP\Models\Post;
use APP\Services\PostService;
use Efx\Core\Controller\AbstractController;
use Efx\Core\Http\Redirect;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use Efx\Core\Session\SessionInterface;

class PostController extends AbstractController
{

    public function __construct(
        private PostService $postService
    )
    {
    }

    public function show(int $id): Response
    {
        $post = $this->postService->findOrFail($id);
        return $this->render('post.index', [
            'post' => $post
        ]);
    }

    public function form(): Response
    {
        return $this->render('post.form', [
            'action' => '/posts/add'
        ]);
    }

    public function add(): Response
    {
        $post = Post::create(
            $this->request->input('title'),
            $this->request->input('content')
        );

        $id = $this->postService->save($post);
        $this->request->getSession()->setFlash('success', 'Пост создан');
        $this->request->getSession()->setFlash('error', 'test warning');

        return new Redirect("/posts/$id");
    }

    public function index(): Response
    {
        return $this->render('post.index', [
        ]);
    }
}