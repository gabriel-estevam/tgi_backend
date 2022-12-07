<?php
    namespace App\Services;

    use App\Models\Retirada;

    class RetiradaService {

        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            return Retirada::select($id, $token);
        }
        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;

            return Retirada::insert($data, $token);
        }
    }
?>