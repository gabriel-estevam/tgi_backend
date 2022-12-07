<?php
    namespace App\Services;

    use App\Models\EstoqueAlmoxarifado;

    class EstoqueAlmoxarifadoService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return EstoqueAlmoxarifado::select($id, $token);
            } else {
                return EstoqueAlmoxarifado::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            if ($id) {
                return EstoqueAlmoxarifado::update($id, $data, $token);
            } else {
                return EstoqueAlmoxarifado::insert($data, $token);
            }
        }
    }
?>