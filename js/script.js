$(document).ready(function() {

    //Carregar versículos caso, altere o livro
    $('#livro').change(function() {
        //Armazena o livro escolhido na variável livro...
        //Obs: o let só permite usar a variável dentro desse escopo
        let livro = $(this).val();

        $.ajax({
            url: 'carrega-capitulos.php',
            method: 'get',
            data: {
                'livro': livro
            },
            complete: function(data) {
                let response = data.responseText;

                //Coloca as options carregadas dentro da combo box(select) com id cap, ou seja, os capitulos
                $('#cap').html(response);
            }
        });
    });
});