<?php
try {
    $data = $_POST;
    $user_id = (int) $data['user_id'];
    $first_name = $data['f_name'];
    $last_name = $data['l_name'];
    $email = $data['email'];
    $now = date('Y-m-d H:i:s');

    // Check if the required data is present
    if (!$user_id || !$first_name || !$last_name || !$email) {
        throw new Exception('Missing required data');
    }

    // updating the record
    include("../connection.php");
    $conn->beginTransaction();

    $sql = "UPDATE adminstaffs SET email=?, first_name=?, last_name=?, updated_at=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $first_name, $last_name, $now, $user_id]);

    $conn->commit();

    echo json_encode([
        "success"=> true,
        "message"=> $first_name. " " . $last_name . " successfully updated."
    ]);
} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode([
        "success"=> false,
        "message"=> 'Error processing request: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success"=> false,
        "message"=> 'Error processing request: ' . $e->getMessage()
    ]);
}
?>