

<?php 

function getValues($text, $pos=0){
    preg_match_all('/(?<=\[)[^\]\[\r\n]*(?=\])/', $text, $matches);
    return $matches[0][$pos];
}

$json_all = [];

if (function_exists("get_field")) {
    $companies_fields = get_field('companies_fields', get_the_id());
    $companies_fields = explode("\n", $companies_fields);

    $ships_fields = get_field('ships_fields', get_the_id());
    $ships_fields = explode("\n", $ships_fields);

    $destiny_fields = get_field('destiny_fields', get_the_id());
    $destiny_fields = explode("\n", $destiny_fields);

    $ports_fields = get_field('ports_fields', get_the_id());
    $ports_fields = explode("\n", $ports_fields);

    // print_r( getValues($ships_fields[0], 1) );
    
    $json_all = array_map(function($el) use($ships_fields, $ports_fields){

        return [ 
            "name_to_search" => "|".getValues($el, 0),
            "name" => getValues($el, 1),
            "ships" => array_values(array_map(function($el0){
                return [
                    "name_to_search" => getValues($el0, 1),
                    "name" => getValues($el0, 2),
                ];
            },array_filter($ships_fields, function($el2) use($el){
                return getValues($el2,0) === getValues($el, 0);
             }))),
            "ports" => array_values(array_map(function($el0){
                return [
                    "name_to_search" => getValues($el0, 1),
                    "name" => getValues($el0, 2),
                ];
            },array_filter($ports_fields, function($el2) use($el){
                return getValues($el2,0) === getValues($el, 0);
             })))
    ];

    }, $companies_fields);
    
    $json_all_destiny_fields = array_values(array_map(function($el0){
        return [
            "name_to_search" => getValues($el0, 0),
            "name" => getValues($el0, 1),
        ];
    },$destiny_fields));



}

?>

<script>
    var json_cias_ships = <?php echo json_encode($json_all, JSON_UNESCAPED_SLASHES); ?>;
    var json_destinations = <?php echo json_encode($json_all_destiny_fields, JSON_UNESCAPED_SLASHES); ?>;
    console.log(json_cias_ships);
    console.log(json_destinations);
</script>


<main role="main">
	<!-- section -->
	<section ng-app="meuApp2" class="ng-cloak search-cruise">

		<form action="/buscar-cruzeiros" method="GET" ng-controller="formSearchCruisesController">
		<h1 class="title2 text-center text-uppercase"> BEM-VINDO AO MYCRUISECONCIERGE </h1>
			<p class="text-center title-form"> <span class="font-3"> Pesquisa de Cruzeiros </span> </p>

			<div class="row init-form">

				<div class="col-sm-4">
					<div class="form-group">
						<label for="" class="title2 text-center text-uppercase">Destino:</label>
						<select name="destination" class="form-control" ng-model="data.destination" ng-options="model.id as model.name for model in form.destinations">
							<option value="">Todos</option>
						</select>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="" class="title2 text-center text-uppercase">Data:</label>
						<input type="hidden" name="dateIni" placeholder="dateIni" ng-model="data.dateIni" >
						<input type="hidden" name="dateFin" placeholder="dateFin" ng-model="data.dateFin" >
						<select name="" class="form-control" ng-change="setDate()" ng-model="data_selected" ng-options="model.id as model.name for model in form.dates">
							<option value="">Todos</option>
						</select>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="" class="title2 text-center text-uppercase">Companhia:</label>
						<select name="company" class="form-control" ng-model="data.company" ng-options="model.id as model.name for model in form.companies" ng-change="updateCompanies(data.company )"> 
							<option value="">Todos</option>
						</select>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="" class="title2 text-center text-uppercase">Porto de Embarque:</label>
						<select name="port" class="form-control" ng-model="data.port" ng-options="model.name as model.name for model in form.ports"  ng-change="updatePorts(data.port )">
							<option value="">Todos</option>
						</select>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="" class="title2 text-center text-uppercase">Duração:</label>
						<select name="duration" class="form-control" ng-model="data.duration" ng-options="model.id as model.name for model in form.durations">
							<option value="">Todos</option>
						</select>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<label for="" class="title2 text-center text-uppercase">Navio:</label>
						<select name="ship" class="form-control" ng-change="updateShips(data.ship )" ng-model="data.ship" ng-options="model.id as model.name for model in form.ships">
							<option value="">Todos</option>
						</select>
					</div>
				</div>

				<div class="col-sm-12 text-right btns-footer">
					<button type="button" ng-click="resetForm()" class="botao1 botao-reset btn text-uppercase">
						Limpar filtros
					</button>
					<button type="button" ng-click="submit()" class="botao1 btn text-uppercase">
						PESQUISAR
					</button>
				</div>
			</div>
		</form>

	</section>
	<!-- /section -->
</main>

<script>
	var meuApp = angular.module("meuApp", []);


// 	meuApp.controller("formSearchController", function ($window, $scope, $http, $location) {

//     window.alert("ldfkdlsfdsfs");
// });

</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/controller/formSearchCruisesController.js?v3=<?php echo version(); ?>"></script>

