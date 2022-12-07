<?php
    namespace App\Services;

    use App\Models\Fornecedor;

    class FornecedorService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Fornecedor::select($id, $token);
            } else {
                return Fornecedor::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return Fornecedor::update($id, $data, $token);
            } else {
                return Fornecedor::insert($data, $token);
            }
        }

        public function delete($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Fornecedor::delete($id, $token);
            } else {
                return "Faltou o parametro de id.";
            }
        }
    }