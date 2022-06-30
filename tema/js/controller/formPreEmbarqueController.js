var meuApp = angular.module( "meuApp", [] );


meuApp.controller( "formPreEmbarqueController", function ( $window, $scope, $http, $location ) {

    $scope.menu = 1;
    $scope.existCPFsInValido = true;

    $scope.user_token_clicksign = "";

    $scope.pattern_DATA = '[0-9]{2}/[0-9]{2}/[0-9]{4}';
    $scope.pattern_CPF = '[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}';


    $scope.UFs = ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"];

    $scope.nacionalidades = ["Brasil", "Afeganistão", "África do Sul", "Akrotiri", "Albânia", "Alemanha", "Andorra", "Angola", "Anguila", "Antárctida", "Antígua e Barbuda", "Arábia Saudita", "Arctic Ocean", "Argélia", "Argentina", "Arménia", "Aruba", "Ashmore and Cartier Islands", "Atlantic Ocean", "Austrália", "Áustria", "Azerbaijão", "Baamas", "Bangladeche", "Barbados", "Barém", "Bélgica", "Belize", "Benim", "Bermudas", "Bielorrússia", "Birmânia", "Bolívia", "Bósnia e Herzegovina", "Botsuana", "Brunei", "Bulgária", "Burquina Faso", "Burúndi", "Butão", "Cabo Verde", "Camarões", "Camboja", "Canadá", "Catar", "Cazaquistão", "Chade", "Chile", "China", "Chipre", "Clipperton Island", "Colômbia", "Comores", "Congo-Brazzaville", "Congo-Kinshasa", "Coral Sea Islands", "Coreia do Norte", "Coreia do Sul", "Costa do Marfim", "Costa Rica", "Croácia", "Cuba", "Curacao", "Dhekelia", "Dinamarca", "Domínica", "Egipto", "Emiratos Árabes Unidos", "Equador", "Eritreia", "Eslováquia", "Eslovénia", "Espanha", "Estados Unidos", "Estónia", "Etiópia", "Faroé", "Fiji", "Filipinas", "Finlândia", "França", "Gabão", "Gâmbia", "Gana", "Gaza Strip", "Geórgia", "Geórgia do Sul e Sandwich do Sul", "Gibraltar", "Granada", "Grécia", "Gronelândia", "Guame", "Guatemala", "Guernsey", "Guiana", "Guiné", "Guiné Equatorial", "Guiné-Bissau", "Haiti", "Honduras", "Hong Kong", "Hungria", "Iémen", "Ilha Bouvet", "Ilha do Natal", "Ilha Norfolk", "Ilhas Caimão", "Ilhas Cook", "Ilhas dos Cocos", "Ilhas Falkland", "Ilhas Heard e McDonald", "Ilhas Marshall", "Ilhas Salomão", "Ilhas Turcas e Caicos", "Ilhas Virgens Americanas", "Ilhas Virgens Britânicas", "Índia", "Indian Ocean", "Indonésia", "Irão", "Iraque", "Irlanda", "Islândia", "Israel", "Itália", "Jamaica", "Jan Mayen", "Japão", "Jersey", "Jibuti", "Jordânia", "Kosovo", "Kuwait", "Laos", "Lesoto", "Letónia", "Líbano", "Libéria", "Líbia", "Listenstaine", "Lituânia", "Luxemburgo", "Macau", "Macedónia", "Madagáscar", "Malásia", "Malávi", "Maldivas", "Mali", "Malta", "Man, Isle of", "Marianas do Norte", "Marrocos", "Maurícia", "Mauritânia", "México", "Micronésia", "Moçambique", "Moldávia", "Mónaco", "Mongólia", "Monserrate", "Montenegro", "Mundo", "Namíbia", "Nauru", "Navassa Island", "Nepal", "Nicarágua", "Níger", "Nigéria", "Niue", "Noruega", "Nova Caledónia", "Nova Zelândia", "Omã", "Pacific Ocean", "Países Baixos", "Palau", "Panamá", "Papua-Nova Guiné", "Paquistão", "Paracel Islands", "Paraguai", "Peru", "Pitcairn", "Polinésia Francesa", "Polónia", "Porto Rico", "Portugal", "Quénia", "Quirguizistão", "Quiribáti", "Reino Unido", "República Centro-Africana", "República Checa", "República Dominicana", "Roménia", "Ruanda", "Rússia", "Salvador", "Samoa", "Samoa Americana", "Santa Helena", "Santa Lúcia", "São Bartolomeu", "São Cristóvão e Neves", "São Marinho", "São Martinho", "São Pedro e Miquelon", "São Tomé e Príncipe", "São Vicente e Granadinas", "Sara Ocidental", "Seicheles", "Senegal", "Serra Leoa", "Sérvia", "Singapura", "Sint Maarten", "Síria", "Somália", "Southern Ocean", "Spratly Islands", "Sri Lanca", "Suazilândia", "Sudão", "Sudão do Sul", "Suécia", "Suíça", "Suriname", "Svalbard e Jan Mayen", "Tailândia", "Taiwan", "Tajiquistão", "Tanzânia", "Território Britânico do Oceano Índico", "Territórios Austrais Franceses", "Timor Leste", "Togo", "Tokelau", "Tonga", "Trindade e Tobago", "Tunísia", "Turquemenistão", "Turquia", "Tuvalu", "Ucrânia", "Uganda", "União Europeia", "Uruguai", "Usbequistão", "Vanuatu", "Vaticano", "Venezuela", "Vietname", "Wake Island", "Wallis e Futuna", "West Bank", "Zâmbia", "Zimbabué"];

    $scope.data = {};


    //$scope.data = { "hospede": { "quantidade_hospedes": "4", "is_hospede_1": 0, "nome_do_hospede_1": "222222222222222", "data_nascimento_hospede_1": "22/03/2020", "nacionalidade_hospede_1": "222222222222222222", "local_nascimento_hospede_1": "222222222222222222222", "numero_passaporte_hospede_1": "22222222222222", "local_emissao_hospede_1": "22222222222", "data_expedicao_hospede_1": "22/03/2020", "data_validade_hospede_1": "17/04/2021", "endereco_hospede_1": "RUA QUIRIQUIRI, JARDIM SAO JUDAS TADEU", "cidade_hospede_1": "São Paulo", "cep_hospede_1": "04858-240", "uf_hospede_1": "RJ", "cpf_hospede_1": "333.333.333-33", "email_hospede_1": "marcelovieira1995@gmail.com", "tel_residencial_hospede_1": "2222222222222", "tel_comercial_hospede_1": "2222222222222222222222", "tel_celular_hospede_1": "222222222222222222222222", "data_nascimento_hospede_2": "22/03/2020", "tel_residencial_hospede_2": "22222-2222", "nome_do_hospede_2": "teste", "nacionalidade_hospede_2": "22222222222", "local_nascimento_hospede_2": "2222222222222222", "numero_passaporte_hospede_2": "2222222222222", "local_emissao_hospede_2": "teste", "data_expedicao_hospede_2": "11/11/1111", "data_validade_hospede_2": "22/03/2020", "endereco_hospede_2": "Rua Quiriquiri, 97", "cidade_hospede_2": "São Paulo", "cep_hospede_2": "04858-240", "email_hospede_2": "marcelovieira1995@gmail.com", "cpf_hospede_2": "222.222.222-22", "uf_hospede_2": "SP", "tel_comercial_hospede_2": "22222-2222", "tel_celular_hospede_2": "22222-2222", "nome_do_hospede_3": "Marcelo Vieira", "endereco_hospede_3": "Rua Quiriquiri, 97", "cidade_hospede_3": "São Paulo", "cep_hospede_3": "04858-240", "email_hospede_3": "marcelovieira1995@gmail.com", "data_nascimento_hospede_3": "22/03/2020", "nacionalidade_hospede_3": "teste", "local_nascimento_hospede_3": "teste", "numero_passaporte_hospede_3": "22222222222222", "local_emissao_hospede_3": "teste", "data_expedicao_hospede_3": "22/03/2020", "data_validade_hospede_3": "22/03/2020", "uf_hospede_3": "SP", "cpf_hospede_3": "222.222.222-22", "tel_residencial_hospede_3": "22222-2222", "tel_comercial_hospede_3": "22222-2222", "tel_celular_hospede_3": "22222-2222", "nome_do_hospede_4": "teste", "data_nascimento_hospede_4": "22/03/2020", "nacionalidade_hospede_4": "teste", "local_nascimento_hospede_4": "22222222222222222", "numero_passaporte_hospede_4": "2222222", "local_emissao_hospede_4": "teste", "data_expedicao_hospede_4": "22/03/2020", "data_validade_hospede_4": "22/03/2020", "endereco_hospede_4": "teste", "cidade_hospede_4": "teste", "uf_hospede_4": "SP", "cep_hospede_4": "22222-222", "cpf_hospede_4": "222.222.222-22", "email_hospede_4": "marcelovieira1995@gmail.com", "tel_residencial_hospede_4": "22222-2222", "tel_comercial_hospede_4": "22222-2222", "tel_celular_hospede_4": "22222-2222" }, "cruzeiro": { "nome_agencia": "asdsadasd", "num_reserva": "222", "nome_navio": "asdasd", "data_embarque": "22/03/2020", "data_desembarque": "18/12/2019", "is_agente": 1, "nome_agente": "asdsadasda", "agente_email": "marcelovieira1995@gmail.com" }, "contratante": { "nome_do_contratante": "Marcelo Vieira", "endereco_contratante": "Rua Quiriquiri, 97", "cidade_contratante": "São Paulo", "cep_contratante": "04858-240", "email_contratante": "marcelo.spfc95@hotmail.com", "data_nascimento_contratante": "22/03/2020", "nacionalidade_contratante": "2222222222", "local_nascimento_contratante": "2222222222", "numero_passaporte_contratante": "2222222", "local_emissao_contratante": "2222222", "data_expedicao_contratante": "22/03/2020", "data_validade_contratante": "22/03/2021", "cpf_contratante": "866.505.740-40", "tel_residencial_contratante": "22222-2222", "tel_comercial_contratante": "22222-2222", "tel_celular_contratante": "22222-2222", "uf_contratante": "RJ" }, "contato_emergencia": { "relacao_com_o_contratante_emergencia": "Filha", "nome_completo_emergencia": "teste", "endereco_contato_emergencia": "Rua Quiriquiri, 97", "cidade_contato_emergencia": "São Paulo", "uf_contato_emergencia": "RJ", "tel_contato_emergencia": "22222-2222", "email_contato_emergencia": "marcelovieira1995@gmail.com" }, "solicitacao_especiais": { "cuidados_medicos": 1, "is_gestante": 1, "tem_menor_de_um_ano": 1, "possui_restricao_alimentar": 1, "vai_celebrar_data_especial_abordo": 1 }, "termos_e_condicoes": { "aceite": 1 } };



    $scope.toMenu = function ( id, valid ) {

        if ( id <= $scope.menu && valid || $scope.menu + 1 == id && valid ) {

            if ( id === 6 ) {// to termos e condições
                $scope.data["termos_e_condicoes"] = {
                    aceite: 1,
                    document_key: false
                };
                $scope.create_doc_clicksign();
            }

            $scope.menu = id;
        }


    }


    $scope.setMesmoContratante = function ( v, id ) {


        if ( v ) {

            console.log( "setou" );
            $scope.data.hospede["nome_do_hospede_" + id] = angular.copy( $scope.data.contratante.nome_do_contratante );
            $scope.data.hospede["data_nascimento_hospede_" + id] = angular.copy( $scope.data.contratante.data_nascimento_contratante );
            $scope.data.hospede["nacionalidade_hospede_" + id] = angular.copy( $scope.data.contratante.nacionalidade_contratante );
            $scope.data.hospede["local_nascimento_hospede_" + id] = angular.copy( $scope.data.contratante.local_nascimento_contratante );
            $scope.data.hospede["numero_passaporte_hospede_" + id] = angular.copy( $scope.data.contratante.numero_passaporte_contratante );
            $scope.data.hospede["local_emissao_hospede_" + id] = angular.copy( $scope.data.contratante.local_emissao_contratante );
            $scope.data.hospede["data_expedicao_hospede_" + id] = angular.copy( $scope.data.contratante.data_expedicao_contratante );
            $scope.data.hospede["data_validade_hospede_" + id] = angular.copy( $scope.data.contratante.data_validade_contratante );
            
            $scope.data.hospede["endereco_hospede_" + id] = angular.copy( $scope.data.contratante.endereco_contratante );
            
            $scope.data.hospede["numero_do_endereco_hospede_" + id] = angular.copy( $scope.data.contratante.numero_do_endereco_contratante );
            $scope.data.hospede["complemento_hospede_" + id] = angular.copy( $scope.data.contratante.complemento_contratante );
            
            $scope.data.hospede["cidade_hospede_" + id] = angular.copy( $scope.data.contratante.cidade_contratante );
            $scope.data.hospede["cep_hospede_" + id] = angular.copy( $scope.data.contratante.cep_contratante );
            $scope.data.hospede["uf_hospede_" + id] = angular.copy( $scope.data.contratante.uf_contratante );
            $scope.data.hospede["cpf_hospede_" + id] = angular.copy( $scope.data.contratante.cpf_contratante );
            $scope.data.hospede["email_hospede_" + id] = angular.copy( $scope.data.contratante.email_contratante );
            $scope.data.hospede["tel_residencial_hospede_" + id] = angular.copy( $scope.data.contratante.tel_residencial_contratante );
            $scope.data.hospede["tel_comercial_hospede_" + id] = "";
            $scope.data.hospede["tel_celular_hospede_" + id] = angular.copy( $scope.data.contratante.tel_celular_contratante );
        }

        else {
            $scope.data.hospede["nome_do_hospede_" + id] = "";
            $scope.data.hospede["data_nascimento_hospede_" + id] = "";
            $scope.data.hospede["nacionalidade_hospede_" + id] = "";
            $scope.data.hospede["local_nascimento_hospede_" + id] = "";
            $scope.data.hospede["numero_passaporte_hospede_" + id] = "";
            $scope.data.hospede["local_emissao_hospede_" + id] = "";
            $scope.data.hospede["data_expedicao_hospede_" + id] = "";
            $scope.data.hospede["data_validade_hospede_" + id] = "";
            $scope.data.hospede["endereco_hospede_" + id] = "";

            $scope.data.hospede["numero_do_endereco_hospede_" + id] = "";
            $scope.data.hospede["complemento_hospede_" + id] = "";

            $scope.data.hospede["cidade_hospede_" + id] = "";
            $scope.data.hospede["cep_hospede_" + id] = "";
            $scope.data.hospede["uf_hospede_" + id] = "";
            $scope.data.hospede["cpf_hospede_" + id] = "";
            $scope.data.hospede["email_hospede_" + id] = "";
            $scope.data.hospede["tel_residencial_hospede_" + id] = "";
            $scope.data.hospede["tel_comercial_hospede_" + id] = "";
            $scope.data.hospede["tel_celular_hospede_" + id] = "";
        }


    }

    $scope.setMesmoEndereco = function ( v, id ) {


        if ( v ) {

            console.log( "setou" );

            $scope.data.hospede["endereco_hospede_" + id] = angular.copy( $scope.data.hospede['endereco_hospede_1'] );

            $scope.data.hospede["numero_do_endereco_hospede_" + id] = angular.copy( $scope.data.hospede['numero_do_endereco_hospede_1'] );
            $scope.data.hospede["complemento_hospede_" + id] = angular.copy( $scope.data.hospede['complemento_hospede_1'] );

            $scope.data.hospede["cidade_hospede_" + id] = angular.copy( $scope.data.hospede['cidade_hospede_1'] );
            $scope.data.hospede["cep_hospede_" + id] = angular.copy( $scope.data.hospede['cep_hospede_1'] );
            $scope.data.hospede["uf_hospede_" + id] = angular.copy( $scope.data.hospede['uf_hospede_1'] );


        }

        else {
            $scope.data.hospede["endereco_hospede_" + id] = "";

            $scope.data.hospede["numero_do_endereco_hospede_" + id] = "";
            $scope.data.hospede["complemento_hospede_" + id] = "";

            $scope.data.hospede["cidade_hospede_" + id] = "";
            $scope.data.hospede["cep_hospede_" + id] = "";
            $scope.data.hospede["uf_hospede_" + id] = "";

        }


    }


    $scope.send = function ( only_send = false ) {

        console.log( "call send 2" );

        if ( !only_send ) {
            $scope.loadingSend = true;
            $scope.successSend = false;
        }

        var postData = {
            action: 'my_ajax_request',
            data: $scope.data,
        }

        //Ajax load more posts
        jQuery.ajax( {
            type: "POST",
            data: postData,
            dataType: "json",
            url: ajax_object.ajax_url,
            //This fires when the ajax 'comes back' and it is valid json
            success: function ( r ) {

                if ( !only_send ) {
                    $scope.loadingSend = false;
                    if ( !r.error ) {


                        $scope.successSend = true;

                    }

                    $scope.$apply();
                }

            }
            //This fires when the ajax 'comes back' and it isn't valid json
        } ).fail( function ( data ) {
            if ( !only_send ) {
                $scope.loadingSend = false;
                $scope.successSend = false;
                $scope.$apply();

                console.log( data );
            }
        } );




    }

    $scope.cpfExist = function ( cpf_str, field, isFieldsContratante ) {

        if ( !cpf_str ) {

            return false;

        }

        const all_cpfs = [];
        all_cpfs.push(
            { "name": "cpf_contratante", "valor": $scope.data.contratante.cpf_contratante },
            { "name": "cpf_hospede_1", "valor": $scope.data.hospede.cpf_hospede_1 },
            { "name": "cpf_hospede_2", "valor": $scope.data.hospede.cpf_hospede_2 },
            { "name": "cpf_hospede_3", "valor": $scope.data.hospede.cpf_hospede_3 },
            { "name": "cpf_hospede_4", "valor": $scope.data.hospede.cpf_hospede_4 }
        );

        $scope.existCPFsInValido = Boolean( all_cpfs.filter( function ( cpf ) {
            if ( cpf.valor ) {
                return !Boolean( isFieldsContratante ) && cpf.name != field && cpf.valor.toString().replace( /[^0-9]/g, '' ).slice( 0, 11 ) == cpf_str.toString().replace( /[^0-9]/g, '' ).slice( 0, 11 );
            }

            else {
                return false;
            }

        } ).length );

        if ( field == "cpf_hospede_1" ) {
            //console.log(cpf_str, field, isFieldsContratante, $scope.existCPFsInValido)
        }



        return $scope.existCPFsInValido;





    }


    $scope.isDataDeEmbarqueValid = function ( dataPassport ) {

        if ( dataPassport ) {
            var valido = true;
            $scope.errors = [];
            var data_desembarque = $scope.data.cruzeiro.data_desembarque.split( "/" )[2] + "-" + $scope.data.cruzeiro.data_desembarque.split( "/" )[1] + "-" + $scope.data.cruzeiro.data_desembarque.split( "/" )[0];
            var dataPassport = dataPassport.split( "/" )[2] + "-" + dataPassport.split( "/" )[1] + "-" + dataPassport.split( "/" )[0];

            var data1 = new Date( dataPassport );
            var data2 = new Date( data_desembarque );

            if ( data1 > data2 ) {
                var total = ( data1.getFullYear() - data2.getFullYear() ) * 12 + ( data1.getMonth() - data2.getMonth() );

                valido = Boolean( total >= 6 );
            }

            else {
                console.log( "foi direto" );
                valido = false;
            }

            if ( !valido ) {
                $scope.errorDataValidadePassport = "*A data de validade do passaporte é inferior a 6 meses da data de desembarque.";
            }

            else {
                $scope.errorDataValidadePassport = '';
            }

            //console.log(total, valido)

            return valido;
        }



    }

    $scope.isMaior = function ( dt ) {

        if ( dt ) {
            var data1 = Number( dt.split( "/" )[2] );
            var hoje = new Date().getFullYear();

            if ( data1 >= hoje ) return false;

            if ( hoje - data1 < 18 ) {
                return false;
            }

            return true;
        }

    }

    $scope.isAdult = function ( dt ) {

        if ( dt ) {
            var data1 = Number( dt.split( "/" )[2] );
            var hoje = Number( $scope.data.cruzeiro.data_embarque.split( "/" )[2] ); // idade em relação a data de embarque


            if ( hoje - data1 < 1 ) {
                return false;
            }

            return true;
        }

    }

    $scope.create_doc_clicksign = function () {
        console.log( "create_doc_clicksign" );


        if ( $scope.data["termos_e_condicoes"]["document_key"] ) {
            return; // documento ja foi criado e enviado
        }


        var formData = new FormData();
        formData.append( 'name', $scope.data.contratante["nome_do_contratante"] );
        formData.append( 'email', $scope.data.contratante["email_contratante"] );
        formData.append( 'nacionalidade', $scope.data.contratante["nacionalidade_contratante"] );
        formData.append( 'cpf', $scope.data.contratante["cpf_contratante"] );
        formData.append( 'is_agente', $scope.data.cruzeiro["is_agente"] || 0 );

        if ( Boolean( formData.get( "is_agente" ) ) ) {
            formData.append( 'nome_agente', $scope.data["cruzeiro"]["nome_agente"] );
            formData.append( 'agente_email', $scope.data["cruzeiro"]["agente_email"] );
        }

        var data_nasc_contratante = $scope.data.contratante.data_nascimento_contratante.split( "/" );
        data_nasc_contratante = data_nasc_contratante[2] + "-" + data_nasc_contratante[1] + "-" + data_nasc_contratante[0];  //"1983-03-31"
        formData.append( 'birthday', data_nasc_contratante );

        if ( $scope.user_token_clicksign != "" ) { // verifica se existe signer key

            runWidgetDocSign( $scope.user_token_clicksign, widget_callback );
        }

        else {

            $scope.loadingSend = true;

            if ( $scope.data.cruzeiro["is_agente"] ) {
                $scope.loadgingSendMensagem = "Aguarde, enviando documento para o contratante por email para capturar sua assinatura...";
            }
            else {
                $scope.loadgingSendMensagem = "Aguarde, carregando documento para assinatura...";
            }


            // $scope.successSend = false;

            //Ajax load more posts
            jQuery.ajax( {
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                url: window.location.href + "?format=json&action=createDoc",
                //This fires when the ajax 'comes back' and it is valid json
                success: function ( r ) {

                    var data = JSON.parse( r.data );

                    if ( r.status == 200 && data.hasOwnProperty( "list" ) ) {

                        var list = data["list"] || null;

                        if ( list.hasOwnProperty( "document_key" ) ) {
                            $scope.data.termos_e_condicoes.document_key = list.document_key || false;
                        }

                        if ( $scope.data.cruzeiro["is_agente"] ) { // é agente e documento foi enviado para o cliente


                            $scope.data.termos_e_condicoes.aceite = 1;

                            $scope.send();

                            return true;
                        }

                        if ( list.hasOwnProperty( "request_signature_key" ) ) {

                            $scope.send( true ); // ja existe o document_key, aqui pode salvar o contrato

                            $scope.user_token_clicksign = list.request_signature_key || false;
                            runWidgetDocSign( $scope.user_token_clicksign, widget_callback );

                            //$scope.successSend = true;
                            $scope.loadingSend = false;
                            $scope.loadgingSendMensagem = "";


                        }

                        $scope.$apply();


                    }

                    else {
                        error( data );
                    }

                }
                //This fires when the ajax 'comes back' and it isn't valid json
            } ).fail( function ( data ) {
                error( data );
            } );



        }

        function widget_callback( widget ) {

            setTimeout( function () {
                widget.on( 'signed', function ( ev ) {

                    setTimeout( function () {
                        console.log( "signed 3 - ok " );

                        $scope.loadingSend = false;
                        $scope.successSend = true;
                        $scope.$apply();

                    }, 5000 )


                } );

            }, 700 );
        };



        function error( data ) {

            $scope.toMenu( 2, true );

            $scope.loadingSend = false;
            //$scope.successSend = false;

            $scope.user_token_clicksign = "";
            $scope.loadgingSendMensagem = "";


            $scope.$apply();

            setTimeout( function () {
                console.log( data );
                alert( data );
            }, 100 );
        }


    }


    $scope.setAddressContratanteByCep = function(cepEvent){

        $scope.data.contratante.endereco_contratante = '';
        $scope.data.contratante.cidade_contratante = '';
        $scope.data.contratante.uf_contratante = '';

        $scope.getCep(cepEvent.target.value).then(function(r){
            $scope.data.contratante.endereco_contratante = r.logradouro;
            $scope.data.contratante.cidade_contratante = r.localidade;
            $scope.data.contratante.uf_contratante = r.uf;

        }).finally(()=>{
            $scope.$apply();
        });
    }

    $scope.setAddressHospedesByCep = function(cepEvent, id_hospede){

        $scope.data.hospede["endereco_hospede_" + id_hospede] = '';
        $scope.data.hospede["cidade_hospede_" + id_hospede] = '';
        $scope.data.hospede["uf_hospede_" + id_hospede] = '';

        $scope.getCep(cepEvent.target.value).then(function(r){
            $scope.data.hospede["endereco_hospede_" + id_hospede] = r.logradouro;
            $scope.data.hospede["cidade_hospede_" + id_hospede] = r.localidade;
            $scope.data.hospede["uf_hospede_" + id_hospede] = r.uf;

        }).finally(()=>{
            $scope.$apply();
        });
    }


    $scope.getCep = function ( cep ) {
        return new Promise( function ( resolve, reject ) {
            jQuery.ajax( {
                type: "GET",
                dataType: "json",
                url: `https://viacep.com.br/ws/${cep}/json/`,
                success: function ( r ) {
                    resolve( r );
                }

            } ).fail( function ( r ) {
                console.log( r );
                reject( r );
            } );

        } );

    }


} );

