<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
} else {
    $Usuario = $_SESSION['usuario'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Registros - EcliCare</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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
    
    <!-- Custom CSS for this page -->
    <style>
        .results-container {
            padding: 20px 0;
        }
        
        .result-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        /* Contenedor de imagen con proporción fija */
        .image-container {
            position: relative;
            width: 100%;
            height: 250px; /* Altura fija más grande */
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .result-image {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain; /* Cambiado de cover a contain para mostrar imagen completa */
            cursor: pointer;
            transition: transform 0.3s ease;
            border-radius: 0;
        }

        .result-image:hover {
            transform: scale(1.02); /* Efecto hover más sutil */
        }

        /* Estilos del modal mejorados */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 95%;
            max-height: 95%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-image {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain; /* Mantener proporción en el modal */
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
            z-index: 10000;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            color: #ff6b6b;
            background: rgba(0,0,0,0.8);
        }

        /* Indicador de zoom */
        .zoom-indicator {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            pointer-events: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .image-container {
                height: 200px;
            }
            
            .modal-content {
                max-width: 98%;
                max-height: 98%;
            }
            
            .close-modal {
                top: 10px;
                right: 15px;
                font-size: 30px;
                width: 40px;
                height: 40px;
            }
}

        .result-info {
            padding: 20px;
        }
        
        .result-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .result-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        
        .detail-value {
            color: #007bff;
            font-weight: 500;
        }
        
        .result-date {
            text-align: center;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 500;
            margin-top: 15px;
            border-radius: 8px;
        }
        
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        /* Modal styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }
        
        .modal-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        
        .close-modal {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close-modal:hover {
            color: #ccc;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 8px;
            margin-top: 10px;
        }
        
        .stat-item {
            text-align: center;
            padding: 10px;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        
        .stat-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .result-details {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <i class="bi bi-list mobile-nav-toggle d-lg-none"></i>
    
    <!-- ======= Header ======= -->
    <header id="header" class="d-flex flex-column justify-content-center">
        <nav id="navbar" class="navbar nav-menu">
            <ul>
                <li><a href="index.php" class="nav-link scrollto"><i class="bx bx-home"></i> <span>Inicio</span></a></li>
                <?php if(isset($_SESSION['usuario'])): ?>
                    <li><a href="registros.php" class="nav-link scrollto active"><i class="bx bx-file-blank"></i> <span>Registros</span></a></li>
                    <li><a href="logout.php" class="nav-link"><i class="bx bx-log-out"></i> <span>Cerrar Sesión</span></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main id="main">
        <section id="services" class="services">
            <div class="container results-container" data-aos="fade-up">
                <div class="section-title">
                    <h2>Análisis de Resultados</h2>
                    <p>Historial completo de análisis de imágenes y detección de colores</p>
                </div>
                
                <!-- Loading spinner -->
                <div id="loading" class="loading-spinner">
                    <div class="spinner"></div>
                </div>
                
                <!-- Results container -->
                <div id="results-container" class="row" style="display: none;">
                    <!-- Los resultados se cargarán aquí -->
                </div>
                
                <!-- No results message -->
                <div id="no-results" class="no-results" style="display: none;">
                    <i class="bx bx-search-alt"></i>
                    <h4>No se encontraron resultados</h4>
                    <p>Aún no hay análisis de imágenes registrados en el sistema.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal">&times;</span>
        <div class="modal-content">
            <img id="modalImage" class="modal-image" src="" alt="Imagen ampliada">
        </div>
    </div>

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/typed.js/typed.umd.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            loadResults();
            
            // Modal functionality
            setupImageModal();
        });

        function loadResults() {
            $.ajax({
                url: 'bbdd/getResultados.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#loading').hide();
                    
                    if (data.error) {
                        $('#no-results').show();
                        console.error('Error:', data.error);
                    } else if (data.length === 0) {
                        $('#no-results').show();
                    } else {
                        displayResults(data);
                        $('#results-container').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#loading').hide();
                    $('#no-results').show();
                    console.error('Error en la petición AJAX:', textStatus, errorThrown);
                }
            });
        }

        function displayResults(results) {
            let html = '';
            
            results.forEach(function(result, index) {
                const imagePath = `img/${result.image}`;
                const formattedDate = formatDate(result.date);
                
                html += `
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="result-card" data-aos="fade-up" data-aos-delay="${index * 100}">
                            <!-- Contenedor de imagen mejorado -->
                            <div class="image-container">
                                <img src="${imagePath}" 
                                     alt="Análisis ${result.id}" 
                                     class="result-image" 
                                     onclick="openImageModal('${imagePath}')"
                                     onerror="this.src='assets/img/no-image.png'">
                                <div class="zoom-indicator">
                                    <i class="bx bx-zoom-in"></i> Click para ampliar
                                </div>
                            </div>
                            
                            <div class="result-info">
                                <div class="result-title">
                                    <i class="bx bx-analyze"></i>
                                    Análisis #${result.id}
                                </div>
                                
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <div class="stat-value">${result.llab.toFixed(2)}</div>
                                        <div class="stat-label">L*a*b*</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.xyz.toFixed(2)}</div>
                                        <div class="stat-label">XYZ</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.vhsv.toFixed(2)}</div>
                                        <div class="stat-label">HSV</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.red.toFixed(2)}</div>
                                        <div class="stat-label">Red</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.porcentaje.toFixed(2)}%</div>
                                        <div class="stat-label">Porcentaje</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.saturacion.toFixed(2)}</div>
                                        <div class="stat-label">Saturación</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.contraste.toFixed(4)}</div>
                                        <div class="stat-label">Contraste</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.rms.toFixed(4)}</div>
                                        <div class="stat-label">RMS</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">${result.kmeans.toFixed(2)}</div>
                                        <div class="stat-label">K-Means</div>
                                    </div>
                                </div>
                                
                                <div class="result-date">
                                    <i class="bx bx-calendar"></i>
                                    ${formattedDate}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#results-container').html(html);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function setupImageModal() {
            const modal = $('#imageModal');
            const modalImg = $('#modalImage');
            const closeBtn = $('.close-modal');

            // Close modal when clicking the X
            closeBtn.click(function() {
                modal.fadeOut(300);
            });

            // Close modal when clicking outside the image
            modal.click(function(e) {
                if (e.target === this) {
                    modal.fadeOut(300);
                }
            });

            // Close modal with Escape key
            $(document).keydown(function(e) {
                if (e.key === 'Escape' && modal.is(':visible')) {
                    modal.fadeOut(300);
                }
            });
        }

        function openImageModal(imageSrc) {
            const modal = $('#imageModal');
            const modalImg = $('#modalImage');
            
            // Precargar la imagen para evitar parpadeos
            const img = new Image();
            img.onload = function() {
                modalImg.attr('src', imageSrc);
                modal.fadeIn(300);
            };
            img.onerror = function() {
                modalImg.attr('src', 'assets/img/no-image.png');
                modal.fadeIn(300);
            };
            img.src = imageSrc;
        }
    </script>
</body>
</html>