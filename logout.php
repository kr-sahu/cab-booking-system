<?php
// Initialize session to access active user state
session_start();
// Terminate all session variables and destroy the session server-side
session_destroy();

// Clear chat history on the client side
echo "<script>localStorage.removeItem('zuber_chat_history'); window.location.href='index.php';</script>";
exit();
?>
