<script style="text/javascript">
	//---------------------------------------------------
		document.addEventListener("DOMContentLoaded", function() {
			// Verificamos si el navegador soporta el compartido nativo (móviles modernos)
			if (navigator.share) {
				document.getElementById("btnShareNative").style.display = "inline-block";
			}
		});
	//---------------------------------------------------
		function compartirNativo() {
			if (navigator.share) {
				navigator.share({
					title: '<?= addslashes($call->nombre_producto); ?>',
					text: 'Mira este producto en CGS Computer',
					url: window.location.href
				})
				.then(() => console.log('Compartido con éxito'))
				.catch((error) => console.log('Error al compartir', error));
			} else {
				alert("Tu navegador no soporta la función de compartir directamente.");
			}
		}
	//---------------------------------------------------
		$(document).ready(function() {
		});
	//---------------------------------------------------
</script>