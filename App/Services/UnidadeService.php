<?php
    namespace App\Services;

    use App\Models\Unidade;

    class UnidadeService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Unidade::select($id, $token);
            } else {
                return Unidade::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return Unidade::update($id, $data, $token);
            } else {
                return Unidade::insert($data, $token);
            }
        }

        public function delete($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Unidade::delete($id, $token);
            } else {
                return "Faltou o parametro de id.";
            }
        }
    }