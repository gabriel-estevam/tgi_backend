<?php
    namespace App\Models;

    class Login {
        private static $table = 'usuario';

        public static function select($data) {
            $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            $login = $data['login'];
            $senha = MD5($data['senha']);

            $sql = 'SELECT * FROM '.self::$table.' WHERE login = :login AND senha = :senha AND usuario_deletado = 0';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->bindValue(':senha', $senha);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                date_default_timezone_set('America/Sao_Paulo');
                // 3600 = 1 Hora
                $expiracao_token = date('Y-m-d H:i:s' , time()+21600);
                $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');
                $token = $jwt->create(array('nome' => $rows[0]['nome'],'id' => $rows[0]['id'],'nivel_acesso' => $rows[0]['nivel_acesso'],'unidade_negocio' => $rows[0]['fk_unidade_negocio'],'expiracao_token' => $expiracao_token));
                $result[0] = array(
                    "token" => $token
                );
                return $result;
            } else {
                $result[0] = array(
                    "error" => "Credenciais incorretas!"
                );
                return $result;
            }
        }

    }