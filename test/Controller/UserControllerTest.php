<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->userController = new UserController();
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();

        $this->userRepository->deleteAll();
    }

    public function test_register()
    {
        $this->userController->register();

        $this->expectOutputRegex("[register]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[name]");
        $this->expectOutputRegex("[password]");
    }

    public function test_post_register_validation_error()
    {
        $_POST['id'] = '';
        $_POST['name'] = 'eko';
        $_POST['password'] = 'eko';

        $this->userController->postRegister();
        $this->expectOutputRegex("[register]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[name]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[Field cannot blank]");
    }

    public function test_post_register_duplicate()
    {
        $user = new User();
        $user->id = 'eko';
        $user->name = 'Eko';
        $user->password = 'rahasia';

        $this->userRepository->save($user);

        $_POST['id'] = 'eko';
        $_POST['name'] = 'Eko';
        $_POST['password'] = 'rahasia';

        $this->userController->postRegister();

        $this->expectOutputRegex("[register]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[name]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[User ID already exists]");
    }

    public function test_login()
    {
        $this->userController->login();

        $this->expectOutputRegex("[Login User]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[password]");
    }

    public function test_login_validation_error()
    {
        $_POST['id'] = '';
        $_POST['password'] = 'eko';

        $this->userController->postLogin();

        $this->expectOutputRegex("[Login User]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[Field cannot blank]");
    }

    public function test_login_not_found()
    {
        $_POST['id'] = 'ekos';
        $_POST['password'] = 'ekos';

        $this->userController->postLogin();

        $this->expectOutputRegex("[Login User]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[Id or Password wrong]");
    }

    public function test_login_wrong_password()
    {
        $user = new User();
        $user->id = 'eko';
        $user->name = 'Eko';
        $user->password = 'rahasia';

        $this->userRepository->save($user);

        $_POST['id'] = 'eko';
        $_POST['password'] = 'ekos';

        $this->userController->postLogin();

        $this->expectOutputRegex("[Login User]");
        $this->expectOutputRegex("[id]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[Id or Password wrong]");
    }

    public function test_logout()
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

        $this->userController->logout();

        $this->expectOutputRegex("[Location: /]");
        $this->expectOutputRegex("[X-PZN-SESSION: ]");
    }
}
