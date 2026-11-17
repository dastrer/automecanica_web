
//Función que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init() {

  // Llama a updateNotifications al cargar la página para una primera actualización
  updateNotifications();
  updateNotificationsMail();
}



function updateNotifications() {
  $.ajax({
    url: '../ajax/header.php?op=listarnotificacionArticulo',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      // Actualiza el número de notificaciones
      $('#notification-badge').text(data.count);
      $('#notification-header').text(data.count + ' articulos con stock bajo');

      // Limpia las notificaciones anteriores
      $('#notification-items').empty();

      // Añade nuevas notificaciones
      $.each(data.notifications, function (index, articulo) {
  
          var item = $('<li></li>').html(`
            <a href="articulo.php" class="dropdown-item">
              <i class="fas fa-box-open mr-2 text-warning"></i>
              <span class="message-text">
                <strong>${articulo.nombre}</strong> tiene solo <strong>${articulo.stock}</strong> unidades.
              </span>
            </a>
          `);

        $('#notification-items').append(item);
      });
    },
    error: function (xhr, status, error) {
      console.error('Error al obtener notificaciones de stock:', error);
    }
  });
}
// Llama a updateNotifications cada segundo
setInterval(updateNotifications, 1000);


function updateNotificationsMail() {
  $.ajax({
    url: '../ajax/header.php?op=listarnotificacion',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
      // Actualiza el número de notificaciones
      $('#notification-badge-mail').text(data.count);
      $('#notification-header-mail').text(data.count + ' Mensajes nuevos');

      // Limpia las notificaciones anteriores
      $('#notification-mail').empty();

      // Añade nuevas notificaciones
      $.each(data.notifications, function (index, notification) {
        var item = $('<li></li>').html(`
            <a href="mensaje.php" class="dropdown-item">
              <i class="fas fa-envelope mr-2 text-primary"></i>
              <span class="message-text">${notification.message}</span><br>
              <span class="float-left text-muted text-sm">${notification.time}</span>
            </a>
          `);
        $('#notification-mail').append(item);

      });
    },
    error: function (xhr, status, error) {
      console.error('Error al obtener notificaciones:', error);
    }
  });
}


// Llama a updateNotifications cada segundo
setInterval(updateNotificationsMail, 1000);



init();