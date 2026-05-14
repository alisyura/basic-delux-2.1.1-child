</div> 
<!-- #main -->

<?php do_action( 'basic_before_footer' ); ?>

<footer id="footer" class="<?php echo apply_filters( 'basic_footer_class', '' );?>" itemscope="itemscope" itemtype="http://schema.org/WPFooter">

	<?php do_action( 'basic_before_footer_menu' ); ?>

	<?php if (has_nav_menu('bottom') && has_menu_items('bottom'))  : ?>
	<div class="<?php echo apply_filters( 'basic_footer_menu_class', 'footer-menu maxwidth' );?>">
		<?php 
		wp_nav_menu( array(
				'theme_location' => 'bottom',
				'menu_id' => 'footer-menu',
				'depth' => 1,
				'container' => false,
				'items_wrap' => '<ul class="footmenu clearfix">%3$s</ul>'
			)); 
		?> 
	</div>
	<?php endif; ?>    

<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
	<div class="<?php echo apply_filters( 'basic_footer_menu_class', 'footer-menu maxwidth' );?>">
    <div class="footer-widget-area">
        <?php dynamic_sidebar( 'footer-widgets' ); ?>
    </div>
</div>
<?php endif; ?>

	<?php do_action( 'basic_before_footer_copyrights' ); ?>
    <?php if ( apply_filters( 'basic_footer_copyrights_enabled', true ) ) : ?>
	<div class="<?php echo apply_filters( 'basic_footer_copyrights_class', 'copyrights maxwidth grid' );?>">
		<div class="<?php echo apply_filters( 'basic_footer_copytext_class', 'copytext col6' );?>">
			<p id="copy">
				<!--noindex--><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="nofollow"><?php bloginfo('name'); ?></a><!--/noindex--> &copy; <?php echo date("Y",time()); ?>
				<br/>
				<span class="copyright-text"><?php echo basic_get_theme_option('copyright_text'); ?></span>
			</p>
	
      
  	        </div>

		<div class="<?php echo apply_filters( 'basic_footer_themeby_class', 'themeby col6 tr' );?>">
			<?php $counters = basic_get_theme_option('footer_counters'); ?>
			<div class="footer-counter"><?php echo wp_specialchars_decode( $counters, ENT_QUOTES ); ?></div>
		</div>
	</div>
    <?php endif; ?>
	<?php do_action( 'basic_after_footer_copyrights' ); ?>

</footer>
<?php do_action( 'basic_after_footer' ); ?>

</div> 
<!-- .wrapper -->

<a id="toTop">&#10148;</a>

<?php wp_footer(); ?>
<script type= "text/javascript">function GoTo(link){window.open(link.replace("_","http://"));}</script>








<!-- САМ БАННЕР -->
    <div id="cookieBanner" class="cookie-banner">
        <div class="cookie-container">
            <div class="cookie-text">
                🍪 Мы используем файлы cookie, чтобы улучшить ваше взаимодействие с сайтом, анализировать трафик и показывать релевантный контент. Продолжая использовать сайт, вы соглашаетесь с нашей 
                <a href="/privacy-policy" target="_blank">Политикой конфиденциальности</a>.
            </div>
            <button id="acceptCookiesBtn" class="cookie-button">Принимаю</button>
        </div>
    </div>

    <script>
        // Функция для скрытия баннера
        function hideBanner() {
            const banner = document.getElementById('cookieBanner');
            if (banner) {
                banner.classList.add('hidden');
            }
        }

        // Проверяем, давал ли пользователь согласие ранее
        function checkCookieConsent() {
            const consent = localStorage.getItem('cookieConsent');
            if (consent === 'accepted') {
                // Пользователь уже принял — скрываем баннер
                hideBanner();
            } else {
                // Нет согласия — показываем баннер
                // (по умолчанию баннер видим, просто убираем hidden если он был)
                const banner = document.getElementById('cookieBanner');
                if (banner) {
                    banner.classList.remove('hidden');
                }
            }
        }

        // Обработчик нажатия на кнопку
        function setupButtonListener() {
            const acceptBtn = document.getElementById('acceptCookiesBtn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    // Сохраняем согласие в localStorage
                    localStorage.setItem('cookieConsent', 'accepted');
                    // Скрываем баннер
                    hideBanner();
                });
            }
        }

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            setupButtonListener();
            checkCookieConsent();
        });
    </script>







</body>
</html>