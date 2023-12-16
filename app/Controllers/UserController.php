<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use App\Models\UserModel;
use App\Models\User_AppointmentModel;
use App\Models\User_RecordsModel;
use App\Models\PatientRecordsModel;
use App\Models\AppointmentModel;


use App\Controllers\BaseController;
use App\Models\UsersModel;

class UserController extends ResourceController
{
    use ResponseTrait;
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
    // public function register(){
    //     helper(['form']);
    //     //rules for validation
    //     $rules = [
    //         'first_name'         => 'required|min_length[3]|max_length[20]',
    //         'last_name'          => 'required|min_length[3]|max_length[20]',
    //         'email'                 => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users_tbl.email]',
    //         'password'          => 'required|min_length[6]|max_length[200]',
    //         'confpassword'   => 'matches[password]'
    //     ];
    //     if($this->validate($rules)){
    //         $model = new UsersModel();
    //         $data = [
    //             'first_name' => $this->request->getVar('first_name'),
    //             'last_name' => $this->request->getVar('last_name'),
    //             'email' => $this->request->getVar('email'),
    //             'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
    //         ];
    //         $model->save($data);
    //         return redirect()->to('/');
    //     } else {
    //         $data['validation'] = $this->validator;
    //         echo view('register', $data);
    //     }
    // }
    public function register(){
        $user = new UsersModel();
        $token = $this->for_token(50);

        // Get role from the request or set a default role
        $selectedRole = $this->request->getVar('role') ?? 'user';

        // Validate if the selected role is valid (optional)
        $validRoles = ['admin', 'user', 'staff', 'doctor']; // Define your valid roles
        if (!in_array($selectedRole, $validRoles)) {
            return $this->respond(['msg' => 'Invalid role']);
        }

        $data = [
            'first_name' => $this->request->getVar('first_name'),
            'last_name' => $this->request->getVar('last_name'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'token' => $token,
            'status' => 'active',
            'role' => $selectedRole, // Use the selected role
        ];
        $us = $user->save($data);
        if ($us) {
            return $this->respond(['msg' => 'okay', 'token' => $token]);
        } else {
            return $this->respond(['msg' => 'failed']);
        }
    }

    public function for_token($length){
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()-_=+[]{}|;:,.<>?';
        return substr(str_shuffle($str_result), 0, $length);
    }

    // public function login(){
    //     $email = $this->request->getVar("email");
    //     $password = $this->request->getVar("password");
    //     $user = new UserModel();
    //     $data = $user->where('email', $email)->first();
    //     if ($data) {
    //         $pass = $data['password'];
    //         $authenticatedPassword = password_verify($password, $pass);
    //         if($authenticatedPassword){
    //             return $this->respond(['msg' => 'okay', 'token' => $data['token']], 200);
    //         }
    //         else{
    //             return $this->respond(['msg' => 'Invalid Password'], 200);
    //         }
    //     }
    //     else{
    //         return $this->respond(['msg' => 'No User Found']);
    //     }
    // }

    public function login(){
        $user = new UsersModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $user->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $authenticatedPassword = password_verify($password, $pass);
            if($authenticatedPassword){
                // Return role-specific response
                $role = $data['role'];
                $token = $data['token'];

                switch ($role) {
                    case 'admin':
                        return $this->respond(['msg' => 'okay', 'token' => $token, 'role' => 'admin', 'redirect' => '/AdminPanel']);
                        break;
                    case 'staff':
                        return $this->respond(['msg' => 'okay', 'token' => $token, 'role' => 'staff', 'redirect' => '/staffPanel']);
                        break;
                    case 'user':
                        return $this->respond(['msg' => 'okay', 'token' => $token, 'role' => 'user', 'redirect' => '/UserPanel']);
                        break;
                    default:
                        return $this->respond(['msg' => 'okay', 'token' => $token, 'role' => 'guest', 'redirect' => '/DefaultPanel']);
                }
            } else {
                return $this->respond(['msg' => 'Invalid Password'], 200);
            }
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

    public function getAppointment_Data(){
        $main = new AppointmentModel();
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

    public function save(){
        $json = $this->request->getJSON();
        $data = [
            'first_name' => $json->first_name,
            'middle_name' => $json->middle_name,
            'last_name' => $json->last_name,
            'birthdate' => $json->birthdate,
            'age' => $json->age,
            'selectedSex' => $json->selectedSex,
            'contact_num' => $json->contact_num,
            'selectedBarangay' => $json->selectedBarangay,
            'date' => $json->date,
            'time' => $json->time,
            'selectedDoctor' => $json->selectedDoctor,
            'reason' => $json->reason,
        ];
        $main = new AppointmentModel();
        $r = $main->save($data);
        return $this->respond($r, 200);
    }
}
