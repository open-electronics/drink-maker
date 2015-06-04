$(document).ready(function(){
    $('select').material_select();
    $(".order").click(function(){
        if($('#na').val().length != 0){
            $('#id').val($(this).attr('id'));
            $(this).closest('form').trigger('submit');
        }else {
            Materialize.toast('Name is required!',3000,'rounded');
        }
    });
    $(".tab").click(function(event){
        location.href = event.target.href;
    });
});

