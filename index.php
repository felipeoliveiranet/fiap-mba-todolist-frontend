<?php header('Content-Type: text/html; charset=UTF-8');

	// Recebido por SQL:
	$todoListItens = array(
        array("id"=>"1", "description"=>"Comprar pão"),
        array("id"=>"2", "description"=>"Comprar leite"),
        array("id"=>"3", "description"=>"Comprar ovos"),
		array("id"=>"4", "description"=>"Comprar manteiga"),
		array("id"=>"5", "description"=>"Comprar um Aston Martin")
	);
	
	$html_todoListItens = '';
	$html_comma_optional = '';
	foreach ($todoListItens as $todoListItem) {
		$id = $todoListItem["id"];
		$description = $todoListItem["description"];
		if ($html_todoListItens == '') { $html_comma_optional = ''; }
		else { $html_comma_optional = ','; }
		$html_todoListItens = $html_todoListItens . $html_comma_optional . '{id: "'. $todoListItem["id"] .'", description: "'. $todoListItem["description"] .'"}';
	}

?><!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Todo List</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<style>
	@charset "UTF-8";
	html,body { height: 100% }
	* { margin: 0; padding: 0; }
	body { min-width: 320px;
	margin: 0 auto;
	padding: 0;
	text-align: center;
	font-family: 'SanFrancisco', sans-serif;
	font-weight: 100;
	font-style: normal;
	font-size:100%;
	-webkit-tap-highlight-color: rgba(0,0,0,0);
	color: rgb(0,0,0); }
	a { outline:0; border:none; }
	img { outline:0; border:none; display:block; }
	*:focus { outline:none; }
	.clear { clear: both; }
	body {background-color: white;}
	main { width: 100%;
	max-width: 400px;
	margin: auto;
	padding: 32px;
	background-color: white;
	height: 400px;
	text-align: left; }
	h1 { margin-bottom: 32px; font-size: 3em; }
	.tableViewCell { width: 100%; height: 45px; position: relative; }
	.divisor {position: absolute; left: 0; bottom: 0; width: 100%; height: 1px; background-color: #666; }
	.deleteButton { position: absolute; right: 0; top: 0; width: 44px; height: 44px; background: url(img/trash.png); cursor: pointer; }
	#plusButton { position: absolute; right: 0; top: 0; width: 44px; height: 44px; background: url(img/plus.png); cursor: pointer; }
	.deleteButton:hover { background: url(img/trash2.png); }
	#plusButton:hover { background: url(img/plus2.png); }
	.input {width: calc(100% - 44px - 16px);
	border: none;
	height: 44px;
	background: none;
	padding-left: 8px;
	padding-right: 8px;
	font-size: 24px;
	color: #666;}
	::placeholder { color: #E6E4E7; opacity: 1; } /* Firefox */
	:-ms-input-placeholder { color: #E6E4E7; }    /* Internet Explorer 10-11 */
	::-ms-input-placeholder { color: #E6E4E7; }   /* Microsoft Edge */
	</style>
</head>
<body>
	
	<main>
	<h1>Todo List</h1>

	    <form id="form" action="sqlProcessing.php" method="post">
			<input type="hidden" id="form_id"          name="id"          value="">
		    <input type="hidden" id="form_description" name="description" value="">
		    <input type="hidden" id="form_sqlCommand"  name="sqlCommand"  value="">
		</form>
	
		<div id="tableView"></div>

		<div class="tableViewCell">
			<input class="input" type="text" id="inputWithNewItem" name="inputWithNewItem" placeholder="Novo item...">
			<div id="plusButton"></div>
			<div class="divisor"></div>
		</div>

	</main>
	
	<script>

		var html = "";
		var todoListItens = [<?php echo $html_todoListItens;?>];

		function createTableViewCellWithItemWith(id, description) {
			var tableViewCellWithItem  = '<div class="tableViewCell" id="tableViewCellDorItem_'+id+'">';
				tableViewCellWithItem += '<input class="input" id="input_'+id+'" type="text" placeholder="Novo item..." value="'+description+'" oninput="updateItem('+id+')">';
				tableViewCellWithItem += '<div class="deleteButton" onclick="deleteItem('+id+')"></div>';
				tableViewCellWithItem += '<div class="divisor"></div>';
				tableViewCellWithItem += '</div>';
			return tableViewCellWithItem
		}
	
		function updateHTML(todoListItem) {
			html = html + createTableViewCellWithItemWith(todoListItem.id, todoListItem.description);
		};
	
		function updateTableViewWithHTML() {
			document.getElementById("tableView").innerHTML = html;
		};
		
		/*
		function submmitToSQLPage(id, description, sqlCommand) {
			document.getElementById("form_id").value = id;
			document.getElementById("form_description").value = description;
			document.getElementById("form_sqlCommand").value = sqlCommand;
			document.getElementById("form").submit();
			
			window.location.reload();
			
		};
		*/

		function ajax(id, description, sqlCommand) {

			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("tableView").innerHTML = this.responseText;
				}
			};
			
			xmlhttp.open("POST", "sqlProcessing.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("id=id&description=description&sqlCommand=sqlCommand");
			
		}

		// ------------------------------------------------------

		// SELECT - Load TableView with data from DB:
		todoListItens.forEach(updateHTML);
		updateTableViewWithHTML();
		
		// UPDATE
		function updateItem(id) {
			var description = document.getElementById("input_"+id).value;
			alert("You wrote: " + description);
		}
	
		// DELETE - Delete Item
		function deleteItem(id) {
		document.getElementById("tableViewCellDorItem_"+id).remove();
		}

		// INSERT INTO - Insert new item to TableView:
		document.getElementById("plusButton").onclick = function () {
			var id = "";
			var description = document.getElementById("inputWithNewItem").value;
			var sqlCommand = "insert";
			if (description == "") {
				alert("Você deve digitar algo...");
				document.getElementById("inputWithNewItem").focus();
			}
			else {
				document.getElementById("inputWithNewItem").value = ""
				ajax(id, description, sqlCommand);
			}
			return true;
		};
		
	</script>

</body>
</html>
