<?php
    namespace App\Models;

    class Usuario {
        private static $table = 'usuario';

        public static function select(int $id, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    
                        $sql = 'SELECT * FROM '.self::$table.' WHERE id = :id AND usuario_deletado = 0';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
    
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetch(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhum usuário encontrado!");
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

                        $sql = 'SELECT USU.*,  UN.NOME AS unidade_negocio FROM '.self::$table.' USU INNER JOIN UNIDADE_NEGOCIO UN ON fk_unidade_negocio = UN.id WHERE usuario_deletado = 0';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhum usuário encontrado!");
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

                        $sql_consulta_login = 'SELECT count(id) AS qtde_usuarios FROM '.self::$table.' WHERE login = :login AND usuario_deletado = 0';
                        $stmt = $connPdo->prepare($sql_consulta_login);
                        $stmt->bindValue(':login', $data['login']);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        if($rows[0]['qtde_usuarios'] == 0){
                            $sql = 'INSERT INTO '.self::$table.' (nome, login, senha, nivel_acesso, usuario_ativo, usuario_deletado, fk_unidade_negocio) VALUES (:nome, :login, :senha, :nivel_acesso, :usuario_ativo, :usuario_deletado, :fk_unidade_negocio)';
                            $stmt = $connPdo->prepare($sql);
                            $stmt->bindValue(':nome', $data['nome']);
                            $stmt->bindValue(':login', $data['login']);
                            $stmt->bindValue(':senha', MD5($data['senha']));
                            $stmt->bindValue(':nivel_acesso', $data['nivel_acesso']);
                            $stmt->bindValue(':usuario_ativo', $data['usuario_ativo']);
                            $stmt->bindValue(':usuario_deletado', '0');
                            if($data['fk_unidade_negocio'] == ''){
                                $fk_unidade_negocio = NULL;
                            } else {
                                $fk_unidade_negocio = $data['fk_unidade_negocio'];
                            }
                            $stmt->bindValue(':fk_unidade_negocio', $fk_unidade_negocio);
                            $stmt->execute();
    
                            if ($stmt->rowCount() > 0) {
                                return 'Usuário(a) inserido com sucesso!';
                            } else {
                                throw new \Exception("Falha ao inserir usuário(a)!");
                            }
                        } else {
                            return 'Usuário Já existente!';
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
                        
                        $sql_consulta_login = 'SELECT count(id) AS qtde_usuarios FROM '.self::$table.' WHERE login = :login AND id <> :id AND usuario_deletado = 0';
                        $stmt = $connPdo->prepare($sql_consulta_login);
                        $stmt->bindValue(':login', $data['login']);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        if($rows[0]['qtde_usuarios'] == 0){

                            $sql = 'UPDATE '.self::$table.' SET nome = :nome, login = :login, senha = :senha, nivel_acesso = :nivel_acesso, usuario_ativo = :usuario_ativo, fk_unidade_negocio = :fk_unidade_negocio WHERE id = :id';
                            $stmt = $connPdo->prepare($sql);
                            $stmt->bindValue(':nome', $data['nome']);
                            $stmt->bindValue(':login', $data['login']);
                            $stmt->bindValue(':senha', MD5($data['senha']));
                            $stmt->bindValue(':nivel_acesso', $data['nivel_acesso']);
                            $stmt->bindValue(':usuario_ativo', $data['usuario_ativo']);
                            $stmt->bindValue(':id', $id);
                            if($data['fk_unidade_negocio'] == ''){
                                $fk_unidade_negocio = NULL;
                            } else {
                                $fk_unidade_negocio = $data['fk_unidade_negocio'];
                            }
                            $stmt->bindValue(':fk_unidade_negocio', $fk_unidade_negocio);
                            $stmt->execute();
                
                            if ($stmt->rowCount() > 0) {
                                return 'Usuário(a) atualizado com sucesso!';
                            } else {
                                throw new \Exception("Falha ao atualizar o usuário(a)!");
                            }
                        } else {
                            return 'Usuário Já existente!';
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

                        $sql = 'UPDATE '.self::$table. ' SET usuario_ativo = :usuario_ativo, usuario_deletado = :usuario_deletado WHERE id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':usuario_ativo', 0);
                        $stmt->bindValue(':usuario_deletado', 1);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return 'Usuário(a) deletado com sucesso!';
                        } else {
                            throw new \Exception("Falha ao deletar o(a) usuário(a)!");
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