<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->homeController = new HomeController();
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->sessionRepository = new SessionRepository($connection);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function test_guest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Management]");
    }

    public function test_login()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "eko";
        $user->password = "eko";

        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user->id;

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();
        $this->expectOutputRegex("[Hello eko]");
    }
}
