$(document).ready(function() {
  loadPhotoResults();
  setupImageModal();
  setupVideoModal();
  setupTimelapseModal();

  $('#fotos-tab').on('shown.bs.tab', loadPhotoResults);
  $('#videos-tab').on('shown.bs.tab', loadVideoResults);
  $('#timelapse-tab').on('shown.bs.tab', loadTimelapseResults);
});

function requestResults(url, onSuccess, onEmpty, onError) {
  $.ajax({
    url,
    type: 'GET',
    dataType: 'json',
    cache: false,
    headers: {
      'Cache-Control': 'no-cache, no-store, must-revalidate',
      Pragma: 'no-cache',
      Expires: '0'
    },
    success: function(response) {
      if (response.success && response.data && response.data.length > 0) {
        onSuccess(response.data);
      } else {
        onEmpty(response);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error('Error cargando resultados:', textStatus, errorThrown, jqXHR.responseText);
      onError();
    }
  });
}

function loadPhotoResults() {
  setLoading('fotos', true);
  requestResults('ws/getResults.php', function(data) {
    $('#photoCount').text(data.length);
    displayPhotoResults(data);
    setResultsVisible('fotos', true);
  }, function() {
    $('#photoCount').text('0');
    setResultsVisible('fotos', false);
  }, function() {
    $('#photoCount').text('!');
    setResultsVisible('fotos', false);
  });
}

function loadVideoResults() {
  setLoading('videos', true);
  requestResults('ws/getVideoResults.php', function(data) {
    $('#videoCount').text(data.length);
    displayVideoResults(data);
    setResultsVisible('videos', true);
  }, function() {
    $('#videoCount').text('0');
    setResultsVisible('videos', false);
  }, function() {
    $('#videoCount').text('!');
    setResultsVisible('videos', false);
  });
}

function loadTimelapseResults() {
  setLoading('timelapse', true);
  requestResults('ws/getTimelapseResults.php', function(data) {
    $('#timelapseCount').text(data.length);
    displayTimelapseResults(data);
    setResultsVisible('timelapse', true);
  }, function() {
    $('#timelapseCount').text('0');
    setResultsVisible('timelapse', false);
  }, function() {
    $('#timelapseCount').text('!');
    setResultsVisible('timelapse', false);
  });
}

function setLoading(type, isLoading) {
  $(`#loading-${type}`).toggle(isLoading);
  $(`#results-${type}`).hide();
  $(`#no-results-${type}`).hide();
}

function setResultsVisible(type, hasResults) {
  $(`#loading-${type}`).hide();
  $(`#results-${type}`).toggle(hasResults);
  $(`#no-results-${type}`).toggle(!hasResults);
}

function displayPhotoResults(results) {
  const html = results.map(function(result) {
    const imagePath = `img/${escapeAttribute(result.image || '')}`;
    const formattedDate = formatDate(result.date);

    return `
      <div class="col-xl-4 col-md-6">
        <article class="result-card">
          <div class="image-container" onclick="openImageModal('${imagePath}')">
            <img src="${imagePath}" alt="Analisis ${escapeAttribute(result.id)}" class="result-image" onerror="this.src='assets/img/no-image.png'">
            <div class="zoom-indicator"><i class="bx bx-zoom-in"></i> Ampliar</div>
          </div>
          <div class="result-info">
            <div class="result-title"><i class="bx bx-analyse"></i> Foto #${escapeHtml(result.id)}</div>
            <div class="stats-grid">
              ${stat('L*a*b*', numberValue(result.llab))}
              ${stat('XYZ', numberValue(result.xyz))}
              ${stat('HSV', numberValue(result.vhsv))}
              ${stat('Rojo', numberValue(result.red))}
              ${stat('% rojo', percentValue(result.porcentaje))}
              ${stat('Saturacion', numberValue(result.saturacion))}
            </div>
            <div class="result-date"><i class="bx bx-calendar"></i> ${formattedDate}</div>
          </div>
        </article>
      </div>
    `;
  }).join('');

  $('#results-fotos').html(html);
}

function displayVideoResults(results) {
  const html = results.map(function(result) {
    const videoName = result.video || result.video_path || '';
    const videoPath = `ws/uploads/videos/${escapeAttribute(videoName)}`;
    const formattedDate = formatDate(result.date || result.created_at || result.timestamp);

    return `
      <div class="col-xl-4 col-md-6">
        <article class="result-card">
          <div class="video-container" onclick="openVideoModal('${videoPath}')">
            <video class="video-preview" muted preload="metadata">
              <source src="${videoPath}" type="video/mp4">
            </video>
            <div class="video-overlay"><i class="bx bx-play"></i></div>
            <div class="video-info"><i class="bx bx-video"></i> Video #${escapeHtml(result.id)}</div>
          </div>
          <div class="result-info">
            <div class="result-title"><i class="bx bx-video"></i> Video #${escapeHtml(result.id)}</div>
            <div class="stats-grid">
              ${stat('Modo', escapeHtml(result.analysis_mode || 'N/D'))}
              ${stat('Frames', escapeHtml(result.total_frames || 0))}
              ${optionalStat('LAB avg', result.llab_avg)}
              ${optionalStat('XYZ avg', result.xyz_avg)}
              ${optionalStat('HSV avg', result.vhsv_avg)}
              ${optionalStat('Rojo avg', result.red_avg)}
            </div>
            <div class="result-date"><i class="bx bx-calendar"></i> ${formattedDate}</div>
          </div>
        </article>
      </div>
    `;
  }).join('');

  $('#results-videos').html(html);
}

function displayTimelapseResults(results) {
  const html = results.map(function(result) {
    const imagePaths = Array.isArray(result.image_paths) ? result.image_paths : [];
    const firstImage = imagePaths.length > 0 ? `img/${escapeAttribute(imagePaths[0])}` : 'assets/img/no-image.png';
    const modalPayload = escapeAttribute(JSON.stringify(imagePaths));
    const formattedDate = formatDate(result.date);
    const shortId = String(result.id || '').slice(-8);

    return `
      <div class="col-xl-4 col-md-6">
        <article class="result-card">
          <div class="image-container" onclick="openTimelapseModal('${escapeAttribute(result.id)}', '${modalPayload}')">
            <img src="${firstImage}" alt="Time-lapse ${escapeAttribute(result.id)}" class="result-image" onerror="this.src='assets/img/no-image.png'">
            <div class="zoom-indicator"><i class="bx bx-images"></i> Secuencia</div>
            <div class="timelapse-badge"><i class="bx bx-movie"></i> ${escapeHtml(result.num_frames || imagePaths.length)} frames</div>
          </div>
          <div class="result-info">
            <div class="result-title"><i class="bx bx-movie"></i> Time-lapse #${escapeHtml(shortId)}</div>
            <div class="stats-grid">
              ${stat('LAB avg', numberValue(result.lab_avg))}
              ${stat('XYZ avg', numberValue(result.xyz_avg))}
              ${stat('V HSV avg', numberValue(result.v_avg))}
              ${stat('Rojo avg', numberValue(result.red_avg))}
              ${stat('Frames', escapeHtml(result.num_frames || imagePaths.length))}
              ${stat('Imagenes', escapeHtml(imagePaths.length))}
            </div>
            <div class="result-date"><i class="bx bx-calendar"></i> ${formattedDate}</div>
          </div>
        </article>
      </div>
    `;
  }).join('');

  $('#results-timelapse').html(html);
}

function stat(label, value) {
  return `
    <div class="stat-item">
      <div class="stat-value">${value}</div>
      <div class="stat-label">${label}</div>
    </div>
  `;
}

function optionalStat(label, value) {
  if (value === null || value === undefined || value === '') {
    return '';
  }

  return stat(label, numberValue(value));
}

function numberValue(value) {
  const parsed = parseFloat(value);
  return Number.isFinite(parsed) ? parsed.toFixed(2) : 'N/D';
}

function percentValue(value) {
  const parsed = parseFloat(value);
  return Number.isFinite(parsed) ? `${parsed.toFixed(2)}%` : 'N/D';
}

function formatDate(dateString) {
  if (!dateString) {
    return 'Sin fecha';
  }

  const date = new Date(dateString);
  if (Number.isNaN(date.getTime())) {
    return escapeHtml(dateString);
  }

  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

function setupImageModal() {
  const modal = $('#imageModal');
  const modalImg = $('#modalImage');

  modal.find('.close-modal').on('click', function() {
    modal.fadeOut(180);
  });

  modal.on('click', function(e) {
    if (e.target === this) {
      modal.fadeOut(180);
    }
  });

  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' && modal.is(':visible')) {
      modal.fadeOut(180);
    }
  });

  modalImg.on('error', function() {
    modalImg.attr('src', 'assets/img/no-image.png');
  });
}

