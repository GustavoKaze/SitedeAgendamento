<?php
session_start(); // Inicia a sessão para poder matá-la

// Apaga todas as variáveis da sessão
$_SESSION = array();

// Se quiser matar o cookie da sessão também (limpeza profunda)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// REDIRECIONAMENTO VIA JAVASCRIPT (Mais garantido neste caso)
echo "<script>
        alert('Você saiu do sistema.');
        window.location.href = 'index.html';
      </script>";
exit;
?>