$(document).ready(function(){

    var adCardContainer = $('#ad-modal-container');
    var adForm = document.getElementById('ad-form');
    var updateBtn = $('#update-ad-btn');

    var deleteMediasForm = document.getElementById('delete-medias-form');

    updateBtn.on('click', function(e){
        e.preventDefault();
        ajaxPostFormData(adForm);
    });


    var deleteAdImgBtn = $('#delete-ad-medias-btn');
    deleteAdImgBtn.on('click', function(e){
        e.preventDefault();
        ajaxDeleteMedias(deleteMediasForm);
        $('#image_url').val('');
    });


    function ajaxPostFormData(adForm){
        var url = document.location.href;
        var adFormData = new FormData(adForm);
        $.ajax({
            url: url,
            type: 'POST',
            data: adFormData,
            contentType: false,
            processData:false,
            success: function(data){
                if(data.status == 'success'){
                    adCardContainer.html(data.response);
                }
            }, error: function(e){
            }
        });
    }


    function ajaxDeleteMedias(deleteMediasForm){
        var url = document.location.href + '/medias';
        var deleteMediasFormData = new FormData(deleteMediasForm);
        $.ajax({
            url: url,
            type: 'POST',
            data: deleteMediasFormData,
            processData:false,
            contentType: false,
            success: function(data){
                if(data.status == 'success'){
                    adCardContainer.html(data.response);
                } else {
                }
            }, error: function(e){
            }
        });
    }

});