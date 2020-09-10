$(document).ready(function(){

    var btn = $('#report-btn');

    btn.on('click', function(e){
        e.preventDefault();
        $('#reportModal').modal('show');
    });


});