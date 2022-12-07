<?php

namespace App\Models;

class Estoque
{
    private static $table = 'estoque_unidade_negocio';

    public static function select(int $id, $token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql = 'SELECT eun.id, 
                           eun.qtde_minima, 
                           eun.qtde_ideal, 
                           eun.qtde_atual, 
                           eun.qtde_maxima, 
                           eun.dt_entrada,
                           un.id as id_unidade_negocio,
                           un.nome as nome_unidade, 
                           p.id as id_produto,
                           p.nome as nome_produto,
                           p.fk_unidade_medida as fk_unidade_medida,
                           unm.unidade_medida as unidade_medida
                    FROM estoque_unidade_negocio as eun
                    INNER JOIN unidade_negocio as un ON eun.fk_unidade_negocio = un.id
                    INNER JOIN produto as p ON eun.fk_produto = p.id
                    INNER JOIN unidade_medida as unm ON unm.id = p.fk_unidade_medida
                    WHERE eun.id = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum estoque encontrado!");
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

            $sql = 'SELECT eun.id, eun.qtde_minima, eun.qtde_ideal, eun.qtde_atual, eun.qtde_maxima, eun.dt_entrada, un.nome as nome_unidade, p.id as id_produto, p.nome as nome_produto, un.id as id_unidade_negocio, p.fk_unidade_medida as fk_unidade_medida, unm.unidade_medida as unidade_medida
                    FROM estoque_unidade_negocio as eun
                    INNER JOIN unidade_negocio as un ON eun.fk_unidade_negocio = un.id
                    INNER JOIN produto as p ON eun.fk_produto = p.id
                    INNER JOIN unidade_medida as unm ON unm.id = p.fk_unidade_medida';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum estoque encontrado!");
            }
        } else {
            return 'Token não enviado!';
        }
    }

    public static function insert($data, $token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql_insert = 'INSERT INTO estoque_unidade_negocio(qtde_minima, qtde_ideal, qtde_atual, qtde_maxima, dt_entrada, fk_unidade_negocio, fk_produto) VALUES (:qtde_minima, :qtde_ideal, :qtde_atual, :qtde_maxima, :dt_entrada, :fk_unidade_negocio, :fk_produto)';
            $stmt_insert = $connPdo->prepare($sql_insert);
            $stmt_insert->bindValue(':qtde_minima', $data['qtde_minima']);
            $stmt_insert->bindValue(':qtde_ideal', $data['qtde_ideal']);
            $stmt_insert->bindValue(':qtde_atual', $data['qtde_atual']);
            $stmt_insert->bindValue(':qtde_maxima', $data['qtde_maxima']);
            $stmt_insert->bindValue(':dt_entrada', $data['dt_entrada']);
            $stmt_insert->bindValue(':fk_unidade_negocio', $data['fk_unidade_negocio']);
            $stmt_insert->bindValue(':fk_produto', $data['fk_produto']);
            $stmt_insert->execute();

            if ($stmt_insert->rowCount() > 0) {
                return 'Estoque inserido com sucesso!';
            } else {
                $result[0] = array(
                    "error" => "Falha ao inserir o estoque!"
                );
                return $result;
            }
        } else {
            return 'Token não enviado!';
        }
    }

    public static function update($id, $data, $token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql = 'UPDATE estoque_unidade_negocio SET qtde_minima = :qtde_minima, qtde_ideal = :qtde_ideal, qtde_atual = :qtde_atual, qtde_maxima = :qtde_maxima, dt_entrada = :dt_entrada, fk_unidade_negocio = :fk_unidade_negocio, fk_produto = :fk_produto WHERE id = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':qtde_minima', $data['qtde_minima']);
            $stmt->bindValue(':qtde_ideal', $data['qtde_ideal']);
            $stmt->bindValue(':qtde_atual', $data['qtde_atual']);
            $stmt->bindValue(':qtde_maxima', $data['qtde_maxima']);
            $stmt->bindValue(':dt_entrada', $data['dt_entrada']);
            $stmt->bindValue(':fk_unidade_negocio', $data['fk_unidade_negocio']);
            $stmt->bindValue(':fk_produto', $data['fk_produto']);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Estoque atualizado com sucesso!';
            } else {
                $result[0] = array(
                    "error" => "Falha ao atualizar o Estoque!"
                );
                return $result;
            }
        } else {
            return 'Token não enviado!';
        }
    }
}
