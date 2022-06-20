var meuApp2 = angular.module( "meuApp2", [] );
function composer( ...fns ) { return function ( value ) { fns.reduceRight( function ( previousValue, fn ) { return fn( previousValue ) }, value ) } }

meuApp2.controller( "formSearchCruisesController", function ( $window, $scope, $http, $location ) {

    $scope.data_init = {
        cbPage: "reservas",
        dateIni: null,
        dateFin: null,
        destination: null,
        company: null,
        port: null,
        duration: null,
        ship: null,
    }

    $scope.data = angular.copy( $scope.data_init );


    $scope.getDates = function () {
        var date = new Date();
        var year = date.getFullYear();
        var month_now = date.getMonth() + 1;// comeca no proximo Mes
        var get_last_day = function ( y, m ) { return new Date( y, m + 1, 0 ).getDate(); };
        var addMonths = 24; // meses adiantes
        var months_name = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
        var months = [];

        var count_months_indexes = month_now;


        for ( let index = 0; index < addMonths; index++ ) {

            if ( count_months_indexes > 11 ) { count_months_indexes = 0; year++ };
            //months[index] = count_months_indexes;

            months[index] = {
                id: count_months_indexes.toString() + year.toString(),
                dateIni: `1-${( count_months_indexes + 1 )}-${year}`,
                dateFin: `${get_last_day( year, count_months_indexes )}-${( count_months_indexes + 1 )}-${year}`,
                name: months_name[count_months_indexes] + " " + year,
                month: count_months_indexes
            }

            count_months_indexes++;

        }

        console.log( months )

        return months.filter( m => !!m );
    }

    var theports = [];
    var theships = [];

    json_cias_ships.map( function ( elem ) {

        elem["ports"].map( function ( ports ) {
            theports.push( { id: ports["name_to_search"], name: ports["name"], cia: elem["name_to_search"] } )
        } );

        elem["ships"].map( function ( ports ) {
            theships.push( { id: ports["name_to_search"], name: ports["name"], cia: elem["name_to_search"] } )
        } )
    } )

    var port_cias = angular.copy( theports );

    theports = theports.filter( function ( item, pos, array ) {
        return array.map( function ( mapItem ) { return mapItem["name"]; } ).indexOf( item["name"] ) === pos;
    } ).map( function ( port ) {
        port["cia"] = port_cias.filter( function ( portName ) {
            return portName["name"] === port["name"]
        } ).map( function ( ciaName ) {
            return ciaName["cia"]
        } ).filter(function(item2, pos2, a) {
            return a.indexOf(item2) == pos2;
        }).toString()
        return port;
    } )


    theships = theships.filter( function ( item, pos, array ) {
        return array.map( function ( mapItem ) { return mapItem["name"]; } ).indexOf( item["name"] ) === pos;
    } )

    function sortByProperty(property){  
        return function(a, b) {
            const digitRegex = /^\d/;
            const alphabetRegex = /^[a-zA-Z]/;
            const symbolRegex = /^[^\w\s]/;
            
            const scoreA =  symbolRegex.test(a[property]) * 1 || digitRegex.test(a[property]) * 10 || alphabetRegex.test(a[property]) * 100;
            const scoreB =  symbolRegex.test(b[property]) * 1 || digitRegex.test(b[property]) * 10 || alphabetRegex.test(b[property]) * 100;
            
            if (scoreA !== scoreB) {
              return scoreA - scoreB;
            }
            
            if (a[property] < b[property]) {
              return -1;
            } else if (a[property] > b[property]) {
              return 1;
            }
            
            return 0;
          }
     }
    $scope.form_reset = {
        destinations: json_destinations.map( function ( elem ) {
            return { id: elem["name_to_search"], name: elem["name"] }
        } ).sort(sortByProperty("name")),

        dates: $scope.getDates(),

        ports: theports.sort(sortByProperty("name")),

        durations: [{ id: "1 - 6", name: "1 - 6 noites" }, { id: "7 - 8", name: "7 - 8 noites" }, { id: "9 - 12", name: "9 - 12 noites" }, { id: "> 12", name: "> 12 noites" }],

        companies: json_cias_ships.map( function ( elem ) {
            return { id: elem["name_to_search"], name: elem["name"] }
        } ).sort(sortByProperty("name")),

        ships: theships.sort(sortByProperty("name"))
    };

    $scope.form = angular.copy( $scope.form_reset );

    console.log( "$scope.form", $scope.form )

    $scope.setDate = function () {
        let data_selected = $scope.form.dates.filter( d => d.id == $scope.data_selected );

        $scope.data.dateIni = data_selected[0] ? data_selected[0].dateIni : null;
        $scope.data.dateFin = data_selected[0] ? data_selected[0].dateFin : null;

        console.log( 'data_selected', data_selected );


        console.log( ' $scope.data.dateIni', $scope.data.dateIni );

    }

    $scope.toQueryString = function ( obj ) {
        var str = [];
        for ( var p in obj )
            if ( obj.hasOwnProperty( p ) && obj[p] ) {
                str.push( encodeURIComponent( p ) + "=" + encodeURIComponent( obj[p] ) );
            }
        return str.join( "&" );
    }


    $scope.submit = function () {
        let querySearch = $scope.toQueryString( $scope.data );
        console.log( querySearch )

        $window.location.href = "/?" + querySearch;

    }

    $scope.resetForm = function () {
        $scope.data = angular.copy( $scope.data_init );
        $scope.data_selected = null;
        $scope.form = angular.copy( $scope.form_reset );
    }

    $scope.updateCompanies = function ( cia ) {
        if ( cia ) {
            $scope.form.ports = angular.copy( $scope.form_reset.ports ).filter( function ( el ) {
                return el["cia"].indexOf(cia) > -1
            } )
            $scope.form.ships = angular.copy( $scope.form_reset.ships ).filter( function ( el ) {
                return el["cia"].indexOf(cia) > -1
            } )
        } else {
            $scope.form.ports = angular.copy( $scope.form_reset.ports )
            $scope.form.ships = angular.copy( $scope.form_reset.ships )
        }
    }

    $scope.updateShips = function ( ship ) {

        if ( ship ) {
            $scope.form.companies = angular.copy( $scope.form_reset.companies ).filter( function ( el ) {
                return el["id"].indexOf( ship.split("-")[2] ) > -1;
            } );
            $scope.updateCompanies("|"+ship.split("-")[2] );
        } else {
            $scope.form.companies = angular.copy( $scope.form_reset.companies )
            $scope.form.ports = angular.copy( $scope.form_reset.ports )
        }
    }

    $scope.updatePorts = function ( port ) {

        if ( port ) {

            var ciasName = $scope.form.ports.filter(function(port1){
                return port1["name"] === port
            })[0]["cia"]

            $scope.form.companies = angular.copy( $scope.form_reset.companies ).filter( function ( el ) {

                return ciasName.indexOf( el["id"] ) > -1;
            } )
            $scope.form.ships = angular.copy( $scope.form_reset.ships ).filter( function ( el ) {
                return ciasName.indexOf( el["cia"] ) > -1;
            } )
        } else {
            $scope.form.companies = angular.copy( $scope.form_reset.companies )
            $scope.form.ships = angular.copy( $scope.form_reset.ships )
        }
    }


    console.log( "form", $scope.form )

} );