<?php
    namespace App\Models;
    
    class JWT {
        private string $secret_key;

        public function __construct(string $secret_key){
            $this->secret_key = $secret_key;
        }

        public function create(array $data_payload): string {
            $header = json_encode(array(
                "type" => "JWT",
                "alg" => "HS256"
            ));

            $payload = json_encode($data_payload);
            $base_header = $this->base64url_encode($header);
            $base_payload = $this->base64url_encode($payload);

            $signature = hash_hmac("sha256",$base_header.'.'.$base_payload, $this->secret_key, true);
            $base_signature = $this->base64url_encode($signature);

            $jwt = $base_header.'.'.$base_payload.'.'.$base_signature;

            return $jwt;
        }

        public function validate(string $token){
            $response = array();

            $jwt_split = explode('.', $token);

            if (count($jwt_split) == 3) {
                $signature = hash_hmac("sha256", $jwt_split[0].'.'.$jwt_split[1], $this->secret_key, true);
                
                $base_signature = $this->base64url_encode($signature);

                if ($base_signature == $jwt_split[2]) {
                    $response = json_decode($this->base64url_decode($jwt_split[1]));
                    return $response;
                }
            } else {
                return false;
            } 
        }

        private function base64url_encode($data){
            return rtrim(strtr(base64_encode($data),'+/','-_'),'=');
        }

        private function base64url_decode($data){
            return base64_decode(strtr($data,'-_','+/').str_repeat('=',3-(3+strlen($data))%4));
        }
    }
?>