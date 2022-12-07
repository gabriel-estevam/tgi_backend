<?php

namespace App\Models;

class Recebimento
{

    public static function select(int $id, $token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);
            $sql = 'SELECT eame.id, me.qtde_movimentada, me.fk_estoque_unidade_negocio, p.nome, um.unidade_medida
            FROM estoque_almoxarifado_movimentacao_estoque AS eame
            INNER JOIN movimentacao_estoque AS me ON eame.fk_movimentacao_estoque = me.id
            INNER JOIN estoque_almoxarifado AS ea ON eame.fk_estoque_almoxarifado = ea.id
            INNER JOIN produto AS p ON ea.fk_produto = p.id
            INNER JOIN unidade_medida AS um ON p.fk_unidade_medida = um.id
            WHERE me.fk_estoque_unidade_negocio = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum Envio encontrada!");
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

            $sql_busca = 'SELECT eame.id, me.qtde_movimentada, me.fk_estoque_unidade_negocio, p.nome, ea.fk_produto
            FROM estoque_almoxarifado_movimentacao_estoque AS eame
            INNER JOIN movimentacao_estoque AS me ON eame.fk_movimentacao_estoque = me.id
            INNER JOIN estoque_almoxarifado AS ea ON eame.fk_estoque_almoxarifado = ea.id
            INNER JOIN produto AS p ON ea.fk_produto = p.id
            WHERE eame.id = :id';

            $stmt_busca = $connPdo->prepare($sql_busca);
            $stmt_busca->bindValue(':id', $data['id']);
            $stmt_busca->execute();
            $dados = $stmt_busca->fetchAll(\PDO::FETCH_ASSOC);

            $qtde = $dados[0]['qtde_movimentada'];
            $unidade_negocio = $dados[0]['fk_estoque_unidade_negocio'];
            $produto = $dados[0]['fk_produto'];

            $sql_busca_qtde_atual = 'SELECT id, qtde_atual FROM estoque_unidade_negocio WHERE fk_unidade_negocio = :unidade_negocio AND fk_produto = :produto LIMIT 1';
            $stmt_busca_qtde_atual = $connPdo->prepare($sql_busca_qtde_atual);
            $stmt_busca_qtde_atual->bindValue(':unidade_negocio', $unidade_negocio);
            $stmt_busca_qtde_atual->bindValue(':produto', $produto);
            $stmt_busca_qtde_atual->execute();
            $dados_qtde_atual = $stmt_busca_qtde_atual->fetchAll(\PDO::FETCH_ASSOC);

            $qtde_em_estoque = $dados_qtde_atual[0]['qtde_atual'];
            $id_estoque = $dados_qtde_atual[0]['id'];

            $qtde_total = $qtde + $qtde_em_estoque;
            $sql_update = 'UPDATE estoque_unidade_negocio SET qtde_atual = :qtde_atual WHERE id = :id';
            $stmt_update = $connPdo->prepare($sql_update);
            $stmt_update->bindValue(':qtde_atual', $qtde_total);
            $stmt_update->bindValue(':id', $id_estoque);
            $stmt_update->execute();

            $sql = 'DELETE FROM estoque_almoxarifado_movimentacao_estoque WHERE id = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $data['id']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Produto Recebido com sucesso!';
            } else {
                $result[0] = array(
                    "error" => "Falha ao receber os produtos!"
                );
                return $result;
            }
        } else {
            return 'Token inválido!';
        }
    }
}
