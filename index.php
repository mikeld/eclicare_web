<?php
session_start();

$Usuario = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>EcliCare | Plataforma de diagnostico ECL</title>
  <meta content="Panel web del proyecto EcliCare para consultar resultados de bioensayos electroquimioluminiscentes." name="description">
  <meta content="EcliCare, ECL, diagnostico, NFC, smartphone, bioensayos" name="keywords">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/css/eclicare.css" rel="stylesheet">
</head>

<body class="landing-page">
  <header class="app-shell-header">
    <a class="brand-mark" href="index.php" aria-label="EcliCare inicio">
      <span class="brand-symbol">EC</span>
      <span>
        <strong>EcliCare</strong>
        <small>Smartphone ECL diagnostics</small>
      </span>
    </a>

    <nav class="top-actions" aria-label="Navegacion principal">
      <a href="#project">Proyecto</a>
      <?php if ($Usuario): ?>
        <a href="registros.php">Registros</a>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Cerrar sesion</a>
      <?php endif; ?>
    </nav>
  </header>

  <main>
    <section class="hero-lab">
      <div class="hero-copy">
        <span class="eyebrow">Euroregion | UPNA · Bordeaux · BCMaterials</span>
        <h1>EcliCare</h1>
        <p class="lead">
          Plataforma para consultar, revisar y comparar resultados de bioensayos
          electroquimioluminiscentes capturados desde smartphone.
        </p>

        <?php if ($Usuario): ?>
          <div class="welcome-panel">
            <span>Sesion iniciada</span>
            <strong><?php echo htmlspecialchars($Usuario, ENT_QUOTES, 'UTF-8'); ?></strong>
            <a class="btn btn-primary" href="registros.php">
              <i class="bi bi-activity"></i>
              Abrir registros
            </a>
          </div>
        <?php else: ?>
          <form id="formLogin" class="login-panel" method="post" novalidate>
            <div>
              <span class="eyebrow">Acceso privado</span>
              <h2>Entrar al panel</h2>
            </div>

            <label class="form-field" for="email">
              <span>Email</span>
              <input type="email" name="email" id="email" placeholder="eclicare@eclicare.com" autocomplete="email" required>
            </label>

            <label class="form-field" for="password">
              <span>Contrasena</span>
              <input type="password" name="password" id="password" placeholder="Tu contrasena" autocomplete="current-password" required>
            </label>

            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-box-arrow-in-right"></i>
              Iniciar sesion
            </button>
            <div id="login-result" class="form-feedback" aria-live="polite"></div>
          </form>
        <?php endif; ?>
      </div>

      <div class="lab-visual" aria-label="Resumen visual del sistema EcliCare">
        <div class="phone-frame">
          <div class="phone-top"></div>
          <div class="signal-card">
            <span>Intensidad ECL</span>
            <strong>87.4</strong>
            <div class="signal-bars">
              <i style="height: 34%"></i>
              <i style="height: 56%"></i>
              <i style="height: 42%"></i>
              <i style="height: 78%"></i>
              <i style="height: 64%"></i>
              <i style="height: 92%"></i>
              <i style="height: 70%"></i>
            </div>
          </div>
          <div class="metric-row">
            <span>LAB</span>
            <b>42.1</b>
          </div>
          <div class="metric-row">
            <span>XYZ</span>
            <b>31.8</b>
          </div>
          <div class="metric-row">
            <span>HSV</span>
            <b>66.5</b>
          </div>
        </div>
      </div>
    </section>

    <section id="project" class="project-band">
      <div class="section-heading">
        <span class="eyebrow">Como funciona</span>
        <h2>Del sensor al resultado, en una interfaz preparada para analisis.</h2>
      </div>

      <div class="feature-grid">
        <article class="feature-card">
          <i class="bx bx-chip"></i>
          <h3>NFC</h3>
          <p>El movil aporta la energia necesaria para activar el dispositivo y registrar la respuesta.</p>
        </article>
        <article class="feature-card">
          <i class="bx bx-image"></i>
          <h3>Imagen</h3>
          <p>Las capturas se procesan para extraer metricas de color, saturacion, contraste y senal.</p>
        </article>
        <article class="feature-card">
          <i class="bx bx-video"></i>
          <h3>Video y time-lapse</h3>
          <p>Los ensayos temporales se agrupan para seguir la evolucion de la senal ECL por frames.</p>
        </article>
        <article class="feature-card">
          <i class="bx bx-bar-chart-alt-2"></i>
          <h3>Resultados</h3>
          <p>El panel web centraliza fotos, videos y secuencias para revisar historico y valores clave.</p>
        </article>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <strong>EcliCare</strong>
    <span>Universidad Publica de Navarra · Universite de Bordeaux · BCMaterials</span>
  </footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#formLogin').on('submit', function(e) {
        e.preventDefault();
        $('#login-result').removeClass('is-error is-ok').text('Comprobando credenciales...');

        $.ajax({
          type: 'POST',
          url: 'bbdd/login.php',
          data: $(this).serialize(),
          success: function(response) {
            if (response == 1) {
              $('#login-result').addClass('is-ok').text('Acceso correcto. Cargando panel...');
              window.location.reload();
            } else {
              $('#login-result').addClass('is-error').text('Email o contrasena incorrectos.');
            }
          },
          error: function() {
            $('#login-result').addClass('is-error').text('No se pudo iniciar sesion. Intentalo de nuevo.');
          }
        });
      });
    });
  </script>
</body>

</html>
