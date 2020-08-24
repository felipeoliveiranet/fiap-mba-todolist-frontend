
function showLoading() {

    $("#tableView").hide();
    $("#tableViewCell").hide();
    $("#tableViewLoading").show();
}

function hideLoading() {

    $("#tableViewLoading").hide();
    $("#tableViewCell").show();
    $("#tableView").show();
}

function showAlertMessage(msg) {

    hideLoading();
    
    $(".alert").show();
    $(".alert .text").html(msg);

    window.location.hash = '#alertAnchor';
}

function hideAlertMessage() {

    $(".alert").hide();
    $(".alert .text").html('');

    window.location.hash = '#todolist';
}

function showErrorMessage() {

    showAlertMessage("Falha ao processar!")
}

function hideErrorMessage() {

    hideAlertMessage()
}

function sleep(delay) {

    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}
