<?php
header('Content-type:application/json;charset=utf-8');
header("Access-Control-Allow-Origin: *");

include "classes/Connection.class.php";


if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else if (isset($_POST['type'])) {
    $type = $_POST['type'];
}

$connection = new Connection("localhost", "root", "", "acordes");
$return = (object)[];

$Types = [
    "add"
];

if (isset($type)) {
    if (in_array($type, $Types)) {
        switch ($type) {
            case 'add':
                if ($connection->select("*", "acordes", "acorde_group = '" . $_POST['group'] . "' AND acorde_cifra = '" . $_POST['cifra'] . "'")->num_rows > 0) {
                    $return = (object)[
                        "result" => 0,
                        "msg" => "Acorde já existente no banco de dados!"
                    ];
                    break;
                }
                $insertColumns = "";
                $insertValues = "";
                foreach ($_POST as $key => $value) {
                    if ($key != "type") {
                        if ($key == "cordas_tocadas" || $key == "posicoes") {
                            $value =  json_encode($value);
                        }
                        $insertColumns .= "acorde_" . $key;
                        $insertValues .= "'" . $value . "'";
                        if ($key != "casa_inicial") {
                            $insertColumns .= ", ";
                            $insertValues .= ", ";
                        }
                    }
                }
                $insert = $connection->insert($insertColumns, "acordes", $insertValues);

                if ($insert) {
                    $return = (object)[
                        "result" => 1,
                        "msg" => "Acorde inserido com sucesso"
                    ];
                }

                break;
        }
    }
}

echo json_encode($return);
