<?php
    namespace App\Models;

    class Produto {
        private static $table = 'produto';

        public static function select(int $id, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
                        $sql = 'SELECT p.id, p.nome, p.produto_ativo, p.produto_deletado, u.unidade_medida 
                        FROM produto as p 
                        INNER JOIN unidade_medida as u ON p.fk_unidade_medida = u.id
                        WHERE p.produto_deletado = 0 AND p.id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
    
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetch(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhum produto encontrado!");
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

                        $sql = 'SELECT p.id, p.nome, p.produto_ativo, p.produto_deletado, u.unidade_medida 
                        FROM produto as p 
                        INNER JOIN unidade_medida as u ON p.fk_unidade_medida = u.id
                        WHERE p.produto_deletado = 0';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhum produto encontrado!");
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

                        $sql_consulta_produto = 'SELECT count(id) AS qtde_produtos FROM '.self::$table.' WHERE nome=:nome AND produto_deletado = 0';
                        $stmt = $connPdo->prepare($sql_consulta_produto);
                        $stmt->bindValue(':nome', $data['nome']);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        if($rows[0]['qtde_produtos'] == 0){
                            $sql = 'INSERT INTO '.self::$table.' (nome, produto_ativo, produto_deletado, fk_unidade_medida) VALUES (:nome, :produto_ativo, :produto_deletado, :fk_unidade_medida)';
                            $stmt = $connPdo->prepare($sql);
                            $stmt->bindValue(':nome', $data['nome']);
                            $stmt->bindValue(':produto_ativo', $data['produto_ativo']);
                            $stmt->bindValue(':produto_deletado', '0');
                            if($data['fk_unidade_medida'] == ''){
                                $fk_unidade_medida = NULL;
                            } else {
                                $fk_unidade_medida = $data['fk_unidade_medida'];
                            }
                            $stmt->bindValue(':fk_unidade_medida', $fk_unidade_medida);
                            $stmt->execute();
    
                            if ($stmt->rowCount() > 0) {
                                return 'produto(a) inserido com sucesso!';
                            } else {
                                throw new \Exception("Falha ao inserir produto(a)!");
                            }
                        } else {
                            return 'produto Já existente!';
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

        public static function update($id, $data, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                        $sql_consulta_produto = 'SELECT count(id) AS qtde_produtos FROM '.self::$table.' WHERE nome=:nome AND produto_deletado = 0 AND produto_ativo=:produto_ativo';
                        $stmt = $connPdo->prepare($sql_consulta_produto);
                        $stmt->bindValue(':nome', $data['nome']);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        if($rows[0]['qtde_produtos'] == 0){

                            $sql = 'UPDATE '.self::$table.' SET nome = :nome, produto_ativo = :produto_ativo, fk_unidade_medida = :fk_unidade_medida WHERE id = :id';
                            $stmt = $connPdo->prepare($sql);
                            $stmt->bindValue(':nome', $data['nome']);
                            $stmt->bindValue(':produto_ativo', $data['produto_ativo']);
                            $stmt->bindValue(':id', $id);
                            if($data['fk_unidade_medida'] == ''){
                                $fk_unidade_medida = NULL;
                            } else {
                                $fk_unidade_medida = $data['fk_unidade_medida'];
                            }
                            $stmt->bindValue(':fk_unidade_medida', $fk_unidade_medida);
                            $stmt->execute();
                
                            if ($stmt->rowCount() > 0) {
                                return 'produto(a) atualizado com sucesso!';
                            } else {
                                throw new \Exception("Falha ao atualizar o produto(a)!");
                            }
                        } else {
                            return 'produto Já existente!';
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

                        $sql = 'UPDATE '.self::$table. ' SET produto_ativo = :produto_ativo, produto_deletado = :produto_deletado WHERE id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':produto_ativo', 0);
                        $stmt->bindValue(':produto_deletado', 1);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return 'produto(a) deletado com sucesso!';
                        } else {
                            throw new \Exception("Falha ao deletar o(a) produto(a)!");
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