<?php
/**
 * The template for displaying the footer.
 *
 * @package shift_cv
 */
?>
    </div><!-- #main -->
	
	<footer id="footer" class="site_footer" role="contentinfo">
		<div class="footer_copyright">
			<?php
				echo get_theme_option('footer_copyright')
			?>
		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
<a href="#" id="toTop"></a>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery.reject({
			reject : {
				all: false, // Nothing blocked
				msie5: true, msie6: true, msie7: true // Covers MSIE 5-7
				/*
				 * Possibilities are endless...
				 *
				 * // MSIE Flags (Global, 5-8)
				 * msie, msie5, msie6, msie7, msie8,
				 * // Firefox Flags (Global, 1-3)
				 * firefox, firefox1, firefox2, firefox3,
				 * // Konqueror Flags (Global, 1-3)
				 * konqueror, konqueror1, konqueror2, konqueror3,
				 * // Chrome Flags (Global, 1-4)
				 * chrome, chrome1, chrome2, chrome3, chrome4,
				 * // Safari Flags (Global, 1-4)
				 * safari, safari2, safari3, safari4,
				 * // Opera Flags (Global, 7-10)
				 * opera, opera7, opera8, opera9, opera10,
				 * // Rendering Engines (Gecko, Webkit, Trident, KHTML, Presto)
				 * gecko, webkit, trident, khtml, presto,
				 * // Operating Systems (Win, Mac, Linux, Solaris, iPhone)
				 * win, mac, linux, solaris, iphone,
				 * unknown // Unknown covers everything else
				 */
			},
            imagePath: '<?php echo get_template_directory_uri(); ?>/js/jreject/images/',
            header: 'Tu Explorador está desactualizado', // Header Text
            paragraph1: 'Actualmente estas viendo este sitio en un explorador que no es compatible con las últimas tecnologias que se usaron en este desarrollo.', // Paragraph 1
            paragraph2: 'Favor instala Chrome, Firefox o alguno que vaya acorde a las nuevas tecnologías',
            closeMessage: 'Cerrar!' // Message below close window link
        });
    });
        empt = '<?php _e("Necesito saber tu nombre", "wpspace"); ?>';
        to_lng = '<?php _e("¿Tu nombre es realmente tan largo?", "wpspace"); ?>';
        empt_mail = '<?php _e("Tu email es necesario", "wpspace"); ?>';
        to_lng_mail = '<?php _e("Me parece que tu correo es demasiado largo.", "wpspace"); ?>';
        incor = '<?php _e("El formato de tu email no es el correcto", "wpspace"); ?>';
        mes_empt = '<?php _e("Hey! me interesa saber para que me contactas. Favor escribe tu mensaje!", "wpspace"); ?>';
        to_lng_mes = '<?php _e("Es mucho el texto, favor resúmelo y sigamos hablando por email.", "wpspace"); ?>';
        <?php if(!isset($_COOKIE['tab_index'])): ?>
        init_ind = <?php echo get_theme_option('expanded_section') != '' ? get_theme_option('expanded_section') : -1; ?>
        <?php else: ?>
        init_ind = <?php echo $_COOKIE['tab_index']; ?>
        <?php endif; ?>
</script>
</body>
</html>