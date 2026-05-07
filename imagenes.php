<?php
session_start(); // Inicia la sesión

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // No hay usuario logueado, redirige a index.php
    header("Location: index.php");
    exit; // Asegúrate de llamar a exit después de redirigir para detener la ejecución del script.
} else {
    $Usuario = $_SESSION['usuario']; // Usuario logueado
    // Aquí puedes continuar con el resto de tu código para usuarios logueados.
}

$directorio = 'img/';
$imagenes = scandir($directorio);
$listaImagenes = array();

foreach ($imagenes as $img) {
    if ($img == '.' || $img == '..') continue;
    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $img)) {
        list($tipo, $id) = explode('-', trim($img, '.jpg .jpeg .png .gif'), 2);
        $listaImagenes[] = array('nombre' => $img, 'tipo' => strtoupper($tipo), 'id' => $id);
    }
}

echo json_encode($listaImagenes);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>eclicare</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/rojole-touch-icon.png" rel="rojole-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $.ajax({
        url: 'listarImagenes.php',
        type: 'GET',
        dataType: 'json',
        success: function(imagenes) {
            imagenes.forEach(function(img) {
                var itemHtml = '<div class="col-lg-4 col-md-6 portfolio-item filter-' + img.tipo.toLowerCase() + '">' +
                               '<div class="portfolio-wrap">' +
                               '<img src="https://mikeld19.sg-host.com/img/' + img.nombre + '" class="img-fluid" alt="">' +
                               '<div class="portfolio-info">' +
                               '<h4>' + img.tipo + ' ' + img.id + '</h4>' +
                               '<p>' + img.tipo + '</p>' +
                               '<div class="portfolio-links">' +
                               '<a href="https://mikeld19.sg-host.com/img/' + img.nombre + '" data-gallery="portfolioGallery" class="portfolio-lightbox" title="' + img.tipo + ' ' + img.id + '"><i class="bx bx-plus"></i></a>' +
                               '</div></div></div></div>';
                $('.portfolio-container').append(itemHtml);
            });

            // Asegúrate de que Isotope se inicialice aquí, después de que todas las imágenes han sido añadidas
            var $grid = $('.portfolio-container').isotope({
                itemSelector: '.portfolio-item',
                layoutMode: 'fitRows'
            });

            // Filtro de elementos cuando se hace clic en los botones de filtro
            $('#portfolio-flters li').on('click', function() {
                $('#portfolio-flters li').removeClass('filter-active');
                $(this).addClass('filter-active');

                var filterValue = $(this).attr('data-filter');
                $grid.isotope({ filter: filterValue });
            });
        },
        error: function() {
            console.log("Error al cargar las imágenes.");
        }
    });
});


</script>




<body>


    <i class="bi bi-list mobile-nav-toggle d-lg-none"></i>
    <!-- ======= Header ======= -->
    <header id="header" class="d-flex flex-column justify-content-center">

      <nav id="navbar" class="navbar nav-menu">
        <ul>
          <li><a href="index.php" class="nav-link scrollto"><i class="bx bx-home"></i> <span>Inicio</span></a></li>
          <?php if(isset($_SESSION['usuario'])): ?>
              <li><a href="registros.php" class="nav-link scrollto"><i class="bx bx-file-blank"></i> <span>Registros</span></a></li>
              <li><a href="imagenes.php" class="nav-link scrollto active"><i class="bx bx-book-content"></i> <span>Imágenes</span></a></li>
             <li><a href="logout.php" class="nav-link"><i class="bx bx-log-out"></i> <span>Cerrar Sesión</span></a></li>
         <?php endif; ?>
     </ul>
 </nav><!-- .nav-menu -->

</header><!-- End Header -->

<main id="main">

  <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Resultados</h2>
          <p>Imagenes realizadas con app eclicare</p>
        </div>

        <div class="row">
          <div class="col-lg-12 d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">All</li>
              <li data-filter=".RED">ROJO</li>
              <li data-filter=".BLUE">AZUL</li>
              <li data-filter=".GREEN">VERDE</li>
            </ul>
          </div>
        </div>

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

          <div class="col-lg-4 col-md-6 portfolio-item GREEN">
            <div class="portfolio-wrap">
              <img src="https://mikeld19.sg-host.com/img/GREEN-10.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>ROJO 1</h4>
                <p>rojo</p>
                <div class="portfolio-links">
                  <a href="https://mikeld19.sg-host.com/img/GREEN-10.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="rojo 1"><i class="bx bx-plus"></i></a>
                  <a href="https://mikeld19.sg-host.com/img/GREEN-10.jpg" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item BLUE">
            <div class="portfolio-wrap">
              <img src="https://mikeld19.sg-host.com/img/RED-11.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>verde 3</h4>
                <p>verde</p>
                <div class="portfolio-links">
                  <a href="https://mikeld19.sg-host.com/img/RED-11.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="verde 3"><i class="bx bx-plus"></i></a>
                  <a href="#" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item RED">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-3.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>rojo 2</h4>
                <p>rojo</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-3.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="rojo 2"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item RED">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-4.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>azul 2</h4>
                <p>azul</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-4.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="azul 2"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item GREEN">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-5.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>verde 2</h4>
                <p>verde</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-5.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="verde 2"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item BLUE">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-6.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>rojo 3</h4>
                <p>rojo</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-6.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="rojo 3"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item RED">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-7.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>azul 1</h4>
                <p>azul</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-7.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="azul 1"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item BLUE">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-8.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>azul 3</h4>
                <p>azul</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-8.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="azul 3"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item GREEN">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-9.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
                <h4>verde 3</h4>
                <p>verde</p>
                <div class="portfolio-links">
                  <a href="assets/img/portfolio/portfolio-9.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="verde 3"><i class="bx bx-plus"></i></a>
                  <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Portfolio Section -->

</main><!-- End #main -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/typed.js/typed.umd.js"></script>
<script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>