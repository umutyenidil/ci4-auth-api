<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Couchbase\User;

class UserController extends BaseController
{
    use ResponseTrait;

    public function readUserList()
    {
        $userList = (new UserModel)->findAll();

        return $this->respond([
            'users' => $userList,
        ], 200);
    }

    public function readUserByUuid()
    {
        $incomingUuid = $this->request->getVar('uuid');

        print_r($incomingUuid);
        $user = (new UserModel())->where('uuid', $incomingUuid)->first();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'User not found'
            ], 402);
        }

        return $this->respond([
            'user' => $user,
        ], 200);
    }
}
