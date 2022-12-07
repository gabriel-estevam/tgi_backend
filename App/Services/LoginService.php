<?php
    namespace App\Services;

    use App\Models\Login;

    class LoginService {
        public function post() 
        {
            $data = $_POST;
            if ($data) {
                return Login::select($data);
            } else {
                return json_encode("Digite as credenciais e tente novamente.");
            }
        }
    }