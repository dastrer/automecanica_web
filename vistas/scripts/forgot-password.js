 

$("#frmForgot").on('submit',function(e)
{
	e.preventDefault();
    Email = $("#Email").val();
    Numero = $("#Numero").val();

    $.post("../ajax/forgot-password.php?op=verificar",
        {"Email":Email,"Numero":Numero},

          function(data)  
         
    {
 
        if (data!="null")
        {
            $(location).attr("href", "recover-password.php");
        }
        else
        {
            bootbox.alert("Usuario y/o datos incorrectos");
        }
    });
})

