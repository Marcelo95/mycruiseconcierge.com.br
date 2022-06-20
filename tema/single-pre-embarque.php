<?php


use App\DocSign\DocSign;

require_once(__DIR__ . '/docsign/DocSign.class.php');

$doc = new DocSign(get_the_permalink());


// $signer0 = [
// 	"auths" => ["api"],
// 	"name" => "Vasconcelos Serviços de Turismo LTDA",
// 	"email" => "thiago@pier1.com.br",
// 	//"birthday" => "22-22-2222",
// 	//"documentation" => "309.593.038-04", //CPF do signatário.
// 	"has_documentation" => false,
// 	"delivery" => "email" // "email"  o signatário receberá as notificações de confirmação de assinatura e de documento finalizado.
// ];

// echo "<pre class='hide'>";
// $my_postid = get_the_id(); //This is page id or post id
// $arquivo_pdf = get_field('arquivo_pdf', $my_postid);
// $content = get_home_path_v1() . $arquivo_pdf;
// $content = str_replace( get_option( 'siteurl' ), "", $content);
// $content = str_replace("public_html//wp-content", "public_html/wp-content", $content);

// $file_test = $doc->new_file( $content );

// print_r( $file_test  );

// echo "</pre>";

// return;
// exit;


if (isset($_GET["format"]) || isset($_POST["format"])) {


	//var_dump( $doc->_generate_hash("489d2d6b-c4a8-44a7-a609-60731b8d6ba2") );


	if (isset($_GET["action"]) && $_GET["action"] == "createDoc") {
		header('Content-Type: application/json');

		try {

			$my_postid = get_the_id(); //This is page id or post id

			if (function_exists("get_field") && $arquivo_pdf = get_field('arquivo_pdf', $my_postid)) {

				$content = get_home_path_v1() . $arquivo_pdf;
				$content = str_replace(get_option('siteurl'), "", $content);
				$content = str_replace("public_html//wp-content", "public_html/wp-content", $content);
			} else {
				$content_post = get_post($my_postid);
				$content = $content_post->post_content;
				$content = wp_filter_nohtml_kses(str_replace(']]>', ']]&gt;', $content));
			}


			$signer = [
				"auths" => ["email"],
				// "phone_number" => "",
				//birthday => "1983-03-31",
				"has_documentation" => true,
				"delivery" => "email" // "email"  o signatário receberá as notificações de confirmação de assinatura e de documento finalizado.
			];


			if (isset($_POST["name"])) {
				$signer["name"] = $_POST["name"];
			}

			if (isset($_POST["email"])) {
				$signer["email"] = $_POST["email"];
			}

			if (isset($_POST["cpf"])) {
				$signer["documentation"] = $_POST["cpf"]; //CPF do signatário.
			}

			if (isset($_POST["birthday"])) {
				$signer["birthday"] = $_POST["birthday"]; // "1983-03-31"
			}


			if (isset($_POST["is_agente"]) && boolval($_POST["is_agente"])) { // envia para o contratante e agente

				// $signer_contrante["has_documentation"] = false;

				// if (isset($_POST["nome_agente"])) {
				// 	$signer_contrante["name"] = $_POST["nome_agente"];
				// }

				// if (isset($_POST["agente_email"])) {
				// 	$signer_contrante["email"] = $_POST["agente_email"];
				// }

				$doc_create = $doc->create_doc_with_signer($content, $signer, true); // cria apenas o contratante e documento e o notifica via email				

				//$doc_create = $doc->create_doc_with_signer(null, $signer_pier, true, $doc_create["data"]["list"]["document_key"]); 

			} else {
				$doc_create = $doc->create_doc_with_signer($content, $signer, false);
			}

			// add Pier com assinatura automatica
			$signer_pier = $doc->_get_signer_API();
			$signer_pier_create = $doc->create_doc_with_signer(null, $signer_pier, false, $doc_create["data"]["list"]["document_key"]);

			$data = json_encode($doc_create["data"]);

			$status = $doc_create["status"];
		} catch (\Throwable $th) {

			$data = $th->getMessage();
			$status = $th->getCode();
		} finally {
			echo json_encode(compact("data", "status"));
		}
	} else if (isset($_GET["action"]) && $_GET["action"] == "getDocs") { // mostrar docs para download
		try {

			if (!isset($_GET["key"])) {
				throw new \Exception("Parametro key é obrigatório", 201);
			}
			$docs_links_to_downloads = $doc->get_documents($_GET["key"]);

			if (!isset($docs_links_to_downloads["data"]["document"])) {
				throw new \Exception("<center>Não foi possível abrir este documento.</center>", 401);
			}

			$docs_links_to_downloads = $docs_links_to_downloads["data"]["document"]["downloads"];


			//header("Location: $docs_links_to_downloads");
			$data .= "<center style='font-family: sans-serif;'>";
			$data .= "<h4>Seu documento está pronto, escolha dentre as opções abaixo:</h4>";

			$data .= "<p style='line-height: 30px;'>";

			if (isset($docs_links_to_downloads['original_file_url'])) {
				$data .= "Documento original: <a href='{$docs_links_to_downloads['original_file_url']}'>clique aqui</a><br>";
			}

			if (isset($docs_links_to_downloads['signed_file_url'])) {
				$data .= "Documento assinado: <a href='{$docs_links_to_downloads['signed_file_url']}'>clique aqui</a><br>";
			}

			if (isset($docs_links_to_downloads['ziped_file_url'])) {
				$data .= "Documentos ZIP: <a href='{$docs_links_to_downloads['ziped_file_url']}'>clique aqui</a><br>";
			}


			$data .= "</p>";

			$data .= "</center>";

			$status = 200;
		} catch (\Throwable $th) {
			$data = $th->getMessage();
			$status = $th->getCode();
		} finally {
			echo $data;
		}
	}


	exit;
	return;
}



get_header(); ?>


