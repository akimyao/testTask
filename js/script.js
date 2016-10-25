$(document).ready(function(){
    $("#changeFormToLog").click(function () {
        $("#regform").hide("slow");
        $("#logform").show("slow");
        return false;
    });

    $("#changeFormToReg").click(function () {
        $("#logform").hide("slow");
        $("#regform").show("slow");
        return false;
    });

    $('#signup').click(function(){
        var userLog = $.trim($('#reglogin').val());
        var userPas = $.trim($('#regpsw').val());

        $.ajax({
            type: 'POST',
            url: 'registration.php',
            data: {login : userLog, psw: userPas},
            error: function(req, text, error) {
                alert('AJAX error: ' + text + ' | ' + error);
            },
            success: function (data) {

            },
            dataType: 'json'
        });
    });
});
