
$(document).ready(function(){


    $('#edit-button').click(function(){
        forms = $('.enabled-disabled');
        for(i = 0; i < forms.length; i++){

            if(forms[i].disabled){
                forms[i].disabled = false;
            } else {
                forms[i].disabled = true;
            }
        }

        if($('#generate-password').hasClass('disabled')){
            $('#generate-password').removeClass('disabled');
        }else{
            $('#generate-password').addClass('disabled');
        }
    });

    

    $('#generate-password').on('click', function(){
        return confirm('Are you sure that you want to generate a new password for this user?');
    });

    $('#delete-asset').on('submit', function(){
        return confirm('Are you sure that you want to delete this asset?');
    });

    $('#add-asset').click(function(){
        if(confirm('Are you sure that you want to approve this asset?')){
            forms = $('.enabled-disabled');
            for(i = 0; i < forms.length; i++){

                if(forms[i].disabled){
                    forms[i].disabled = false;
                }
            }

            $('#add-asset-form').submit();

        } else{
            return false;
        }
    });

    $('.delete-form-confirm').on('submit', function(){
      return confirm('Are you sure that you want to delete this item?');
    });

    $('.edit-form-confirm').on('submit', function(){
      return confirm('Are you sure that you want to edit this item?');
    });

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    var selectedTags = $('#hidden-tag-input');
    if(selectedTags.length > 0){
      var selectedTags = selectedTags.val();
      var selectedTags = selectedTags.split(', ');
    }
    

      $('#tag-input').on('input keyup',
        function getTags() {
          if(this.value){
            var exist = Array();
            var url = window.location.protocol + '//' + document.location.hostname;
            var selectedTagsUl = $('#selected-tags-ul');
            var selectedTagsUlChildren = selectedTagsUl.children('li').each(
              function(){
                exist.push($(this).text());
              }
            );
            jQuery.ajax({
              url: url + "/suggest/tags",
              type: "POST",
              data: {tag:$('#tag-input').val(), exist: exist},
              success:function(data){
                  if(data.status == 'success'){
                    clearAllTags();
                      suggest(data.results);
                      tagClick();
                  } else {
                    clearAllTags();
                  }
              }, 
            });
          } else {
            clearAllTags();
          }
      });



    function suggest(arr){

      var tagsUl = $('#tags');
      for(let i = 0; i < arr.length; i++){
        var name = arr[i].name;
        var elm = '<li class="list-group-item tags-li" id="tags-li-' + i + '">' + name + '</li>';
        tagsUl.append(elm);
      }
    }

    function tagClick(){
      var hiddenInput = $('#hidden-tag-input');
      $('.tags-li').on('click', function(e){
        let tagName = $(this).text();
        let tag = '<li class="list-group-item list-group-item-primary selected-tags-li">' + tagName + '</li>';
        $('#selected-tags-ul').append(tag);
        selectedTags.push(tagName);
        hiddenInput.val(selectedTags.join(', '));
        $(this).remove();
        deleteOnClick();
      });
    }

    function clearAllTags(){
      var tagsUl = $('#tags');
      tagsUl.empty();
    }

    function deleteOnClick(){
      var hiddenInput = $('#hidden-tag-input');
      var tag = $('.selected-tags-li');
      tag.on('click', function(){
        var text = $(this).text();
        selectedTags = $.grep(selectedTags, function(element, i){
          return element != text;
        });
        hiddenInput.val(selectedTags.join(', '));
        $(this).remove();
      });
    }

    deleteOnClick();
    



    var addUserAdBtn = $('#add-userad');
    var addUserAdForm = $('#add-userad-form');
    addUserAdBtn.click(function(){
      addUserAdForm.submit();
    });
    
});