<section ng-app="meuApp" class="ng-cloak" ng-controller="formPreEmbarqueController">

	<h1 class="title2 text-center text-uppercase"> Informações de Pré-Embarque </h1>
	<p class="text-center"> <span class="font-3"> <?php the_title(); ?> </span> </p>



	<!-- <pre class="hide">
	{{ data  | json }}
	</pre> -->



	<div class="row passos">

		<div class="col-sm-2 passos-item" ng-class="{'active' : menu >= 1, 'actual' : menu == 1}" ng-click="toMenu(1, my_form.$valid)">
			<div class="separator"></div>
			<span>Cruzeiro</span>
			<i class="icon-checked" ng-show="my_form.$valid"></i>
		</div>
		<div class="col-sm-2 passos-item" ng-class="{'active' : menu >= 2, 'actual' : menu == 2 }" ng-click="toMenu(2, my_form.$valid)">
			<div class="separator"></div>
			<span>Contratante</span>
			<i class="icon-checked" ng-show="my_form2.$valid"></i>
		</div>
		<div class="col-sm-2 passos-item " ng-class="{'active' : menu >= 3, 'actual' : menu == 3 }" ng-click="toMenu(3, (my_form2.$valid && isDataDeEmbarqueValid(data.contratante.data_validade_contratante)))">
			<div class="separator"></div>
			<span>Hóspedes</span>
			<i class="icon-checked" ng-show="my_form3.$valid"></i>
		</div>
		<div class="col-sm-2 passos-item " ng-class="{'active' : menu >= 4, 'actual' : menu == 4 }" ng-click="toMenu(4, (my_form3.$valid && isDataDeEmbarqueValid(data.hospede.data_validade_hospede_1) && data.hospede.quantidade_hospedes==1 || isDataDeEmbarqueValid(data.hospede.data_validade_hospede_2) && data.hospede.quantidade_hospedes==2 || isDataDeEmbarqueValid(data.hospede.data_validade_hospede_3) && data.hospede.quantidade_hospedes==3 || isDataDeEmbarqueValid(data.hospede.data_validade_hospede_4) && data.hospede.quantidade_hospedes==4 ))">
			<div class="separator"></div>
			<span>Contato de <br> emergência</span>
			<i class="icon-checked" ng-show="my_form4.$valid"></i>
		</div>
		<div class="col-sm-2 passos-item " ng-class="{'active' : menu >= 5, 'actual' : menu == 5 }" ng-click="toMenu(5, my_form4.$valid)">
			<div class="separator"></div>
			<span>Solicitações <br> especiais</span>
			<i class="icon-checked" ng-show="my_form5.$valid"></i>
		</div>
		<div class="col-sm-2 passos-item " ng-class="{'active' : menu >= 6, 'actual' : menu == 6 }" ng-click="toMenu(6, my_form5.$valid)">
			<span>Termos e <br> Condições</span>
			<i class="icon-checked" ng-show="my_form6.$valid"></i>
		</div>

	</div>



	<form autocomplete="off" ng-show="menu==1" name="my_form" ng-class="['mt-20', {'form-submited' : myForm.is_submitted}]" ng-init="data.navio='<?php the_title(); ?>'; data.user_id= <?php echo get_current_user_id(); ?>; data.page_id=<?php echo get_the_ID(); ?>">

		<div class="row">
			<div class="col-sm-12">
				<p class="titleBox">
					Preencher as informações do cruzeiro e agência de viagens.
				</p>
			</div>


			<div class="col-sm-3">
				<div class="form-group" ng-class="{'has-error-disable' : my_form.nome_navio.$invalid  }">
					<?php
					if (function_exists("get_field") && $ships_names = get_field('ships_name', get_the_id())) {
						$ships_names = explode("\n", $ships_names);

					?>
						<select class="input-placeholder input-select form-control" required="" placeholder="*nome do navio" name="nome_navio" id="nome_navio" ng-model="data.cruzeiro.nome_navio">
							<option value="">*nome do navio</option>

							<?php foreach ($ships_names as $key => $ship_name) { ?>
								<option value="<?php echo $ship_name; ?>"><?php echo $ship_name; ?></option>
							<?php } ?>

						</select>
					<?php
					} else {
					?>
						<input type="text" class="input-placeholder" required="" placeholder="*nome do navio" name="nome_navio" id="nome_navio" ng-model="data.cruzeiro.nome_navio">

					<?php
					}

					?>




				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group" ng-class="{'has-error-disable' : my_form.num_reserva.$invalid  }">
					<input type="text" class="input-placeholder" required="" placeholder="*n° da reserva" name="num_reserva" id="num_reserva" ng-model="data.cruzeiro.num_reserva">
				</div>

			</div>

			<div class="col-sm-6">
				<div class="row input-daterange">
					<div class="col-sm-6">
						<div class="form-group" ng-class="{'has-error-disable' : my_form.data_embarque.$invalid  }">
							<input type="text" class="input-placeholder data-futura-embarque" ng-pattern='pattern_DATA' required="" placeholder="*data de embarque" name="data_embarque" id="data_embarque" ng-model="data.cruzeiro.data_embarque">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group" ng-class="{'has-error-disable' : my_form.data_desembarque.$invalid  }">
							<input type="text" class="input-placeholder data-futura-desembarque" ng-pattern='pattern_DATA' required="" placeholder="*data de desembarque" name="data_desembarque" id="data_desembarque" ng-model="data.cruzeiro.data_desembarque">
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group" ng-class="{'has-error-disable' : my_form.is_agente.$invalid  }" ng-init="cruzeiro.is_agente=0">
					<b class="pergunta">*Você é um agente de viagem?</b>
					<div style="display: inline-block;">
						<input type="radio" name="is_agente" ng-model="data.cruzeiro.is_agente" class="toggleRadio" id="is_agente_1" ng-value="1">
						<label for="is_agente_1" class="toggleStatusRadio"></label> Sim
					</div>
					<div style="display: inline-block;">
						<input type="radio" name="is_agente" ng-model="data.cruzeiro.is_agente" class="toggleRadio" id="is_agente_0" ng-value="0" ng-checked="true" checked>
						<label for="is_agente_0" class="toggleStatusRadio"></label> Não
					</div>
				</div>
			</div>
			<div class="clearfix"></div>

			<div class="col-sm-6" ng-show="data.cruzeiro.is_agente==1">
				<div class="form-group " ng-class="{'has-error-disable' : my_form.nome_agencia.$invalid  }">
					<input type="text" class="input-placeholder" ng-required="data.cruzeiro.is_agente==1" placeholder="*nome da agência" name="nome_agencia" id="nome_agencia" ng-model="data.cruzeiro.nome_agencia">
				</div>
			</div>
			<div class="col-sm-6" ng-show="data.cruzeiro.is_agente==1">
				<div class="form-group" ng-class="{'has-error-disable' : my_form.cnpj_agencia.$invalid  }">
					<input type="text" class="input-placeholder cnpj" ng-required="data.cruzeiro.is_agente==1" placeholder="CNPJ da agência" name="cnpj_agencia" id="cnpj_agencia" ng-model="data.cruzeiro.cnpj_agencia">
				</div>
			</div>


			<div class="col-sm-6" ng-show="data.cruzeiro.is_agente==1">
				<div class="form-group" ng-class="{'has-error-disable' : my_form.nome_agente.$invalid  }">
					<input type="text" class="input-placeholder" name="nome_agente" ng-required="data.cruzeiro.is_agente==1" placeholder="*nome completo do agente de viagem" id="nome_agente" ng-model="data.cruzeiro.nome_agente">
				</div>
			</div>


			<div class="col-sm-6" ng-show="data.cruzeiro.is_agente==1">
				<div class="form-group" ng-class="{'has-error-disable' : my_form.agente_email.$invalid  }">
					<input type="email" class="input-placeholder" name="agente_email" ng-required="data.cruzeiro.is_agente==1" placeholder="*e-mail" id="agente_email" ng-model="data.cruzeiro.agente_email">
				</div>
			</div>
			<div class="col-sm-12">
				<div class="row">

					<div class="col-xs-6">
						<b class="text-required">*Campos obrigatórios</b>
					</div>
					<div class="col-xs-6 text-right">


						<button type="submit" class="botao1 btn text-uppercase" data-disabledng-disabled="my_form.$invalid" ng-click="myForm.is_submitted=1; toMenu(2, my_form.$valid)">
							PRÓXIMO
						</button>
					</div>
				</div>
			</div>




		</div>


	</form>

	<form autocomplete="off" ng-show="menu==2" name="my_form2" ng-class="['mt-20', {'form-submited' : myForm2.is_submitted}]">
		<div class="col-sm-12 text-center">
			<p class="titleBox">
				O Contratante é o cliente responsável pelo pagamento do cruzeiro. <br />
				As informações devem ser preenchidas conforme constam no seu passaporte.
			</p>
			<div class="clearfix mt-20"></div>
		</div>
		<div class="col-sm-6 ">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.nome_do_contratante.$invalid  }">
				<input type="text" class="input-placeholder" name="nome_do_contratante" required="" placeholder="*nome completo do contratante" id="nome_do_contratante" ng-model="data.contratante.nome_do_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.data_nascimento_contratante.$invalid  }">
				<input type="text" class="data input-placeholder" ng-pattern='pattern_DATA' name="data_nascimento_contratante" required="" placeholder="*data de nascimento (+18 anos)" id="data_nascimento_contratante" ng-model="data.contratante.data_nascimento_contratante">
				<div style="color: red; font-size: 12px;" ng-show="data.contratante.data_nascimento_contratante && !isMaior(data.contratante.data_nascimento_contratante)">*Precisa ser maior que 18 anos.</div>

			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.nacionalidade_contratante.$invalid  }">
				<select class="input-placeholder input-select form-control" required name="nacionalidade_contratante" id="nacionalidade_contratante" required="" ng-model="data.contratante.nacionalidade_contratante" ng-options="model as model for model in nacionalidades">
					<option value="">*nacionalidade</option>
				</select>

			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.local_nascimento_contratante.$invalid  }">
				<input type="text" class="input-placeholder" name="local_nascimento_contratante" required="" placeholder="*local de nascimento" id="local_nascimento_contratante" ng-model="data.contratante.local_nascimento_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.numero_passaporte_contratante.$invalid  }">
				<input type="text" class="input-placeholder" name="numero_passaporte_contratante" required="" placeholder="*nº passaporte com data validade acima de 6 meses" id="numero_passaporte_contratante" ng-model="data.contratante.numero_passaporte_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.local_emissao_contratante.$invalid  }">
				<input type="text" class="input-placeholder" name="local_emissao_contratante" required="" placeholder="*local de emissão" id="local_emissao_contratante" ng-model="data.contratante.local_emissao_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.data_expedicao_contratante.$invalid  }">
				<input type="text" class="input-placeholder data-passada" ng-pattern='pattern_DATA' name="data_expedicao_contratante" required="" placeholder="*data de expedição" id="data_expedicao_contratante" ng-model="data.contratante.data_expedicao_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-forced' : my_form2.data_validade_contratante.$invalid && data.contratante.data_validade_contratante || data.contratante.data_validade_contratante && !isDataDeEmbarqueValid(data.contratante.data_validade_contratante) }">
				<input type="text" class="input-placeholder data-futura" ng-pattern='pattern_DATA' name="data_validade_contratante" required="" placeholder="*data de validade" id="data_validade_contratante" ng-model="data.contratante.data_validade_contratante">
				<div style="color: red; font-size: 12px;" ng-show="data.contratante.data_validade_contratante && !isDataDeEmbarqueValid(data.contratante.data_validade_contratante)">*A data de validade do passaporte não pode ser inferior a 6 meses da data de desembarque</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.endereco_contratante.$invalid  }">
				<input type="text" class="input-placeholder" name="endereco_contratante" required="" placeholder="*endereço" id="endereco_contratante" ng-model="data.contratante.endereco_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.cidade_contratante.$invalid  }">
				<input type="text" class="input-placeholder" name="cidade_contratante" required="" placeholder="*cidade" id="cidade_contratante" ng-model="data.contratante.cidade_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group" ng-class="{'has-error-disable' : my_form2.uf_contratante.$invalid  }">
						<select class="input-placeholder input-select form-control" name="uf_contratante" id="uf_contratante" required="" ng-model="data.contratante.uf_contratante" ng-options="model as model for model in UFs">
							<option value="">*uf</option>
						</select>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="form-group" ng-class="{'has-error-disable' : my_form2.cep_contratante.$invalid  }">
						<input type="text" class="input-placeholder cep" name="cep_contratante" required="" placeholder="*cep" id="cep_contratante" ng-model="data.contratante.cep_contratante">
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.cpf_contratante.$invalid || cpfExist(data.contratante.cpf_contratante, 'cpf_contratante', 1 )  }">
				<input type="text" cpf-valido class="input-placeholder cpf" ng-class="{'cpf-invalid' : cpfExist(data.contratante.cpf_contratante, 'cpf_contratante', 1 )}" data-disable-ng-pattern='pattern_CPF' name="cpf_contratante" required="" placeholder="*cpf" id="cpf_contratante" ng-model="data.contratante.cpf_contratante">

				<div style="color: red; font-size: 12px;" ng-show="cpfExist(data.contratante.cpf_contratante, 'cpf_contratante', 1 )">*Este CPF já foi utilizado</div>

			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.email_contratante.$invalid  }">
				<input type="email" class="input-placeholder" name="email_contratante" required="" placeholder="*e-mail" id="email_contratante" ng-model="data.contratante.email_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.tel_residencial_contratante.$invalid  }">
				<input type="text" class="input-placeholder tel" name="tel_residencial_contratante" placeholder="tel residencial" id="tel_residencial_contratante" ng-model="data.contratante.tel_residencial_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.tel_comercial_contratante.$invalid  }">
				<input type="text" class="input-placeholder tel" name="tel_comercial_contratante" placeholder="tel comercial" id="tel_comercial_contratante" ng-model="data.contratante.tel_comercial_contratante">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form2.tel_celular_contratante.$invalid  }">
				<input type="text" class="input-placeholder tel" name="tel_celular_contratante" required="" placeholder="*tel celular" id="tel_celular_contratante" ng-model="data.contratante.tel_celular_contratante">
			</div>
		</div>

		<div class="col-sm-12">
			<div class="row">

				<div class="col-sm-6 mt-20">
					<b class="text-required">*Campos obrigatórios</b>
				</div>
				<div class="col-sm-6 mt-20 text-right">

					<button type="button" class="botao1 btn text-uppercase mobile" ng-click="toMenu(1)">
						VOLTAR
					</button>


					<button type="submit" class="botao1 btn text-uppercase" data-disabledng-disabled="my_form2.$invalid || !isDataDeEmbarqueValid(data.contratante.data_validade_contratante) || existCPFsInValido " ng-click="myForm2.is_submitted=1; toMenu(3, my_form2.$valid)">
						PRÓXIMO
					</button>
				</div>
			</div>
		</div>



	</form>



	<form autocomplete="off" ng-show="menu==3" name="my_form3" ng-class="['mt-20', {'form-submited' : myForm3.is_submitted}]">

		<div class="col-sm-12 text-center">
			<b class="pergunta col-sm-12 titleBox">*Qual o número de hóspedes na cabine?</b>
			<div style="display: inline-block;">
				<select class="input-select form-control" onchange="mydatapicker(300)" name="quantidade_hospedes" id="quantidade_hospedes" required="" ng-model="data.hospede.quantidade_hospedes" ng-init="data.hospede.quantidade_hospedes=''">
					<option value="">-</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
				</select>
			</div>

		</div>
		<div class="col-sm-12">
			<p class="titleBox" ng-show="data.hospede.quantidade_hospedes!=''">
				<br />
				As informações do(s) hóspede(s) devem ser preenchidas conforme constam no(s) seu(s) passaporte(s):
			</p>
		</div>

		<div class="col-sm-12">
			<div class="row hospede_1" ng-if="data.hospede.quantidade_hospedes>=1">
				<div class="col-sm-12">

					<div class="form-group" ng-class="{'has-error-disable' : my_form3.is_hospede_1.$invalid  }" ng-init="data.hospede.is_hospede_1=0">
						<b class="pergunta">1º HÓSPEDE: O Contratante e o 1° Hóspede são a mesma pessoa?</b>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.is_hospede_1" class="toggleRadio" id="is_hospede_1_1" ng-value="1" ng-click="setMesmoContratante(true, 1)">
							<label for="is_hospede_1_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.is_hospede_1" class="toggleRadio" id="is_hospede_1_0" ng-value="0" ng-click="setMesmoContratante(false, 1)" ng-checked="true" checked>
							<label for="is_hospede_1_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>
				<div class="col-sm-6 ">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nome_do_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder" name="nome_do_hospede_1" placeholder="*nome completo do contratante" id="nome_do_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.nome_do_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_nascimento_hospede_1.$invalid  }">
						<input type="text" class="data input-placeholder" ng-pattern='pattern_DATA' name="data_nascimento_hospede_1" placeholder="*data de nascimento" id="data_nascimento_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.data_nascimento_hospede_1">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_nascimento_hospede_1 && !isAdult(data.hospede.data_nascimento_hospede_1)">*Não pode ter menos que 1 ano na data da viagem</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nacionalidade_hospede_1.$invalid  }">
						<select class="input-placeholder input-select form-control" required name="nacionalidade_hospede_1" id="nacionalidade_hospede_1" required="" ng-model="data.hospede.nacionalidade_hospede_1" ng-options="model as model for model in nacionalidades">
							<option value="">*nacionalidade</option>
						</select>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_nascimento_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder" name="local_nascimento_hospede_1" placeholder="*local de nascimento" id="local_nascimento_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.local_nascimento_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.numero_passaporte_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder" name="numero_passaporte_hospede_1" placeholder="*nº passaporte com data validade acima de 6 meses" id="numero_passaporte_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.numero_passaporte_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_emissao_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder" name="local_emissao_hospede_1" placeholder="*local de emissão" id="local_emissao_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.local_emissao_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_expedicao_hospede_1.$invalid  }">
						<input type="text" class="data-passada input-placeholder" ng-pattern='pattern_DATA' name="data_expedicao_hospede_1" placeholder="*data de expedição" id="data_expedicao_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.data_expedicao_hospede_1">

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-forced' : my_form3.data_validade_hospede_1.$invalid && data.hospede.data_validade_hospede_1|| data.hospede.data_validade_hospede_1 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_1) }">
						<input type="text" class="input-placeholder data-futura" ng-pattern='pattern_DATA' name="data_validade_hospede_1" placeholder="*data de validade" id="data_validade_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.data_validade_hospede_1">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_validade_hospede_1 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_1)">*A data de validade do passaporte não pode ser inferior a 6 meses da data de desembarque</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.endereco_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder" name="endereco_hospede_1" placeholder="*endereço" id="endereco_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.endereco_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cidade_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder" name="cidade_hospede_1" placeholder="*cidade" id="cidade_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.cidade_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.uf_hospede_1.$invalid  }">
								<select class="input-placeholder input-select form-control" name="uf_hospede_1" id="uf_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.uf_hospede_1" ng-options="model as model for model in UFs">
									<option value="">*uf</option>
								</select>
							</div>
						</div>
						<div class="col-sm-9">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.cep_hospede_1.$invalid  }">
								<input type="text" class="input-placeholder cep" name="cep_hospede_1" placeholder="*cep" id="cep_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.cep_hospede_1">
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cpf_hospede_1.$invalid || cpfExist(data.hospede.cpf_hospede_1, 'cpf_hospede_1', data.hospede.is_hospede_1 ) }">
						<input type="text" cpf-valido class="cpf input-placeholder" ng-class="{'cpf-invalid' : cpfExist(data.hospede.cpf_hospede_1, 'cpf_hospede_1', data.hospede.is_hospede_1 )}" data-disable-ng-pattern='pattern_CPF' name="cpf_hospede_1" placeholder="*cpf" id="cpf_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.cpf_hospede_1">

						<div style="color: red; font-size: 12px;" ng-show="cpfExist(data.hospede.cpf_hospede_1, 'cpf_hospede_1', data.hospede.is_hospede_1 )">*Este CPF já foi utilizado</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.email_hospede_1.$invalid  }">
						<input type="email" class="input-placeholder" name="email_hospede_1" placeholder="*e-mail" id="email_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.email_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_residencial_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_residencial_hospede_1" placeholder="tel residencial" id="tel_residencial_hospede_1" ng-model="data.hospede.tel_residencial_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_comercial_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_comercial_hospede_1" placeholder="tel comercial" id="tel_comercial_hospede_1" ng-model="data.hospede.tel_comercial_hospede_1">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_celular_hospede_1.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_celular_hospede_1" placeholder="*tel celular" id="tel_celular_hospede_1" ng-required="data.hospede.quantidade_hospedes>=1" ng-model="data.hospede.tel_celular_hospede_1">
					</div>
				</div>

			</div>

			<div class="row hospede_2" ng-if="data.hospede.quantidade_hospedes>=2">
				<div class="col-sm-12">
					<div class="form-group">
						<b class="pergunta">2º HÓSPEDE: Possui o mesmo endereço do 1º Hóspede?</b>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_2" ng-model="is_hospede_2" class="toggleRadio" id="is_hospede_2_1" ng-value="1" ng-click="setMesmoEndereco(true, 2)">
							<label for="is_hospede_2_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_2" ng-model="is_hospede_2" class="toggleRadio" id="is_hospede_2_0" ng-value="0" ng-click="setMesmoEndereco(false, 2)" ng-checked="true" checked>
							<label for="is_hospede_2_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>

				<div class="col-sm-6 ">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nome_do_hospede_2.$invalid  }" ng-init="is_hospede_2=0">
						<input type="text" class="input-placeholder" name="nome_do_hospede_2" placeholder="*nome completo do hóspede" id="nome_do_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.nome_do_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_nascimento_hospede_2.$invalid  }">
						<input type="text" class="data input-placeholder" ng-pattern='pattern_DATA' name="data_nascimento_hospede_2" placeholder="*data de nascimento" id="data_nascimento_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.data_nascimento_hospede_2">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_nascimento_hospede_2 && !isAdult(data.hospede.data_nascimento_hospede_2)">*Não pode ter menos que 1 ano na data da viagem</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nacionalidade_hospede_2.$invalid  }">

						<select class="input-placeholder input-select form-control" required name="nacionalidade_hospede_2" id="nacionalidade_hospede_2" required="" ng-model="data.hospede.nacionalidade_hospede_2" ng-options="model as model for model in nacionalidades">
							<option value="">*nacionalidade</option>
						</select>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_nascimento_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder" name="local_nascimento_hospede_2" placeholder="*local de nascimento" id="local_nascimento_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.local_nascimento_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.numero_passaporte_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder" name="numero_passaporte_hospede_2" placeholder="*nº passaporte com data validade acima de 6 meses" id="numero_passaporte_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.numero_passaporte_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_emissao_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder" name="local_emissao_hospede_2" placeholder="*local de emissão" id="local_emissao_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.local_emissao_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_expedicao_hospede_2.$invalid  }">
						<input type="text" class="data-passada input-placeholder" ng-pattern='pattern_DATA' name="data_expedicao_hospede_2" placeholder="*data de expedição" id="data_expedicao_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.data_expedicao_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-forced' : my_form3.data_validade_hospede_2.$invalid && data.hospede.data_validade_hospede_2 || data.hospede.data_validade_hospede_2 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_2) }">
						<input type="text" class="input-placeholder data-futura" ng-pattern='pattern_DATA' name="data_validade_hospede_2" placeholder="*data de validade" id="data_validade_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.data_validade_hospede_2">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_validade_hospede_2 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_2)">*A data de validade do passaporte não pode ser inferior a 6 meses da data de desembarque</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.endereco_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder" name="endereco_hospede_2" placeholder="*endereço" id="endereco_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.endereco_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cidade_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder" name="cidade_hospede_2" placeholder="*cidade" id="cidade_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.cidade_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.uf_hospede_2.$invalid  }">
								<select class="input-placeholder input-select form-control" name="uf_hospede_2" id="uf_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.uf_hospede_2" ng-options="model as model for model in UFs">
									<option value="">*uf</option>
								</select>
							</div>
						</div>
						<div class="col-sm-9">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.cep_hospede_2.$invalid  }">
								<input type="text" class="input-placeholder cep" name="cep_hospede_2" placeholder="*cep" id="cep_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.cep_hospede_2">
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cpf_hospede_2.$invalid || cpfExist(data.hospede.cpf_hospede_2, 'cpf_hospede_2', 0 ) }">
						<input type="text" cpf-valido class="cpf input-placeholder" ng-class="{'cpf-invalid' : cpfExist(data.hospede.cpf_hospede_2, 'cpf_hospede_2', 0 )}" data-disable-ng-pattern='pattern_CPF' name="cpf_hospede_2" placeholder="*cpf" id="cpf_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.cpf_hospede_2">

						<div style="color: red; font-size: 12px;" ng-show="cpfExist(data.hospede.cpf_hospede_2, 'cpf_hospede_2', 0 )">*Este CPF já foi utilizado</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.email_hospede_2.$invalid  }">
						<input type="email" class="input-placeholder" name="email_hospede_2" placeholder="*e-mail" id="email_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.email_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_residencial_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_residencial_hospede_2" placeholder="tel residencial" id="tel_residencial_hospede_2" ng-model="data.hospede.tel_residencial_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_comercial_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_comercial_hospede_2" placeholder="tel comercial" id="tel_comercial_hospede_2" ng-model="data.hospede.tel_comercial_hospede_2">
					</div>
				</div>

				<div class="col-sm-6">

					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_celular_hospede_2.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_celular_hospede_2" placeholder="*tel celular" id="tel_celular_hospede_2" ng-required="data.hospede.quantidade_hospedes>=2" ng-model="data.hospede.tel_celular_hospede_2">
					</div>
				</div>

			</div>

			<div class="row hospede_3" ng-if="data.hospede.quantidade_hospedes>=3">

				<div class="col-sm-12">
					<div class="form-group">
						<b class="pergunta">3º HÓSPEDE: Possui o mesmo endereço do 1º Hóspede?</b>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_3" ng-model="is_hospede_3" class="toggleRadio" id="is_hospede_3_1" ng-value="1" ng-click="setMesmoEndereco(true, 3)">
							<label for="is_hospede_3_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_3" ng-model="is_hospede_3" class="toggleRadio" id="is_hospede_3_0" ng-value="0" ng-click="setMesmoEndereco(false, 3)" ng-checked="true" checked>
							<label for="is_hospede_3_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>
				<div class="col-sm-6 ">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nome_do_hospede_3.$invalid  }" ng-init="is_hospede_3=0">
						<input type="text" class="input-placeholder" name="nome_do_hospede_3" placeholder="*nome completo do hóspede" id="nome_do_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.nome_do_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_nascimento_hospede_3.$invalid  }">
						<input type="text" class="data input-placeholder" ng-pattern='pattern_DATA' name="data_nascimento_hospede_3" placeholder="*data de nascimento" id="data_nascimento_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.data_nascimento_hospede_3">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_nascimento_hospede_3 && !isAdult(data.hospede.data_nascimento_hospede_3)">*Não pode ter menos que 1 ano na data da viagem</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nacionalidade_hospede_3.$invalid  }">
						<select class="input-placeholder input-select form-control" required name="nacionalidade_hospede_3" id="nacionalidade_hospede_3" required="" ng-model="data.hospede.nacionalidade_hospede_3" ng-options="model as model for model in nacionalidades">
							<option value="">*nacionalidade</option>
						</select>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_nascimento_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder" name="local_nascimento_hospede_3" placeholder="*local de nascimento" id="local_nascimento_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.local_nascimento_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.numero_passaporte_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder" name="numero_passaporte_hospede_3" placeholder="*nº passaporte com data validade acima de 6 meses" id="numero_passaporte_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.numero_passaporte_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_emissao_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder" name="local_emissao_hospede_3" placeholder="*local de emissão" id="local_emissao_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.local_emissao_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_expedicao_hospede_3.$invalid  }">
						<input type="text" class="data-passada input-placeholder" ng-pattern='pattern_DATA' name="data_expedicao_hospede_3" placeholder="*data de expedição" id="data_expedicao_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.data_expedicao_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-forced' : my_form3.data_validade_hospede_3.$invalid && data.hospede.data_validade_hospede_3 || data.hospede.data_validade_hospede_3 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_3)  }">
						<input type="text" class="input-placeholder data-futura" ng-pattern='pattern_DATA' name="data_validade_hospede_3" placeholder="*data de validade" id="data_validade_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.data_validade_hospede_3">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_validade_hospede_3 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_3)">*A data de validade do passaporte não pode ser inferior a 6 meses da data de desembarque</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.endereco_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder" name="endereco_hospede_3" placeholder="*endereço" id="endereco_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.endereco_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cidade_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder" name="cidade_hospede_3" placeholder="*cidade" id="cidade_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.cidade_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.uf_hospede_3.$invalid  }">
								<select class="input-placeholder input-select form-control" name="uf_hospede_3" id="uf_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.uf_hospede_3" ng-options="model as model for model in UFs">
									<option value="">*uf</option>
								</select>
							</div>
						</div>
						<div class="col-sm-9">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.cep_hospede_3.$invalid  }">
								<input type="text" class="input-placeholder cep" name="cep_hospede_3" placeholder="*cep" id="cep_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.cep_hospede_3">
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cpf_hospede_3.$invalid || cpfExist(data.hospede.cpf_hospede_3, 'cpf_hospede_3', 0 )  }">
						<input type="text" cpf-valido class="cpf input-placeholder" ng-class="{'cpf-invalid' : cpfExist(data.hospede.cpf_hospede_3, 'cpf_hospede_3', 0 )}" data-disable-ng-pattern='pattern_CPF' name="cpf_hospede_3" placeholder="*cpf" id="cpf_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.cpf_hospede_3">

						<div style="color: red; font-size: 12px;" ng-show="cpfExist(data.hospede.cpf_hospede_3, 'cpf_hospede_3', 0 )">*Este CPF já foi utilizado</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.email_hospede_3.$invalid  }">
						<input type="email" class="input-placeholder" name="email_hospede_3" placeholder="*e-mail" id="email_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.email_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_residencial_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_residencial_hospede_3" placeholder="tel residencial" id="tel_residencial_hospede_3" ng-model="data.hospede.tel_residencial_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_comercial_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_comercial_hospede_3" placeholder="tel comercial" id="tel_comercial_hospede_3" ng-model="data.hospede.tel_comercial_hospede_3">
					</div>
				</div>

				<div class="col-sm-6">

					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_celular_hospede_3.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_celular_hospede_3" placeholder="*tel celular" id="tel_celular_hospede_3" ng-required="data.hospede.quantidade_hospedes>=3" ng-model="data.hospede.tel_celular_hospede_3">
					</div>
				</div>

			</div>

			<div class="row hospede_4" ng-if="data.hospede.quantidade_hospedes>=4">
				<div class="col-sm-12">
					<div class="form-group">
						<b class="pergunta">4º HÓSPEDE: Possui o mesmo endereço do 1º Hóspede?</b>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_4" ng-model="is_hospede_4" class="toggleRadio" id="is_hospede_4_1" ng-value="1" ng-click="setMesmoEndereco(true, 4)">
							<label for="is_hospede_4_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="is_hospede_4" ng-model="is_hospede_4" class="toggleRadio" id="is_hospede_4_0" ng-value="0" ng-click="setMesmoEndereco(false, 4)" ng-checked="true" checked>
							<label for="is_hospede_4_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>
				<div class="col-sm-6 ">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nome_do_hospede_4.$invalid  }" ng-init="is_hospede_4=0">
						<input type="text" class="input-placeholder" name="nome_do_hospede_4" placeholder="*nome completo do hóspede" id="nome_do_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.nome_do_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_nascimento_hospede_4.$invalid  }">
						<input type="text" class="data input-placeholder" ng-pattern='pattern_DATA' name="data_nascimento_hospede_4" placeholder="*data de nascimento" id="data_nascimento_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.data_nascimento_hospede_4">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_nascimento_hospede_4 && !isAdult(data.hospede.data_nascimento_hospede_4)">*Não pode ter menos que 1 ano na data da viagem</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.nacionalidade_hospede_4.$invalid  }">
						<select class="input-placeholder input-select form-control" required name="nacionalidade_hospede_4" id="nacionalidade_hospede_4" required="" ng-model="data.hospede.nacionalidade_hospede_4" ng-options="model as model for model in nacionalidades">
							<option value="">*nacionalidade</option>
						</select>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_nascimento_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder" name="local_nascimento_hospede_4" placeholder="*local de nascimento" id="local_nascimento_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.local_nascimento_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.numero_passaporte_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder" name="numero_passaporte_hospede_4" placeholder="*nº passaporte com data validade acima de 6 meses" id="numero_passaporte_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.numero_passaporte_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.local_emissao_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder" name="local_emissao_hospede_4" placeholder="*local de emissão" id="local_emissao_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.local_emissao_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.data_expedicao_hospede_4.$invalid  }">
						<input type="text" class="data-passada input-placeholder" ng-pattern='pattern_DATA' name="data_expedicao_hospede_4" placeholder="*data de expedição" id="data_expedicao_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.data_expedicao_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-forced' : my_form3.data_validade_hospede_4.$invalid && data.hospede.data_validade_hospede_4 || data.hospede.data_validade_hospede_4 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_4) }">
						<input type="text" class="input-placeholder data-futura" ng-pattern='pattern_DATA' name="data_validade_hospede_4" placeholder="*data de validade" id="data_validade_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.data_validade_hospede_4">
						<div style="color: red; font-size: 12px;" ng-show="data.hospede.data_validade_hospede_4 && !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_4)">*A data de validade do passaporte não pode ser inferior a 6 meses da data de desembarque</div>
					</div>
				</div>


				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.endereco_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder" name="endereco_hospede_4" placeholder="*endereço" id="endereco_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.endereco_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cidade_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder" name="cidade_hospede_4" placeholder="*cidade" id="cidade_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.cidade_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.uf_hospede_4.$invalid  }">
								<select class="input-placeholder input-select form-control" name="uf_hospede_4" id="uf_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.uf_hospede_4" ng-options="model as model for model in UFs">
									<option value="">*uf</option>
								</select>
							</div>
						</div>
						<div class="col-sm-9">
							<div class="form-group" ng-class="{'has-error-disable' : my_form3.cep_hospede_4.$invalid  }">
								<input type="text" class="input-placeholder cep" name="cep_hospede_4" placeholder="*cep" id="cep_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.cep_hospede_4">
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.cpf_hospede_4.$invalid || cpfExist(data.hospede.cpf_hospede_4, 'cpf_hospede_4', 0 ) }">
						<input type="text" cpf-valido class="cpf input-placeholder" ng-class="{'cpf-invalid' : cpfExist(data.hospede.cpf_hospede_4, 'cpf_hospede_4', 0 )}" data-disable-ng-pattern='pattern_CPF' name="cpf_hospede_4" placeholder="*cpf" id="cpf_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.cpf_hospede_4">

						<div style="color: red; font-size: 12px;" ng-show="cpfExist(data.hospede.cpf_hospede_4, 'cpf_hospede_4', 0 )">*Este CPF já foi utilizado</div>

					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.email_hospede_4.$invalid  }">
						<input type="email" class="input-placeholder" name="email_hospede_4" placeholder="*e-mail" id="email_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.email_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_residencial_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_residencial_hospede_4" placeholder="tel residencial" id="tel_residencial_hospede_4" ng-model="data.hospede.tel_residencial_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_comercial_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_comercial_hospede_4" placeholder="tel comercial" id="tel_comercial_hospede_4" ng-model="data.hospede.tel_comercial_hospede_4">
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group" ng-class="{'has-error-disable' : my_form3.tel_celular_hospede_4.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_celular_hospede_4" placeholder="*tel celular" id="tel_celular_hospede_4" ng-required="data.hospede.quantidade_hospedes>=4" ng-model="data.hospede.tel_celular_hospede_4">
					</div>
				</div>

			</div>
		</div>

		<div class="col-sm-12">
			<div class="row">

				<div class="col-sm-6 mt-20">
					<b class="text-required">*Campos obrigatórios</b>
				</div>
				<div class="col-sm-6 mt-20 text-right">

					<button type="button" class="botao1 btn text-uppercase mobile" ng-click="toMenu(2)">
						VOLTAR
					</button>

					<button type="submit" class="botao1 btn text-uppercase" data-disabledng-disabled="my_form3.$invalid || !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_1) && data.hospede.quantidade_hospedes==1 || !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_2) && data.hospede.quantidade_hospedes==2|| !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_3) && data.hospede.quantidade_hospedes==3 || !isDataDeEmbarqueValid(data.hospede.data_validade_hospede_4) && data.hospede.quantidade_hospedes==4" ng-click="myForm3.is_submitted=1; toMenu(4, my_form3.$valid)">
						PRÓXIMO
					</button>
				</div>
			</div>
		</div>



	</form>


	<form autocomplete="off" ng-show="menu==4" name="my_form4" ng-class="['mt-20', {'form-submited' : myForm4.is_submitted}]">
		<div class="col-sm-12">
			<p class="titleBox">
				Preencher as informações do contato de emergência dos hóspedes:
			</p>
		</div>
		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form4.relacao_com_o_contratante_emergencia.$invalid  }">
				<select class="input-placeholder input-select form-control" name="relacao_com_o_contratante_emergencia" id="relacao_com_o_contratante_emergencia" required="" ng-model="data.contato_emergencia.relacao_com_o_contratante_emergencia">
					<option value="">*relação com os hóspedes</option>
					<option value="Filha">Filha</option>
					<option value="Filho">Filho</option>
					<option value="Mãe">Mãe</option>
					<option value="Pai">Pai</option>
					<option value="Sogro(a)">Sogro(a)</option>
					<option value="Cônjuge">Cônjuge</option>
					<option value="Irmã">Irmã</option>
					<option value="Irmão">Irmão</option>
					<option value="Cunhado(a)">Cunhado(a)</option>
					<option value="Parceiro(a)">Parceiro(a)</option>
					<option value="Amigo(a)">Amigo(a)</option>
					<option value="Vizinho(a)">Vizinho(a)</option>
					<option value="Advogado(a)">Advogado(a)</option>
					<option value="Colega de trabalho">Colega de trabalho</option>
					<option value="Outro membro da família">Outro membro da família</option>
				</select>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form4.nome_completo_emergencia.$invalid  }">
				<input type="text" class="input-placeholder" name="nome_completo_emergencia" required="" placeholder="*nome completo" id="nome_completo_emergencia" ng-model="data.contato_emergencia.nome_completo_emergencia">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form4.endereco_contato_emergencia.$invalid  }">
				<input type="text" class="input-placeholder" name="endereco_contato_emergencia" required="" placeholder="*endereço" id="endereco_contato_emergencia" ng-model="data.contato_emergencia.endereco_contato_emergencia">
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form4.cidade_contato_emergencia.$invalid  }">
				<input type="text" class="input-placeholder" name="cidade_contato_emergencia" required="" placeholder="*cidade" id="cidade_contato_emergencia" ng-model="data.contato_emergencia.cidade_contato_emergencia">
			</div>
		</div>



		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group" ng-class="{'has-error-disable' : my_form4.uf_contato_emergencia.$invalid  }">
						<select class="input-placeholder input-select form-control" name="uf_contato_emergencia" id="uf_contato_emergencia" required="" ng-model="data.contato_emergencia.uf_contato_emergencia" ng-options="model as model for model in UFs">
							<option value="">*uf</option>
						</select>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="form-group" ng-class="{'has-error-disable' : my_form4.tel_contato_emergencia.$invalid  }">
						<input type="text" class="input-placeholder tel" name="tel_contato_emergencia" required="" placeholder="*telefone" id="tel_contato_emergencia" ng-model="data.contato_emergencia.tel_contato_emergencia">
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group" ng-class="{'has-error-disable' : my_form4.email_contato_emergencia.$invalid  }">
				<input type="email" class="input-placeholder" name="email_contato_emergencia" required="" placeholder="*e-mail" id="email_contato_emergencia" ng-model="data.contato_emergencia.email_contato_emergencia">
			</div>
		</div>

		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-6 mt-20">
					<b class="text-required">*Campos obrigatórios</b>
				</div>
				<div class="col-sm-6 mt-20 text-right">

					<button type="button" class="botao1 btn text-uppercase mobile" ng-click="toMenu(3)">
						VOLTAR
					</button>

					<button type="button" class="botao1 btn text-uppercase" data-disabledng-disabled="my_form4.$invalid" ng-click="myForm4.is_submitted=1; toMenu(5, my_form4.$valid)">
						PRÓXIMO
					</button>
				</div>
			</div>
		</div>

	</form>

	<form autocomplete="off" ng-show="menu==5" name="my_form5" ng-class="['mt-20', {'form-submited' : myForm5.is_submitted}]">
		<div class="col-sm-12">
			<p class="titleBox">
				Preencher as solicitações especiais do(s) hóspede(s) para o cruzeiro:
			</p>
		</div>
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-8">
					Alguns dos hóspedes necessita de cuidados médicos especiais?
				</div>
				<div class="col-sm-4">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.cuidados_medicos.$invalid  }" ng-init="data.solicitacao_especiais.cuidados_medicos=0">
						<div style="display: inline-block;">
							<input type="radio" name="cuidados_medicos" ng-model="data.solicitacao_especiais.cuidados_medicos" class="toggleRadio" id="cuidados_medicos_1" ng-value="1">
							<label for="cuidados_medicos_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="cuidados_medicos" ng-model="data.solicitacao_especiais.cuidados_medicos" class="toggleRadio" id="cuidados_medicos_0" ng-value="0" ng-checked="true" checked>
							<label for="cuidados_medicos_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>

				<div class="col-sm-12" ng-show="data.solicitacao_especiais.cuidados_medicos==1">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.cuidados_medicos_observacao.$invalid  }">
						<input type="text" name="cuidados_medicos_observacao" ng-required="data.solicitacao_especiais.cuidados_medicos" placeholder="*Medicamentos? Cadeiras de rodas? Aparelho respiratório? Etc..." id="cuidados_medicos_observacao" ng-model="data.solicitacao_especiais.cuidados_medicos_observacao">
					</div>

				</div>
			</div>

		</div>

		<div class="col-sm-12">
			<div class="row">

				<div class="col-sm-8">
					Há hóspedes gestantes? Hóspedes que entrarem na 24° semana de gestação até o último dia do Cruzeiro não poderão reservar ou embarcar no navio.
				</div>
				<div class="col-sm-4">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.is_gestante.$invalid  }" ng-init="data.solicitacao_especiais.is_gestante=0">
						<div style="display: inline-block;">
							<input type="radio" name="is_gestante" ng-model="data.solicitacao_especiais.is_gestante" class="toggleRadio" id="is_gestante_1" ng-value="1">
							<label for="is_gestante_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="is_gestante" ng-model="data.solicitacao_especiais.is_gestante" class="toggleRadio" id="is_gestante_0" ng-value="0" ng-checked="true" checked>
							<label for="is_gestante_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>

				<div class="col-sm-12" ng-show="data.solicitacao_especiais.is_gestante==1">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.is_gestante_observacao.$invalid  }">
						<label for="" class="mobile">Em qual semana da gestação a hóspede estará na data de desembarque?</label>
						<input type="text" name="is_gestante_observacao" ng-required="data.solicitacao_especiais.is_gestante" placeholder="*Em qual semana da gestação a hóspede estará na data de desembarque?" id="is_gestante_observacao" ng-model="data.solicitacao_especiais.is_gestante_observacao">
					</div>

				</div>
			</div>

		</div>


		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-8">
					Haverá algum bebê com menos de 1 (um) ano de idade na data de embarque? Bebês com menos de um ano poderão ter restrições para o embarque.

				</div>
				<div class="col-sm-4">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.tem_menor_de_um_ano.$invalid  }" ng-init="data.solicitacao_especiais.tem_menor_de_um_ano=0">
						<div style="display: inline-block;">
							<input type="radio" name="tem_menor_de_um_ano" ng-model="data.solicitacao_especiais.tem_menor_de_um_ano" class="toggleRadio" id="tem_menor_de_um_ano_1" ng-value="1">
							<label for="tem_menor_de_um_ano_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="tem_menor_de_um_ano" ng-model="data.solicitacao_especiais.tem_menor_de_um_ano" class="toggleRadio" id="tem_menor_de_um_ano_0" ng-value="0" ng-checked="true" checked>
							<label for="tem_menor_de_um_ano_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>

				<div class="col-sm-12" ng-show="data.solicitacao_especiais.tem_menor_de_um_ano==1">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.tem_menor_de_um_ano_observacao.$invalid  }">
						<input type="text" name="tem_menor_de_um_ano_observacao" ng-required="data.solicitacao_especiais.tem_menor_de_um_ano" placeholder="*Por favor detalhar" id="tem_menor_de_um_ano_observacao" ng-model="data.solicitacao_especiais.tem_menor_de_um_ano_observacao">
					</div>

				</div>
			</div>

		</div>


		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-8">
					Algum dos hóspedes possui restrição alimentar?

				</div>
				<div class="col-sm-4">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.possui_restricao_alimentar.$invalid  }" ng-init="data.solicitacao_especiais.possui_restricao_alimentar=0">
						<div style="display: inline-block;">
							<input type="radio" name="possui_restricao_alimentar" ng-model="data.solicitacao_especiais.possui_restricao_alimentar" class="toggleRadio" id="possui_restricao_alimentar_1" ng-value="1">
							<label for="possui_restricao_alimentar_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="possui_restricao_alimentar" ng-model="data.solicitacao_especiais.possui_restricao_alimentar" class="toggleRadio" id="possui_restricao_alimentar_0" ng-value="0" ng-checked="true" checked>
							<label for="possui_restricao_alimentar_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>

				<div class="col-sm-12" ng-show="data.solicitacao_especiais.possui_restricao_alimentar==1">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.possui_restricao_alimentar_observacao.$invalid  }">
						<input type="text" name="possui_restricao_alimentar_observacao" ng-required="data.solicitacao_especiais.possui_restricao_alimentar" placeholder="*Vegetariano? Intolerância a lactose? Glúten? Etc..." id="possui_restricao_alimentar_observacao" ng-model="data.solicitacao_especiais.possui_restricao_alimentar_observacao">
					</div>

				</div>

			</div>

		</div>


		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-8">
					Será celebrada alguma data especial durante o cruzeiro?

				</div>
				<div class="col-sm-4">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.vai_celebrar_data_especial_abordo.$invalid  }" ng-init="data.solicitacao_especiais.vai_celebrar_data_especial_abordo=0">
						<div style="display: inline-block;">
							<input type="radio" name="vai_celebrar_data_especial_abordo" ng-model="data.solicitacao_especiais.vai_celebrar_data_especial_abordo" class="toggleRadio" id="vai_celebrar_data_especial_abordo_1" ng-value="1">
							<label for="vai_celebrar_data_especial_abordo_1" class="toggleStatusRadio"></label> Sim
						</div>
						<div style="display: inline-block;">
							<input type="radio" name="vai_celebrar_data_especial_abordo" ng-model="data.solicitacao_especiais.vai_celebrar_data_especial_abordo" class="toggleRadio" id="vai_celebrar_data_especial_abordo_0" ng-value="0" ng-checked="true" checked>
							<label for="vai_celebrar_data_especial_abordo_0" class="toggleStatusRadio"></label> Não
						</div>
					</div>
				</div>

				<div class="col-sm-12" ng-show="data.solicitacao_especiais.vai_celebrar_data_especial_abordo==1">
					<div class="form-group" ng-class="{'has-error-disable' : my_form5.vai_celebrar_data_especial_abordo_observacao.$invalid  }">
						<input type="text" name="vai_celebrar_data_especial_abordo_observacao" ng-required="data.solicitacao_especiais.vai_celebrar_data_especial_abordo" placeholder="*Aniversário? Bodas de Casamento? Lua de Mel? Etc..." id="vai_celebrar_data_especial_abordo_observacao" ng-model="data.solicitacao_especiais.vai_celebrar_data_especial_abordo_observacao">
					</div>

				</div>
			</div>

		</div>

		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-6 mt-20">
					<b class="text-required">*Campos obrigatórios</b>
				</div>
				<div class="col-sm-6 mt-20 text-right">

					<button type="button" class="botao1 btn text-uppercase mobile" ng-click="toMenu(4)">
						VOLTAR
					</button>

					<button type="submit" class="botao1 btn text-uppercase" data-disabledng-disabled="my_form5.$invalid" ng-click="myForm5.is_submitted=1; toMenu(6, my_form5.$valid)">
						PRÓXIMO
					</button>
				</div>
			</div>
		</div>

	</form>

	<form autocomplete="off" ng-show="menu==6" name="my_form6" ng-class="['mt-20', {'form-submited' : myForm6.is_submitted}]">



		<div class="row" ng-hide="loadingSend || successSend">
			<div class="col-sm-12">
				TERMOS E CONDIÇÕES - <small>Obs. O contratante receberá um token via email para validar a assinatura.
			</div>

			<div class="col-sm-12">
				<div class="termos-e-condicoes">

					<?php $doc->widget(); ?>

				</div>
			</div>
			<!-- <div class="col-sm-12">
				<label class="li_e_concordo_text">
					<input required type="checkbox" name="li_e_concordo" ng-model="data.termos_e_condicoes.aceite" ng-true-value="1" ng-false-value="0"> *Li e concordo com os termos acima

				</label>
			</div> -->


			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-6 mt-20">
						<!-- <b class="text-required">*Campos obrigatórios</b> -->
					</div>
					<div class="col-sm-6 mt-20 text-right">


						<button type="button" class="botao1 btn text-uppercase mobile" ng-click="toMenu(5)">
							VOLTAR
						</button>

						<!-- <button type="button" class="botao1 btn text-uppercase" ng-disabled="my_form6.$invalid" ng-click="send()">
							ENVIAR
						</button> -->
					</div>
				</div>
			</div>
		</div>

		<h4 class="text-center mt-20" ng-show="loadingSend"><span class="fa fa-spin fa-spinner"></span> {{ loadgingSendMensagem != "" ? loadgingSendMensagem : "Enviando, aguarde...."}}</h4>

		<div class="col-sm-12  box-sucess" ng-show="successSend">

			<p class="mt-20">
				<b>Envio de informações com sucesso!</b>
			</p>

			<p class="mt-20">
				<b>Aguarde nosso contato.</b>
			</p>

			<p class="mt-20">
				<span class="font-3"> Obrigado! </span>
			</p>


		</div>


	</form>