meuApp.directive( 'cpfValido', function () {

    var testaCPF = function ( strCPF ) {

        var Soma;
        var Resto;
        Soma = 0;
        if ( strCPF == "00000000000" ) return false;

        for ( i = 1; i <= 9; i++ ) Soma = Soma + parseInt( strCPF.substring( i - 1, i ) ) * ( 11 - i );
        Resto = ( Soma * 10 ) % 11;

        if ( ( Resto == 10 ) || ( Resto == 11 ) ) Resto = 0;
        if ( Resto != parseInt( strCPF.substring( 9, 10 ) ) ) return false;

        Soma = 0;
        for ( i = 1; i <= 10; i++ ) Soma = Soma + parseInt( strCPF.substring( i - 1, i ) ) * ( 12 - i );
        Resto = ( Soma * 10 ) % 11;

        if ( ( Resto == 10 ) || ( Resto == 11 ) ) Resto = 0;
        if ( Resto != parseInt( strCPF.substring( 10, 11 ) ) ) return false;
        return true;

    }


    return {
        restrict: 'A',
        require: 'ngModel',
        link: function ( scope, elem, attrs, ctrl ) {

            scope.$watch( attrs.ngModel, function ( value ) {
                //scope.$watch( attrs.cpfValido, function ( value ) {

                var country = elem[0].form[attrs.idNacionalidade].value;


                if ( country == "Brasil" ) {

                    var valor = elem[0].value.replace( /[^0-9]/g, '' ).slice( 0, 11 );
                    ctrl.$setValidity( 'cpfValido', testaCPF( valor ) );
                }




            } );
        }
    };
} );

