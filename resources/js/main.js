$(document).ready(function(){

    max = 5;
    count = 1;
    formGroupStart = '<div class="form-group upload-form-group"> ';
    formGroupEnd = ' <span class="delete-upload-btn">x</span></div>';
    invalidForm = '<div class="invalid-feedback">Please provide a valid option: maximum allowed number of characters is 300.</div>';

    $('#add-download').click(function(){

        if(count < max){
            num = count + 1;
            var option = formGroupStart + '<input type="file" name="uploads[' + count + ']" />' + formGroupEnd;
            $('.uploads').append(option);
            count++;
        } else{
            alert('Maximum number of uploads has been reached.');
        }

    }); 


    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);



      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#username').on('keyup',

        function checkusername() {
          var url = window.location.href;
          jQuery.ajax({
            url: url + "/checkusername",
            type: "POST",
            data: {username:$('#username').val()},
            success:function(data){
              if(data.status == 'success'){
                $('#username').removeClass('is-invalid');
                $('#username').addClass('is-valid');
                
              } else {
                $('#username').removeClass('is-valid');
                $('#username').addClass('is-invalid');
                
              }
            },
            error:function (e){
              console.log(e);
            }
          });
        }

      );

      var selectedTags = [];

      $('#tag-input').on('input keyup focusin', function(e){
        e.stopPropagation();
        getTags(e);
      });


      
      function getTags(e) {
        var category = e.target.dataset.category;
        var exist = Array();
        var hiddenInputValue = $('#hidden-tag-input').val();
        exist = hiddenInputValue.split(', ');
        var url = window.location.protocol + '//' + document.location.hostname;
        var selectedTagsUl = $('#selected-tags-ul');
        jQuery.ajax({
          url: url + "/suggest/tags",
          type: "POST",
          data: {tag:$('#tag-input').val(), exist: exist, category: category},
          success:function(data){
              if(data.status == 'success'){
                  clearAllTags();
                  suggest(data.results);
                  tagClick();
              } else {
                clearAllTags();
              }
          }, error: function(e){
            console.log(e);
          }
        });
  }


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
        let tag = '<li class="list-group-item list-group-item-primary selected-tags-li">' + tagName + '&nbsp;&nbsp;<span style="color:#959595">x</span></li>';
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


    $(document).click(function(e){
      if(!e.target.classList.contains('tags-li')){
        clearAllTags();
      }
    });


    function uploadDeleteBtn(){
      
      let uploads = $('.uploads');
      uploads.on('click', '.delete-upload-btn', function(e){
        let parentGroup = e.target.parentNode;
        parentGroup.remove();
        count--;
      });
    }

    uploadDeleteBtn();
    
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
});