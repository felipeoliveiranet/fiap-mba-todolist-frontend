var html = "";

function createTableViewCellWithItemWith(id, description) {
    var tableViewCellWithItem  = '<div class="tableViewCell" id="tableViewCellDorItem_'+id+'">';
        tableViewCellWithItem += '<input class="input" id="input_'+id+'" type="text" placeholder="Novo item..." value="'+description+'"';
        tableViewCellWithItem += "oninput=\"updateItemTimeout('"+id+"', this)\">";
        tableViewCellWithItem += "<div class=\"deleteButton\" onclick=\"deleteItem('" + id + "', this)\"></div>";
        tableViewCellWithItem += '<div class="divisor"></div>';
        tableViewCellWithItem += '</div>';
    return tableViewCellWithItem
}

function updateHTML(id, title) {
    html = html + createTableViewCellWithItemWith(id, title);
};

function updateTableViewWithHTML() {
    document.getElementById("tableView").innerHTML = html;
};
