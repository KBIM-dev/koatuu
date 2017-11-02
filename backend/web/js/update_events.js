
function updateUserTim(element) {
    event.stopPropagation();
    event.preventDefault();
    var user_id = $(element).data('user_id');
    var event_id = $(element).data('event_id');
    var url = $(element).data('request');
    var target_class =  $(element).data('target_class');
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: {event_id: event_id, user_id:user_id},
        success: function(data){
            if(data.status){
                $.pjax.reload({container: '#event-tim-pjax', async:false});
                $.pjax.reload({container: '#event-user-pjax', async:false});
                $.pjax.reload({container: '#user-search-form-pjax', async:false});
            }
        }
    });
}


