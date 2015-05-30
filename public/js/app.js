$(document).ready(function(){
    $(".order").click(function(){
        if($('#na').val().length != 0){
            $('#id').val($(this).attr('id'));
            $(this).closest('form').trigger('submit');
        }else {
            Materialize.toast('Name is required!',1500,'rounded');
        }
    });
});

