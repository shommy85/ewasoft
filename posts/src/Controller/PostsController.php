<?php

namespace App\Controller;

use App\Components\Controller\FormErrorControllerTrait;
use App\Components\Storage\EntityStorageInterface;
use App\Entity\Post;
use App\Event\PostDeletedEvent;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PostsController extends AbstractController
{
    use FormErrorControllerTrait;

    #[Route('/posts', name: 'all_posts', methods: 'GET')]
    public function index(PostRepository $postRepository, SerializerInterface $serializer): JsonResponse
    {
        $allPosts = $postRepository->findBy([], null, 50);

        return new JsonResponse($serializer->serialize($allPosts, 'json'), 200, [], true);
    }

    #[Route('/posts/{id}', name: 'show_post', methods: 'GET', requirements: ['postId' => '\d+'])]
    public function show(Post $post, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse($serializer->serialize($post, 'json'), 200, [], true);
    }

    #[Route('/posts', name: 'create_post', methods: 'POST')]
    public function create(Request $request, SerializerInterface $serializer, EntityStorageInterface $storage): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $post = new Post();
        $post->setAuthorId($this->getUser()->getId());
        $form = $this->createForm(PostType::class, $post);

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action...
            $storage->save($post);
//            return new JsonResponse($serializer->serialize($user, 'json'));
            return new JsonResponse($serializer->serialize($post, 'json'), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }

    #[Route('/posts/{id}', name: 'update_post', methods: 'PATCH')]
    public function update(Post $post, Request $request, SerializerInterface $serializer, EntityStorageInterface $storage): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER', $post, 'Action not allowed');
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $form = $this->createForm(PostType::class, $post);

        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $storage->save($post);
            return new JsonResponse($serializer->serialize($post, 'json'), 200, [], true);
        }

        return $this->buildFormErrorResponse($form);
    }

    #[Route('/posts/{id}', name: 'delete_post', methods: 'DELETE')]
    public function delete(Post $post, EntityStorageInterface $storage, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', $post, 'Action not allowed');

        $deletedEvent = new PostDeletedEvent($post->getId(), $post->getAuthorId());
        $storage->delete($post);

        $eventDispatcher->dispatch($deletedEvent, PostDeletedEvent::class);

        return new Response(null, 204);
    }

    #[Route('/test', name: 'app_test')]
    public function test(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse($serializer->serialize($user, 'json'), 200, [], true);

//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/PostsController.php',
//        ]);
    }
}
