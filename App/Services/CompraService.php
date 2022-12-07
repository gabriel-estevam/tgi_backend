<?php
    namespace App\Services;

    use App\Models\Compra;

    class CompraService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            return Compra::selectAll($token);
        }
    }
?>