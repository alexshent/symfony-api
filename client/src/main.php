<?php

use Alexshent\ApiClient\Client;
use Alexshent\ApiClient\Group\AddUserToGroupCommand;
use Alexshent\ApiClient\Group\GroupCreateCommand;
use Alexshent\ApiClient\Group\GroupDeleteCommand;
use Alexshent\ApiClient\Group\GroupReadCommand;
use Alexshent\ApiClient\Group\GroupReadPageCommand;
use Alexshent\ApiClient\Group\GroupsUsersCommand;
use Alexshent\ApiClient\Group\GroupUpdateCommand;
use Alexshent\ApiClient\Menu;
use Alexshent\ApiClient\User\UserCreateCommand;
use Alexshent\ApiClient\User\UserDeleteCommand;
use Alexshent\ApiClient\User\UserReadCommand;
use Alexshent\ApiClient\User\UserReadPageCommand;
use Alexshent\ApiClient\User\UserUpdateCommand;

require_once __DIR__.'/../vendor/autoload.php';

function addUserCommands(Menu $menu, Client $client): void
{
    $menu->addItem('create user', function () use ($client) {
        $username = readline('username = ');
        $email = readline('email = ');
        $password = readline('password = ');

        $command = new UserCreateCommand($client, $username, $email, $password);
        $command->execute();
    });

    $menu->addItem('read user', function () use ($client) {
        $id = readline('id = ');
        if (is_numeric($id)) {
            $id = (int) $id;
            $command = new UserReadCommand($client, $id);
            $command->execute();
        }
    });

    $menu->addItem('update user', function () use ($client) {
        $id = readline('id = ');
        if (is_numeric($id)) {
            $id = (int) $id;
            $username = readline('username = ');
            $email = readline('email = ');

            $command = new UserUpdateCommand($client, $id, $username, $email);
            $command->execute();
        }
    });

    $menu->addItem('delete user', function () use ($client) {
        $id = readline('id = ');
        if (is_numeric($id)) {
            $id = (int) $id;
            $command = new UserDeleteCommand($client, $id);
            $command->execute();
        }
    });

    $menu->addItem('read page user', function () use ($client) {
        $page = readline('page = ');
        $itemsPerPage = readline('items per page = ');

        if (is_numeric($page) && is_numeric($itemsPerPage)) {
            $page = (int) $page;
            $itemsPerPage = (int) $itemsPerPage;

            $command = new UserReadPageCommand($client, $page, $itemsPerPage);
            $command->execute();
        }
    });
}

// -------------------------------------------------------------------------------------------------------------------

function addGroupCommands(Menu $menu, Client $client): void
{
    $menu->addItem('create group', function () use ($client) {
        $name = readline('name = ');

        $command = new GroupCreateCommand($client, $name);
        $command->execute();
    });

    $menu->addItem('read group', function () use ($client) {
        $id = readline('id = ');
        if (is_numeric($id)) {
            $id = (int) $id;
            $command = new GroupReadCommand($client, $id);
            $command->execute();
        }
    });

    $menu->addItem('update group', function () use ($client) {
        $id = readline('id = ');
        if (is_numeric($id)) {
            $id = (int) $id;
            $name = readline('name = ');

            $command = new GroupUpdateCommand($client, $id, $name);
            $command->execute();
        }
    });

    $menu->addItem('delete group', function () use ($client) {
        $id = readline('id = ');
        if (is_numeric($id)) {
            $id = (int) $id;

            $command = new GroupDeleteCommand($client, $id);
            $command->execute();
        }
    });

    $menu->addItem('read page group', function () use ($client) {
        $page = readline('page = ');
        $itemsPerPage = readline('items per page = ');

        if (is_numeric($page) && is_numeric($itemsPerPage)) {
            $page = (int) $page;
            $itemsPerPage = (int) $itemsPerPage;

            $command = new GroupReadPageCommand($client, $page, $itemsPerPage);
            $command->execute();
        }
    });

    $menu->addItem('add user to group', function () use ($client) {
        $userId = readline('user id = ');
        $groupId = readline('group id = ');

        if (is_numeric($userId) && is_numeric($groupId)) {
            $userId = (int) $userId;
            $groupId = (int) $groupId;

            $command = new AddUserToGroupCommand($client, $userId, $groupId);
            $command->execute();
        }
    });

    $menu->addItem('groups users', function () use ($client) {
        $command = new GroupsUsersCommand($client);
        $command->execute();
    });
}

// -------------------------------------------------------------------------------------------------------------------

//$client = new Client('http://127.0.0.1:8000');
$client = new Client('http://server-php-apache:80');

if (!$client->login()) {
    throw new RuntimeException('Login failed');
}

$menu = new Menu();

$menu->addItem('login', function () use ($client) {
    $client->login();
});

addUserCommands($menu, $client);
addGroupCommands($menu, $client);

$menu->start();
