<?php
    namespace App\Models;

    class Fornecedor {
        private static $table = 'fornecedor';

        public static function select(int $id, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    
                        $sql = 'SELECT f.id as id_fornecedor, 
                        f.nome as nome_fornecedor, 
                        f.fornecedor_ativo, 
                        f.fornecedor_deletado, 
                        f.telefone as telefone_fornecedor,
                        r.id as id_representante,
                        r.nome as nome_representante, 
                        r.telefone as telefone_representante, 
                        r.email as email_representante, 
                        e.id as id_endereco, 
                        e.logradouro, 
                        e.cep, e.numero, 
                        e.complemento, 
                        e.bairro, 
                        e.cidade, 
                        e.estado, 
                        e.endereco_ativo, 
                        e.endereco_deletado
                        FROM fornecedor as f
                        INNER JOIN representante as r ON f.fk_representante = r.id
                        INNER JOIN endereco as e ON f.fk_endereco = e.id
                        WHERE f.fornecedor_deletado = 0 AND f.id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
    
                        if ($stmt->rowCount() > 0) {
                            return $stmt->fetch(\PDO::FETCH_ASSOC);
                        } else {
                            throw new \Exception("Nenhum fornecedor encontrado!");
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

                        $sql = 'SELECT f.id as id_fornecedor, f.nome as nome_fornecedor, f.fornecedor_ativo, f.fornecedor_deletado, f.telefone as telefone_fornecedor, r.nome as nome_representante, r.telefone as telefone_representante, r.email as email_representante, e.id as id_endereco, e.logradouro, e.cep, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.endereco_ativo, e.endereco_deletado
                        FROM fornecedor as f
                        INNER JOIN representante as r ON f.fk_representante = r.id
                        INNER JOIN endereco as e ON f.fk_endereco = e.id
                        WHERE f.fornecedor_deletado = 0';
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

                        $sql_consulta_endereco = 'SELECT count(id) AS qtde_endereco, id FROM endereco WHERE cep = :cep AND numero = :numero AND complemento = :complemento AND endereco_deletado = 0';
                        $stmt = $connPdo->prepare($sql_consulta_endereco);
                        $stmt->bindValue(':cep', $data['cep']);
                        $stmt->bindValue(':numero', $data['numero']);
                        $stmt->bindValue(':complemento', $data['complemento']);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                        if($rows[0]['qtde_endereco'] == 0){
                            //INSERT ENDEREÇO
                            $sql_insert = 'INSERT INTO endereco(logradouro, cep, numero, complemento, bairro, cidade, estado, endereco_ativo, endereco_deletado) VALUES (:logradouro, :cep, :numero, :complemento, :bairro, :cidade, :estado, :endereco_ativo, :endereco_deletado)';
                            $stmt_insert = $connPdo->prepare($sql_insert);
                            $stmt_insert->bindValue(':logradouro', $data['logradouro']);
                            $stmt_insert->bindValue(':cep', $data['cep']);
                            $stmt_insert->bindValue(':numero', $data['numero']);
                            $stmt_insert->bindValue(':complemento', $data['complemento']);
                            $stmt_insert->bindValue(':bairro', $data['bairro']);
                            $stmt_insert->bindValue(':cidade', $data['cidade']);
                            $stmt_insert->bindValue(':estado', $data['estado']);
                            $stmt_insert->bindValue(':endereco_ativo', 1);
                            $stmt_insert->bindValue(':endereco_deletado', 0);
                            $stmt_insert->execute();

                            $sql_consulta_endereco_cadastro = 'SELECT count(id) AS qtde_endereco, id FROM endereco WHERE cep = :cep AND numero = :numero AND complemento = :complemento AND endereco_deletado = 0';
                            $stmt_consulta_endereco_cadastro = $connPdo->prepare($sql_consulta_endereco_cadastro);
                            $stmt_consulta_endereco_cadastro->bindValue(':cep', $data['cep']);
                            $stmt_consulta_endereco_cadastro->bindValue(':numero', $data['numero']);
                            $stmt_consulta_endereco_cadastro->bindValue(':complemento', $data['complemento']);
                            $stmt_consulta_endereco_cadastro->execute();
                            $rows_consulta_endereco_cadastro = $stmt_consulta_endereco_cadastro->fetchAll(\PDO::FETCH_ASSOC);
                            $id_endereco = $rows_consulta_endereco_cadastro[0]['id'];
                        } else {
                            $id_endereco = $rows[0]['id'];
                        }

                        //INSERIR FORNECEDOR
                        $sql = 'INSERT INTO ' . self::$table . ' (nome, fornecedor_ativo, fornecedor_deletado, telefone, fk_representante, fk_endereco) VALUES (:nome, :fornecedor_ativo, :fornecedor_deletado, :telefone, :fk_representante, :fk_endereco)';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':nome', $data['nome']);
                        $stmt->bindValue(':fornecedor_ativo', 1);
                        $stmt->bindValue(':fornecedor_deletado', 0);
                        $stmt->bindValue(':telefone', $data['telefone']);
                        $stmt->bindValue(':fk_representante', $data['fk_representante']);
                        $stmt->bindValue(':fk_endereco', $id_endereco);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            return 'Fornecedor inserido com sucesso!';
                        } else {
                            $result[0] = array(
                                "error" => "Falha ao inserir o fornecedor!"
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

        public static function update($id, $data, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                        $sql_endereco = 'UPDATE endereco SET logradouro = :logradouro, cep = :cep, numero = :numero, complemento = :complemento, bairro = :bairro, cidade = :cidade, estado = :estado, endereco_ativo = :endereco_ativo, endereco_deletado = :endereco_deletado WHERE id = :id_endereco';
                        $stmt_endereco = $connPdo->prepare($sql_endereco);
                        $stmt_endereco->bindValue(':logradouro', $data['logradouro']);
                        $stmt_endereco->bindValue(':cep', $data['cep']);
                        $stmt_endereco->bindValue(':numero', $data['numero']);
                        $stmt_endereco->bindValue(':complemento', $data['complemento']);
                        $stmt_endereco->bindValue(':bairro', $data['bairro']);
                        $stmt_endereco->bindValue(':cidade', $data['cidade']);
                        $stmt_endereco->bindValue(':estado', $data['estado']);
                        $stmt_endereco->bindValue(':endereco_ativo', 1);
                        $stmt_endereco->bindValue(':endereco_deletado', 0);
                        $stmt_endereco->bindValue(':id_endereco', $data['fk_endereco']);
                        $stmt_endereco->execute();

                        $sql = 'UPDATE ' . self::$table . ' SET nome = :nome, fornecedor_ativo = :fornecedor_ativo, fornecedor_deletado = :fornecedor_deletado, telefone = :telefone, fk_representante = :fk_representante, fk_endereco = :fk_endereco WHERE id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':nome', $data['nome']);
                        $stmt->bindValue(':fornecedor_ativo', $data['fornecedor_ativo']);
                        $stmt->bindValue(':fornecedor_deletado', 0);
                        $stmt->bindValue(':telefone', $data['telefone']);
                        $stmt->bindValue(':fk_representante', $data['fk_representante']);
                        $stmt->bindValue(':fk_endereco', $data['fk_endereco']);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0 || $stmt_endereco->rowCount() > 0) {
                            return 'Fornecedor atualizado com sucesso!';
                        } else {
                            $result[0] = array(
                                "error" => "Falha ao atualizar o Fornecedor!"
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

        public static function delete(int $id, $token) {

            $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

            if(!empty($token)){        
                $info = $jwt->validate($token);
                $data_json = json_decode(json_encode($info), true);
                date_default_timezone_set('America/Sao_Paulo');

                if($data_json['expiracao_token'] > date('Y-m-d H:i:s')){
                    if ($info){
                        $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                        $sql = 'UPDATE '.self::$table. ' SET fornecedor_ativo = :fornecedor_ativo, fornecedor_deletado = :fornecedor_deletado WHERE id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':fornecedor_ativo', 0);
                        $stmt->bindValue(':fornecedor_deletado', 1);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
            
                        if ($stmt->rowCount() > 0) {
                            return 'Fornecedor deletado com sucesso!';
                        } else {
                            throw new \Exception("Falha ao deletar o fornecedor!");
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