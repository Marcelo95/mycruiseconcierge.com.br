<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */


function theme_enqueue_scripts()
{
  /**
   * frontend ajax requests.
   */
  wp_enqueue_script('ajax-script', get_template_directory_uri() . '/js/controller/formPreEmbarqueController.js', array(), null, true);
  wp_localize_script(
    'ajax-script',
    'ajax_object',
    array(
      'ajax_url' => admin_url('admin-ajax.php')
    )
  );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');



function query($num, $table_name, $date_init, $date_fim, $id = '')
{
  $queryBD = "SELECT 
  DADOS.`ID`,
  DADOS.`nome_agencia` as 'NOME AGENCIA',
  DADOS.`cnpj_agencia` as 'CNPJ AGENCIA',
  DADOS.`navio` as 'COMPANHIA',
  DADOS.`num_reserva` as 'NÚMERO DA RESERVA',
  DADOS.`nome_navio` as 'NOME NAVIO',
  DADOS.`data_embarque` as 'DATA EMBARQUE',
  DADOS.`data_desembarque` as 'DATA DESEMBARQUE',
  (select if( DADOS.`is_agente` , 'SIM', 'NÃO')) as 'É AGENTE',
  DADOS.`nome_agente` as 'NOME AGENTE',
  DADOS.`agente_email` as 'AGENTE EMAIL',

  DADOS.`nome_do_contratante` as 'NOME DO CONTRATANTE',
  DADOS.`endereco_contratante` as 'ENDEREÇO CONTRATANTE',
  DADOS.`cidade_contratante` as 'CIDADE CONTRATANTE',
  DADOS.`cep_contratante` as 'CEP CONTRATANTE',
  DADOS.`email_contratante` as 'EMAIL CONTRATANTE',
  DADOS.`data_nascimento_contratante` as 'DATA DE NASCIMENTO CONTRATANTE',
  DADOS.`nacionalidade_contratante` as 'NACIONALIDADE CONTRATANTE',
  DADOS.`local_nascimento_contratante` as 'LOCAL NASCIMENTO CONTRATANTE',
  DADOS.`numero_passaporte_contratante` as 'NÚMERO PASSAPORTE CONTRATANTE',
  DADOS.`local_emissao_contratante` as 'LOCAL EMISSÃO CONTRATANTE',
  DADOS.`data_expedicao_contratante` as 'DATA EXPEDICÃO CONTRATANTE',
  DADOS.`data_validade_contratante` as 'DATA VALIDADE CONTRATANTE',
  DADOS.`cpf_contratante` as 'CPF CONTRATANTE',
  DADOS.`tel_residencial_contratante` as 'TELEFONE RESIDENCIAL CONTRATANTE',
  DADOS.`tel_comercial_contratante` as 'TELEFONE COMERCIAL CONTRATANTE',
  DADOS.`tel_celular_contratante` as 'TELEFONE CELULAR CONTRATANTE',
  DADOS.`uf_contratante` as 'UF CONTRATANTE',
  DADOS.`quantidade_hospedes` as 'QUANTIDADE DE HÓSPEDE',
  (select if( DADOS.`is_hospede_1` , 'SIM', 'NÃO')) as 'HÓSPEDE 1 É O MESMO QUE O CONTRATANTE?',

  DADOS.`nome_do_hospede_1` as 'NOME DO HOSPEDE 1',
  DADOS.`data_nascimento_hospede_1` as 'DATA DE NASCIMENTO HOSPEDE 1',
  DADOS.`nacionalidade_hospede_1` as 'NACIONALIDADE HOSPEDE 1',
  DADOS.`local_nascimento_hospede_1` as 'LOCAL NASCIMENTO HOSPEDE 1',
  DADOS.`numero_passaporte_hospede_1` as 'NÚMERO PASSAPORTE HOSPEDE 1',
  DADOS.`local_emissao_hospede_1` as 'LOCAL EMISSÃO HOSPEDE 1',
  DADOS.`data_expedicao_hospede_1` as 'DATA EXPEDICÃO HOSPEDE 1',
  DADOS.`data_validade_hospede_1` as 'DATA VALIDADE HOSPEDE 1',
  DADOS.`endereco_hospede_1` as 'ENDEREÇO HOSPEDE 1',
  DADOS.`cidade_hospede_1` as 'CIDADE HOSPEDE 1',
  DADOS.`cep_hospede_1` as 'CEP HOSPEDE 1',
  DADOS.`uf_hospede_1` as 'UF HOSPEDE 1',
  DADOS.`cpf_hospede_1` as 'CPF HOSPEDE 1',
  DADOS.`email_hospede_1` as 'EMAIL HOSPEDE 1',
  DADOS.`tel_residencial_hospede_1` as 'TELEFONE RESIDENCIAL HOSPEDE 1',
  DADOS.`tel_comercial_hospede_1` as 'TELEFONE COMERCIAL HOSPEDE 1',
  DADOS.`tel_celular_hospede_1` as 'TELEFONE CELULAR HOSPEDE 1',

  DADOS.`data_nascimento_hospede_2` as 'DATA DE NASCIMENTO HOSPEDE 2',
  DADOS.`tel_residencial_hospede_2` as 'TELEFONE RESIDENCIAL HOSPEDE 2',
  DADOS.`nome_do_hospede_2` as 'NOME DO HOSPEDE 2',
  DADOS.`nacionalidade_hospede_2` as 'NACIONALIDADE HOSPEDE 2',
  DADOS.`local_nascimento_hospede_2` as 'LOCAL NASCIMENTO HOSPEDE 2',
  DADOS.`numero_passaporte_hospede_2` as 'NÚMERO PASSAPORTE HOSPEDE 2',
  DADOS.`local_emissao_hospede_2` as 'LOCAL EMISSÃO HOSPEDE 2',
  DADOS.`data_expedicao_hospede_2` as 'DATA EXPEDICÃO HOSPEDE 2',
  DADOS.`data_validade_hospede_2` as 'DATA VALIDADE HOSPEDE 2',
  DADOS.`endereco_hospede_2` as 'ENDEREÇO HOSPEDE 2',
  DADOS.`cidade_hospede_2` as 'CIDADE HOSPEDE 2',
  DADOS.`cep_hospede_2` as 'CEP HOSPEDE 2',
  DADOS.`email_hospede_2` as 'EMAIL HOSPEDE 2',
  DADOS.`cpf_hospede_2` as 'CPF HOSPEDE 2',
  DADOS.`uf_hospede_2` as 'UF HOSPEDE 2',
  DADOS.`tel_comercial_hospede_2` as 'TELEFONE COMERCIAL HOSPEDE 2',
  DADOS.`tel_celular_hospede_2` as 'TELEFONE CELULAR HOSPEDE 2',

  DADOS.`nome_do_hospede_3` as 'NOME DO HOSPEDE 3',
  DADOS.`endereco_hospede_3` as 'ENDEREÇO HOSPEDE 3',
  DADOS.`cidade_hospede_3` as 'CIDADE HOSPEDE 3',
  DADOS.`cep_hospede_3` as 'CEP HOSPEDE 3',
  DADOS.`email_hospede_3` as 'EMAIL HOSPEDE 3',
  DADOS.`data_nascimento_hospede_3` as 'DATA DE NASCIMENTO HOSPEDE 3',
  DADOS.`nacionalidade_hospede_3` as 'NACIONALIDADE HOSPEDE 3',
  DADOS.`local_nascimento_hospede_3` as 'LOCAL NASCIMENTO HOSPEDE 3',
  DADOS.`numero_passaporte_hospede_3` as 'NÚMERO PASSAPORTE HOSPEDE 3',
  DADOS.`local_emissao_hospede_3` as 'LOCAL EMISSÃO HOSPEDE 3',
  DADOS.`data_expedicao_hospede_3` as 'DATA EXPEDICÃO HOSPEDE 3',
  DADOS.`data_validade_hospede_3` as 'DATA VALIDADE HOSPEDE 3',
  DADOS.`uf_hospede_3` as 'UF HOSPEDE 3',
  DADOS.`cpf_hospede_3` as 'CPF HOSPEDE 3',
  DADOS.`tel_residencial_hospede_3` as 'TELEFONE RESIDENCIAL HOSPEDE 3',
  DADOS.`tel_comercial_hospede_3` as 'TELEFONE COMERCIAL HOSPEDE 3',
  DADOS.`tel_celular_hospede_3` as 'TELEFONE CELULAR HOSPEDE 3',

  DADOS.`nome_do_hospede_4` as 'NOME DO HOSPEDE 4',
  DADOS.`data_nascimento_hospede_4` as 'DATA DE NASCIMENTO HOSPEDE 4',
  DADOS.`nacionalidade_hospede_4` as 'NACIONALIDADE HOSPEDE 4',
  DADOS.`local_nascimento_hospede_4` as 'LOCAL NASCIMENTO HOSPEDE 4',
  DADOS.`numero_passaporte_hospede_4` as 'NÚMERO PASSAPORTE HOSPEDE 4',
  DADOS.`local_emissao_hospede_4` as 'LOCAL EMISSÃO HOSPEDE 4',
  DADOS.`data_expedicao_hospede_4` as 'DATA EXPEDICÃO HOSPEDE 4',
  DADOS.`data_validade_hospede_4` as 'DATA VALIDADE HOSPEDE 4',
  DADOS.`endereco_hospede_4` as 'ENDEREÇO HOSPEDE 4',
  DADOS.`cidade_hospede_4` as 'CIDADE HOSPEDE 4',
  DADOS.`uf_hospede_4` as 'UF HOSPEDE 4',
  DADOS.`cep_hospede_4` as 'CEP HOSPEDE 4',
  DADOS.`cpf_hospede_4` as 'CPF HOSPEDE 4',
  DADOS.`email_hospede_4` as 'EMAIL HOSPEDE 4',
  DADOS.`tel_residencial_hospede_4` as 'TELEFONE RESIDENCIAL HOSPEDE 4',
  DADOS.`tel_comercial_hospede_4` as 'TELEFONE COMERCIAL HOSPEDE 4',
  DADOS.`tel_celular_hospede_4` as 'TELEFONE CELULAR HOSPEDE 4',

  DADOS.`relacao_com_o_contratante_emergencia` as 'RELAÇÃO COM O CONTRATANTE EMERGENCIA',
  DADOS.`nome_completo_emergencia` as 'NOME COMPLETO EMERGENCIA',
  DADOS.`endereco_contato_emergencia` as 'ENDEREÇO CONTATO EMERGENCIA',
  DADOS.`cidade_contato_emergencia` as 'CIDADE CONTATO EMERGENCIA',
  DADOS.`uf_contato_emergencia` as 'UF CONTATO EMERGENCIA',
  DADOS.`tel_contato_emergencia` as 'TELEFONE CONTATO EMERGENCIA',
  DADOS.`email_contato_emergencia` as 'EMAIL CONTATO EMERGENCIA',

  (select if( DADOS.`cuidados_medicos` , 'SIM', 'NÃO')) as 'CUIDADOS MEDICOS',
  DADOS.`cuidados_medicos_observacao` as 'CUIDADOS MEDICOS OBSERVAÇÃO',
  (select if( DADOS.`is_gestante` , 'SIM', 'NÃO')) as 'É GESTANTE',
  DADOS.`is_gestante_observacao` as 'É GESTANTE EM QUAL SEMANA DA GESTAÇÃO A HÓSPEDE ESTARÁ NA DATA DE DESEMBARQUE',
  (select if( DADOS.`tem_menor_de_um_ano` , 'SIM', 'NÃO')) as 'TEM MENOR DE UM ANO',
  DADOS.`tem_menor_de_um_ano_observacao` as 'TEM MENOR DE UM ANO OBSERVAÇÃO',
  (select if( DADOS.`possui_restricao_alimentar` , 'SIM', 'NÃO')) as 'POSSUI RESTRICAO ALIMENTAR',
  DADOS.`possui_restricao_alimentar_observacao` as 'POSSUI RESTRICAO ALIMENTAR OBSERVAÇÃO',
  (select if( DADOS.`vai_celebrar_data_especial_abordo` , 'SIM', 'NÃO')) as 'VAI CELEBRAR DATA ESPECIAL ABORDO',
  DADOS.`vai_celebrar_data_especial_abordo_observacao` as 'VAI CELEBRAR DATA ESPECIAL ABORDO OBSERVAÇÃO',
  (select if( DADOS.`aceite` , 'SIM', 'NÃO')) as 'ACEITE',

  DADOS.`date_created` as 'DATA DE CRIAÇÃO',

  (SELECT CONCAT('<a target=\"_blank\" href=\"', 'http://mycruiseconcierge.com.br/pre-embarque/aqua-expedition/?format=json&action=getDocs&key=' , ( SELECT DADOS.`document_key` ) , '\">', 'VISUALIZAR' ,'</a>')) as 'DOCUMENTO DOCKSIGN'

  FROM " . $table_name . " as DADOS ###;";


  if ($num == 1) {
    return str_replace("###", "WHERE DADOS.`date_created` BETWEEN  '" . $date_init . "' AND '" . $date_fim . "' order by DADOS.`date_created` desc", $queryBD);
  }

  if ($num == 2) {
    return str_replace("###", "WHERE DADOS.`ID`=" . $id, $queryBD);
  }
}

function query_to_email($num, $table_name, $date_init, $date_fim, $id = '')
{
  $queryBD = "SELECT 
  DADOS.`ID` as '1 - ID',
  DADOS.`nome_agencia` as '1 - NOME AGENCIA',
  DADOS.`cnpj_agencia` as '1 - CNPJ AGENCIA',

  DADOS.`navio` as '1 - COMPANHIA',

  DADOS.`num_reserva` as '1 - NÚMERO DA RESERVA',
  DADOS.`nome_navio` as '1 - NOME NAVIO',
  DADOS.`data_embarque` as '1 - DATA EMBARQUE',
  DADOS.`data_desembarque` as '1 - DATA DESEMBARQUE',
  (select if( DADOS.`is_agente` , 'SIM', 'NÃO')) as '1 - É AGENTE',
  DADOS.`nome_agente` as '1 - NOME AGENTE',
  DADOS.`agente_email` as '1 - AGENTE EMAIL',

  DADOS.`nome_do_contratante` as '2 - NOME DO CONTRATANTE',
  DADOS.`endereco_contratante` as '2 - ENDEREÇO CONTRATANTE',
  DADOS.`numero_do_endereco_contratante` as '2 - NÚMERO DE ENDEREÇO CONTRATANTE',
  DADOS.`complemento_contratante` as '2 - COMPLEMENTO CONTRATANTE',

  DADOS.`cidade_contratante` as '2 - CIDADE CONTRATANTE',
  DADOS.`cep_contratante` as '2 - CEP CONTRATANTE',
  DADOS.`email_contratante` as '2 - EMAIL CONTRATANTE',
  DADOS.`data_nascimento_contratante` as '2 - DATA DE NASCIMENTO CONTRATANTE',
  DADOS.`nacionalidade_contratante` as '2 - NACIONALIDADE CONTRATANTE',
  DADOS.`local_nascimento_contratante` as '2 - LOCAL NASCIMENTO CONTRATANTE',
  DADOS.`numero_passaporte_contratante` as '2 - NÚMERO PASSAPORTE CONTRATANTE',
  DADOS.`local_emissao_contratante` as '2 - LOCAL EMISSÃO CONTRATANTE',
  DADOS.`data_expedicao_contratante` as '2 - DATA EXPEDICÃO CONTRATANTE',
  DADOS.`data_validade_contratante` as '2 - DATA VALIDADE CONTRATANTE',
  DADOS.`cpf_contratante` as '2 - CPF CONTRATANTE',
  DADOS.`tel_residencial_contratante` as '2 - TELEFONE RESIDENCIAL CONTRATANTE',
  DADOS.`tel_comercial_contratante` as '2 - TELEFONE COMERCIAL CONTRATANTE',
  DADOS.`tel_celular_contratante` as '2 - TELEFONE CELULAR CONTRATANTE',
  DADOS.`uf_contratante` as '2 - UF CONTRATANTE',
  DADOS.`quantidade_hospedes` as '2 - QUANTIDADE DE HÓSPEDE',
  (select if( DADOS.`is_hospede_1` , 'SIM', 'NÃO')) as '2 - HÓSPEDE 1 É O MESMO QUE O CONTRATANTE?',

  DADOS.`nome_do_hospede_1` as '3 - NOME HÓSPEDE 1',
  DADOS.`data_nascimento_hospede_1` as '3 - DATA DE NASCIMENTO HÓSPEDE 1',
  DADOS.`nacionalidade_hospede_1` as '3 - NACIONALIDADE HÓSPEDE 1',
  DADOS.`local_nascimento_hospede_1` as '3 - LOCAL NASCIMENTO HÓSPEDE 1',
  DADOS.`numero_passaporte_hospede_1` as '3 - NÚMERO PASSAPORTE HÓSPEDE 1',
  DADOS.`local_emissao_hospede_1` as '3 - LOCAL EMISSÃO HÓSPEDE 1',
  DADOS.`data_expedicao_hospede_1` as '3 - DATA EXPEDICÃO HÓSPEDE 1',
  DADOS.`data_validade_hospede_1` as '3 - DATA VALIDADE HÓSPEDE 1',

  DADOS.`endereco_hospede_1` as '3 - ENDEREÇO HÓSPEDE 1',
  DADOS.`numero_do_endereco_hospede_1` as '3 - NÚMERO DE ENDEREÇO HÓSPEDE 1',
  DADOS.`complemento_hospede_1` as '3 - COMPLEMENTO HÓSPEDE 1',

  DADOS.`cidade_hospede_1` as '3 - CIDADE HÓSPEDE 1',
  DADOS.`cep_hospede_1` as '3 - CEP HÓSPEDE 1',
  DADOS.`uf_hospede_1` as '3 - UF HÓSPEDE 1',
  DADOS.`cpf_hospede_1` as '3 - CPF HÓSPEDE 1',
  DADOS.`email_hospede_1` as '3 - EMAIL HÓSPEDE 1',
  DADOS.`tel_residencial_hospede_1` as '3 - TELEFONE RESIDENCIAL HÓSPEDE 1',
  DADOS.`tel_comercial_hospede_1` as '3 - TELEFONE COMERCIAL HÓSPEDE 1',
  DADOS.`tel_celular_hospede_1` as '3 - TELEFONE CELULAR HÓSPEDE 1',

  DADOS.`data_nascimento_hospede_2` as '4 - DATA DE NASCIMENTO HÓSPEDE 2',
  DADOS.`tel_residencial_hospede_2` as '4 - TELEFONE RESIDENCIAL HÓSPEDE 2',
  DADOS.`nome_do_hospede_2` as '4 - NOME HÓSPEDE 2',
  DADOS.`nacionalidade_hospede_2` as '4 - NACIONALIDADE HÓSPEDE 2',
  DADOS.`local_nascimento_hospede_2` as '4 - LOCAL NASCIMENTO HÓSPEDE 2',
  DADOS.`numero_passaporte_hospede_2` as '4 - NÚMERO PASSAPORTE HÓSPEDE 2',
  DADOS.`local_emissao_hospede_2` as '4 - LOCAL EMISSÃO HÓSPEDE 2',
  DADOS.`data_expedicao_hospede_2` as '4 - DATA EXPEDICÃO HÓSPEDE 2',
  DADOS.`data_validade_hospede_2` as '4 - DATA VALIDADE HÓSPEDE 2',
  DADOS.`endereco_hospede_2` as '4 - ENDEREÇO HÓSPEDE 2',

  DADOS.`numero_do_endereco_hospede_2` as '4 - NÚMERO DE ENDEREÇO HÓSPEDE 2',
  DADOS.`complemento_hospede_2` as '4 - COMPLEMENTO HÓSPEDE 2',

  DADOS.`cidade_hospede_2` as '4 - CIDADE HÓSPEDE 2',
  DADOS.`cep_hospede_2` as '4 - CEP HÓSPEDE 2',
  DADOS.`email_hospede_2` as '4 - EMAIL HÓSPEDE 2',
  DADOS.`cpf_hospede_2` as '4 - CPF HÓSPEDE 2',
  DADOS.`uf_hospede_2` as '4 - UF HÓSPEDE 2',
  DADOS.`tel_comercial_hospede_2` as '4 - TELEFONE COMERCIAL HÓSPEDE 2',
  DADOS.`tel_celular_hospede_2` as '4 - TELEFONE CELULAR HÓSPEDE 2',

  DADOS.`nome_do_hospede_3` as '5 - NOME HÓSPEDE 3',
  DADOS.`endereco_hospede_3` as '5 - ENDEREÇO HÓSPEDE 3',

  DADOS.`numero_do_endereco_hospede_3` as '5 - NÚMERO DE ENDEREÇO HÓSPEDE 3',
  DADOS.`complemento_hospede_3` as '5 - COMPLEMENTO HÓSPEDE 3',

  DADOS.`cidade_hospede_3` as '5 - CIDADE HÓSPEDE 3',
  DADOS.`cep_hospede_3` as '5 - CEP HÓSPEDE 3',
  DADOS.`email_hospede_3` as '5 - EMAIL HÓSPEDE 3',
  DADOS.`data_nascimento_hospede_3` as '5 - DATA DE NASCIMENTO HÓSPEDE 3',
  DADOS.`nacionalidade_hospede_3` as '5 - NACIONALIDADE HÓSPEDE 3',
  DADOS.`local_nascimento_hospede_3` as '5 - LOCAL NASCIMENTO HÓSPEDE 3',
  DADOS.`numero_passaporte_hospede_3` as '5 - NÚMERO PASSAPORTE HÓSPEDE 3',
  DADOS.`local_emissao_hospede_3` as '5 - LOCAL EMISSÃO HÓSPEDE 3',
  DADOS.`data_expedicao_hospede_3` as '5 - DATA EXPEDICÃO HÓSPEDE 3',
  DADOS.`data_validade_hospede_3` as '5 - DATA VALIDADE HÓSPEDE 3',
  DADOS.`uf_hospede_3` as '5 - UF HÓSPEDE 3',
  DADOS.`cpf_hospede_3` as '5 - CPF HÓSPEDE 3',
  DADOS.`tel_residencial_hospede_3` as '5 - TELEFONE RESIDENCIAL HÓSPEDE 3',
  DADOS.`tel_comercial_hospede_3` as '5 - TELEFONE COMERCIAL HÓSPEDE 3',
  DADOS.`tel_celular_hospede_3` as '5 - TELEFONE CELULAR HÓSPEDE 3',

  DADOS.`nome_do_hospede_4` as '6 - NOME HÓSPEDE 4',
  DADOS.`data_nascimento_hospede_4` as '6 - DATA DE NASCIMENTO HÓSPEDE 4',
  DADOS.`nacionalidade_hospede_4` as '6 - NACIONALIDADE HÓSPEDE 4',
  DADOS.`local_nascimento_hospede_4` as '6 - LOCAL NASCIMENTO HÓSPEDE 4',
  DADOS.`numero_passaporte_hospede_4` as '6 - NÚMERO PASSAPORTE HÓSPEDE 4',
  DADOS.`local_emissao_hospede_4` as '6 - LOCAL EMISSÃO HÓSPEDE 4',
  DADOS.`data_expedicao_hospede_4` as '6 - DATA EXPEDICÃO HÓSPEDE 4',
  DADOS.`data_validade_hospede_4` as '6 - DATA VALIDADE HÓSPEDE 4',
  DADOS.`endereco_hospede_4` as '6 - ENDEREÇO HÓSPEDE 4',

  DADOS.`numero_do_endereco_hospede_4` as '6 - NÚMERO DE ENDEREÇO HÓSPEDE 4',
  DADOS.`complemento_hospede_4` as '6 - COMPLEMENTO HÓSPEDE 4',

  DADOS.`cidade_hospede_4` as '6 - CIDADE HÓSPEDE 4',
  DADOS.`uf_hospede_4` as '6 - UF HÓSPEDE 4',
  DADOS.`cep_hospede_4` as '6 - CEP HÓSPEDE 4',
  DADOS.`cpf_hospede_4` as '6 - CPF HÓSPEDE 4',
  DADOS.`email_hospede_4` as '6 - EMAIL HÓSPEDE 4',
  DADOS.`tel_residencial_hospede_4` as '6 - TELEFONE RESIDENCIAL HÓSPEDE 4',
  DADOS.`tel_comercial_hospede_4` as '6 - TELEFONE COMERCIAL HÓSPEDE 4',
  DADOS.`tel_celular_hospede_4` as '6 - TELEFONE CELULAR HÓSPEDE 4',

  DADOS.`relacao_com_o_contratante_emergencia` as '7 - RELAÇÃO COM O CONTRATANTE EMERGENCIA',
  DADOS.`nome_completo_emergencia` as '7 - NOME COMPLETO EMERGENCIA',
  DADOS.`endereco_contato_emergencia` as '7 - ENDEREÇO CONTATO EMERGENCIA',
  DADOS.`cidade_contato_emergencia` as '7 - CIDADE CONTATO EMERGENCIA',
  DADOS.`uf_contato_emergencia` as '7 - UF CONTATO EMERGENCIA',
  DADOS.`tel_contato_emergencia` as '7 - TELEFONE CONTATO EMERGENCIA',
  DADOS.`email_contato_emergencia` as '7 - EMAIL CONTATO EMERGENCIA',

  (select if( DADOS.`cuidados_medicos` , 'SIM', 'NÃO')) as '8 - CUIDADOS MEDICOS',
  DADOS.`cuidados_medicos_observacao` as '8 - CUIDADOS MEDICOS - OBSERVAÇÃO',
  (select if( DADOS.`is_gestante` , 'SIM', 'NÃO')) as '8 - É GESTANTE',
  DADOS.`is_gestante_observacao` as '8 - É GESTANTE EM QUAL SEMANA DA GESTAÇÃO',
  (select if( DADOS.`tem_menor_de_um_ano` , 'SIM', 'NÃO')) as '8 - TEM MENOR DE UM ANO',
  DADOS.`tem_menor_de_um_ano_observacao` as '8 - TEM MENOR DE UM ANO - OBSERVAÇÃO',
  (select if( DADOS.`possui_restricao_alimentar` , 'SIM', 'NÃO')) as '8 - POSSUI RESTRICAO ALIMENTAR',
  DADOS.`possui_restricao_alimentar_observacao` as '8 - POSSUI RESTRICAO ALIMENTAR - OBSERVAÇÃO',
  (select if( DADOS.`vai_celebrar_data_especial_abordo` , 'SIM', 'NÃO')) as '8 - VAI CELEBRAR DATA ESPECIAL ABORDO',
  DADOS.`vai_celebrar_data_especial_abordo_observacao` as '8 - VAI CELEBRAR DATA ESPECIAL ABORDO - OBSERVAÇÃO',

  (select if( DADOS.`aceite` , 'SIM', 'NÃO')) as '9 - LI E CONCORDEI COM OS TERMOS',
  #DADOS.`document_key` as '10 - DOCKSIGN CHAVE DO DOCUMENTO',

  #(select 'TEXT_CONTRACT' as '10 - CONTRATO')
  (SELECT CONCAT('<a target=\"_blank\" href=\"', 'http://mycruiseconcierge.com.br/pre-embarque/aqua-expedition/?format=json&action=getDocs&key=' , ( SELECT DADOS.`document_key` ) , '\">', 'VISUALIZAR' ,'</a>')) as '10 - CONTRATO'

  FROM " . $table_name . " as DADOS ###;";


  if ($num == 1) {
    return str_replace("###", "WHERE DADOS.`date_created` BETWEEN  '" . $date_init . "' AND '" . $date_fim . "' order by DADOS.`date_created` desc", $queryBD);
  }

  if ($num == 2) {
    return str_replace("###", "WHERE DADOS.`ID`=" . $id, $queryBD);
  }

  return $queryBD;
}


add_action('wp_ajax_my_ajax_request', 'my_ajax_request');
add_action('wp_ajax_nopriv_my_ajax_request', 'my_ajax_request');

function my_ajax_request()
{
  try {

    header("Content-type: application/json");

    $data = $_POST["data"];

    $response = array();
    //$response["data"] = $data;

    $principal = array('user_id' => $data["user_id"], 'page_id' => $data["page_id"], 'navio' => $data['navio']);
    $contato_emergencia = array_combine(array_keys($data["contato_emergencia"]), array_values($data["contato_emergencia"]));
    $contratante = array_combine(array_keys($data["contratante"]), array_values($data["contratante"]));
    $cruzeiro = array_combine(array_keys($data["cruzeiro"]), array_values($data["cruzeiro"]));
    $hospede = array_combine(array_keys($data["hospede"]), array_values($data["hospede"]));
    $solicitacao_especiais = array_combine(array_keys($data["solicitacao_especiais"]), array_values($data["solicitacao_especiais"]));
    $termos_e_condicoes = array_combine(array_keys($data["termos_e_condicoes"]), array_values($data["termos_e_condicoes"]));


    global $wpdb;
    $table = $wpdb->prefix . 'users_contacts';

    $user_id = (int) $data["user_id"];
    $page_id = (int) $data["page_id"];

    $email_contratante = $data['contratante']['email_contratante'];

    $email_agente = "";

    if (isset($data['cruzeiro']['agente_email']) && $data['cruzeiro']['agente_email']) { // envia para o agente ou o cliente
      $email_agente = $data['cruzeiro']['agente_email'];
    }

    $data = array_merge($principal, $contato_emergencia, $contratante, $cruzeiro, $hospede, $solicitacao_especiais, $termos_e_condicoes);

    $wpdb->insert($table, $data);
    $my_id = $wpdb->insert_id;

    echo  ["error" => false, "message" => "Pedido criado com sucesso id: $my_id"] ;
    exit;
  } catch (\Throwable $th) {
    echo  ["error" => true, "message" =>  $th->getMessage() ] ;    
  }

  exit;
}


function sendmail($to, $subject, $body)
{

  $headers = array('Content-Type: text/html; charset=UTF-8');

  $resultEmail = wp_mail($to, $subject, $body, $headers);
  if ($resultEmail) {
    return true;
    //echo "Enviado com sucesso";
  } else {
    return false;
    //echo "Falhou algo";
  }

  die();
}


//TODO criar endpoint POST action=send_mail_contacts para dispardo de emails em fila
add_action('wp_ajax_send_mail_contacts', 'send_mail_contacts');
add_action('wp_ajax_nopriv_send_mail_contacts', 'send_mail_contacts');

function send_mail_contacts()
{

  //header("Content-type: text/html");

  header("Content-type: application/json");

  try {

    global $wpdb;
    $table = $wpdb->prefix . 'users_contacts';

    // send email
    $date_init = date('Y-m-d', strtotime('-1 month'));
    $date_fim =  date("Y-m-d", strtotime('+1 day'));
    $table_name = $wpdb->prefix . 'users_contacts';

    $queryRelatorio = query_to_email(0, $table_name, $date_init, $date_fim, null);

    //QUERY para filtrar e disparar os emails
    $queryRelatorio = str_replace("###", "WHERE DADOS.`status` IS NULL", $queryRelatorio);

    $all_contacts = $wpdb->get_results($wpdb->prepare($queryRelatorio, OBJECT), ARRAY_A);


    $to = ["cruiseconcierge@pier1.com.br"]; // novo email
   // $to = ["marcelovieira1995@gmail.com"];
    $subject = "Contato via site - Pré-embarque";


    foreach ($all_contacts as $key => $v) {
      $all_fields_contacts_formated = parse_fields_to_email($v);
      
      $html = get_my_email_template_v2($all_fields_contacts_formated, null);


      $id_contact = $v["1 - ID"];
      $queryUpdate = "UPDATE $table SET `status` = %s WHERE id = $id_contact";

      if(sendmail($to, $subject, $html)){
        $results = $wpdb->get_results($wpdb->prepare($queryUpdate, "enviado"), ARRAY_A);        
      } else {
        $results = $wpdb->get_results($wpdb->prepare($queryUpdate, null), ARRAY_A);
      }

    }


    $response = json_encode(["error" => false, "results" => sprintf("Total enviados: %d", count($all_contacts) )]);
  } catch (\Throwable $th) {
    $log_file = WP_CONTENT_DIR . '/error_logs.txt';
    $log_message = "[" . date('Y-m-d H:i:s') . "] " . get_class($exception) . ": " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";

    error_log( $log_message . "\n", 3, $log_file);
    $response = json_encode(["error" => true, "message" => $th->getMessage()]);
  } finally {

    echo $response;
    exit;
  }
}


add_action("sair", 'logout_user_logged', 1, 1);

function logout_user_logged()
{
  wp_destroy_current_session();
  wp_clear_auth_cookie();
  wp_redirect('/');
  exit;
}

add_action('wp_login_failed', 'my_front_end_login_fail');  // hook failed login

function my_front_end_login_fail($username)
{
  $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;  // where did the post submission come from?
  // if there's a valid referrer, and it's not the default log-in screen
  if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {

    $param = isset($_GET['loginSuccess']) ? '?loginSuccess=failed' : '';

    wp_redirect($referrer . $param);  // let's append some information (login=failed) to the URL for the theme to use
    exit;
  }
}


if (!function_exists('user_fields')) :

  function user_fields($field_user)
  {
    $field_user['user_agency_name'] = __('Nome da Agência');
    $field_user['user_phone'] = __('Telefone com DDD');


    return $field_user;
  }
  add_filter('user_contactmethods', 'user_fields', 10, 1);

endif;



/*------------------------------------*\
    Theme Support
    \*------------------------------------*/

if (!isset($content_width)) {
  $content_width = 900;
}

if (function_exists('add_theme_support')) {
  // Add Menu Support
  add_theme_support('menus');

  // Add Thumbnail Theme Support
  add_theme_support('post-thumbnails');
  add_image_size('large', 700, '', true); // Large Thumbnail
  add_image_size('medium', 250, '', true); // Medium Thumbnail
  add_image_size('small', 120, '', true); // Small Thumbnail
  add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

  // Add Support for Custom Backgrounds - Uncomment below if you're going to use
  /*add_theme_support('custom-background', array(
    'default-color' => 'FFF',
    'default-image' => get_template_directory_uri() . '/img/bg.jpg'
));*/

  // Add Support for Custom Header - Uncomment below if you're going to use
  /*add_theme_support('custom-header', array(
    'default-image'         => get_template_directory_uri() . '/img/headers/default.jpg',
    'header-text'           => false,
    'default-text-color'        => '000',
    'width'             => 1000,
    'height'            => 198,
    'random-default'        => false,
    'wp-head-callback'      => $wphead_cb,
    'admin-head-callback'       => $adminhead_cb,
    'admin-preview-callback'    => $adminpreview_cb
));*/

  // Enables post and comment RSS feed links to head
  add_theme_support('automatic-feed-links');

  // Localisation Support
  load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
    Functions
    \*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
  wp_nav_menu(
    array(
      'theme_location'  => 'header-menu',
      'menu'            => '',
      'container'       => 'div',
      'container_class' => 'menu-{menu slug}-container',
      'container_id'    => '',
      'menu_class'      => 'menu',
      'menu_id'         => '',
      'echo'            => true,
      'fallback_cb'     => 'wp_page_menu',
      'before'          => '',
      'after'           => '',
      'link_before'     => '',
      'link_after'      => '',
      'items_wrap'      => '<ul>%3$s</ul>',
      'depth'           => 0,
      'walker'          => ''
    )
  );
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {


    wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), version()); // Conditionizr
    wp_enqueue_script('conditionizr'); // Enqueue it!

    wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), version()); // Modernizr
    wp_enqueue_script('modernizr'); // Enqueue it!

    wp_register_script('bootstrapJS', get_template_directory_uri() . '/includes/bootstrap/js/bootstrap.min.js', array('jquery'), version()); // Custom scripts
    wp_enqueue_script('bootstrapJS'); // Enqueue it!

    wp_register_script('maskJS', get_template_directory_uri() . '/includes/jquery/jquery.mask.min.js', array('jquery'), version()); // Custom scripts
    wp_enqueue_script('maskJS'); // Enqueue it!

    wp_register_script('angularJS', get_template_directory_uri() . '/includes/angular/angular.min.js', array('jquery'), version()); // Custom scripts
    wp_enqueue_script('angularJS'); // Enqueue it!

    wp_register_script('html5blankscripts2', get_template_directory_uri() . '/js/scripts.js', array('jquery'), version()); // Custom scripts
    wp_enqueue_script('html5blankscripts2'); // Enqueue it!
  }
}

