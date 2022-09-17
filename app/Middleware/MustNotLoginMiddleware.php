<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Middleware;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;

class MustNotLoginMiddleware implements Middleware
{

    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;
    private SessionService $sessionService;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
    }
    public function before(): void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            View::redirect("/");
        }
    }
}
