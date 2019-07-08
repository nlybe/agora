define(function (require) {
    var elgg = require('elgg');
    var $ = require('jquery');
    
    $(document).ready(function () {
                
        
    });
    
});

function digital_file_show(status)
{
    status=!status;	
    document.agoraForm.digital_file_box.disabled = status;
}
