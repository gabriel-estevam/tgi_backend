<?php

namespace App\Models;

class MovimentaEstoqueAlmoxarifado
{

    public static function insert($data, $token)
    {

        $jwt = new JWT('21jdj0-10i343901ujdsakpdask9131j2ommasda');

        if (!empty($token)) {
            $info = $jwt->validate($token);
            $data_json = json_decode(json_encode($info), true);
            date_default_timezone_set('America/Sao_Paulo');

            if ($data_json['expiracao_token'] > date('Y-m-d H:i:s')) {
                if ($info) {
                    $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

                    $sql_busca_qtde = 'SELECT qtde_atual FROM estoque_almoxarifado WHERE fk_produto = :fk_produto';
                    $stmt_busca_qtde = $connPdo->prepare($sql_busca_qtde);
                    $stmt_busca_qtde->bindValue(':fk_produto', $data['fk_produto']);
                    $stmt_busca_qtde->execute();
                    $dados = $stmt_busca_qtde->fetchAll(\PDO::FETCH_ASSOC);

                    if($data['qtde_movimentada'] <= $dados[0]['qtde_atual']){
                        $qtde_atualizada = $dados[0]['qtde_atual'] - $data['qtde_movimentada'];
                        $sql_update_qtde_almoxarifado = 'UPDATE estoque_almoxarifado SET qtde_atual = :qtde_atual WHERE id = :id';
                        $stmt_update_qtde_almoxarifado = $connPdo->prepare($sql_update_qtde_almoxarifado);
                        $stmt_update_qtde_almoxarifado->bindValue(':qtde_atual', $qtde_atualizada);
                        $stmt_update_qtde_almoxarifado->bindValue(':id', $data['fk_estoque_almoxarifado']);
                        $stmt_update_qtde_almoxarifado->execute();

                        $sql_insert = 'INSERT INTO movimentacao_estoque(dt_movimentacao, qtde_movimentada, tipo_movimentacao, fk_usuario, fk_estoque_unidade_negocio, fk_estoque_almoxarifado) VALUES (:dt_movimentacao, :qtde_movimentada, :tipo_movimentacao, :fk_usuario, :fk_estoque_unidade_negocio, :fk_estoque_almoxarifado)';
                        $stmt_insert = $connPdo->prepare($sql_insert);
                        $stmt_insert->bindValue(':dt_movimentacao', $data['dt_movimentacao']);
                        $stmt_insert->bindValue(':qtde_movimentada', $data['qtde_movimentada']);
                        $stmt_insert->bindValue(':tipo_movimentacao', $data['tipo_movimentacao']);
                        $stmt_insert->bindValue(':fk_usuario', $data['fk_usuario']);
                        $stmt_insert->bindValue(':fk_estoque_unidade_negocio', $data['fk_estoque_unidade_negocio']);
                        $stmt_insert->bindValue(':fk_estoque_almoxarifado', $data['fk_estoque_almoxarifado']);
                        $stmt_insert->execute();

                        $last_insert_id = $connPdo->lastInsertId();

                        $sql = 'INSERT INTO estoque_almoxarifado_movimentacao_estoque (fk_estoque_almoxarifado, fk_movimentacao_estoque) VALUES (:fk_estoque_almoxarifado, :fk_movimentacao_estoque)';
                        $stmt = $connPdo->prepare($sql);
                        $stmt->bindValue(':fk_estoque_almoxarifado', $data['fk_estoque_almoxarifado']);
                        $stmt->bindValue(':fk_movimentacao_estoque', $last_insert_id);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            return 'Movimentação criada com sucesso!';
                        } else {
                            $result[0] = array(
                                "error" => "Falha ao inserir a unidade de negócio!"
                            );
                            return $result;
                        }
                    } else {
                        $result[0] = array("error" => "Não há a quantidade desejada!");
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

    public static function selectAll($token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql = 'SELECT p.id AS id_produto,
                           p.nome AS nome_produto,
                           um.id AS id_unidade_medida,
                           um.unidade_medida AS unidade_medida,
                           mov.id AS id_movimentacao,
                           mov.qtde_movimentada AS qtde_movimentada,
                           mov.dt_movimentacao AS dt_movimentacao,
                           mov.tipo_movimentacao AS tipo_movimentacao,
                           u.id AS id_usuario,
                           u.nome AS nome_usuario,
                           un.id AS id_unidade_negocio,
                           un.nome AS unidade_negocio
                    FROM movimentacao_estoque mov
                    INNER JOIN estoque_almoxarifado est ON mov.fk_estoque_almoxarifado = est.id
                    INNER JOIN produto p ON est.fk_produto = p.id
                    INNER JOIN unidade_medida um ON p.fk_unidade_medida = um.id
                    INNER JOIN unidade_negocio un ON mov.fk_estoque_unidade_negocio = un.id
                    INNER JOIN usuario u ON mov.fk_usuario = u.id';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum produto encontrado!");
            }
        } else {
            return 'Token não enviado!';
        }
    }
}
