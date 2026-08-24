	</main>

	<!-- FOOTER-NAV:START -->
	<footer>
		<div class="container">
			<div class="footer-top row">
				<div class="col-12 col-lg-5">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/HTS_Logo_White.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="footer-logo-img">
					<p class="footer-tagline">Premium temporary and semi-permanent modular structures for industry and commerce. A division of HTS-Tentiq — German-engineered, UK-installed since 2002.</p>
					<div class="footer-social">
						<?php
						$cb_hts_js_2026_linkedin_url = cb_hts_js_2026_get_setting( 'linkedin_url' );
						if ( $cb_hts_js_2026_linkedin_url ) {
							?>
							<a href="<?php echo esc_url( $cb_hts_js_2026_linkedin_url ); ?>" class="social-btn" aria-label="LinkedIn" target="_blank" rel="noopener">
								<svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zM4.943 13.5V6.169H2.542V13.5zM3.742 5.167c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.21 2.4 3.919c0 .694.521 1.248 1.327 1.248zm4.564 8.333V9.409c0-.219.016-.438.08-.595.175-.438.574-.892 1.244-.892.877 0 1.228.673 1.228 1.658V13.5h2.401V9.303c0-2.248-1.197-3.293-2.794-3.293-1.288 0-1.845.71-2.165 1.21h.016V6.169H5.915c.03.694 0 7.331 0 7.331z"/></svg>
							</a>
							<?php
						}

						$cb_hts_js_2026_facebook_url = cb_hts_js_2026_get_setting( 'facebook_url' );
						if ( $cb_hts_js_2026_facebook_url ) {
							?>
							<a href="<?php echo esc_url( $cb_hts_js_2026_facebook_url ); ?>" class="social-btn" aria-label="Facebook" target="_blank" rel="noopener">
								<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7h2.35l.35-2.73H13.5V9.53c0-.79.22-1.32 1.35-1.32h1.44V5.77c-.25-.03-1.1-.1-2.1-.1-2.08 0-3.5 1.27-3.5 3.6v2h-2.35V14h2.35v7z"/></svg>
							</a>
							<?php
						}

						$cb_hts_js_2026_instagram_url = cb_hts_js_2026_get_setting( 'instagram_url' );
						if ( $cb_hts_js_2026_instagram_url ) {
							?>
							<a href="<?php echo esc_url( $cb_hts_js_2026_instagram_url ); ?>" class="social-btn" aria-label="Instagram" target="_blank" rel="noopener">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
							</a>
							<?php
						}
						?>
					</div>
				</div>
				<div class="col-6 col-md-4 col-lg-2">
					<div class="footer-col-title">Products</div>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_menu_products',
							'container'      => false,
							'menu_class'     => 'footer-links',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
				</div>
				<div class="col-6 col-md-4 col-lg-2">
					<div class="footer-col-title">Applications</div>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_menu_applications',
							'container'      => false,
							'menu_class'     => 'footer-links',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
				</div>
				<div class="col-12 col-md-4 col-lg-3">
					<div class="footer-col-title">Get in touch</div>
					<?php
					$cb_hts_js_2026_footer_phone = cb_hts_js_2026_get_setting( 'phone' );
					if ( $cb_hts_js_2026_footer_phone ) {
						?>
						<div class="footer-contact-item">
							<div class="label">Phone</div>
							<a href="tel:<?php echo esc_attr( parse_phone( $cb_hts_js_2026_footer_phone ) ); ?>"><?php echo esc_html( $cb_hts_js_2026_footer_phone ); ?></a>
						</div>
						<?php
					}

					$cb_hts_js_2026_footer_email = cb_hts_js_2026_get_setting( 'email' );
					if ( $cb_hts_js_2026_footer_email ) {
						?>
						<div class="footer-contact-item">
							<div class="label">Email</div>
							<a href="mailto:<?php echo esc_attr( antispambot( $cb_hts_js_2026_footer_email ) ); ?>"><?php echo antispambot( esc_html( $cb_hts_js_2026_footer_email ) ); ?></a>
						</div>
						<?php
					}
					?>
					<a href="/contact/" class="btn btn-primary footer-cta">Request a quote</a>
				</div>
			</div>
			<?php
			$cb_hts_js_2026_accreditation_ids = cb_hts_js_2026_get_footer_accreditation_ids();
			if ( $cb_hts_js_2026_accreditation_ids ) {
				?>
				<div class="footer-accreditations">
					<?php
					foreach ( $cb_hts_js_2026_accreditation_ids as $cb_hts_js_2026_badge_id ) {
						?>
						<div class="footer-accreditation">
							<?php
							echo wp_get_attachment_image(
								$cb_hts_js_2026_badge_id,
								'medium',
								false,
								array(
									'class' => 'footer-accreditation-img',
									'alt'   => '',
								)
							);
							?>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
			<div class="footer-bottom d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
				<div class="footer-bottom-left">&copy; <?= esc_html( gmdate( 'Y') ); ?> HTS Industries Ltd. A division of HTS-Tentiq.</div>
				<div class="footer-bottom-right d-flex flex-column flex-sm-row gap-2 gap-sm-4">
					<a href="/privacy-policy/">Privacy Policy</a>
					<a href="/cookie-policy/">Cookie Policy</a>
					<a href="/terms-conditions/">Terms &amp; Conditions</a>
				</div>
			</div>
		</div>
	</footer>
	<!-- FOOTER-NAV:END -->

	<?php wp_footer(); ?>
</body>
</html>
