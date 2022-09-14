<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

function setcookie(string $name, string $value)
{
    echo "$name: $value";
}

class SessionServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    private SessionService $sessionService;


    protected function setUp(): void
    {

        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();

        $user->id = "eko";
        $user->name = "eko";
        $user->password = "eko";

        $this->userRepository->save($user);
    }

    public function test_create()
    {
        $session = $this->sessionService->create('eko');

        $this->expectOutputRegex("[X-PZN-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);

        $this->assertEquals("eko", $result->user_id);
    }

    public function test_destroy()
    {
        $session = new Session();

        $session->id = uniqid();
        $session->user_id = "eko";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-PZN-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);

        $this->assertNull($result);
    }

    public function test_current()
    {
        $session = $this->sessionService->create('eko');

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        $this->assertEquals($session->user_id, $user->id);
    }

    public function test_delete()
    {
        $session = $this->sessionService->create('eko');

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-PZN-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);

        $this->assertNull($result);
    }
}
