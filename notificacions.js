/* al final del document, just abans del </body> */
<script>
var close = document.getElementById("close");

if (close !== null) {
	/* internets explorers < 9 i d'altres navegadors obsolets */
	close.onclick = function() {
		document.getElementById("notificacions").style.display = 'none';
	};
}
</script>
