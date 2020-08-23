<?php header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	
	$id = test_input($_POST["id"]);
    $description = test_input($_POST["description"]);
    $sqlCommand = test_input($_POST["sqlCommand"]);

    
	include_once '../../_db/conector.php';

    $mysqli -> set_charset('utf8');

        $html = '';

        if ($action == 'update') {
            if ($stmt = $mysqli->prepare("UPDATE items SET descriptions=? WHERE id=?")) {
                $stmt -> bind_param('si', $description, $id);
                $stmt -> execute();
                $stmt -> close();
            }
            $html = 'ready';
        }

        if ($action == 'delete') {
            if ($stmt = $mysqli->prepare("DELETE FROM items WHERE id=?")) {
                $stmt -> bind_param('i',$id);
                $stmt -> execute();
                $stmt -> close();
            }
            $html = 'ready';
        }

        if ($action == 'insert') {
            if ($stmt = $mysqli->prepare("INSERT INTO items (descriptions) VALUES (?)")) {
                $stmt -> bind_param('s', $descriptions);
                $stmt -> execute();
                $stmt -> close();
            }
            $html = 'ready';
        }

        if ($action == 'select') {

            if ($stmt = $mysqli->prepare("SELECT id, descriptions FROM items WHERE id=?")) {
                $stmt -> bind_param('i', $id);
                $stmt -> execute();
                $stmt -> bind_result($id, $descriptions);
                while ($stmt -> fetch()){

                    // TODO $html .= '';

                }

                $stmt -> close();

            }
			
            // TODO $html .= '</div>';

        }

    $mysqli -> close();

	echo $html;

}

?>
