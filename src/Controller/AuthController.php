<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ResponseService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    private $responseService;
    private $userRepository;

    public function __construct(ResponseService $responseService, UserRepository $userRepository)
    {
        $this->responseService = $responseService;
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $request = $this->responseService->transformJsonBody($request);
        $firstName = $request->get('firstname');
        $lastName = $request->get('lastname');
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');

        if (empty($username) || empty($email) || empty($password) ){
            return $this->responseService->respondValidationError("Kullanıcı Adı, E-Mail veya Parola geçersiz.");
        }

        $this->userRepository->addUser($firstName, $lastName, $username, $email, $password);

        return $this->responseService->respondWithSuccess('Kullanıcı başarılı bir şekilde eklendi.');
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse([
            'token' => $JWTManager->create($user)
        ]);
    }
}
