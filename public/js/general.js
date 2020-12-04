$(".favorito").on('click', function(e){
    e.preventDefault();
    var $this = $(this);
    var url = $this.data("url");
    var idMarcador = $this.data("id");

    $this.addClass("disabled");
    
    $.post(url, {id:idMarcador})
        .done(function(respuesta){
            if(respuesta.actualizado){
                $this.toggleClass("activo");
                $this.removeClass("disabled");
            }
        }).fail(function(){
            $this.removeClass("disabled");
        });
});

$("body").on("submit", 'form[name="etiqueta"][data-ajax="true"]', function(e){
    e.preventDefault();
    
    var $form = $(this),
    $bttnSubmit = $form.find('button[type="submit"]'),
    $container = $form.closest(".modal-body"),
    $etiqueta = $("#marcador_etiquetas")
    url = $form.attr('action');

    $bttnSubmit.addClass("disabled");

    var data = {};

    $.each($form.serializeArray(), function(){
        data[this.name] = this.value
    });

    $.post(url, data)
        .done(function(response){
                $container.html('');
                $container.append(response.form.content)
                $bttnSubmit.removeClass("disabled");
        }).fail(function(){
            $bttnSubmit.removeClass("disabled");

        });
});