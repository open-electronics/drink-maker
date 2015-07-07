$(document).ready(function(){
    $('select').material_select();
    $(".order").click(function(){
        if($('#na').val().length == 0){
            Materialize.toast('Name is required!',3000,'rounded');
            return false;
        }
        if(parseInt($('#vol').val())>parseInt($(this).closest('.drink').attr('data-max'))){
            Materialize.toast('Try with a lower quantity!',3000,'rounded');
            return false;
        }
        $('#id').val($(this).attr('id'));
        $(this).closest('form').trigger('submit');
    });
    $("#vol").change(function(){
        var v=$(this).val();
        $.each($(".drink"),function(index,value){
            var krs=$(this).find(".right");
           if(parseInt(v,10)<=parseInt($(this).attr('data-max'),10)){
               krs.removeClass(krs.attr("data-disable"));
               krs.addClass(krs.attr("data-enable"));
           }else{
               krs.addClass(krs.attr("data-disable"));
               krs.removeClass(krs.attr("data-enable"));
           }
        });
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

