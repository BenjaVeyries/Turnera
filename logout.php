<?php
session_start();
session_destroy();
echo "<script>sessionStorage.removeItem('usuario_id'); window.location.href='login.html';</script>";
exit;
?>

