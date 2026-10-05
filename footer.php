<?php if ( function_exists( 'bajwa_render_managed_sections' ) ) { bajwa_render_managed_sections(); } ?><?php if ( ! is_front_page() && ! is_page( array( 'contact', 'contact-us' ) ) && ! is_page_template( 'page-contact.php' ) ) : // The homepage ends with its own call to action. ?><section class="closing-cta"><div class="container closing-cta__inner"><div><p class="eyebrow">Start with a conversation</p><h2>Bring clarity to your next financial decision.</h2></div><a class="button button--gold" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a consultation <?php echo bajwa_icon( 'arrow' ); ?></a></div></section><?php endif; ?>
<footer class="site-footer">
	<div class="container footer-grid">
		<div>
			<img class="footer-logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bajwa-logo.png' ) ); ?>" width="250" height="68" alt="Bajwa CPA">
			<p>Professional tax, accounting and advisory services for individuals and businesses in Brampton, Mississauga and across Ontario.</p>
		</div>
		<div>
			<h2>Contact</h2>
			<address>
				2 County Court Blvd, Suite # 400<br>
				Brampton Ontario L6W 3W8, Canada
			</address>
			<p>
				Phone: <a href="tel:4169070568">416-907-0568</a><br>
				Fax: <a href="tel:9056981218">905-698-1218</a><br>
				Email: <a href="mailto:info@bajwacpa.com">info@bajwacpa.com</a>
			</p>
		</div>
		<div>
			<h2>Quick Links</h2>
			<?php if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) );
			} else { ?>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About Us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/tax-accounting-services/' ) ); ?>">Tax &amp; Accounting Services</a></li>
				<li><a href="<?php echo esc_url( home_url( '/tax-checklists/' ) ); ?>">Tax Checklists</a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
			</ul>
			<?php } ?>
		</div>
		<div>
			<h2>Accreditations</h2>
			<div class="footer-badges">
				<div class="footer-badge-card footer-badge-card--qb">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/quickbooks-proadvisor.png' ) ); ?>" width="140" height="78" alt="QuickBooks Certified ProAdvisor Online" loading="lazy">
				</div>
				<div class="footer-badge-card footer-badge-card--cpa">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/cpa-canada-logo.png' ) ); ?>" width="165" height="58" alt="CPA Chartered Professional Accountants Canada" loading="lazy">
				</div>
			</div>
		</div>
	</div>
	<div class="container footer-bottom">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Bajwa CPA Professional Corporation. All rights reserved.</span>
		<span>Chartered Professional Accountants</span>
	</div>
</footer><?php wp_footer(); ?></body></html>
