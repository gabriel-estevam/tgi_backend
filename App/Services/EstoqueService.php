<?php
    namespace App\Services;

    use App\Models\Estoque;

    class EstoqueService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Estoque::select($id, $token);
            } else {
                return Estoque::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return Estoque::update($id, $data, $token);
            } else {
                return Estoque::insert($data, $token);
            }
        }
    }
?>