</section>
<div class="loading"></div>


<script src="<?php echo get_template_directory_uri(); ?>/js/controller/formPreEmbarqueController.js?v3=<?php echo version(); ?>">
</script>


<script src="<?php echo get_template_directory_uri(); ?>/includes/bootstrap-datepicker/bootstrap-datepicker.min.js">
</script>
<script src="<?php echo get_template_directory_uri(); ?>/includes/bootstrap-datepicker/bootstrap-datepicker.pt-BR.min.js">
</script>


<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/includes/bootstrap-datepicker/bootstrap-datepicker.min.css">

<script>
	function mydatapicker(time) {

		var today = new Date();

		setTimeout(function() {
			jQuery(".data").datepicker({
				language: "pt-BR",
				autoclose: true,
			});

			jQuery(".data-futura").datepicker({
				language: "pt-BR",
				autoclose: true,
				minDate: 0,
				startDate: today
			});

			// jQuery('.input-daterange').datepicker({
			// 	language: "pt-BR",
			// 	autoclose: true,
			// 	minDate: 0,
			// }).on('hide', function(e) {
			// 	if (jQuery(e.target).hasClass("data-futura-embarque")) {
			// 		jQuery(".data-futura-desembarque").attr('tabindex', -1).focus();
			// 	}
			// });

			//data-futura-embarque
			//data-futura-desembarque

			(function($) {
				var dateFormat = "dd/mm/yy",
					from = $(".data-futura-embarque")
					.datepicker({
						language: "pt-BR",
						autoclose: true,
						changeMonth: true,
						minDate: 0
					})
					.on("change", function() {
						to.datepicker("option", "minDate", getDate(this));
					}),
					to = $(".data-futura-desembarque").datepicker({
						language: "pt-BR",
						autoclose: true,
						changeMonth: true,
					})
					.on("change", function() {
						from.datepicker("option", "maxDate", getDate(this));
					});

				function getDate(element) {
					var date;
					try {
						date = $.datepicker.parseDate(dateFormat, element.value);
					} catch (error) {
						date = null;
					}

					return date;
				}
			})(jQuery);



			jQuery(".data-passada").datepicker({
				autoclose: true,
				language: "pt-BR",
				endDate: "today",
				maxDate: today
			});

			console.log("mydatapicker")
		}, time)


	}


	jQuery(function() {

		mydatapicker(0);

	});




	jQuery(".input-placeholder").each(function() {
		switch (jQuery(this).prop("tagName").toLowerCase()) {
			case "input":
				jQuery(this).attr("title", jQuery(this).attr("placeholder")).after('<span class="label-placeholder">' + jQuery(this).attr("placeholder") + '</span>')
				jQuery(this).attr("placeholder", "")
				break;
			case "select":
				const firstOption = jQuery(this).attr("placeholder") || jQuery(this).find("option").eq(0).text();
				jQuery(this).attr("title", jQuery(this).attr("placeholder")).after('<span class="label-placeholder">' + firstOption + '</span>')
				jQuery(this).find("option").eq(0).text(firstOption || "-")
				break;

			default:
				break;
		}

	})
</script>

<style>
	.bg {
		background: #f4f4f4;
	}
</style>
<?php get_footer(); ?>