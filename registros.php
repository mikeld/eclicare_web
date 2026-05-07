<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$Usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Registros | EcliCare</title>
  <meta content="Panel de resultados de EcliCare para fotos, videos y time-lapse ECL." name="description">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/css/eclicare.css" rel="stylesheet">
</head>

<body class="records-page">
  <header class="records-topbar">
    <a class="brand-mark" href="index.php" aria-label="EcliCare inicio">
      <span class="brand-symbol">EC</span>
      <span>
        <strong>EcliCare</strong>
        <small><?php echo htmlspecialchars($Usuario, ENT_QUOTES, 'UTF-8'); ?></small>
      </span>
    </a>

    <div class="records-actions">
      <a href="index.php" class="btn btn-outline-secondary">
        <i class="bi bi-house"></i>
        Inicio
      </a>
      <a href="logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i>
        Salir
      </a>
    </div>
  </header>

  <main>
    <section class="records-hero">
      <div>
        <span class="eyebrow">Panel privado</span>
        <h1>Analisis de resultados</h1>
        <p>Historico de capturas, videos y secuencias time-lapse generado desde la app Android EcliCare.</p>
      </div>

      <div class="records-summary" aria-label="Resumen de registros">
        <div class="summary-pill">
          <span>Fotos</span>
          <strong id="photoCount">-</strong>
        </div>
        <div class="summary-pill">
          <span>Videos</span>
          <strong id="videoCount">-</strong>
        </div>
        <div class="summary-pill">
          <span>Time-lapse</span>
          <strong id="timelapseCount">-</strong>
        </div>
      </div>
    </section>

    <section class="records-shell">
      <div class="content-wrapper">
        <ul class="nav nav-tabs" id="resultTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="fotos-tab" data-bs-toggle="tab" data-bs-target="#fotos" type="button" role="tab">
              <i class="bx bx-image"></i>
              Fotos
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos" type="button" role="tab">
              <i class="bx bx-video"></i>
              Videos
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="timelapse-tab" data-bs-toggle="tab" data-bs-target="#timelapse" type="button" role="tab">
              <i class="bx bx-movie"></i>
              Time-lapse ECL
            </button>
          </li>
        </ul>

        <div class="tab-content" id="resultTabsContent">
          <div class="tab-pane fade show active" id="fotos" role="tabpanel" aria-labelledby="fotos-tab">
            <div id="loading-fotos" class="loading-spinner">
              <div>
                <div class="spinner mx-auto mb-3"></div>
                <p>Cargando analisis de fotos...</p>
              </div>
            </div>
            <div id="results-fotos" class="row g-4" style="display: none;"></div>
            <div id="no-results-fotos" class="no-results" style="display: none;">
              <div>
                <i class="bx bx-image fs-1"></i>
                <h4>No hay analisis de fotos</h4>
                <p>Aun no hay capturas registradas en el sistema.</p>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="videos-tab">
            <div id="loading-videos" class="loading-spinner">
              <div>
                <div class="spinner mx-auto mb-3"></div>
                <p>Cargando analisis de videos...</p>
              </div>
            </div>
            <div id="results-videos" class="row g-4" style="display: none;"></div>
            <div id="no-results-videos" class="no-results" style="display: none;">
              <div>
                <i class="bx bx-video fs-1"></i>
                <h4>No hay analisis de videos</h4>
                <p>Aun no hay videos registrados en el sistema.</p>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="timelapse" role="tabpanel" aria-labelledby="timelapse-tab">
            <div id="loading-timelapse" class="loading-spinner">
              <div>
                <div class="spinner mx-auto mb-3"></div>
                <p>Cargando time-lapse ECL...</p>
              </div>
            </div>
            <div id="results-timelapse" class="row g-4" style="display: none;"></div>
            <div id="no-results-timelapse" class="no-results" style="display: none;">
              <div>
                <i class="bx bx-movie fs-1"></i>
                <h4>No hay time-lapse ECL</h4>
                <p>Aun no hay secuencias registradas en el sistema.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div id="imageModal" class="image-modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
      <img id="modalImage" class="modal-image" src="" alt="Imagen ampliada">
    </div>
  </div>

  <div id="videoModal" class="video-modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
      <video id="modalVideo" class="modal-video" controls>
        <source src="" type="video/mp4">
        Tu navegador no soporta video HTML5.
      </video>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/registros.js"></script>
</body>

</html>
