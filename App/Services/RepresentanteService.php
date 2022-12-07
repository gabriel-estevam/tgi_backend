<?php
    namespace App\Services;

    use App\Models\Representante;

    class RepresentanteService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Representante::select($id, $token);
            } else {
                return Representante::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return Representante::update($id, $data, $token);
            } else {
                return Representante::insert($data, $token);
            }
        }

        public function delete($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Representante::delete($id, $token);
            } else {
                return "Faltou o parametro de id.";
            }
        }
    }