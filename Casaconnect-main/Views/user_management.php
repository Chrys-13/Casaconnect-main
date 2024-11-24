<?php
include '../db/connect.php';

$result = $conn->query("SELECT id, first_name, last_name, email, role, created_at FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
</head>
<body>
    <h2>Manage Users</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Registration Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role'] == 1 ? "Super Admin" : "Admin"; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="../actions/delete_user.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
