<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserProfileUpdateRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegisterRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function register()
    {
        View::render('user/register', [
            'title' => 'Register'
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        } catch (ValidationException $e) {
            View::render('user/register', [
                'title' => 'Register',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function login()
    {
        View::render('user/login', [
            'title' => 'Login User'
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('user/login', [
                'title' => 'Login User',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }

    public function updateProfile()
    {
        $user = $this->sessionService->current();

        View::render("user/profile", [
            "title" => "Update Profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = $_POST['name'];

        try {
            $this->userService->updateProfile($request);
            View::redirect('/');
        } catch (ValidationException $e) {
            View::render('user/profile', [
                'title' => 'Update Profile',
                'error' => $e->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name'],
                ]
            ]);
        }
    }
}
