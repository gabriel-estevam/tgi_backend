<?php

namespace App\Models;

class Unidade {
    private static $table = 'unidade_negocio';

    public static function select(int $id, $token){

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql = 'SELECT unidade_negocio.id as id_unidade_negocio, unidade_negocio.nome as nome_unidade_negocio, unidade_negocio.telefone as telefone_unidade_negocio, unidade_negocio.unidade_negocio_ativo as unidade_negocio_ativo, usuario.id as id_usuario, usuario.nome as nome_responsavel, endereco.id as id_endereco, endereco.logradouro as logradouro_endereco, endereco.cep as cep_endereco, endereco.numero as numero_endereco, endereco.complemento as complemento_endereco, endereco.bairro as bairro_endereco, endereco.cidade as cidade_endereco, endereco.estado as estado_endereco
                    FROM unidade_negocio 
                    INNER JOIN usuario ON unidade_negocio.fk_usuario = usuario.id
                    INNER JOIN endereco ON unidade_negocio.fk_endereco = endereco.id
                    WHERE unidade_negocio.id = :id AND unidade_negocio.unidade_negocio_deletado = 0';

                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return $stmt->fetch(\PDO::FETCH_ASSOC);
                    } else {
                        $result[0] = array(
                            "error" => "Nenhuma unidade de negócio encontrada!"
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

                    $sql = 'SELECT unidade_negocio.id as id_unidade_negocio, unidade_negocio.nome as nome_unidade_negocio, unidade_negocio.telefone as telefone_unidade_negocio, unidade_negocio.unidade_negocio_ativo as unidade_negocio_ativo, usuario.id as id_usuario, usuario.nome as nome_responsavel, endereco.id as id_endereco, endereco.logradouro as logradouro_endereco, endereco.cep as cep_endereco, endereco.numero as numero_endereco, endereco.complemento as complemento_endereco, endereco.bairro as bairro_endereco, endereco.cidade as cidade_endereco, endereco.estado as estado_endereco
                    FROM unidade_negocio 
                    INNER JOIN usuario ON unidade_negocio.fk_usuario = usuario.id
                    INNER JOIN endereco ON unidade_negocio.fk_endereco = endereco.id
                    WHERE unidade_negocio.unidade_negocio_deletado = 0';
                    
                    $stmt = $connPdo->prepare($sql);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    } else {
                        $result[0] = array(
                            "error" => "Nenhuma unidade de negócio encontrada!"
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
                        $stmt_insert->bindValue(':endereco_ativo', '1');
                        $stmt_insert->bindValue(':endereco_deletado', '0');
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



                    //INSERIR UNIDADE
                    $sql = 'INSERT INTO ' . self::$table . ' (nome, telefone, unidade_negocio_deletado, unidade_negocio_ativo, fk_usuario, fk_endereco) VALUES (:nome, :telefone, :unidade_negocio_deletado, :unidade_negocio_ativo, :fk_usuario, :fk_endereco)';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':nome', $data['nome']);
                    $stmt->bindValue(':telefone', $data['telefone']);
                    $stmt->bindValue(':unidade_negocio_deletado', '0');
                    $stmt->bindValue(':unidade_negocio_ativo', '1');
                    $stmt->bindValue(':fk_usuario', $data['id_usuario_responsavel']);
                    $stmt->bindValue(':fk_endereco', $id_endereco);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return 'Unidade de negócio inserida com sucesso!';
                    } else {
                        $result[0] = array(
                            "error" => "Falha ao inserir a unidade de negócio!"
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

                    $sql_endereco = 'UPDATE endereco SET logradouro = :logradouro, cep = :cep, numero = :numero, complemento = :complemento, bairro = :bairro, cidade = :cidade, estado = :estado, endereco_ativo = :endereco_ativo, endereco_deletado = :endereco_deletado WHERE id = :id_endereco';
                    $stmt_endereco = $connPdo->prepare($sql_endereco);
                    $stmt_endereco->bindValue(':logradouro', $data['logradouro']);
                    $stmt_endereco->bindValue(':cep', $data['cep']);
                    $stmt_endereco->bindValue(':numero', $data['numero']);
                    $stmt_endereco->bindValue(':complemento', $data['complemento']);
                    $stmt_endereco->bindValue(':bairro', $data['bairro']);
                    $stmt_endereco->bindValue(':cidade', $data['cidade']);
                    $stmt_endereco->bindValue(':estado', $data['estado']);
                    $stmt_endereco->bindValue(':endereco_ativo', '1');
                    $stmt_endereco->bindValue(':endereco_deletado', '0');
                    $stmt_endereco->bindValue(':id_endereco', $data['id_endereco']);
                    $stmt_endereco->execute();

                    $sql = 'UPDATE ' . self::$table . ' SET nome = :nome, telefone = :telefone, unidade_negocio_deletado = :unidade_negocio_deletado, unidade_negocio_ativo = :unidade_negocio_ativo, fk_usuario = :fk_usuario, fk_endereco = :fk_endereco WHERE id = :id';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':nome', $data['nome']);
                    $stmt->bindValue(':telefone', $data['telefone']);
                    $stmt->bindValue(':unidade_negocio_deletado', '0');
                    $stmt->bindValue(':unidade_negocio_ativo', $data['unidade_negocio_ativo']);
                    $stmt->bindValue(':fk_usuario', $data['id_usuario_responsavel']);
                    $stmt->bindValue(':fk_endereco', $data['id_endereco']);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0 || $stmt_endereco->rowCount() > 0) {
                        return 'Unidade de negócio atualizada com sucesso!';
                    } else {
                        $result[0] = array(
                            "error" => "Falha ao atualizar a unidade de negócio!"
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

                    $sql = 'UPDATE ' . self::$table . ' SET unidade_negocio_deletado = :unidade_negocio_deletado, unidade_negocio_ativo = :unidade_negocio_ativo WHERE id = :id';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':unidade_negocio_deletado', 1);
                    $stmt->bindValue(':unidade_negocio_ativo', 0);
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        return 'Unidade de negócio deletado com sucesso!';
                    } else {
                        $result[0] = array(
                            "error" => "Falha ao deletar a unidade de negócio!"
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
