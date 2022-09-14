<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();

        $user->id = "eko";
        $user->name = "eko";
        $user->password = "eko";

        $this->userRepository->save($user);
    }

    public function test_save_success()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "eko";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        $this->assertEquals($session->id, $result->id);
        $this->assertEquals($session->user_id, $result->user_id);
    }

    public function test_delete_success()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "eko";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);

        $this->assertEquals($session->id, $result->id);
        $this->assertEquals($session->user_id, $result->user_id);

        $this->sessionRepository->deleteById($session->id);

        $result = $this->sessionRepository->findById($session->id);

        $this->assertNull($result);
    }

    public function test_find_by_id_notFound()
    {
        $result = $this->sessionRepository->findById('notfound');

        $this->assertNull($result);
    }
}
