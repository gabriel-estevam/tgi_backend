<?php

namespace App\Models;

class Representante {
    private static $table = 'representante';

    public static function select(int $id, $token){

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql = 'SELECT * FROM ' . self::$table . ' WHERE id = :id AND representante_deletado = 0';

                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return $stmt->fetch(\PDO::FETCH_ASSOC);
                    } else {
                        $result[0] = array(
                            "error" => "Nenhum representante encontrado!"
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

    public static function selectAll($token){

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql = 'SELECT * FROM ' . self::$table . ' WHERE representante_deletado = 0';
                    
                    $stmt = $connPdo->prepare($sql);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    } else {
                        $result[0] = array(
                            "error" => "Nenhum representante encontrado!"
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

    public static function insert($data, $token){

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql_consulta_representante = 'SELECT count(id) AS qtde_representante, id FROM ' . self::$table . ' WHERE nome = :nome AND telefone = :telefone AND email = :email AND representante_deletado = 0';
                    $stmt = $connPdo->prepare($sql_consulta_representante);
                    $stmt->bindValue(':nome', $data['nome']);
                    $stmt->bindValue(':telefone', $data['telefone']);
                    $stmt->bindValue(':email', $data['email']);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                    if($rows[0]['qtde_representante'] == 0){
                        //INSERT REPRESENTANTE
                        $sql = 'INSERT INTO ' . self::$table . '(nome, telefone, email, representante_ativo, representante_deletado) VALUES (:nome, :telefone, :email, :representante_ativo, :representante_deletado)';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':nome', $data['nome']);
                        $stmt->bindValue(':telefone', $data['telefone']);
                        $stmt->bindValue(':email', $data['email']);
                        $stmt->bindValue(':representante_ativo', '1');
                        $stmt->bindValue(':representante_deletado', '0');
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            return 'Representante cadastrado com sucesso!';
                        } else {
                            $result[0] = array(
                                "error" => "Falha ao inserir o representante!"
                            );
                            return $result;
                        }
                    } else {
                        return 'Representante já cadastrado!';
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

    public static function update($id, $data, $token)
    {

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql_consulta_representante = 'SELECT count(id) AS qtde_representante, id FROM ' . self::$table . ' WHERE nome = :nome AND telefone = :telefone AND email = :email AND representante_deletado = 0';
                    $stmt = $connPdo->prepare($sql_consulta_representante);
                    $stmt->bindValue(':nome', $data['nome']);
                    $stmt->bindValue(':telefone', $data['telefone']);
                    $stmt->bindValue(':email', $data['email']);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                    if($rows[0]['qtde_representante'] == 0){

                        $sql = 'UPDATE ' . self::$table . ' SET nome = :nome, telefone = :telefone, email = :email, representante_ativo = :representante_ativo, representante_deletado = :representante_deletado WHERE id = :id';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':nome', $data['nome']);
                        $stmt->bindValue(':telefone', $data['telefone']);
                        $stmt->bindValue(':email', $data['email']);
                        $stmt->bindValue(':representante_deletado', 0);
                        $stmt->bindValue(':representante_ativo', $data['representante_ativo']);
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            return 'Representante atualizado com sucesso!';
                        } else {
                            $result[0] = array(
                                "error" => "Falha ao atualizar o representante!"
                            );
                            return $result;
                        }
                    } else {
                        return 'Representante já cadastrado!';
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

    public static function delete(int $id, $token)
    {

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql = 'UPDATE ' . self::$table . ' SET representante_deletado = :representante_deletado, representante_ativo = :representante_ativo WHERE id = :id';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':representante_deletado', 1);
                    $stmt->bindValue(':representante_ativo', 0);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return 'Representante deletado com sucesso!';
                    } else {
                        $result[0] = array(
                            "error" => "Falha ao deletar o representante!"
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
