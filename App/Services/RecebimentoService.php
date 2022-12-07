<?php
    namespace App\Services;

    use App\Models\Recebimento;

    class RecebimentoService {

        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            return Recebimento::select($id, $token);
        }
        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;

            return Recebimento::insert($data, $token);
        }
    }
?>