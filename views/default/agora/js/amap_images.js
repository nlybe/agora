define(function (require) {
    var elgg = require('elgg');
    var $ = require('jquery');
    
    var settings = require("agora/settings");
    var max_images_gallery = settings['max_images_gallery'];
    
    $(document).ready(function () {
        $("#product_icon").on("change", function() {
            var img_existed = $("#agora-icons li").length;
            var img_new = $("#product_icon")[0].files.length;
            var remaning_images = max_images_gallery - img_existed;
            if( img_existed+img_new > max_images_gallery) {
               $(this).val(null); 
               alert("You can select only " + remaning_images + " images");
            } 
        });

        // remove screenshot field
        $('td.remove a .elgg-icon-delete').on('click', function (e) {
            e.preventDefault();

            $(this).parents('table').eq(0).remove();
        });


        // delete a screenshot
        $('.agora-icon-delete').on('click', function (e) {
            e.preventDefault();

            var container = $(this).parents('li').eq(0);

            // hide it initially
            container.hide();

            elgg.action('agora/icon/delete', {
                timeout: 30000,
                data: {
                    guid: $(this).attr('data-guid')
                },
                success: function (result, success, xhr) {
                    if (result.status == 0) {
                        // successfully removed it, remove the markup
                        container.remove();

                        // if we have less than 9 screenshots remaining we can show the field if hidden
                        if ($('ul.agora-icons li').length < 9) {
                            $('.agora-icon-input').show();
                        }
                        elgg.system_message(elgg.echo('agora:icon:delete:success'));
                    } else {
                        // it didn't delete properly, show it again
                        container.show();
                        elgg.register_error(elgg.echo('agora:icon:delete:failed'));
                    }
                },
                error: function (result, response, xhr) {
                    container.show();
                    elgg.register_error(elgg.echo('agora:icon:delete:failed'));
                }
            });
        });
        
        return false;
    });
    
});