// Load HTML5 Blank conditional scripts
function html5blank_conditional_scripts()
{
  if (is_page('pagenamehere')) {
    wp_enqueue_script('scriptname'); // Enqueue it!
  }
  //wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), version()); // Conditional script(s)
}

// Load HTML5 Blank styles
function html5blank_styles()
{
  wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), version(), 'all');
  wp_enqueue_style('normalize'); // Enqueue it!

  wp_register_style('bootstrapCSS', get_template_directory_uri() . '/includes/bootstrap/css/bootstrap.min.css', array(), version(), 'all');
  wp_enqueue_style('bootstrapCSS'); // Enqueue it!

  wp_register_style('html5blank', get_template_directory_uri() . '/style.css', array(), version(), 'all');
  wp_enqueue_style('html5blank'); // Enqueue it!
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
  register_nav_menus(array( // Using array to specify more menus if needed
    'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
    'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
    'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
  ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
  $args['container'] = false;
  return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
  return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
  return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
  global $post;
  if (is_home()) {
    $key = array_search('blog', $classes);
    if ($key > -1) {
      unset($classes[$key]);
    }
  } elseif (is_page()) {
    $classes[] = sanitize_html_class($post->post_name);
  } elseif (is_singular()) {
    $classes[] = sanitize_html_class($post->post_name);
  }

  return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar')) {
  // Define Sidebar Widget Area 1
  register_sidebar(array(
    'name' => __('Widget Area 1', 'html5blank'),
    'description' => __('Description for this widget-area...', 'html5blank'),
    'id' => 'widget-area-1',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));

  // Define Sidebar Widget Area 2
  register_sidebar(array(
    'name' => __('Widget Area 2', 'html5blank'),
    'description' => __('Description for this widget-area...', 'html5blank'),
    'id' => 'widget-area-2',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
  global $wp_widget_factory;
  remove_action('wp_head', array(
    $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
    'recent_comments_style'
  ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
  global $wp_query;
  $big = 999999999;
  echo paginate_links(array(
    'base' => str_replace($big, '%#%', get_pagenum_link($big)),
    'format' => '?paged=%#%',
    'current' => max(1, get_query_var('paged')),
    'total' => $wp_query->max_num_pages
  ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
  return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
  return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
  global $post;
  if (function_exists($length_callback)) {
    add_filter('excerpt_length', $length_callback);
  }
  if (function_exists($more_callback)) {
    add_filter('excerpt_more', $more_callback);
  }
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
  global $post;
  return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
  return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html)
{
  $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
  return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar($avatar_defaults)
{
  $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
  $avatar_defaults[$myavatar] = "Custom Gravatar";
  return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
  if (!is_admin()) {
    if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
      wp_enqueue_script('comment-reply');
    }
  }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
  $GLOBALS['comment'] = $comment;
  extract($args, EXTR_SKIP);

  if ('div' == $args['style']) {
    $tag = 'div';
    $add_below = 'comment';
  } else {
    $tag = 'li';
    $add_below = 'div-comment';
  }
?>
  <!-- heads up: starting < for the html tag (li or div) in the next line: -->
  <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
      <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
      <?php endif; ?>
      <div class="comment-author vcard">
        <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['180']); ?>
        <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
        <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
        <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
          <?php
          printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'), '  ', '');
                                                                                    ?>
      </div>

      <?php comment_text() ?>

      <div class="reply">
        <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      <?php if ('div' != $args['style']) : ?>
      </div>
    <?php endif; ?>
  <?php }

/*------------------------------------*\
    Actions + Filters + ShortCodes
    \*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
    Custom Post Types
    \*------------------------------------*/

// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_post_type_html5()
{
  register_taxonomy_for_object_type('category', 'pre-embarque'); // Register Taxonomies for Category
  register_taxonomy_for_object_type('post_tag', 'pre-embarque');
  register_post_type(
    'pre-embarque', // Register Custom Post Type
    array(
      'labels' => array(
        'name' => __('Pré Embarque', 'html5blank'), // Rename these to suit
        'singular_name' => __('Pré Embarque', 'html5blank'),
        'add_new' => __('Add New', 'html5blank'),
        'add_new_item' => __('Add New Pré Embarque', 'html5blank'),
        'edit' => __('Edit', 'html5blank'),
        'edit_item' => __('Edit Pré Embarque', 'html5blank'),
        'new_item' => __('New Pré Embarque', 'html5blank'),
        'view' => __('View Pré Embarque', 'html5blank'),
        'view_item' => __('View Pré Embarque', 'html5blank'),
        'search_items' => __('Search Pré Embarque', 'html5blank'),
        'not_found' => __('No Pré Embarque found', 'html5blank'),
        'not_found_in_trash' => __('No Pré Embarque found in Trash', 'html5blank')
      ),
      'public' => true,
      'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
      'has_archive' => true,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail'
      ), // Go to Dashboard Custom HTML5 Blank post for supports
      'can_export' => true, // Allows export in Tools > Export
      'taxonomies' => array(
        'post_tag',
        'category'
      ) // Add Category and Post Tags support
    )
  );

  register_taxonomy_for_object_type('category', 'webinars'); // Register Taxonomies for Category
  register_taxonomy_for_object_type('post_tag', 'webinars');
  register_post_type(
    'webinars', // Register Custom Post Type
    array(
      'labels' => array(
        'name' => __('Webinars', 'html5blank'), // Rename these to suit
        'singular_name' => __('Webinars', 'html5blank'),
        'add_new' => __('Add New', 'html5blank'),
        'add_new_item' => __('Add New Webinars', 'html5blank'),
        'edit' => __('Edit', 'html5blank'),
        'edit_item' => __('Edit Webinars', 'html5blank'),
        'new_item' => __('New Webinars', 'html5blank'),
        'view' => __('View Webinars', 'html5blank'),
        'view_item' => __('View Webinars', 'html5blank'),
        'search_items' => __('Search Webinars', 'html5blank'),
        'not_found' => __('No Webinars found', 'html5blank'),
        'not_found_in_trash' => __('No Webinars found in Trash', 'html5blank')
      ),
      'public' => true,
      'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
      'has_archive' => true,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail'
      ), // Go to Dashboard Custom HTML5 Blank post for supports
      'can_export' => true, // Allows export in Tools > Export
      'taxonomies' => array(
        'post_tag',
        'category'
      ) // Add Category and Post Tags support
    )
  );
}

/*------------------------------------*\
    ShortCode Functions
    \*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
  return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
  return '<h2>' . $content . '</h2>';
}



function is_logged()
{
  if (!is_user_logged_in()) {
    if (wp_redirect(home_url('entrar'))) {
      exit;
    }
  }
}

function custom_login_logo()
{

  switch ($_GET["layout"]) {
    case '2':

      //include( './includes/templates/layout-iframe.php');

      get_template_part('includes/templates/layout', 'iframe');
      break;

    default:
      //include( './includes/templates/layout-login.php');

      get_template_part('includes/templates/layout', 'login');
      break;
  }

  // $pos = strpos($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_SCHEME'] . '://' .$_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);

  // if ( $pos === false ) {
  // }

  // else {
  // }



}

add_action('login_head', 'custom_login_logo');



if (!function_exists('retrieve_password2')) :

  function retrieve_password2()
  {
    $errors = new WP_Error();

    if (empty($_POST['user_login']) || !is_string($_POST['user_login'])) {
      $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
    } elseif (strpos($_POST['user_login'], '@')) {
      $user_data = get_user_by('email', trim(wp_unslash($_POST['user_login'])));
      if (empty($user_data))
        $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
    } else {
      $login = trim($_POST['user_login']);
      $user_data = get_user_by('login', $login);
    }

    /**
     * Fires before errors are returned from a password reset request.
     *
     * @since 2.1.0
     * @since 4.4.0 Added the `$errors` parameter.
     *
     * @param WP_Error $errors A WP_Error object containing any errors generated
     *                         by using invalid credentials.
     */
    do_action('lostpassword_post', $errors);

    if ($errors->get_error_code())
      return $errors;

    if (!$user_data) {
      $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
      return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key = get_password_reset_key($user_data);

    if (is_wp_error($key)) {
      return $key;
    }

    if (is_multisite()) {
      $site_name = get_network()->site_name;
    } else {
      /*
     * The blogname option is escaped with esc_html on the way into the database
     * in sanitize_option we want to reverse this for the plain text arena of emails.
     */
      $site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    }

    $message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
    /* translators: %s: site name */
    $message .= sprintf(__('Site Name: %s'), $site_name) . "\r\n\r\n";
    /* translators: %s: user login */
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

    /* translators: Password reset email subject. %s: Site name */
    $title = sprintf(__('[%s] Password Reset'), $site_name);

    /**
     * Filters the subject of the password reset email.
     *
     * @since 2.8.0
     * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
     *
     * @param string  $title      Default email title.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    $title = apply_filters('retrieve_password_title', $title, $user_login, $user_data);

    /**
     * Filters the message body of the password reset mail.
     *
     * If the filtered message is empty, the password reset email will not be sent.
     *
     * @since 2.8.0
     * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    $message = apply_filters('retrieve_password_message', $message, $key, $user_login, $user_data);

    if ($message && !wp_mail($user_email, wp_specialchars_decode($title), $message))
      wp_die(__('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.'));

    return true;
  }


endif;

add_action('admin_menu', 'register_my_custom_menu_page');
function register_my_custom_menu_page()
{
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  add_menu_page('Relatórios', 'Relatórios', 'manage_options', 'page_relatorios', 'page_relatorios', 'dashicons-chart-area', 90);
}

function page_relatorios()
{
  require_once "pages-admin/relatorios/relatorio-contacts.php";
}


function get_my_email_template($all_contacts, $theads)
{
  $body = '';
  $body .= '<center><h2>Formulário Pré-embarque</h2><table id="relatorio1" class="widefat fixed" cellspacing="0"  style="border: 1px solid #000; "><tbody>';

  foreach ($all_contacts as $key => $_result) {
    $_values = array_values(json_decode(json_encode($_result), true));

    foreach ($_values as $key1 => $_result1) {

      $body .= '<tr class="alternate"><td class="column-columnname " style="padding: 10px;text-align: center; font-weight: bold;border-bottom: 1px solid black;">';

      $body .= $theads[$key1];

      $body .= ':</td><td class="column-columnname " style="padding: 10px;text-align: center;border-bottom: 1px solid black;">';

      $body .= $_result1;

      $body .= '</td></tr>';
    }
  }


  $body .= '</tbody></table></center>';

  return $body;
}


function parse_fields_to_email($fields_v)
{

  $all_fields = [
    [
      "title" => "CRUZEIRO",
      "fields" => []
    ],
    [
      "title" => "CONTRATANTE",
      "fields" => []
    ],
    [
      "title" => "HÓSPEDE 1",
      "fields" => []
    ],
    [
      "title" => "HÓSPEDE 2",
      "fields" => []
    ],
    [
      "title" => "HÓSPEDE 3",
      "fields" => []
    ],
    [
      "title" => "HÓSPEDE 4",
      "fields" => []
    ],
    [
      "title" => "CONTATO DE EMERGÊNCIA",
      "fields" => []
    ],
    [
      "title" => "SOLICITAÇÕES ESPECIAIS",
      "fields" => []
    ],
    [
      "title" => "TERMOS E CONDIÇÕES",
      "fields" => []
    ],
    [
      "title" => "CONTRATO",
      "fields" => []
    ]
  ];

  foreach ($fields_v as $key => $value) {

    if ($value) {

      $key_formated = explode(" HÓSPEDE ", substr($key, 4))[0]; // renomeando nome dos campos "1 - "

      switch ((string) $key) {

        case boolval(strpos($key, "1 - ") !== false):
          $all_fields[0]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "2 - ") !== false):
          $all_fields[1]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "3 - ") !== false):
          $all_fields[2]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "4 - ") !== false):
          $all_fields[3]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "5 - ") !== false):
          $all_fields[4]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "6 - ") !== false):
          $all_fields[5]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "7 - ") !== false):
          $all_fields[6]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "8 - ") !== false):
          $all_fields[7]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "9 - ") !== false):
          $all_fields[8]['fields'][] = [$key_formated => $value];
          break;

        case boolval(strpos($key, "10 - ") !== false):
          $all_fields[8]['fields'][] = [$key_formated => $value];
          break;
      }
    }
  }


  return $all_fields;
}

// $all_fields = [

//   [
//     "title" => "Grupos de campos 1", 
//     "fields" => [
//       ["name" => "Nome do contato", "value" => "Marcelo da Silva" ],
//       ["name" => "Nome do contato", "value" => "Marcelo da Silva" ],
//     ]
//   ],
//   [
//     "title" => "Grupos de campos 2", 
//     "fields" => [
//       ["name" => "Nome do contato", "value" => "Marcelo da Silva" ],
//       ["name" => "Nome do contato", "value" => "Marcelo da Silva" ],
//     ]
//   ],
// ];
function get_my_email_template_v2($all_fields, $page_id = '')
{
  // Linha com o titulo do grupos de campos  basta fazer um replace ##VALOR##
  $linha_header = '<tr style="background-color: #c3b48b;color: #fff; "> <td colspan="3" style="border-top: 20px solid #fff;"> <p style="margin:0px;font-weight: bold;padding: 7px 10px; font-size: 14px"> ##VALOR## </p> </td> </tr>';
  // Linha com nome do campo (esquerda) e valor (direita) basta fazer um replace ##CAMPO## e ##VALOR##
  $linha_campo_valor = '<tr> <td style="padding: 10px 8px; color: #003958;font-size:14px;"> ##CAMPO##: </td> <td style="padding: 10px 8px; color: #151414;font-size:14px;"> ##VALOR## </td> <td style="padding: 10px 8px;"></td> </tr>';

  // Comecando template
  $body = '';
  $body .= '<center> <table border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; font-size: 12px; width: 860px"> <tbody>';

  // Header
  //$image_header = get_template_directory_uri(). '/img/topo_email.jpg';
  $image_header = 'https://mycruiseconcierge.com.br/wp-content/themes/mycruiseconcierge.com.br/img/topo_email.jpg';
  $body .= '<tr > <td colspan="3"> <a target="_blank" href="https://mycruiseconcierge.com.br/"> <img style="display:block;border:0;width: 860px;" alt="" title="" src="' . $image_header . '"> </a> </td> </tr>';

  // Agradecimentos
  $body .= str_replace('##data_envio##', date("d/m/Y"), '<tr> <td colspan="3" style="padding-top: 30px; padding-bottom: 10px;"> <p style="color: #374555;"> Obrigado pelo preenchimento das Informações. <br> Data do envio: ##data_envio## </p> </td> </tr>');

  // Body Email
  foreach ($all_fields as $key => $_result) {

    if (count($_result['fields']) > 0) {

      $body .= str_replace('##VALOR##', $_result['title'], $linha_header);

      //if ($_result['title'] != "CONTRATO") {

      foreach ($_result['fields'] as $key1 => $_result1) {

        foreach ($_result1 as $key2 => $_result2) {

          $linha_key_val = str_replace('##VALOR##', $_result2, $linha_campo_valor);
          $body .= str_replace('##CAMPO##', $key2, $linha_key_val);
        }
      }
      //}


    }
  }


  $body .= '</tbody></table>';

  // Footer
  //$image_footer = get_template_directory_uri(). '/img/rodape_email.jpg';
  $image_footer = 'https://mycruiseconcierge.com.br/wp-content/themes/mycruiseconcierge.com.br/img/rodape_email.jpg';
  $body .= '<table border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; font-size: 12px; width: 860px; text-align:center;"> <tbody> <tr> <td colspan="3"> <a target="_blank" href="https://mycruiseconcierge.com.br/"> <img style="display:block;border:0;margin: auto;" src="' . $image_footer . '"> </a> </td> </tr> </tbody> </table>';

  $body .= '</center>';

  return $body;
}

function is_wplogin()
{
  $ABSPATH_MY = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, ABSPATH);
  return ((in_array($ABSPATH_MY . 'wp-login.php', get_included_files()) || in_array($ABSPATH_MY . 'wp-register.php', get_included_files())) || (isset($_GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') || $_SERVER['PHP_SELF'] == '/wp-login.php');
}

function get_home_path_v1()
{
  $home    = set_url_scheme(get_option('home'), 'http');
  $siteurl = set_url_scheme(get_option('siteurl'), 'http');
  if (!empty($home) && 0 !== strcasecmp($home, $siteurl)) {
    $wp_path_rel_to_home = str_ireplace($home, '', $siteurl); /* $siteurl - $home */
    $pos                 = strripos(str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']), trailingslashit($wp_path_rel_to_home));
    $home_path           = substr($_SERVER['SCRIPT_FILENAME'], 0, $pos);
    $home_path           = trailingslashit($home_path);
  } else {
    $home_path = ABSPATH;
  }

  return str_replace('\\', '/', $home_path);
}


function version()
{
  //return rand();
  return wp_get_theme()->get('Version');
}



add_filter('upload_mimes', 'add_custom_upload_mimes');
function add_custom_upload_mimes($existing_mimes)
{
  $existing_mimes['otf'] = 'application/x-font-otf';
  $existing_mimes['woff'] = 'application/x-font-woff';
  $existing_mimes['ttf'] = 'application/x-font-ttf';
  $existing_mimes['svg'] = 'image/svg+xml';
  $existing_mimes['eot'] = 'application/vnd.ms-fontobject';
  return $existing_mimes;
}

add_query_arg(array(
  'cbPages' => 'reservas',
), 'https://mycruiseconcierge.com.br/');
