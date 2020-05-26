$(document).ready(function(){
    // Show the overlay on hover in and hide it on hover out
    var cards = $('.card');
    cards.hover(function(){
        $(this).children('.card-user-img-transition').css('opacity', '1');
        let vid = $(this).find('video');
        if(vid.length > 0){
            vid.get(0).play();
        }
    }, function(){
        $(this).children('.card-user-img-transition').css('opacity', '0');
        let vid = $(this).find('video');
        if(vid.length > 0){
            vid.get(0).pause();
        }
    });

    $('audio').mediaelementplayer({
        // Do not forget to put a final slash (/)
        pluginPath: 'https://cdnjs.com/libraries/mediaelement/',
        // this will allow the CDN to use Flash without restrictions
        // (by default, this is set as `sameDomain`)
        shimScriptAccess: 'always',
        // more configuration
    });
});