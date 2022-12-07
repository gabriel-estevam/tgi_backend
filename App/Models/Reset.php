<?php
    namespace App\Models;

    class Reset {
        private static $table = 'usuario';

        public static function select($data) {
            $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            $login = $data['login'];

            $sql = 'SELECT login, nome FROM '.self::$table.' WHERE login = :login AND usuario_deletado = 0';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $mailDestino = $rows[0]['login'];
                $nome = $rows[0]['nome'];
                $assunto = 'Redefinição de Senha';

                date_default_timezone_set('America/Sao_Paulo');
                $data_envio = date('d-m-Y');

                $nova_senha = rand();

                $html = "<html>";
                $html .= "<body style='background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;'>";
                $html .= "<table style='max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;'>";
                $html .= "<thead>";
                $html .= "<tr>";
                $html .= "<th style='text-align:left;'><img style='max-width: 150px;' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQQ7TT07vNqW3mOUvDWdABWdquqRJntyNckFrrZuMZe_wxCfNPUG135gB7ZfzUXjBCzaB4&usqp=CAU' alt='Logo Prefeitura'></th>";
                $html .= "<th style='text-align:right;font-weight:400;'>" . $data_envio . "</th>";
                $html .= "</tr>";
                $html .= "</thead>";
                $html .= "<tbody>";
                $html .= "<tr>";
                $html .= "<td style='height:35px;'></td>";
                $html .= "</tr>";
                $html .= "<br>";
                $html .= "<tr>";
                $html .= "<td colspan='2' style='border: solid 1px #ddd; padding:10px 20px;'>";
                $html .= "<p style='font-size:14px;margin:0 0 6px 0;'><span style='font-weight:bold;display:inline-block;min-width:150px'>Nova Senha:</span>" . $nova_senha . "</p>";
                $html .= "</td>";
                $html .= "</tr>";
                $html .= "</tbody>";
                $html .= "<tfooter>";
                $html .= "<tr>";
                $html .= "<td colspan='2' style='font-size:14px;padding:50px 15px 0 15px;'>";
                $html .= "<strong style='display:block;margin:0 0 10px 0;'>Obrigado</strong> Sistema de Controle de Estoque<br> Prefeitura de Salto<br><br>";
                $html .= "<b>Telefone:</b> (11) 96842-6955";
                $html .= "<br>";
                $html .= "<b>Endereço:</b> Paço Municipal - Abadia de São Norberto Av. Tranquillo Gianinni, 861 - Dist. Ind. Santos Dumont, Cep: 13329-600, Salto/SP";
                $html .= "</td>";
                $html .= "</tr>";
                $html .= "</tfooter>";
                $html .= "</table>";
                $html .= "</body>";
                $html .= "</html>";

                $sql = 'UPDATE '.self::$table.' SET senha = :senha WHERE login = :login';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':senha', MD5($nova_senha));
                $stmt->bindValue(':login', $login);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $mail = new \PHPMailer();
                    $mail->IsSMTP(); 
                    $mail->CharSet = 'UTF-8';
                    $mail->True;
                    $mail->Host = "smtp.gmail.com"; // SMTP servers
                    $mail->Port = 587; 
                    $mail->SMTPAuth = true; // Caso o servidor SMTP precise de autenticação
                    $mail->Username = "sistema@millanelo.com.br"; // SMTP username
                    $mail->Password = "Millanelo2022!"; // SMTP password
                    $mail->From = "sistema@millanelo.com.br"; // From
                    $mail->FromName = "Sistema Controle de Estoque" ; // Nome de quem envia o email
                    $mail->AddAddress($mailDestino, $nome); // Email e nome de quem receberá //Responder
                    $mail->WordWrap = 50; // Definir quebra de linha
                    $mail->IsHTML = true ; // Enviar como HTML
                    $mail->Subject = $assunto ; // Assunto
                    $mail->Body = $html; //Corpo da mensagem caso seja HTML
                    $mail->AltBody = "$html" ; //PlainText, para caso quem receber o email não aceite o corpo HTML

                    if(!$mail->Send()){ // Envia o email
                        $result[0] = array(
                            "error" => "Não foi possível enviar o e-mail."
                        );
                        return $result;
                    } else {
                        $result[0] = array(
                            "success" => "Enviado e-mail com as instruções."
                        );
                        return $result;
                    }
                } else {
                    $result[0] = array(
                        "error" => "Não foi possível redefinir a senha."
                    );
                    return $result;
                }

            } else {
                $result[0] = array(
                    "error" => "Usuário incorreto!"
                );
                return $result;
            }
        }

    }