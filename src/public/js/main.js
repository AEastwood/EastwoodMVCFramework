document.addEventListener('DOMContentLoaded', addEventListeners);

function addEventListeners() {
    let sendMessageButton = document.getElementById('sendMessageButton');
    let closeModalMessageButton = document.getElementById('closeMessageModalButton');

    sendMessageButton.addEventListener('click', () => {

        $('#modal-status').val();

        email = {
            csrf: $('#CSRFToken').val(),
            name: $('#sender-name').val(),
            email: $('#sender-email').val(),
            message: $('#message-text').val()
        };

        $.ajax({
            type: "POST",
            url: '/message/send',
            data: email,
            success: function(response) {
                handleResponse(response);
            },
            dataType: 'JSON'
        });
    });

    closeModalMessageButton.addEventListener('click', () => {
        $('#modal-status').html('');
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
            document.getElementById("messageForm").reset();
            createAlert('success', response.message);
            break;

        default:
            createAlert('failed', response.message);
            break;
    }
}