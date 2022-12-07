<?php
    namespace App\Services;

    use App\Models\MovimentaEstoqueAlmoxarifado;

    class MovimentaEstoqueAlmoxarifadoService {

        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            
            return MovimentaEstoqueAlmoxarifado::selectAll($token);
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;

            return MovimentaEstoqueAlmoxarifado::insert($data, $token);
        }
    }
?>