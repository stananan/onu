$(document).ready(function () {

    let user;
    let room;
    let messageLength = 0;
    getSessionData();

    $("#loading").show();
    $("#empty").hide();


    setInterval(getChat, 2000);


    function getSessionData() {
        $.ajax({
            type: 'GET',
            url: 'backend/getsessiondata.php',

            dataType: 'json',
            success: function (data) {
                user = data.user;
                room = data.room;

            },
            error: function () {
                console.log('Error occurred while fetching session data.');
            }
        });
    }

    function getChat() {
        $.ajax({
            type: 'GET',
            url: 'backend/getmessages.php',
            data: { room: room, messageLength: messageLength },
            dataType: 'json',
            success: function (data) {


                data.forEach(function (message) {
                    $("#loading").hide();
                    $("#empty").hide();
                    messageLength += 1;
                    formattedDate = formatDateTime(String(message.creation_time));
                    $('.chat').prepend('<div class="messages"><strong>' + message.username + '</strong>: ' + message.content + '<span><i>' + formattedDate + '</i></span></div>');

                });
                if (($(".messages").length == 0)) {
                    $("#loading").hide();
                    $("#empty").show();
                }

            },
            error: function () {
                console.log("DEAD ROOM");

                window.location.href = 'index.php';
            }
        });
    }




    $("#send").click(() => {

        let message = $('#message').val();

        $.ajax({
            type: 'POST',
            url: 'backend/sendmessage.php',
            data: { user: user, message: message, room: room },
            success: function () {
                $('#message').val('');
                getChat();


            }
        });
    })

    $("#tts").click(() => {
        var speech = true;
        window.SpeechRecognition = window.webkitSpeechRecognition;
        let transcript;
        const recognition = new SpeechRecognition();
        recognition.interimResults = true;

        recognition.addEventListener('result', e => {
            transcript = Array.from(e.results)
                .map(result => result[0])
                .map(result => result.transcript)
                .join('')

        });

        recognition.addEventListener('end', () => {
            // Restart recognition when it stops
            if (speech) {
                if ($("#message").val() == undefined) {
                    $("#message").val(transcript + " ");
                } else {
                    $("#message").val($("#message").val() + transcript + " ");
                }



                console.log($("#message").val());
                //uncomment if you dont want it to stop.
                //recognition.start();
            }
        });

        if (speech) {
            recognition.start();
        }
    });




});