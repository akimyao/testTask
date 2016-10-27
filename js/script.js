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

        var email = $.trim($('#email').val());
        var gender = $('#gender').val();
        var about = $.trim($('#about').val());
        var name = $.trim($('#name').val());

        var csrf = $.trim($('#csrf').val());
        
        $.ajax({
            type: 'POST',
            url: 'registration.php',
            data: {login: userLog,
                psw: userPas,
                gender: gender,
                about: about,
                name: name,
                email: email,
                csrf: csrf
            },
            error: function(req, text, error) {
                alert('AJAX error: ' + text + ' | ' + error);
            },
            success: function (data) {
                $('#alerts').remove();
                if(data[0] == false){
                    $('html, body').animate({scrollTop: 0},500);
                    $('#main-box').before('<div class="box alerts" id="alerts"></div>');
                    for (var i = 0; i < data[1].length; i++) {
                        $('#alerts').append('<p class="alert-msg">'+data[1][i]+'</p>');
                    }
                } else {
                    var form = $('<form action="" method="post">' +
                        '<input type="hidden" name="login" value="'+userLog+'">' +
                        '<input type="hidden" name="suc_msg" value="'+data[1][0]+'">' +
                        '<input type="hidden" name="pass" value="'+userPas+'">' +
                        '<input type="hidden" name="csrf" value="'+csrf+'">' +
                        '<input type="hidden" name="signin" value="true">' +
                        '</form>');
                    $('body').append(form);
                    form.submit();
                }
            },
            dataType: 'json'
        });
    });
});
