<?php
    namespace App\Models;

    class Solicitacao {
        private static $table = 'solicitacao';

        public static function select(int $id, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    
                        $sql = 'SELECT s.id, s.qtde, s.observacoes, p.nome AS produto, um.unidade_medida, u.nome AS usuario_solicitante, un.nome AS unidade_negocio
                        FROM solicitacao AS s
                        INNER JOIN produto AS p ON s.fk_produto = p.id
                        INNER JOIN unidade_medida AS um ON s.fk_unidade_medida = um.id
                        INNER JOIN usuario AS u ON s.fk_usuario = u.id
                        INNER JOIN unidade_negocio AS un ON s.fk_unidade_negocio = un.id
                        WHERE s.solicitacao_deletado = 0 AND s.fk_unidade_negocio = :fk_unidade_negocio';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':fk_unidade_negocio', $id);
                        $stmt->execute();
    
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhuma solicitação encontrada!");
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

        public static function selectAll($token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                        $sql = 'SELECT s.id, s.qtde, s.observacoes, p.nome AS produto, um.unidade_medida, u.nome AS usuario_solicitante, un.nome AS unidade_negocio
                        FROM solicitacao AS s
                        INNER JOIN produto AS p ON s.fk_produto = p.id
                        INNER JOIN unidade_medida AS um ON s.fk_unidade_medida = um.id
                        INNER JOIN usuario AS u ON s.fk_usuario = u.id
                        INNER JOIN unidade_negocio AS un ON s.fk_unidade_negocio = un.id
                        WHERE s.solicitacao_deletado = 0';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhuma solicitação encontrada!");
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

        public static function insert($data, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                        $sql = 'INSERT INTO solicitacao (fk_produto, qtde, fk_unidade_medida, observacoes, fk_usuario, fk_unidade_negocio, solicitacao_deletado) VALUES (:fk_produto, :qtde, :fk_unidade_medida, :observacoes, :fk_usuario, :fk_unidade_negocio, :solicitacao_deletado)';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':fk_produto', $data['fk_produto']);
                        $stmt->bindValue(':qtde', $data['qtde']);
                        $stmt->bindValue(':fk_unidade_medida', $data['fk_unidade_medida']);
                        if($data['observacoes'] == ''){
                            $observacoes = NULL;
                        } else {
                            $observacoes = $data['observacoes'];
                        }
                        $stmt->bindValue(':observacoes', $observacoes);
                        $stmt->bindValue(':fk_usuario', $data['fk_usuario']);
                        $stmt->bindValue(':fk_unidade_negocio', $data['fk_unidade_negocio']);
                        $stmt->bindValue(':solicitacao_deletado', 0);
                        $stmt->execute();
    
                        if ($stmt->rowCount() > 0) {
                            return 'Solicitação inserida com sucesso!';
                        } else {
                            throw new \Exception("Falha ao inserir a Solicitação!");
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

        public static function delete(int $id, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                        $sql = 'UPDATE solicitacao SET solicitacao_deletado = :solicitacao_deletado WHERE id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':solicitacao_deletado', 1);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return 'Solicitação deletada com sucesso!';
                        } else {
                            // throw new \Exception("Falha ao deletar a Solicitação!");
                            return $id;
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