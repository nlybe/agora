define(function (require) {
    var elgg = require('elgg');
    var $ = require('jquery');
    
    $(document).ready(function() {
        // initialize profile types box
        changeAdaptiveStatus();
//        changeComissionStatus();
        
        $('#agora_paypal_enabled').change(function() {
            changeAdaptiveStatus();      
        });
        
        $('#agora_adaptive_payments').change(function() {
            changeComissionStatus();      
        });
    });
    
    function changeAdaptiveStatus() {
        if($("#agora_paypal_enabled").is(':checked')) {
            $('.agora_adaptive_settings input[type="checkbox"]').prop( "disabled", false );
            $('.agora_adaptive_settings fieldset').css('background','#fff')
            changeComissionStatus();
        }
        else {
            $('.agora_adaptive_settings input').prop( "disabled", true );
            $('.agora_adaptive_settings fieldset').css('background','#eaeaea')
        }  
    }
    
    function changeComissionStatus() {
        if($("#agora_adaptive_payments").is(':checked')) {
            $('#agora_adaptive_payments_commission').prop( "disabled", false );
        }
        else {
            $('#agora_adaptive_payments_commission').prop( "disabled", true );
        }        
    }
    
});
