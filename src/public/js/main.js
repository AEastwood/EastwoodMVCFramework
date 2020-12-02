var email = {
    name: null,
    email: null,
    message: null
}

document.addEventListener('DOMContentLoaded', addEventListeners);

function addEventListeners() {
    let sendMessageButton = document.getElementById('sendMessageButton');

    sendMessageButton.addEventListener('click', () => {

        $('#modal-status').val();

        email.name = $('#sender-name').val();
        email.email = $('#sender-email').val();
        email.message = $('#message-text').val();

        $.ajax({
            type: "POST",
            url: '/message/send',
            data: email,
            success: function(response) {
                handleResponse(response);
            },
            dataType: 'JSON'
        });

        document.getElementById("messageForm").reset();
    });
}

function createAlert(status, message) {
    let alert;

    switch (status) {
        case "success":
            alert = $('<div role="alert">').addClass('alert alert-success').html('<i class="fas fa-check pr-2"></i>' + message);
            break;

        default:
            alert = $('<div role="alert">').addClass('alert alert-danger').html('<i class="fas fa-times pr-2"></i>' + message);
            break;
    }

    $('#modal-status').html(alert);
}

function handleResponse(response) {

    switch (response.code) {
        case 200:
            createAlert('success', response.message);
            break;

        case 400:
            createAlert('failed', response.message);
            break;

        case 500:
            createAlert('failed', response.message);
            break;
    }
}