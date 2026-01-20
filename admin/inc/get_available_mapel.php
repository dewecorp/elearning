<?php
session_start();
include "../koneksi.php";

try {
    $sql = mysqli_query($db, "SELECT id, mapel FROM tb_mapel ORDER BY mapel ASC");
    $mapel_list = [];
    
    while($row = mysqli_fetch_assoc($sql)) {
        $mapel_list[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($mapel_list);
} catch(Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>