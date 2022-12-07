<?php

namespace App\Models;

class Retirada {

    public static function select(int $id, $token){

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);
            $sql = 'SELECT me.id as movimentacao_estoque_id, me.dt_movimentacao, me.qtde_movimentada, u.nome
            FROM movimentacao_estoque as me
            INNER JOIN usuario as u ON me.fk_usuario = u.id
            WHERE u.fk_unidade_negocio = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhuma Retirada encontrada!");
            }
        } else {
            return 'Token não enviado!';
        }
    }

    public static function insert($data, $token){

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql_busca_qtde = 'SELECT qtde_atual FROM estoque_unidade_negocio WHERE fk_produto = :fk_produto AND fk_unidade_negocio = :fk_unidade_negocio';
            $stmt_busca_qtde = $connPdo->prepare($sql_busca_qtde);
            $stmt_busca_qtde->bindValue(':fk_produto', $data['fk_produto']);
            $stmt_busca_qtde->bindValue(':fk_unidade_negocio', $data['fk_unidade_negocio']);
            $stmt_busca_qtde->execute();
            $dados = $stmt_busca_qtde->fetchAll(\PDO::FETCH_ASSOC);

            if ($data['qtde_movimentada'] <= $dados[0]['qtde_atual']) {
                $qtde_atualizada = $dados[0]['qtde_atual'] - $data['qtde_movimentada'];
                $sql_update_qtde = 'UPDATE estoque_unidade_negocio SET qtde_atual = :qtde_atual WHERE id = :id';
                $stmt_update_qtde = $connPdo->prepare($sql_update_qtde);
                $stmt_update_qtde->bindValue(':qtde_atual', $qtde_atualizada);
                $stmt_update_qtde->bindValue(':id', $data['id_estoque_unidade_negocio']);
                $stmt_update_qtde->execute();

                $sql_insert = 'INSERT INTO movimentacao_estoque(dt_movimentacao, qtde_movimentada, tipo_movimentacao, fk_usuario, fk_estoque_unidade_negocio) VALUES (:dt_movimentacao, :qtde_movimentada, :tipo_movimentacao, :fk_usuario, :fk_estoque_unidade_negocio)';
                $stmt_insert = $connPdo->prepare($sql_insert);
                $stmt_insert->bindValue(':dt_movimentacao', $data['dt_movimentacao']);
                $stmt_insert->bindValue(':qtde_movimentada', $data['qtde_movimentada']);
                $stmt_insert->bindValue(':tipo_movimentacao', 2);
                $stmt_insert->bindValue(':fk_usuario', $data['fk_usuario']);
                $stmt_insert->bindValue(':fk_estoque_unidade_negocio', $data['fk_unidade_negocio']);
                $stmt_insert->execute();

                $last_insert_id = $connPdo->lastInsertId();

                $sql = 'INSERT INTO estoque_unidade_negocio_movimentacao_estoque (fk_movimentacao_estoque, fk_estoque_unidade_negocio) VALUES (:fk_movimentacao_estoque, :fk_estoque_unidade_negocio)';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':fk_movimentacao_estoque', $last_insert_id);
                $stmt->bindValue(':fk_estoque_unidade_negocio', $data['fk_unidade_negocio']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return 'Produto Retirado com sucesso!';
                } else {
                    $result[0] = array(
                        "error" => "Falha ao inserir a unidade de negócio!"
                    );
                    return $result;
                }
            } else {
                $result[0] = array("error" => "Nao ha a quantidade desejada!");
                return $result;
            }
        } else {
            return 'Token inválido!';
        }
    }
}
