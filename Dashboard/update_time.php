<?php
// Sertakan file koneksi
include('../config.php');

// Menerima ID dan waktu dari permintaan AJAX dan menyimpan waktu ke database berdasarkan ID
if(isset($_POST['id_schedule']) && isset($_POST['waktu'])) {
    $id_schedule = $_POST['id_schedule'];
    $waktu = $_POST['waktu'];

    // Pastikan koneksi database berhasil
    if ($conn->connect_error) {
        die(json_encode(array('status' => 'error', 'message' => 'Koneksi database gagal: ' . $conn->connect_error)));
    }

    // Gunakan prepared statement untuk mencegah SQL injection
    $sql = "UPDATE schedule_delivery SET waktu = ? WHERE id_schedule = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameter ke statement
        $stmt->bind_param("si", $waktu, $id_schedule);

        // Eksekusi statement
        if ($stmt->execute()) {
            $response = array('status' => 'success', 'message' => 'Waktu berhasil disimpan ke database');
        } else {
            $response = array('status' => 'error', 'message' => 'Gagal menyimpan waktu ke database: ' . $stmt->error);
        }

        // Tutup statement
        $stmt->close();
    } else {
        $response = array('status' => 'error', 'message' => 'Error dalam persiapan statement: ' . $conn->error);
    }

    // Tutup koneksi setelah selesai
    $conn->close();

    // Memberikan respons dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Permintaan tidak valid'));
}
?>