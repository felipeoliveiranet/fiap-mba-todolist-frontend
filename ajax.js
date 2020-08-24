
var endpoint = "http://todolink-backend-env.eba-hdhbezsi.us-east-1.elasticbeanstalk.com/tasks/"

function getItems() {

    $.ajax({
        type: "GET",
        url: endpoint,
        success: function(data) {

            hideAlertMessage();

            for(i=0; i < data.length; i++) {

                item = data[i];

                $("#tableView").after(createTableViewCellWithItemWith(item['id_task']['S'], item['title']['S']));
            }

            hideLoading();

        },
        error: function() {
            showErrorMessage();
        },
        done: function() {

		    $('#inputWithNewItem').focus();
        },
        dataType: 'json'
    });
}

    function addItemClick() {

        $("#plusButton").click(function() { addItem(this); });
    }
        
    addItemClick();

    function addItem(obj) {

        field = $('#inputWithNewItem');
        title = field.val();

        if(title == "") {
            showAlertMessage("Prencha o titulo da tarefa!");
            return false;
        }

        $(this).unbind('click');

        $.ajax({
            type: "POST",
            url: endpoint,
            data: {'title': title },
            success: function(data) {

                hideAlertMessage();

                item = createTableViewCellWithItemWith(data['id_task']['S'], data['title']['S']);
                $("#tableView").append(item);

                field.val('');
                field.focus();
            },
            error: function() {
                showErrorMessage();
            },
            done: function() {

                addItemClick();
            },
            dataType: 'json'
        });
    }

function onDelete(id, bt) {

    $(bt).removeAttr('onclick');

    $.ajax({
        type: "DELETE",
        url: endpoint + id,
        success: function(data) {

            hideAlertMessage();

            $("#tableViewCellDorItem_" + id).remove();
            
        },
        error: function() {
            showErrorMessage();
        },
        dataType: 'json'
    });
}

var updateTimeout;
var updateTask;

function updateItemTimeout(id, obj) {

    clearTimeout(updateTimeout);
    updateTimeout = setTimeout(updateItem, 1000);

    updateTask = {

        'id': id,
        'bt': obj
    }

}

function updateItem() {

    id = updateTask['id'];
    title = $(updateTask['bt']).val();

    if(title == "") {
    
        showAlertMessage("Prencha o titulo da tarefa!");
        return false;
    }

    $.ajax({
        type: "PATCH",
        url: endpoint + id,
        data: {'title': title},
        success: function(data) {

            hideAlertMessage();
            updateTask = {};
        },
        error: function() {
            showErrorMessage();
        },
        dataType: 'json'
    });
}
