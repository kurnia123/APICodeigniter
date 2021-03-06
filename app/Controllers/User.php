<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Controllers\Auth;
use \Firebase\JWT\JWT;

class User extends ResourceController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->protect = new Auth();
    }

    public function index()
    {
        $output = $this->userModel->getUser();
        return $this->respond($output, 200);
    }

    public function show($id = false)
    {
        $data = $this->userModel->getUser($id);
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound("tidak ditemukan data dengan ID : " . $id);
        }
    }

    public function create()
    {
        $data = [
            'name_user' => $this->request->getPost('name_user'),
            'username_user' => $this->request->getPost('username_user'),
            'password_user' => $this->request->getPost('password_user'),
            'is_seller' => $this->request->getPost('is_seller'),
        ];

        // $data = json_decode(file_get_contents("php://input"));
        $this->userModel->insert($data);
        $response = [
            'status' => 201,
            'error' => null,
            'message' => [
                'success' => 'Data berhasil di Simpan'
            ]
        ];

        return $this->respondCreated($response, 201);
    }

    public function update($id = null)
    {
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'firstname_user' => $json->firstname_user,
                'lastname_user' => $json->lastname_user,
                'username_user' => $json->username_user,
                'password_user' => $json->password_user,
                'alamat' => $json->alamat,
                'provinsi' => $json->provinsi,
                'kabupaten' => $json->kabupaten,
                'kecamatan' => $json->kecamatan,
                'no_telephone' => $json->no_telephone,
                'bio' => $json->bio,
                'is_seller' => $json->is_seller,
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'firstname_user' => $input['firstname_user'],
                'lastname_user' => $input['lastname_user'],
                'username_user' => $input['username_user'],
                'password_user' => $input['password_user'],
                'alamat' => $input['alamat'],
                'provinsi' => $input['provinsi'],
                'kabupaten' => $input['kabupaten'],
                'kecamatan' => $input['kecamatan'],
                'no_telephone' => $input['no_telephone'],
                'bio' => $input['bio'],
                'is_seller' => $input['is_seller'],
            ];
        }

        $this->userModel->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => [
                'success' => 'Data berhasil di Update'
            ]
        ];

        return $this->respond($response);
    }


    public function updateSeller($id = null)
    {
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'is_seller' => $json->is_seller,
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'is_seller' => $input['is_seller'],
            ];
        }

        $this->userModel->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => [
                'success' => 'Data berhasil di Update'
            ]
        ];

        return $this->respond($response);
    }


    public function delete($id = null)
    {
        $data = $this->userModel->find($id);
        if ($data) {
            $this->userModel->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => [
                    'success' => 'Data berhasil di Update'
                ]
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound("Data dengan ID : " . $id . " tidak ditemukan");
        }
    }
}