function setupVideoModal() {
  const modal = $('#videoModal');
  const modalVideo = $('#modalVideo');

  modal.find('.close-modal').on('click', function() {
    modalVideo[0].pause();
    modal.fadeOut(180);
  });

  modal.on('click', function(e) {
    if (e.target === this) {
      modalVideo[0].pause();
      modal.fadeOut(180);
    }
  });

  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' && modal.is(':visible')) {
      modalVideo[0].pause();
      modal.fadeOut(180);
    }
  });
}

function setupTimelapseModal() {
  const modal = $(`
    <div id="timelapseModal" class="image-modal">
      <span class="close-modal">&times;</span>
      <div class="modal-content">
        <div class="timelapse-viewer">
          <img id="timelapseImage" class="modal-image" src="" alt="Frame time-lapse">
          <div class="timelapse-controls">
            <button id="prevFrame" class="btn-control" type="button" aria-label="Frame anterior"><i class="bx bx-chevron-left"></i></button>
            <span id="frameCounter">Frame 1/1</span>
            <button id="nextFrame" class="btn-control" type="button" aria-label="Frame siguiente"><i class="bx bx-chevron-right"></i></button>
          </div>
        </div>
      </div>
    </div>
  `);

  $('body').append(modal);

  modal.find('.close-modal').on('click', function() {
    modal.fadeOut(180);
  });

  modal.on('click', function(e) {
    if (e.target === this) {
      modal.fadeOut(180);
    }
  });
}

