<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white shadow-xl rounded-xl p-8 w-96 text-center">
    <h1 class="text-2xl font-bold mb-4">Panel privado</h1>
    <p class="mb-6">Estás logueado como: <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong></p>

    <a href="logout.php" class="block bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">Cerrar sesión</a>
  </div>

</body>
</html>


