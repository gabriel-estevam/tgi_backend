<?php

namespace App\Models;

class EstoqueAlmoxarifado
{
    private static $table = 'estoque_almoxarifado';

    public static function select(int $id, $token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql = 'SELECT ea.id, 
                           ea.qtde_minima, 
                           ea.qtde_ideal, 
                           ea.qtde_atual, 
                           ea.qtde_maxima, 
                           ea.dt_entrada,
                           ea.estoque_almoxarifado_ativo, 
                           p.id as id_produto,
                           p.nome as nome_produto,
                           f.id as id_fornecedor,
                           f.nome as nome_fornecedor, 
                           u.nome as nome_usuario
                    FROM estoque_almoxarifado AS ea
                    INNER JOIN produto as p ON ea.fk_produto = p.id
                    INNER JOIN fornecedor as f ON ea.fk_fornecedor = f.id
                    INNER JOIN usuario as u ON ea.fk_usuario_entrada = u.id
                    WHERE ea.id = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
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

    public static function selectAll($token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql = 'SELECT ea.id, ea.qtde_minima, ea.qtde_ideal, ea.qtde_atual, ea.qtde_maxima, ea.dt_entrada, ea.estoque_almoxarifado_ativo, p.id as id_produto, p.nome as nome_produto, f.nome as nome_fornecedor, u.nome as nome_usuario
                    FROM estoque_almoxarifado AS ea
                    INNER JOIN produto as p ON ea.fk_produto = p.id
                    INNER JOIN fornecedor as f ON ea.fk_fornecedor = f.id
                    INNER JOIN usuario as u ON ea.fk_usuario_entrada = u.id
                    ';
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

    public static function insert($data, $token)
    {

        $jwt = new JWT('21jdj0-bc1qzk3kxhdxnzkpdgdn9ueg34y08smxgfv0hxvcu3');

        if (!empty($token)) {
            $connPdo = new \PDO(DBDRIVE . ': host=' . DBHOST . '; dbname=' . DBNAME, DBUSER, DBPASS);

            $sql_insert = 'INSERT INTO estoque_almoxarifado(qtde_minima, qtde_ideal, qtde_atual, qtde_maxima, dt_entrada, estoque_almoxarifado_ativo, fk_produto, fk_fornecedor, fk_usuario_entrada) VALUES (:qtde_minima, :qtde_ideal, :qtde_atual, :qtde_maxima, :dt_entrada, :estoque_almoxarifado_ativo, :fk_produto, :fk_fornecedor, :fk_usuario_entrada)';
            $stmt_insert = $connPdo->prepare($sql_insert);
            $stmt_insert->bindValue(':qtde_minima', $data['qtde_minima']);
            $stmt_insert->bindValue(':qtde_ideal', $data['qtde_ideal']);
            $stmt_insert->bindValue(':qtde_atual', $data['qtde_atual']);
            $stmt_insert->bindValue(':qtde_maxima', $data['qtde_maxima']);
            $stmt_insert->bindValue(':dt_entrada', $data['dt_entrada']);
            $stmt_insert->bindValue(':estoque_almoxarifado_ativo', $data['estoque_almoxarifado_ativo']);
            $stmt_insert->bindValue(':fk_produto', $data['fk_produto']);
            $stmt_insert->bindValue(':fk_fornecedor', $data['fk_fornecedor']);
            $stmt_insert->bindValue(':fk_usuario_entrada', $data['fk_usuario_entrada']);
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

            $sql = 'UPDATE estoque_almoxarifado SET qtde_minima = :qtde_minima, qtde_ideal = :qtde_ideal, qtde_atual = :qtde_atual, qtde_maxima = :qtde_maxima, estoque_almoxarifado_ativo = :estoque_almoxarifado_ativo, fk_produto = :fk_produto, fk_fornecedor = :fk_fornecedor, fk_usuario_entrada = :fk_usuario_entrada WHERE id = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':qtde_minima', $data['qtde_minima']);
            $stmt->bindValue(':qtde_ideal', $data['qtde_ideal']);
            $stmt->bindValue(':qtde_atual', $data['qtde_atual']);
            $stmt->bindValue(':qtde_maxima', $data['qtde_maxima']);
            $stmt->bindValue(':estoque_almoxarifado_ativo', $data['estoque_almoxarifado_ativo']);
            $stmt->bindValue(':fk_produto', $data['fk_produto']);
            $stmt->bindValue(':fk_fornecedor', $data['fk_fornecedor']);
            $stmt->bindValue(':fk_usuario_entrada', $data['fk_usuario_entrada']);
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
