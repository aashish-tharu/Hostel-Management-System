<?php
session_start();
include("admin_connect.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_response'])) {
    $message_id = (int)$_POST['message_id'];
    $response = $conn->real_escape_string($_POST['response']);
    $admin_id = $_SESSION['admin_id'];
    
    $stmt = $conn->prepare("UPDATE messages SET 
                           admin_response = ?,
                           responded_by = ?,
                           response_date = NOW()
                           WHERE id = ?");
    $stmt->bind_param("sii", $response, $admin_id, $message_id);
    $stmt->execute();
}

$messages = $conn->query("SELECT m.*, u.full_name, u.room_no
                          FROM messages m
                          JOIN users u ON m.student_id = u.id
                          ORDER BY m.created_at DESC");
?>

<table class="messages-table">
    <thead>
        <tr>
            <th>Student</th>
            <th>Room</th>
            <th>Message</th>
            <th>Date</th>
            <th>Response</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($message = $messages->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($message['full_name']) ?></td>
            <td><?= htmlspecialchars($message['room_no']) ?></td>
            <td><?= htmlspecialchars($message['message']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($message['created_at'])) ?></td>
            <td>
                <?php if ($message['admin_response']): ?>
                    <div class="response">
                        <strong>Response:</strong>
                        <p><?= htmlspecialchars($message['admin_response']) ?></p>
                        <small>By Admin on <?= date('d/m/Y', strtotime($message['response_date'])) ?></small>
                    </div>
                <?php else: ?>
                    <span class="pending">Pending response</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!$message['admin_response']): ?>
                <form method="post">
                    <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                    <textarea name="response" placeholder="Type your response..." required></textarea>
                    <button type="submit" name="send_response" class="btn-respond">Send Response</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>