<?php

namespace App\Models;

class UnidadeMedida {
    private static $table = 'unidade_medida';

    public static function select($token){

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql = 'SELECT * FROM '.self::$table.' WHERE unidade_medida_deletado = 0';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    } else {
                        $result[0] = array(
                            "error" => "Nenhuma unidade de medida encontrada!"
                        );
                        return $result;
                    }
                } else {
                    return 'Token inválido!';
                }
            } else {
                return 'Token Expirado!';
            }
        } else {
            return 'Token não enviado!';
        }
    }
}
