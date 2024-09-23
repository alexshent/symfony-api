<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GroupController extends AbstractController
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
    ) {
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/groups', name: 'app_api_groups_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);
        $groupName = $data['name'];

        $group = new Group();
        $group->setName($groupName);
        $this->groupRepository->save($group);

        return $this->json($group);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/groups/{id}', name: 'app_api_groups_read', methods: ['GET'])]
    public function read(Group $group): JsonResponse
    {
        return $this->json($group);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/groups/{id}', name: 'app_api_groups_update', methods: ['PUT'])]
    public function update(Request $request, Group $group): JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);
        $groupName = $data['name'];

        $group->setName($groupName);
        $this->groupRepository->save($group);

        return $this->json($group);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/groups/{id}', name: 'app_api_groups_delete', methods: ['DELETE'])]
    public function delete(Group $group): JsonResponse
    {
        $this->groupRepository->remove($group);

        return $this->json([
            'success' => true,
        ]);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/groups/page/{page}/{itemsPerPage}', name: 'app_api_groups_page', methods: ['GET'])]
    public function page(int $page, int $itemsPerPage = 10): JsonResponse
    {
        $page = $this->groupRepository->getPage($page, $itemsPerPage);

        return $this->json($page);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/groups/users', name: 'app_api_groups_users', methods: ['GET'])]
    public function users(): JsonResponse
    {
        $groups = $this->groupRepository->findAll();
        $list = [];

        foreach ($groups as $group) {
            $item = [];
            $item['id'] = $group->getId();
            $item['name'] = $group->getName();
            $item['users'] = $group->getUsers();

            $list[] = $item;
        }

        return $this->json($list);
    }

    // ----------------------------------------------------------------------------------------------------------------

    #[Route('/api/group/{group}/user/{user}', name: 'app_api_groups_add_user', methods: ['PATCH'])]
    public function addUser(Group $group, User $user): JsonResponse
    {
        $group->addUser($user);
        $this->groupRepository->save($group);

        return $this->json([
            'success' => true,
        ]);
    }
}
