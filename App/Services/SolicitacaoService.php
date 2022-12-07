<?php
    namespace App\Services;

    use App\Models\Solicitacao;

    class SolicitacaoService {
        public function get($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Solicitacao::select($id, $token);
            } else {
                return Solicitacao::selectAll($token);
            }
        }

        public function post($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            $data = $_POST;
            
            return Solicitacao::insert($data, $token);
        }

        public function delete($id = null) 
        {
            $header = getallheaders();
            $token = $header['token'];
            if ($id) {
                return Solicitacao::delete($id, $token);
            } else {
                return "Faltou o parametro de id.";
            }
        }
    }