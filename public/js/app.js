$(document).ready(function(){
    $('select').material_select();
    $(".order").click(function(){
        if($('#na').val().length == 0){
            Materialize.toast('Name is required!',3000,'rounded');
            return false;
        }
        $('#id').val($(this).attr('id'));
        $(this).closest('form').trigger('submit');
    });
    $(".tab").click(function(event){
        location.href = event.target.href;
    });
    $('.delete-ingredient').click(function(e){
        var url = $(this).attr('href');
        var response = $.ajax({
           url: url.replace("#",""),
            method: "DELETE"
        });

        response.done(function(data){
           location.reload();
        });
        e.preventDefault();
        return false;
    });
});

