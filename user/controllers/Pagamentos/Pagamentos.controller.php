<?php

require_once MODELS . '/Pagamentos/Pagamentos.class.php';
require_once MODELS . '/Planos/Planos.class.php';
require_once MODELS . '/Moip/order.php';
require_once MODELS . '/Secure/Secure.class.php';
// require_once HELPERS . '/PlanosHelper.class.php';

class PagamentosController extends Pagamentos {

    public function __construct() {
        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();
        $this->data_atual = date('Y-m-d H:i:s');

        $this->req = $_REQUEST;
    }


    public function pagar() {

        $this->secure->tokens_secure($this->input->token);

        $moip = new MoipPayment();
        $pagamentosOBJ = new Pagamentos();

        if($this->input->forma_pagamento == 1) { // PAGAMENTO CARTÃO

                $moip->order(
                        $this->input->id_plano,
                        $this->input->id_user,
                        $id_fornecedor=1,
                        $this->input->card_name,
                        $this->input->card_cpf,
                        $this->input->card_cep,
                        $this->input->card_estado,
                        $this->input->card_cidade,
                        $this->input->card_endereco,
                        $this->input->card_bairro,
                        $this->input->card_numero,
                        $this->input->card_complemento,
                        $this->input->card_celular,
                        $this->input->card_nascimento,
                        $this->input->hash_card,
                        moneySQL($this->input->valor),
                        $this->input->id_endereco
                    );

                    if($moip->payment_id !="") {

                        $pagamentosOBJ->save(
                            $this->input->id_plano,
                            $this->input->id_user,
                            $this->input->forma_pagamento,
                            dataUS($this->data_atual),
                            $moip->payment_id,
                            moneySQL($this->input->valor),
                            $this->input->parcelas,
                            $link_boleto="",
                            $moip->payment_status
                        );

                        $success = array(
                            "status" => "01",
                            "msg" => "Pagamento efetuado com sucesso, aguarde a aprovação de deus dados."

                        );

                        echo json_encode(array($success));
                    }
                    else {
                            $error = array(
                                "status" => "02",
                                "msg" => "Não foi possível efetuar o pagamento, verifique seu cartão e tente novamente."
                            );

                            echo json_encode(array($error));
                    }
        }
        else {

                    $data = date('Y-m-d');
                    $data_expiracao = date('Y-m-d', strtotime("+2 days",strtotime($data)));

                    $moip->Boleto(
                        $this->input->id_plano,
                        $this->input->id_user,
                        moneySQL($this->input->valor),
                        $data_expiracao,
                        $this->input->card_name,
                        $this->input->card_cpf,
                        $this->input->card_cep,
                        $this->input->card_estado,
                        $this->input->card_cidade,
                        $this->input->card_endereco,
                        $this->input->card_bairro,
                        $this->input->card_numero,
                        $this->input->card_complemento
                    );

                    if($moip->payment_id !="") {

                        $pagamentosOBJ->save(
                            $this->input->id_plano,
                            $this->input->id_user,
                            $this->input->forma_pagamento,
                            dataUS($this->data_atual),
                            $moip->payment_id,
                            moneySQL($this->input->valor),
                            $this->input->parcelas,
                            $moip->link_boleto,
                            $moip->payment_status
                        );

                        $success = array(
                            "status" => "01",
                            "msg" => "Pagamento efetuado, não esqueça de pagar o boleto para concluir sua ativação do plano.",
                            "link_boleto" => $moip->link_boleto
                        );

                        echo json_encode(array($success));

                    }
                    else {
                        $error = array(
                            "status" => "02",
                            "msg" => "Não foi possível gerar o boleto, verifique seus dados e tente novamente."
                        );

                        echo json_encode(array($error));
                    }

        }
    }

    public function listpagamentos($id_user){
        
        $this->secure->tokens_secure($this->input->token);

        $pagamentosOBJ = new Pagamentos();

        $lista_pagamentos = $pagamentosOBJ->listAll($id_user);

        jsonReturn($lista_pagamentos);
    }
}
