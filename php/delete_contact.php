<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id']) && isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $contact_id = intval($_GET['id']);

    $verify_query = "SELECT * FROM contacts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("ii", $contact_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $delete_query = "DELETE FROM contacts WHERE id = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("i", $contact_id);
        if ($stmt_delete->execute()) {
            echo "<script>
                    alert('Contact deleted successfully!');
                    window.location.href = 'dashboard.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error deleting contact!');
                    window.location.href = 'dashboard.php';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Unauthorized or contact not found!');
                window.location.href = 'dashboard.php';
              </script>";
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
