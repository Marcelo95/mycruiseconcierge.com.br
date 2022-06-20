(function ($) {
	
	$(function () {
		
		'use strict';

        console.log("of")
		
		$(document).ready(function () {
            $('.stm-header .stm-text i').eq(0).addClass("fa fa-phone");
            $('.stm-header .stm-text i').eq(1).addClass("fa fa-envelope");

            $('.stm-header .stm-text a').eq(0).attr("href","tel:+551130782474");
            $('.stm-header .stm-text a').eq(1).attr("href","tel:03007772474");
            $('.stm-header .stm-text a').eq(2).attr("href","mailto:contato@pier1.com.br");


            $('.stm-navigation a').each(function( index ) {
                //[".pdf", ".docx", ".doc"]

                if( $(this).attr("href").indexOf( ".pdf" ) > -1 ){

                    $(this).attr("target","_blank")

                }

              });



        })
		
	});
	
})(jQuery);
