	<?php include_once($rut.CONF."5toastr.php"); ?>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<div class="wa-floating-container">
		<div class="wa-panel" id="waPanel">
			<div class="wa-header">
				<i class="fab fa-whatsapp fa-2x"></i>
				<div>
					<strong>¿Necesitas ayuda?</strong>
					<p>Chatea con nosotros por WhatsApp</p>
				</div>
			</div>
			<div class="wa-body">
				<?= $data->asesores_wsp->inf; ?>
			</div>
		</div>
		
		<button class="wa-button" onclick="toggleWaPanel()">
			<i class="fab fa-whatsapp fa-2x"></i>
			<?php if($data->asesores_wsp->cant > 0){ ?>
				<span class="wa-badge"><?= ($data->asesores_wsp->cant - 1); ?></span>
			<?php } ?>
		</button>
	</div>

	<!-- end java -->
		<script type="text/javascript">
			// ---------------------------------------------------------
				// Configuración de rutas
				const URL = '<?= URL; ?>';
				const ACTI = '<?= ACTI; ?>';
			// ---------------------------------------------------------
				// Función para abrir/cerrar el panel
				function toggleWaPanel(event) {
					// Si usas onclick="toggleWaPanel()" en el HTML, event será undefined
					// Para mayor seguridad, prevenimos que el clic suba al window
					if(event) {
						event.stopPropagation();
					} else if (window.event) {
						window.event.cancelBubble = true;
					}
					// ---------------------------------------------------------
					var panel = document.getElementById('waPanel');
					if (panel.style.display === 'block') {
						panel.style.display = 'none';
					} else {
						panel.style.display = 'block';
					}
				}
			// ---------------------------------------------------------
				// Detener la propagación si hacen clic DENTRO del panel (para que no se cierre)
				document.getElementById('waPanel').addEventListener('click', function(event) {
					event.stopPropagation();
				});
			// ---------------------------------------------------------
				// Cerrar el panel SOLO si hacen clic fuera de todo el contenedor
				window.addEventListener('click', function(event) {
					var panel = document.getElementById('waPanel');
					// Si el panel existe y está abierto, lo cerramos
					if (panel && panel.style.display === 'block') {
						panel.style.display = 'none';
					}
				});
			// ---------------------------------------------------------
				// EVENTOS DE ANALYTICS (GLOBAL PARA CUALQUIER ENLACE)
				// Compatible con Cloudflare Zaraz y GA4 nativo
				// ---------------------------------------------------------
				$(document).on('click', 'a[href]', function() {
					// Capturamos el href y le quitamos espacios
					var botonUrl = $(this).attr('href').trim();
					// ---------------------------------------------------------
					// Filtro de seguridad: Ignoramos href vacíos, o que solo sean "#" o scripts
					if (botonUrl === '' || botonUrl === '#' || botonUrl.indexOf('javascript:') === 0) {
						return; // Sale de la función y no envía nada
					}
					// ---------------------------------------------------------
					// Inteligencia para capturar el nombre del botón:
					// 1. Intenta tomar el texto visible
					// 2. Si no hay texto, busca un atributo 'title'
					// 3. Si es una imagen, busca el atributo 'alt' de esa imagen
					// 4. Si todo falla, usa 'Enlace sin texto'
					var botonNombre = $(this).text().trim() 
								|| $(this).attr('title') 
								|| $(this).find('img').attr('alt') 
								|| 'Enlace sin texto';
					// ---------------------------------------------------------
					// 1. Si Cloudflare Zaraz está manejando Analytics
					if (typeof zaraz !== 'undefined') {
						zaraz.track('click_enlace', { 
							nombre_boton: botonNombre, 
							url_destino: botonUrl 
						});
					} 
					// 2. Si Google Analytics clásico (gtag) está activo
					else if (typeof gtag === 'function') {
						gtag('event', 'click_enlace', { 
							'nombre_boton': botonNombre, 
							'url_destino': botonUrl 
						});
					} 
					// ---------------------------------------------------------
					// Log para que puedas depurar en la consola de tu navegador
					console.log('Evento global capturado: ', botonNombre, ' -> ', botonUrl);
				});
			// ---------------------------------------------------------
				// EVENTOS DE ANALYTICS (ESPECÍFICO PARA TUS SLIDERS)
				// ---------------------------------------------------------
				$(document).on('click', '.flex-caption a, .provider a, .cat-slider a', function() {
					var botonUrl = $(this).attr('href');
					if (!botonUrl || botonUrl === '#') return;
					// ---------------------------------------------------------
					// Detectamos si es un producto por la URL (si usas prod=ID o producto/nombre)
					var prodMatch = botonUrl.match(/prod=(\d+)/) || botonUrl.match(/id=(\d+)/);
					var idIdentificado = prodMatch ? ' (ID: ' + prodMatch[1] + ')' : '';
					// ---------------------------------------------------------
					var seccion = "";
					if ($(this).closest('.flex-slider').length) seccion = "Banner Principal";
					else if ($(this).closest('.cat-slider').length) seccion = "Slider Categorías";
					else if ($(this).closest('.brand-slider').length) seccion = "Slider Marcas";
					else seccion = "Sección Marketing";
					// ---------------------------------------------------------
					var botonNombre = seccion + idIdentificado + ": " + ($(this).text().trim() || "Imagen/Icono");
					// ---------------------------------------------------------
					if (typeof zaraz !== 'undefined') {
						zaraz.track('click_marketing', { nombre: botonNombre, url: botonUrl });
					} else if (typeof gtag === 'function') {
						gtag('event', 'click_marketing', { 'nombre': botonNombre, 'url': botonUrl });
					}
					// ---------------------------------------------------------
					console.log('Métrica de Slider: ', botonNombre);
				});
			// ---------------------------------------------------------
				function copiarTexto(texto) {
					navigator.clipboard.writeText(texto).then(function() {
						Swal.fire({
							icon: 'success',
							title: '¡Copiado!',
							text: 'El número ' + texto + ' se copió al portapapeles.',
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2500,
							timerProgressBar: true
						});
					}).catch(function(err) {
						console.error('Error al copiar: ', err);
						Swal.fire('Error', 'No se pudo copiar el texto.', 'error');
					});
				}
			// ---------------------------------------------------------
				$(document).ready(function(){
				});
			// ---------------------------------------------------------
		</script>
	<!-- end java -->