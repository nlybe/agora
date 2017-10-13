define(function (require) {
    var elgg = require('elgg');
    var $ = require('jquery');
    
    var settings = require("agora/settings");
    var max_images_gallery = settings['max_images_gallery'];
    
    $(document).ready(function () {
        // add another screenshot field
        $('.agora-icon-add-another').click(function (e) {
            e.preventDefault();

            // make sure we don't give more than 8 uploads
            var existing_screenshots = $('ul.agora-icons li').length;
            var available_fields = $('.agora-icon-input table').length;
            var total_screenshots = existing_screenshots + available_fields;

            if (total_screenshots >= max_images_gallery) {
                elgg.register_error(elgg.echo('agora:add:images:limit', [max_images_gallery]));
                return;
            }


            $('#agora-icon-wrapper').clone(true)
                .removeAttr('id')
                .insertBefore($(this))
                .find('td.remove')
                .html('<a href="#"><span class="elgg-icon elgg-icon-delete"></span></a>');
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
                    } else {
                        // it didn't delete properly, show it again
                        container.show();
                        elgg.register_error(elgg.echo('agora:icon:imagedelete:failed'));
                    }
                },
                error: function (result, response, xhr) {
                    container.show();
                    elgg.register_error(elgg.echo('agora:icon:imagedelete:failed'));
                }
            });
        });
        
        return false;
    });
    
});
