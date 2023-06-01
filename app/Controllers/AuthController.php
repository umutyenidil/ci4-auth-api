<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

use Firebase\JWT\JWT;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function signUp()
    {
        $rules = [
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
            ],
            'password' => [
                'rules' => 'min_length[6]',
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]'
            ]
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => 'user has not been created'
            ];

            return $this->respond($response, 422);
        }

        $userModelData = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];

        (new UserModel())->save($userModelData);

        return $this->respond(['message' => 'test4'], 200);


        return $this->respond([
            'status' => true,
            'message' => 'user has been created',
        ], 200);
    }

    public function signIn()
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $userByEmail = (new UserModel())->where('email', $email)->first();

        if (!$userByEmail) {
            return $this->respond([
                'status' => false,
                'message' => 'user not found',
            ], 401);
        }

        if (!password_verify($password, $userByEmail['password'])) {
            return $this->respond([
                'status' => false,
                'message' => 'email or password is wrong',
            ], 401);
        }

        $JWT_KEY = getenv('JWT_SECRET');

        $serverTimestamp = time();
        $expirationTimestamp = $serverTimestamp + (60 * 60);

        $payload = [
            'iss' => 'ci4-jwt',
            'sub' => 'sign-in-token',
            'serverTimestamp' => $serverTimestamp,
            'expirationTimestamp' => $expirationTimestamp,
            'email' => $email,
        ];

        $token = JWT::encode($payload, $JWT_KEY, 'HS256');

        return $this->respond([
            'status' => true,
            'token' => $token,
        ], 200);
    }
}
