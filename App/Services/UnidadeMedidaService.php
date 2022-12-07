<?php
    namespace App\Services;

    use App\Models\UnidadeMedida;

    class UnidadeMedidaService {
        public function get() 
        {
            $header = getallheaders();
            $token = $header['token'];
            return UnidadeMedida::select($token);
            
        }
    }
?>