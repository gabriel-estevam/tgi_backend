<?php
    namespace App\Services;

    use App\Models\Produto;

    class ProdutoService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Produto::select($id, $token);
            } else {
                return Produto::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return Produto::update($id, $data, $token);
            } else {
                return Produto::insert($data, $token);
            }
        }

        public function delete($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Produto::delete($id, $token);
            } else {
                return "Faltou o parametro de id.";
            }
        }
    }