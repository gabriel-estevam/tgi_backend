<?php
    namespace App\Services;

    use App\Models\Usuario;

    class UsuarioService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Usuario::select($id, $token);
            } else {
                return Usuario::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return Usuario::update($id, $data, $token);
            } else {
                return Usuario::insert($data, $token);
            }
        }

        public function delete($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Usuario::delete($id, $token);
            } else {
                return "Faltou o parametro de id.";
            }
        }
    }