function openImageModal(imageSrc) {
  $('#modalImage').attr('src', imageSrc);
  $('#imageModal').fadeIn(180);
}

function openVideoModal(videoSrc) {
  const modalVideo = $('#modalVideo');
  modalVideo.find('source').attr('src', videoSrc);
  modalVideo[0].load();
  $('#videoModal').fadeIn(180);
}

function openTimelapseModal(timelapseId, imagePathsJson) {
  let imagePaths = [];

  try {
    imagePaths = JSON.parse(unescapeAttribute(imagePathsJson));
  } catch (error) {
    console.error('No se pudo abrir el time-lapse', timelapseId, error);
  }

  if (!Array.isArray(imagePaths) || imagePaths.length === 0) {
    alert('No hay imagenes disponibles para este time-lapse');
    return;
  }

  const modal = $('#timelapseModal');
  const img = $('#timelapseImage');
  const counter = $('#frameCounter');
  let currentFrame = 0;

  function showFrame(index) {
    img.attr('src', `img/${imagePaths[index]}`);
    counter.text(`Frame ${index + 1}/${imagePaths.length}`);
  }

  $('#prevFrame').off('click').on('click', function() {
    currentFrame = (currentFrame - 1 + imagePaths.length) % imagePaths.length;
    showFrame(currentFrame);
  });

  $('#nextFrame').off('click').on('click', function() {
    currentFrame = (currentFrame + 1) % imagePaths.length;
    showFrame(currentFrame);
  });

  showFrame(0);
  modal.fadeIn(180);
}

function escapeHtml(value) {
  return String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function escapeAttribute(value) {
  return escapeHtml(value).replaceAll('`', '&#096;');
}

function unescapeAttribute(value) {
  return $('<textarea/>').html(value).text();
}