jQuery( '.data' ).unmask().mask( '00/00/0000' );
jQuery( '.cep' ).unmask().mask( '00000-000' );
jQuery( '.cnpj' ).unmask().mask( '00.000.000/0000-00' );
jQuery( '.cpf' ).unmask().mask( '000.000.000-00' );
jQuery( '.decimal' ).unmask().mask( '#.##0,00', { reverse: true } );
jQuery( '.moeda' ).unmask().mask( '###0.00', { reverse: true } );
jQuery( '.pontos' ).unmask().mask( '###0.00', { reverse: true } );

jQuery( '.moedaComVirgula' ).unmask().mask( '###.###.###,00', { reverse: true } );
jQuery( '.pontosComVirgula' ).unmask().mask( '###.###.###.###.###,00', { reverse: true } );

jQuery( '.pontoComVirgula3CasasDecimais' ).unmask().mask( '###.###.###.###.###,000', { reverse: true } );
jQuery( '.semvirgula' ).unmask().mask( '##########', { reverse: true } );
jQuery( '.seisCasa' ).unmask().mask( '###.000000', { reverse: true } );
jQuery( '.sonumeros' ).unmask().mask( '00000000000', { reverse: true } );

// jQuery('.data').datepicker({
// 	language: "pt-BR",
// 	autoclose: true
// });
// jQuery('.input-daterange').datepicker({
// 	language: "pt-BR",
// 	autoclose: true
// });
// jQuery('.input-daterange-today').datepicker({
// 	language: "pt-BR",
// 	startDate: "today",
// 	autoclose: true
// });

var SPMaskBehavior = function ( val ) {
    return val.replace( /\D/g, '' ).length === 9 ? '(00) 00000-0000' : '(00) 0000-00009';
},
    spOptions = {
        onKeyPress: function ( val, e, field, options ) {
            field.mask( SPMaskBehavior.apply( {}, arguments ), options );
        }
    };
jQuery( '.tel' ).unmask().mask( SPMaskBehavior, spOptions );