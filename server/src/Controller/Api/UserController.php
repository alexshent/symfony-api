<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/users', name: 'app_api_users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);
        $username = $data['username'];
        $plaintextPassword = $data['password'];
        $email = $data['email'];

        $user = new User();
        $user
            ->setRoles(['ROLE_USER'])
            ->setUsername($username)
            ->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);

        return $this->json($user);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/users/{id}', name: 'app_api_users_read', methods: ['GET'])]
    public function read(User $user): JsonResponse
    {
        return $this->json($user);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/users/{id}', name: 'app_api_users_update', methods: ['PUT'])]
    public function update(Request $request, User $user): JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        if (key_exists('username', $data)) {
            $username = $data['username'];
            $user->setUsername($username);
        }

        if (key_exists('email', $data)) {
            $email = $data['email'];
            $user->setEmail($email);
        }

        $this->userRepository->save($user);

        return $this->json($user);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/users/{id}', name: 'app_api_users_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userRepository->remove($user);

        return $this->json([
            'success' => true,
        ]);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/users/page/{page}/{itemsPerPage}', name: 'app_api_users_page', methods: ['GET'])]
    public function page(int $page, int $itemsPerPage = 10): JsonResponse
    {
        $page = $this->userRepository->getPage($page, $itemsPerPage);

        return $this->json($page);
    }
}
