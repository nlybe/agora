define(['jquery', 'elgg/lightbox'], function($, lightbox) { 
    var options = {
        photo: true,
    };

    lightbox.bind('a[rel="showcase-gallery"]', options, false); 
});
