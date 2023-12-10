<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use App\Models\UserModel;
use App\Models\User_AppointmentModel;
use App\Models\User_RecordsModel;
use App\Models\PatientRecordsModel;


use App\Controllers\BaseController;

class UserController extends ResourceController
{
    //REGISTER
    // public function register(){
    //     // Assuming you have received the email and password from the registration form
    //     $email = $this->request->getVar("email");
    //     $password = $this->request->getVar("password");
    
    //     // Hash the password
    //     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    //     $user = new UserModel();
        
    //     // Save the hashed password in the database
    //     $user->insert([
    //         'email' => $email,
    //         'password' => $hashedPassword,
    //         // other fields...
    //     ]);
    
    //     // You might want to add some success handling here
    // }
    
    public function login(){
        $email = $this->request->getVar("email");
        $password = $this->request->getVar("password");
        $user = new UserModel();
        $data = $user->where('email', $email)->first();
        if ($data) {
            $pass = $data['password'];
            $authenticatedPassword = password_verify($password, $pass);
            if($authenticatedPassword){
                return $this->respond(['msg' => 'okay', 'token' => $data['token']], 200);
            }
            else{
                return $this->respond(['msg' => 'Invalid Password'], 200);
            }
        }
        else{
            return $this->respond(['msg' => 'No User Found']);
        }
    }
    

    public function index()
    {
        //
    }

    public function getData(){
        $main = new User_AppointmentModel();
        $data = $main->findAll();
        return $this->respond($data, 200);
    }

    public function getData2(){
        $main = new User_RecordsModel();
        $data = $main->findAll();
        return $this->respond($data, 200);
    }

    public function getpatrecData(){
        $main = new PatientRecordsModel();
        $data = $main->findAll();
        return $this->respond($data, 200);
    }
}
