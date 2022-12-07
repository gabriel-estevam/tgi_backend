<?php
    namespace App\Services;

    use App\Models\Reset;

    class ResetService {
        public function post() 
        {
            $data = $_POST;
            if ($data) {
                return Reset::select($data);
            } else {
                return json_encode("Digite o login e tente novamente.");
            }
        }
    }