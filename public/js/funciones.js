// Para mostrar el nombre del archivo a subir
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
//To English
function traduceToEnglish() {
    //Mostrar
    $("#English").removeClass("d-none");
    $("#English").addClass("d-block");
    //Ocultar
    $("#Español").removeClass("d-block");
    $("#Español").addClass("d-none");
}
//A español
function traduceEspañol() {
    //Mostrar
    $("#Español").removeClass("d-none");
    $("#Español").addClass("d-block");
    //Ocultar
    $("#English").removeClass("d-block");
    $("#English").addClass("d-none");
}