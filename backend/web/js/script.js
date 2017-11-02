$('document').ready(function () {
    //sending message to all invited users witch was selected
    $('.modal-button-send').on('click', function (e) {
        e.preventDefault();
        var needMark = $(this).data('need-mark');
        var msg = $('#msg-modal').val();
        var selectedUsers = $('#personal_office_grid').yiiGridView('getSelectedRows');
        $.ajax({
            dataType: 'JSON',
            type: 'POST',
            url: $('#message-send-form').attr('action'),
            data: {users: selectedUsers, msg: msg, needMark: needMark},
            success: function (data) {
                //show result on same modal
                $('.modal-body').css('display', 'none');
                if(data.response) {
                    $('.modal-title').text('Повідомлення надіслано.');
                } else {
                    $('.modal-title').html('Повідомлення не надіслано.' + '</br>' + ' Перевірте введені дані або зверніться до адміністратора.');
                }
            }
        });
        return false;
    });

    //after hide modal reset value of title
    $('#send-message-modal').on('hidden.bs.modal', function () {
        $('.modal-title').text('Надіслати повідомлення для всіх обраних користувачів');
        $('.modal-body').css('display', 'block');
        $('#message-send-form').trigger('reset');
        $('.modal-body p').detach();
    });

    //copy follow link after click
    $('#followLink').on('click', function (e) {
        var message =
        '<div id="w10-success" class="alert-success alert fade in">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
            '<i class="icon fa fa-check"></i>' + $(this).data('text') + '</div>';
        var text = $(this).find('span').text();
        copyToClipboard(text);
        $('section.content').prepend(message);
        $('div#w10-success').delay(2000).slideUp(300);
        e.preventDefault();
    })
    /**/
});

function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}
/*
$('#user-region_id').on('select2:opening', function(e) {
    var region_id = $(this).val();
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/site/get-regions',
        data: {region_id: region_id},
        success: function(data) {
            $('#user-region_id').text('');
            $('#user-region_id').val('').change();
            $('#user-region_id').append('<option></option>');
            for (var region_id in data){
                $('#user-region_id').append('<optgroup label=\"' + region_id + '\">');
                for(var inner_id in data[region_id]) {
                    $('#user-region_id').append('<option value=\"' + inner_id + '\">' + data[region_id][inner_id] + '</option>');
                }
                $('#user-region_id').append('</optgroup>');
            }
        }
    });
});*/

/*$('#user-areas_id').on('select2:selected', function() {
    var id = $('#user-areas_id option:selected').val();
    console.log(id);
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: '/site/set-session-for-areas',
        data: {id: id},
        success: function(data) {
           console.log("success");
        }
    });
});*/

function updateParticipant(element) {
    var button = $(element);
    var url = button.attr('data-request');
    var id = button.attr('data-id');
    var event_id = button.attr('data-event-id');
    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {'user_id' : id, 'event_id' : event_id},
        success: function(data) {
            if(data.status) {
                $.pjax.reload({container: '#user-events-pjax'});
            }
        },
        error: function(data) {
            console.log("error");
        }
    });
}
