<?php

require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/phpMailer/class.phpmailer.php';

class EnviarEmail extends Conexao {

    public function __construct() {

    }

    public function NovoCadastro($nome, $email, $token) {

      $mail = new PHPMailer();
      $mail->IsMail(true);
      $mail->IsHTML(true);
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $this->email_remetente = EMAIL_REMETENTE;
      $mail->From = EMAIL_REMETENTE; // Seu e-mail
      $mail->FromName = "Novo Cadastro | " . NOME_REMETENTE; // Seu nome
      $this->logo_email = 'https://dragilapp.com.br/views/_images/logo_oficial.png';
      $this->link_token = 'https://dragilapp.com.br/sucesso/' . $token;
      $mail->AddAddress($email); //E-mail Destinatario
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML

      $mail->Subject = "Novo Cadastro - Dr Ágil"; // Assunto da mensagem
      $mail->Body .= "Prezado(a) <strong>" . ucwords($nome) . "</strong>, <br/><br/>";
      $mail->Body .= "Obrigado por se cadastrar em nossa plataforma.<br /><br />";
      $mail->Body .= "Para confirmar seu cadastro, basta clicar no link abaixo.<br />";
      $mail->Body .= $this->link_token  . "<br /><br />";
      $mail->Body .= "Atenciosamente, <br/> ";
      $mail->Body .= "Equipe Dr Ágil, <br/><br/> ";
      $mail->Body .= "<img src='$this->logo_email' width='120'>";

      //envia e-mail
      $mail->Send();

    }

    public function recuperarsenha($nome, $email, $token) {

      $mail = new PHPMailer();
      $mail->IsMail(true);
      $mail->IsHTML(true);
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $this->email_remetente = EMAIL_REMETENTE;
      $mail->From = EMAIL_REMETENTE; // Seu e-mail
      $mail->FromName = "Recuperar Senha | ApsMais"; // Seu nome
      //$this->logo_email = 'https://benditasmaes.com.br/views/_images/logo.png';
      $this->link_recuperar = 'https://apsmais.com.br/recuperarFormSenha/' . $token;
      $mail->AddAddress($email); //E-mail Destinatario
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
      $mail->Subject = "Recuperar Senha " . " | " . NOME_REMETENTE; // Assunto da mensagem
      $mail->Body .= "Prezado(a) <strong>" . ucwords($nome) . "</strong>, <br/><br/>";
      $mail->Body .= "Você solicitou a recuperação de senha da sua conta, clique no link abaixo e redefina sua senha.<br /><br />";
      $mail->Body .= "<a href='$this->link_recuperar'>" . $this->link_recuperar  . "</a>" . "<br /><br />";
      $mail->Body .= "Atenciosamente, <br/> ";
      $mail->Body .= "Equipe ApsMais <br/><br/> ";


      //envia e-mail
      $mail->Send();

    }

    public function PagamentoOnline() {

      $mail = new PHPMailer();
      $mail->IsMail(true);
      $mail->IsHTML(true);
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $this->email_remetente = EMAIL_REMETENTE;
      $mail->From = EMAIL_REMETENTE; // Seu e-mail
      $mail->FromName = "Tele Mercado"; // Seu nome
      $mail->AddAddress("mercadoimperio4001@yahoo.com"); //E-mail Destinatario
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
      $mail->Subject = "Novo Pagamento Online"; // Assunto da mensagem
      $mail->Body .= "Olá, a plataforma Tele Mercado, recebeu um novo pagamento online.<br /><br />";
      $mail->Body .= "Atenciosamente, <br/> ";
      $mail->Body .= "Equipe Tele Mercado <br/><br/> ";

      //envia e-mail
      $mail->Send();

    }


    public function pagamento($id_consulta, $data, $valor, $nome, $email, $tipo_pagamento, $endereco, $bairro, $nome_cidade, $sigla, $cep) {

      if($tipo_pagamento == 1){ $tp_nome = 'Cartão de Crédito'; } else {$tp_nome = 'Dinheiro';}

      $mail = new PHPMailer();
      $mail->IsMail(true);
      $mail->IsHTML(true);
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $this->email_remetente = EMAIL_REMETENTE;
      $mail->From = EMAIL_REMETENTE; // Seu e-mail
      $mail->FromName = "Pagamento de Solicitação | " . NOME_REMETENTE; // Seu nome
      $this->logo_email = 'https://dragilapp.com.br/views/_images/logo_oficial.png';
      $mail->AddAddress($email); //E-mail Destinatario
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
      $mail->Subject = "Pagamento de Solicitação " . " | " . NOME_REMETENTE; // Assunto da mensagem
      $mail->Body = $this->getbody($id_consulta, $data, $valor, $nome, $email, $tp_nome, $endereco, $bairro, $nome_cidade, $sigla, $cep);
      // $mail->Body .= "obrigado por utilizar nossos serviços <strong>". $tipo_pagamento . "</strong>, <br/><br/>";
      // $mail->Body .= "Atenciosamente, <br/> ";
      // $mail->Body .= "Equipe Dr Ágil, <br/><br/> ";


      //envia e-mail

      $mail->Send();

    }


