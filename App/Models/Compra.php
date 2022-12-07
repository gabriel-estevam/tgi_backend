<?php
    namespace App\Models;

    class Compra {

        public static function selectAll($token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
            
                        $sql = 'SELECT ea.id, p.nome as nome_produto, ea.qtde_minima, ea.qtde_ideal, ea.qtde_maxima, ea.qtde_atual,
                        IF(ea.qtde_atual<ea.qtde_minima, 1, 0) as menor_minimo,
                        IF(ea.qtde_ideal>ea.qtde_atual, 1, 0) as recomenda_comprar
                        FROM estoque_almoxarifado as ea
                        INNER JOIN produto as p ON ea.fk_produto = p.id
                        WHERE ea.estoque_almoxarifado_ativo = 1';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->execute();
                        
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhuma compra encontrada!");
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
?>