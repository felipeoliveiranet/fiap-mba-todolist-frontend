<!DOCTYPE html>
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
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<script>

		var html = "";
		var endpoint = "http://localhost/todolist/tasks/"

		function createTableViewCellWithItemWith(id, description) {
			var tableViewCellWithItem  = '<div class="tableViewCell" id="tableViewCellDorItem_'+id+'">';
				tableViewCellWithItem += '<input class="input" id="input_'+id+'" type="text" placeholder="Novo item..." value="'+description+'" oninput="updateItem('+id+', this)">';
				tableViewCellWithItem += '<div class="deleteButton" onclick="onDelete('+id+', this)"></div>';
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
		
		$(document).ready(function() {

			$('#inputWithNewItem').focus();

			$("#inputWithNewItem").on('keypress',function(e) {
				if(e.which == 13)
					$("#plusButton").trigger('click');
			});

			$.ajax({
					type: "GET",
					url: endpoint,
					success: function(data) {

						for(i=0; i < data.length; i++) {

							item = data[i];

							$("#tableView").after(createTableViewCellWithItemWith(item['id_task']['N'], item['title']['S']));
						}
					},
					error: function() {
						alert('Error occured');
					},
					dataType: 'json'
				});

			$("#plusButton").click(function() {

				field = $('#inputWithNewItem');
				title = field.val();

				$.ajax({
					type: "POST",
					url: endpoint,
					data: {'title': title },
					success: function(data) {

						item = createTableViewCellWithItemWith(data['id_task']['N'], data['title']['S']);
						$("#tableView").append(item);

						field.val('');
						field.focus();

					},
					error: function() {
						alert('Error occured');
					},
					dataType: 'json'
				});
			});
		});

		function onDelete(id, bt) {

			$(bt).removeAttr('onclick');

			$.ajax({
				type: "DELETE",
				url: endpoint + id,
				success: function(data) {

					$("#tableViewCellDorItem_" + id).remove();
					
				},
				error: function() {
					alert('Error occured');
				},
				dataType: 'json'
			});
		}

		var updateTimeout;
		var updateTask;

		function updateItem(id, obj) {

			clearTimeout(updateTimeout);
			updateTimeout = setTimeout(updateStop, 3000);

			updateTask = {

				'id': id,
				'bt': obj
			}

		}
		function updateStop() {

			
			id = updateTask['id'];
			title = $(updateTask['bt']).val();

			$.ajax({
				type: "PATCH",
				url: endpoint + id,
				data: {'title': title},
				success: function(data) {

					updateTask = {};
				},
				error: function() {
					alert('Error occured');
				},
				dataType: 'json'
			});
		}
	</script>
</body>
</html>