    public function getbody($id_consulta, $data, $valor, $nome, $email, $tipo_pagamento, $endereco, $bairro, $nome_cidade, $sigla, $cep){

         $this->css = " /* CONFIG STYLES Please do not delete and edit CSS styles below */
          /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
          #outlook a {
              padding: 0;
          }

          .color-agil{
              color: #22c1d6;
          }

          .banner{
              position: relative;
              background-image: url(banner.jpg);
              width: 600px;
              height: 300px;
              background-position: center;
              background-size: cover;
          }
          .banner:before{
              position: absolute;
              left: 10px;
              right: 10px;
              top: 10px;
              bottom: 10px;
              content: '';
              border: 5px solid #fff;
              z-index: 1
          }
          .banner:after{
              position: absolute;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              content: '';
              background-image: -moz-linear-gradient(50deg, #155763 0%, #a7c4ca 100%);
              background-image: -webkit-linear-gradient(50deg, #155763 0%, #a7c4ca 100%);
              background-image: -ms-linear-gradient(50deg, #155763 0%, #a7c4ca 100%);
              opacity: .6;
          }

          .banner a{
              z-index: 2;
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              width: 100%;
          }

          .ExternalClass {
              width: 100%;
          }

          .ExternalClass,
          .ExternalClass p,
          .ExternalClass span,
          .ExternalClass font,
          .ExternalClass td,
          .ExternalClass div {
              line-height: 100%;
          }

          .es-button {
              mso-style-priority: 100 !important;
              text-decoration: none !important;
          }

          a[x-apple-data-detectors] {
              color: inherit !important;
              text-decoration: none !important;
              font-size: inherit !important;
              font-family: inherit !important;
              font-weight: inherit !important;
              line-height: inherit !important;
          }

          .es-desk-hidden {
              display: none;
              float: left;
              overflow: hidden;
              width: 0;
              max-height: 0;
              line-height: 0;
              mso-hide: all;
          }

          td .es-button-border:hover a.es-button-1556804085234 {
              background: #7dbf44 !important;
              border-color: #7dbf44 !important;
          }

          td .es-button-border-1556804085253:hover {
              background: #7dbf44 !important;
          }

          .es-button-border:hover a.es-button {
              background: #7dbf44 !important;
              border-color: #7dbf44 !important;
          }

          .es-button-border:hover {
              background: #7dbf44 !important;
              border-color: #7dbf44 #7dbf44 #7dbf44 #7dbf44 !important;
          }

          td .es-button-border:hover a.es-button-1556806949166 {
              background: #7dbf44 !important;
              border-color: #7dbf44 !important;
          }

          td .es-button-border-1556806949166:hover {
              background: #7dbf44 !important;
          }

          /*
          END OF IMPORTANT
          */
          s {
              text-decoration: line-through;
          }

          html,
          body {
              width: 100%;
              font-family: arial, 'helvetica neue', helvetica, sans-serif;
              -webkit-text-size-adjust: 100%;
              -ms-text-size-adjust: 100%;
          }

          table {
              mso-table-lspace: 0pt;
              mso-table-rspace: 0pt;
              border-collapse: collapse;
              border-spacing: 0px;
          }

          table td,
          html,
          body,
          .es-wrapper {
              padding: 0;
              Margin: 0;
          }

          .es-content,
          .es-header,
          .es-footer {
              table-layout: fixed !important;
              width: 100%;
          }

          img {
              display: block;
              border: 0;
              outline: none;
              text-decoration: none;
              -ms-interpolation-mode: bicubic;
          }

          table tr {
              border-collapse: collapse;
          }

          p,
          hr {
              Margin: 0;
          }

          h1,
          h2,
          h3,
          h4,
          h5 {
              Margin: 0;
              line-height: 120%;
              mso-line-height-rule: exactly;
              font-family: arial, 'helvetica neue', helvetica, sans-serif;
          }

          p,
          ul li,
          ol li,
          a {
              -webkit-text-size-adjust: none;
              -ms-text-size-adjust: none;
              mso-line-height-rule: exactly;
          }

          .es-left {
              float: left;
          }

          .es-right {
              float: right;
          }

          .es-p5 {
              padding: 5px;
          }

          .es-p5t {
              padding-top: 5px;
          }

          .es-p5b {
              padding-bottom: 5px;
          }

          .es-p5l {
              padding-left: 5px;
          }

          .es-p5r {
              padding-right: 5px;
          }

          .es-p10 {
              padding: 10px;
          }

          .es-p10t {
              padding-top: 10px;
          }

          .es-p10b {
              padding-bottom: 10px;
          }

          .es-p10l {
              padding-left: 10px;
          }

          .es-p10r {
              padding-right: 10px;
          }

          .es-p15 {
              padding: 15px;
          }

          .es-p15t {
              padding-top: 15px;
          }

          .es-p15b {
              padding-bottom: 15px;
          }

          .es-p15l {
              padding-left: 15px;
          }

          .es-p15r {
              padding-right: 15px;
          }

          .es-p20 {
              padding: 20px;
          }

          .es-p20t {
              padding-top: 20px;
          }

          .es-p20b {
              padding-bottom: 20px;
          }

          .es-p20l {
              padding-left: 20px;
          }

          .es-p20r {
              padding-right: 20px;
          }

          .es-p25 {
              padding: 25px;
          }

          .es-p25t {
              padding-top: 25px;
          }

          .es-p25b {
              padding-bottom: 25px;
          }

          .es-p25l {
              padding-left: 25px;
          }

          .es-p25r {
              padding-right: 25px;
          }

          .es-p30 {
              padding: 30px;
          }

          .es-p30t {
              padding-top: 30px;
          }

          .es-p30b {
              padding-bottom: 30px;
          }

          .es-p30l {
              padding-left: 30px;
          }

          .es-p30r {
              padding-right: 30px;
          }

          .es-p35 {
              padding: 35px;
          }

          .es-p35t {
              padding-top: 35px;
          }

          .es-p35b {
              padding-bottom: 35px;
          }

          .es-p35l {
              padding-left: 35px;
          }

          .es-p35r {
              padding-right: 35px;
          }

          .es-p40 {
              padding: 40px;
          }

          .es-p40t {
              padding-top: 40px;
          }

          .es-p40b {
              padding-bottom: 40px;
          }

          .es-p40l {
              padding-left: 40px;
          }

          .es-p40r {
              padding-right: 40px;
          }

          .es-menu td {
              border: 0;
          }

          .es-menu td a img {
              display: inline-block !important;
          }

          /* END CONFIG STYLES */
          a {
              font-family: arial, 'helvetica neue', helvetica, sans-serif;
              font-size: 14px;
              text-decoration: none;
          }

          h1 {
              font-size: 30px;
              font-style: normal;
              font-weight: normal;
              color: #659c35;
          }

          h1 a {
              font-size: 30px;
          }

          h2 {
              font-size: 26px;
              font-style: normal;
              font-weight: bold;
              color: #659C35;
          }

          h2 a {
              font-size: 26px;
          }

          h3 {
              font-size: 22px;
              font-style: normal;
              font-weight: normal;
              color: #659c35;
          }

          h3 a {
              font-size: 22px;
          }

          p,
          ul li,
          ol li {
              font-size: 14px;
              font-family: arial, 'helvetica neue', helvetica, sans-serif;
              line-height: 150%;
          }

          ul li,
          ol li {
              Margin-bottom: 15px;
          }

          .es-menu td a {
              text-decoration: none;
              display: block;
          }

          .es-wrapper {
              width: 100%;
              height: 100%;
              background-image: ;
              background-repeat: repeat;
              background-position: center top;
          }

          .es-wrapper-color {
              background-color: #f6f6f6;
          }

          .es-content-body {
              background-color: #ffffff;
          }

          .es-content-body p,
          .es-content-body ul li,
          .es-content-body ol li {
              color: #333333;
          }

          .es-content-body a {
              /*color: #659c35;*/
              color: #22c1d6;
          }

          .es-header {
              background-color: transparent;
              background-image: ;
              background-repeat: repeat;
              background-position: center top;
          }

          .es-header-body {
              background-color: #ffffff;
          }

          .es-header-body p,
          .es-header-body ul li,
          .es-header-body ol li {
              color: #659c35;
              font-size: 16px;
          }

          .es-header-body a {
              color: #659C35;
              font-size: 16px;
          }

          .es-footer {
              background-color: transparent;
              background-image: ;
              background-repeat: repeat;
              background-position: center top;
          }

          .es-footer-body {
              background-color: transparent;
          }

          .es-footer-body p,
          .es-footer-body ul li,
          .es-footer-body ol li {
              color: #ffffff;
              font-size: 14px;
          }

          .es-footer-body a {
              color: #ffffff;
              font-size: 14px;
          }

          .es-infoblock,
          .es-infoblock p,
          .es-infoblock ul li,
          .es-infoblock ol li {
              line-height: 120%;
              font-size: 12px;
              color: #cccccc;
          }

          .es-infoblock a {
              font-size: 12px;
              color: #cccccc;
          }

          a.es-button {
              border-style: solid;
              border-color: #659C35;
              border-width: 10px 20px 10px 20px;
              display: inline-block;
              background: #659C35;
              border-radius: 0px;
              font-size: 18px;
              font-family: arial, 'helvetica neue', helvetica, sans-serif;
              font-weight: normal;
              font-style: normal;
              line-height: 120%;
              color: #ffffff;
              text-decoration: none;
              width: auto;
              text-align: center;
          }

          .es-button-border {
              border-style: solid solid solid solid;
              border-color: #659c35 #659c35 #659c35 #659c35;
              background: #659C35;
              border-width: 0px 0px 0px 0px;
              display: inline-block;
              border-radius: 0px;
              width: auto;
          }

          /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don't need responsive layout, please delete this section. */
          @media only screen and (max-width: 600px) {

              p,
              ul li,
              ol li,
              a {
                  font-size: 14px !important;
                  line-height: 150% !important;
              }

              h1 {
                  font-size: 30px !important;
                  text-align: center;
                  line-height: 120% !important;
              }

              h2 {
                  font-size: 22px !important;
                  text-align: center;
                  line-height: 120% !important;
              }

              h3 {
                  font-size: 20px !important;
                  text-align: center;
                  line-height: 120% !important;
              }

              h1 a {
                  font-size: 30px !important;
              }

              h2 a {
                  font-size: 22px !important;
              }

              h3 a {
                  font-size: 20px !important;
              }

              .es-menu td a {
                  font-size: 16px !important;
              }

              .es-header-body p,
              .es-header-body ul li,
              .es-header-body ol li,
              .es-header-body a {
                  font-size: 16px !important;
              }

              .es-footer-body p,
              .es-footer-body ul li,
              .es-footer-body ol li,
              .es-footer-body a {
                  font-size: 14px !important;
              }

              .es-infoblock p,
              .es-infoblock ul li,
              .es-infoblock ol li,
              .es-infoblock a {
                  font-size: 12px !important;
              }

              *[class='gmail-fix'] {
                  display: none !important;
              }

              .es-m-txt-c,
              .es-m-txt-c h1,
              .es-m-txt-c h2,
              .es-m-txt-c h3 {
                  text-align: center !important;
              }

              .es-m-txt-r,
              .es-m-txt-r h1,
              .es-m-txt-r h2,
              .es-m-txt-r h3 {
                  text-align: right !important;
              }

              .es-m-txt-l,
              .es-m-txt-l h1,
              .es-m-txt-l h2,
              .es-m-txt-l h3 {
                  text-align: left !important;
              }

              .es-m-txt-r img,
              .es-m-txt-c img,
              .es-m-txt-l img {
                  display: inline !important;
              }

              .es-button-border {
                  display: block !important;
              }

              a.es-button {
                  font-size: 20px !important;
                  display: block !important;
                  border-left-width: 0px !important;
                  border-right-width: 0px !important;
              }

              .es-btn-fw {
                  border-width: 10px 0px !important;
                  text-align: center !important;
              }

              .es-adaptive table,
              .es-btn-fw,
              .es-btn-fw-brdr,
              .es-left,
              .es-right {
                  width: 100% !important;
              }

              .es-content table,
              .es-header table,
              .es-footer table,
              .es-content,
              .es-footer,
              .es-header {
                  width: 100% !important;
                  max-width: 600px !important;
              }

              .es-adapt-td {
                  display: block !important;
                  width: 100% !important;
              }

              .adapt-img {
                  width: 100% !important;
                  height: auto !important;
              }

              .es-m-p0 {
                  padding: 0px !important;
              }

              .es-m-p0r {
                  padding-right: 0px !important;
              }

              .es-m-p0l {
                  padding-left: 0px !important;
              }

              .es-m-p0t {
                  padding-top: 0px !important;
              }

              .es-m-p0b {
                  padding-bottom: 0 !important;
              }

              .es-m-p20b {
                  padding-bottom: 20px !important;
              }

              .es-mobile-hidden,
              .es-hidden {
                  display: none !important;
              }

              .es-desk-hidden {
                  display: table-row !important;
                  width: auto !important;
                  overflow: visible !important;
                  float: none !important;
                  max-height: inherit !important;
                  line-height: inherit !important;
              }

              .es-desk-menu-hidden {
                  display: table-cell !important;
              }

              table.es-table-not-adapt,
              .esd-block-html table {
                  width: auto !important;
              }

              table.es-social {
                  display: inline-block !important;
              }

              table.es-social td {
                  display: inline-block !important;
              }
          }

          /* END RESPONSIVE STYLES */";


      $this->bodyPgto = '
        <html xmlns="http://www.w3.org/1999/xhtml">

    <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta charset="UTF-8" />
      <meta content="width=device-width, initial-scale=1" name="viewport" />
      <meta name="x-apple-disable-message-reformatting" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta content="telephone=no" name="format-detection" />
      <title></title>

      </head>

    <body style="width: 100%;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;padding: 0;margin: 0">
      <div class="es-wrapper-color" style="background-color: #f6f6f6">
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;padding: 0;margin: 0;width: 100%;height: 100%;background-repeat: repeat;background-position: center top">
          <tbody>
            <tr style="border-collapse: collapse">
              <td class="esd-email-paddings" valign="top" style="padding: 0;margin: 0">
                <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;table-layout: fixed !important;width: 100%;background-color: transparent;background-repeat: repeat;background-position: center top">
                  <tbody>
                    <tr style="border-collapse: collapse">
                      <td class="esd-stripe" align="center" esd-custom-block-id="88656" style="padding: 0;margin: 0">
                        <table class="es-header-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;background-color: #fff">
                          <tbody>
                            <tr style="border-collapse: collapse">
                              <td class="esd-structure es-p20t es-p10b es-p20r es-p20l" style="background-position: center center;padding: 0;margin: 0;padding-bottom: 10px;padding-top: 20px;padding-left: 20px;padding-right: 20px" align="center">
                                  <a target="_blank" href="#" style="-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 16px;text-decoration: none;color: #659C35">
                                    <img src="https://dragilapp.com.br/webservice/usuario/models/phpMailer/logo.png" alt="" style="display: block;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic" width="125" />
                                    <img src="logo.png" alt="" width="125" style="display: block;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic" />
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;table-layout: fixed !important;width: 100%">
                    <tbody>
                      <tr style="border-collapse: collapse">
                        <td class="esd-stripe" align="center" style="padding: 0;margin: 0">
                          <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;background-color: #fff">
                            <tbody>
                              <tr style="border-collapse: collapse">
                                <td class="esd-structure" align="left" style="background-position: center top;padding: 0;margin: 0" esd-custom-block-id="44739">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                    <tbody>
                                      <tr style="border-collapse: collapse">
                                        <td width="600" class="esd-container-frame" align="center" valign="top" style="padding: 0;margin: 0">
                                          <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                            <tbody>
                                              <tr style="border-collapse: collapse">
                                               <td align="center" class="esd-block-banner banner" esdev-config="h2" style="position: relative;background-image: url(https://dragilapp.com.br/webservice/usuario/models/phpMailer/banner1.jpg);width: 600px;height: 300px;background-position: center;background-size: cover;padding: 0;margin: 0">
                                                  <a target="_blank" href="#" style="z-index: 2;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);width: 100%;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;color: #22c1d6">
                                                    <h2 style="margin:20px 0;color:#fff;line-height:120%;font-family:arial,&quot;helvetica neue&quot;,helvetica,sans-serif;font-size:26px;font-style:normal;font-weight:bold;">Obrigado por utilizar o Dr Ágil.</h2>
                                                  </a>

                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;table-layout: fixed !important;width: 100%">
                    <tbody>
                      <tr style="border-collapse: collapse">
                        <td class="esd-stripe" align="center" esd-custom-block-id="44757" style="padding: 0;margin: 0">
                          <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;background-color: #fff">
                            <tbody>
                              <tr style="border-collapse: collapse">
                                <td class="esd-structure es-p20t es-p20r es-p20l" align="left" style="background-position: center top;padding: 0;margin: 0;padding-top: 20px;padding-left: 20px;padding-right: 20px" esd-custom-block-id="44741">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                    <tbody>
                                      <tr style="border-collapse: collapse">
                                        <td width="560" class="esd-container-frame" align="center" valign="top" style="padding: 0;margin: 0">
                                          <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                            <tbody>
                                              <tr style="border-collapse: collapse">
                                                <td align="center" class="esd-block-text" style="padding: 0;margin: 0">
                                                  <h2 style="margin: 20px 0;color: #22c1d6;line-height: 120%;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 26px;font-style: normal;font-weight: bold" class="color-agil">Esperamos que você tenha sido bem atendido.</h2>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                              <tr style="border-collapse: collapse">
                                <td class="esd-structure es-p20t es-p10b es-p20r es-p20l" align="left" style="background-position: center top;padding: 0;margin: 0;padding-bottom: 10px;padding-top: 20px;padding-left: 20px;padding-right: 20px" esd-custom-block-id="44742">
                                  <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="280" valign="top"><![endif]-->
                                    <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;><!--float: left"-->
                                      <tbody>
                                        <tr style="border-collapse: collapse">
                                          <td class="es-m-p20b esd-container-frame" width="280" align="left" style="padding: 0;margin: 0">
                                            <table style="border-left: 1px solid transparent;border-top: 1px solid transparent;border-bottom: 1px solid transparent;background-color: #efefef;border-collapse: separate;background-position: center top;mso-table-lspace: 0;mso-table-rspace: 0;border-spacing: 0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#efefef">
                                              <tbody>
                                                <tr style="border-collapse: collapse">
                                                  <td class="esd-block-text es-p20t es-p10b es-p20r es-p20l" align="left" style="padding: 0;margin: 0;padding-bottom: 10px;padding-top: 20px;padding-left: 20px;padding-right: 20px">
                                                    <h4 class="color-agil" style="color: #22c1d6;margin: 0;line-height: 120%;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">INFORMAÇÕES:</h4>
                                                  </td>
                                                </tr>
                                                <tr style="border-collapse: collapse">
                                                  <td class="esd-block-text es-p20b es-p20r es-p20l" align="left" style="padding: 0;margin: 0;padding-bottom: 20px;padding-left: 20px;padding-right: 20px">
                                                    <table style="width: 100%;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left">
                                                      <tbody>
                                                        <tr style="border-collapse: collapse">
                                                          <td style="font-size: 14px;line-height: 150%;padding: 0;margin: 0">Atendimento Nº #:</td>
                                                          <td style="padding: 0;margin: 0"><strong><span style="font-size: 14px; line-height: 150%;">'.$id_consulta.'</span></strong></td>
                                                        </tr>
                                                        <tr style="border-collapse: collapse">
                                                          <td style="font-size: 14px;line-height: 150%;padding: 0;margin: 0">Data:</td>
                                                          <td style="padding: 0;margin: 0"><strong><span style="font-size: 14px; line-height: 150%;">'.$data.'</span></strong></td>
                                                        </tr>
                                                        <tr style="border-collapse: collapse">
                                                          <td style="font-size: 14px;line-height: 150%;padding: 0;margin: 0">Valor Total:</td>
                                                          <td style="padding: 0;margin: 0"><strong><span style="font-size: 14px; line-height: 150%;">'.$valor.'</span></strong></td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                    <p style="line-height: 150%;margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;color: #333"><br /></p>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <!--[if mso]></td><td width="0"></td><td width="280" valign="top"><![endif]-->
                                      <table class="es-right" cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;><!--float: right"-->
                                        <tbody>
                                          <tr style="border-collapse: collapse">
                                            <td class="esd-container-frame" width="280" align="left" style="padding: 0;margin: 0">
                                              <table style="border-left: 1px solid transparent;border-right: 1px solid transparent;border-top: 1px solid transparent;border-bottom: 1px solid transparent;background-color: #efefef;border-collapse: separate;background-position: center top;mso-table-lspace: 0;mso-table-rspace: 0;border-spacing: 0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#efefef">
                                                <tbody>
                                                  <tr style="border-collapse: collapse">
                                                    <td class="esd-block-text es-p20t es-p10b es-p20r es-p20l" align="left" style="padding: 0;margin: 0;padding-bottom: 10px;padding-top: 20px;padding-left: 20px;padding-right: 20px">
                                                      <h4 class="color-agil" style="color: #22c1d6;margin: 0;line-height: 120%;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">SEU ENDEREÇO:</h4>
                                                    </td>
                                                  </tr>
                                                  <tr style="border-collapse: collapse">
                                                    <td class="esd-block-text es-p20b es-p20r es-p20l" align="left" style="padding: 0;margin: 0;padding-bottom: 20px;padding-left: 20px;padding-right: 20px">
                                                      <p style="margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%;color: #333">'.$endereco.'</p>
                                                      <p style="margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%;color: #333">'.$bairro.'</p>
                                                      <p style="margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%;color: #333">'.$nome_cidade.', '.$sigla.' - '.$cep.'</p>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!--[if mso]></td></tr></table><![endif]-->
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;table-layout: fixed !important;width: 100%">
                        <tbody>
                          <tr style="border-collapse: collapse">
                            <td class="esd-stripe" align="center" style="padding: 0;margin: 0">
                              <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;background-color: #fff">
                                <tbody>
                                  <tr style="border-collapse: collapse">
                                    <td class="esd-structure es-p15t es-p20r es-p20l" align="left" style="background-position: center top;padding: 0;margin: 0;padding-top: 15px;padding-left: 20px;padding-right: 20px">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                        <tbody>
                                          <tr style="border-collapse: collapse">
                                            <td width="560" class="esd-container-frame" align="center" valign="top" style="padding: 0;margin: 0">
                                              <table cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;background-position: center top;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                <tbody>
                                                  <tr style="border-collapse: collapse">
                                                    <td align="left" class="esd-block-text es-p20t es-p20b" style="padding: 0;margin: 0;padding-top: 20px;padding-bottom: 20px">
                                                      <table border="0" cellspacing="1" cellpadding="1" style="width: 100%;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0" class="cke_show_border" align="center">
                                                        <tbody>
                                                          <tr style="border-collapse: collapse">
                                                            <td style="padding: 0;margin: 0">
                                                              <h4 style="color: #333;line-height: 200%;margin: 0;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif"><strong>Subtotal:</strong></h4>
                                                            </td>
                                                            <td align="right" style="padding: 0;margin: 0"><strong>'.$valor.'</strong></td>
                                                          </tr>
                                                          <tr style="border-collapse: collapse">
                                                            <td style="padding: 0;margin: 0">
                                                              <h4 style="color: #333;line-height: 200%;margin: 0;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">Taxa adicional:</h4>
                                                            </td>
                                                            <td align="right" style="padding: 0;margin: 0"><strong>R$ 0,00</strong></td>
                                                          </tr>
                                                          <tr style="border-collapse: collapse">
                                                            <td style="padding: 0;margin: 0">
                                                              <h4 style="color: #333;line-height: 200%;margin: 0;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">Desconto:</h4>
                                                            </td>
                                                            <td align="right" style="padding: 0;margin: 0"><strong>R$ 0,00</strong></td>
                                                          </tr>
                                                          <tr style="border-collapse: collapse">
                                                            <td style="padding: 0;margin: 0">
                                                              <h4 style="color: #333;line-height: 200%;margin: 0;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">Total:</h4>
                                                            </td>
                                                            <td align="right" style="padding: 0;margin: 0"><strong>'.$valor.'</strong></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr style="border-collapse: collapse">
                                            <td width="560" class="esd-container-frame" align="center" valign="top" style="padding: 0;margin: 0">
                                              <table cellpadding="0" cellspacing="0" width="100%" style="border-bottom: 1px solid #ccc;background-position: center top;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                <tbody>
                                                  <tr style="border-collapse: collapse">
                                                    <td class="es-m-p20b es-p20t es-p20b esd-container-frame" width="270" align="left" style="padding: 0;margin: 0;padding-top: 20px;padding-bottom: 20px">
                                                      <table width="100%" cellspacing="0" cellpadding="0" style="background-position: center center;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                        <tbody>
                                                          <tr style="border-collapse: collapse">
                                                            <td class="esd-block-text" align="left" style="padding: 0;margin: 0">
                                                              <h4 class="color-agil" style="color: #22c1d6;margin: 0;line-height: 120%;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">Pagamento:</h4>
                                                            </td>
                                                          </tr>
                                                          <tr style="border-collapse: collapse">
                                                            <td class="es-p10t es-p10b" align="left" style="padding: 0;margin: 0;padding-top: 10px;padding-bottom: 10px">'.$tipo_pagamento.'</td>
                                                            <td class="es-p10t es-p10b" align="right" style="display: flex;justify-content: flex-end;padding: 0;margin: 0;padding-top: 10px;padding-bottom: 10px;justify-content: flex-end;">

                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                    <td class="esd-structure es-p30t es-p30b es-p20r es-p20l" align="left" style="background-position: left top;padding: 0;margin: 0;padding-left: 20px;padding-right: 20px;padding-top: 30px;padding-bottom: 30px" esd-custom-block-id="44746">
                                      <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="270" valign="top"><![endif]-->
                                        <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;float: left">
                                          <tbody>
                                            <tr style="border-collapse: collapse">
                                              <td class="es-m-p20b esd-container-frame" width="270" align="left" style="padding: 0;margin: 0">
                                                <table width="100%" cellspacing="0" cellpadding="0" style="background-position: center center;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                  <tbody>
                                                    <tr style="border-collapse: collapse">
                                                      <td class="esd-block-text" align="left" style="padding: 0;margin: 0">
                                                        <h4 class="color-agil" style="color: #22c1d6;margin: 0;line-height: 120%;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif">Contate-nos:</h4>
                                                      </td>
                                                    </tr>
                                                    <tr style="border-collapse: collapse">
                                                      <td class="esd-block-text es-p10t es-p15b" align="left" style="padding: 0;margin: 0;padding-top: 10px;padding-bottom: 15px">
                                                        <p style="margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%;color: #333">Precisa de ajuda? Entre em contato através dos canais a seguir.</p>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                        <!--[if mso]></td><td width="20"></td><td width="270" valign="top"><![endif]-->
                                          <table class="es-right" cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;float: right">
                                            <tbody>
                                              <tr style="border-collapse: collapse">
                                                <td class="esd-block-image es-p5t es-p5b es-p10r" valign="top" align="left" style="font-size: 0;padding: 0;margin: 0;padding-top: 5px;padding-bottom: 5px;padding-right: 10px"><a href="" target="_blank" style="-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;color: #22c1d6"><svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></a></td>
                                                <td align="left" style="padding: 0;margin: 0">
                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                    <tbody>
                                                      <tr style="border-collapse: collapse">
                                                        <td class="esd-block-text" align="left" esd-links-color="#333333" esd-links-underline="none" style="padding: 0;margin: 0">
                                                          <p style="margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%;color: #333"><a target="_blank" href="mailto:exemplo@mail.com" style="color: #333;text-decoration: none;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px">contato@dragilapp.com.br</a></p>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                              <tr style="border-collapse: collapse">
                                                <td class="esd-block-image es-p5t es-p5b es-p10r" valign="top" align="left" style="font-size: 0;padding: 0;margin: 0;padding-top: 5px;padding-bottom: 5px;padding-right: 10px"><a href="" target="_blank" style="-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;color: #22c1d6"><svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></a></td>
                                                <td align="left" style="padding: 0;margin: 0">
                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                    <tbody>
                                                      <tr style="border-collapse: collapse">
                                                        <td class="esd-block-text" align="left" esd-links-color="#333333" esd-links-underline="none" style="padding: 0;margin: 0">
                                                          <p style="margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-size: 14px;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%;color: #333"><a target="_blank" style="color: #333;text-decoration: none;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px"></a></p>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                          <!--[if mso]></td></tr></table><![endif]-->
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0;table-layout: fixed !important;width: 100%;background-color: transparent;background-repeat: repeat;background-position: center top">
                            <tbody>
                              <tr style="border-collapse: collapse">
                                <td class="esd-stripe" esd-custom-block-id="88654" align="center" style="padding: 0;margin: 0">
                                  <table class="es-footer-body" style="background-color: #333;mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0" width="600" cellspacing="0" cellpadding="0" bgcolor="#333333" align="center">
                                    <tbody>
                                      <tr style="border-collapse: collapse">
                                        <td class="esd-structure es-p20t es-p20r es-p20l" style="background-position: center center;background-color: #22c1d6;padding: 0;margin: 0;padding-top: 20px;padding-left: 20px;padding-right: 20px" bgcolor="#22c1d6" align="left">
                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                            <tbody>
                                              <tr style="border-collapse: collapse">
                                                <td class="esd-container-frame" width="560" valign="top" align="center" style="padding: 0;margin: 0">
                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                    <tbody>
                                                      <tr style="border-collapse: collapse">
                                                        <td class="esd-block-menu" esd-img-prev-h="16" esd-img-prev-w="16" style="padding: 0;margin: 0">
                                                          <table class="es-menu" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                            <tbody>
                                                              <tr class="links" style="border-collapse: collapse">
                                                                <td class="es-p10t es-p10b es-p5r es-p5l" style="padding-bottom: 0;padding-top: 0;padding: 0;margin: 0;padding-left: 5px;padding-right: 5px;border: 0" width="33.33%" valign="top" bgcolor="transparent" align="center">
                                                                  <a target="_blank" style="color: #fff;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;display: block" href="#"></a>
                                                                </td>
                                                                <td class="es-p10t es-p10b es-p5r es-p5l" style="border-left: 1px solid #fff;padding-bottom: 0;padding-top: 0;padding: 0;margin: 0;padding-left: 5px;padding-right: 5px;border: 0" width="33.33%" valign="top" bgcolor="transparent" align="center">
                                                                  <a target="_blank" style="color: #fff;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;display: block" href="#"></a>
                                                                </td>
                                                                <td class="es-p10t es-p10b es-p5r es-p5l" style="border-left: 1px solid #fff;padding-bottom: 0;padding-top: 0;padding: 0;margin: 0;padding-left: 5px;padding-right: 5px;border: 0" width="33.33%" valign="top" bgcolor="transparent" align="center">
                                                                  <a target="_blank" style="color: #fff;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;display: block" href="#"></a>
                                                                </td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                      <tr style="border-collapse: collapse">
                                        <td class="esd-structure es-p20t es-p15b es-p20r es-p20l" style="background-position: center center;background-color: #22c1d6;padding: 0;margin: 0;padding-bottom: 15px;padding-top: 20px;padding-left: 20px;padding-right: 20px" bgcolor="#22c1d6" align="left">
                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                            <tbody>
                                              <tr style="border-collapse: collapse">
                                                <td class="esd-container-frame" width="560" valign="top" align="center" style="padding: 0;margin: 0">
                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                    <tbody>
                                                      <tr style="border-collapse: collapse">
                                                        <td class="esd-block-social es-p15b" align="center" style="font-size: 0;padding: 0;margin: 0;padding-bottom: 15px">
                                                          <table class="es-table-not-adapt es-social" cellspacing="0" cellpadding="0" style="mso-table-lspace: 0;mso-table-rspace: 0;border-collapse: collapse;border-spacing: 0">
                                                            <tbody>
                                                              <tr style="border-collapse: collapse">
                                                                <td class="es-p15r" valign="top" align="center" style="padding: 0;margin: 0;padding-right: 15px"><a target="_blank" href="" style="-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;color: #fff"><img title="Facebook" src="https://tlr.stripocdn.email/content/assets/img/social-icons/circle-white/facebook-circle-white.png" alt="Fb" width="32" style="display: block;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic" /></a></td>
                                                                <td valign="top" align="center" style="padding: 0;margin: 0"><a target="_blank" href="" style="-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;font-size: 14px;text-decoration: none;color: #fff"><img title="Instagram" src="https://tlr.stripocdn.email/content/assets/img/social-icons/circle-white/instagram-circle-white.png" alt="Yt" width="32" style="display: block;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic" /></a></td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                        </td>
                                                      </tr>
                                                      <tr style="border-collapse: collapse">
                                                        <td class="esd-block-text" align="center" style="padding: 0;margin: 0">
                                                          <p style="font-size: 13px;color: #fff;margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;font-family: arial, &quot;helvetica neue&quot;, helvetica, sans-serif;line-height: 150%">O Dr. Ágil é o melhor e mais completo app para agendamento de consultas com profissionais da saúde, exames de todos os tipos e procedimentos, inclusive com atendimento a domicilio!</p>
                                                        </td>
                                                      </tr>


                                                    </tbody>
                                                  </table>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </body>

              </html>

      ';




             return $this->bodyPgto;


    }


}
