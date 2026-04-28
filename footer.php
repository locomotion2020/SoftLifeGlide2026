<!-- =====================
     SITE FOOTER
===================== -->
<footer class="site-footer">
    <span class="footer-copy">©2026</span>
    <nav class="footer-nav">
        <button class="footer-nav-btn" id="footer-information">Information</button>
        <button class="footer-nav-btn" id="footer-terms">Terms &amp; Conditions</button>
        <button class="footer-nav-btn" id="footer-privacy">Privacy Policy</button>
    </nav>
    <a href="https://www.instagram.com/soft_life_glide"
       class="footer-instagram"
       target="_blank"
       rel="noopener noreferrer">@soft_life_glide</a>
</footer>

<!-- =====================
     INTRODUCTION MODAL
     (opened by ? icon on homepage)
===================== -->
<div class="intro-modal" id="intro-modal">
    <div class="modal-inner">
        <button class="intro-modal-close" aria-label="Close">
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="1" y1="1" x2="19" y2="19" stroke="#000" stroke-width="1.2"/>
                <line x1="19" y1="1" x2="1" y2="19" stroke="#000" stroke-width="1.2"/>
            </svg>
        </button>

        <div class="intro-txt">
            <div class="intro-text">
                <p>Soft · Life · Glide is a project that expands graphics into forms that can be worn and used. </p>
                <p>The garments and objects of Soft · Life · Glide are not simply final products, but closer to records of a process and moments in which images are formed.</p>
                <p>Each piece is produced in small quantities or as a one-of-a-kind item, reflecting the specific conditions and decisions made during the time of its creation.</p>
                <p>The project presents not only the final outcomes but also the entire process—from the development of ideas to the generation and transformation of the results—through exhibitions and sales.</p>
                <p>Both the process and the resulting works can be experienced in various ways through the online platform (@soft_life_glide) and offline exhibitions.</p>
            </div>

            <div class="intro-info">
                <p class="intro-info-title">Team</p>
                <div class="intro-info-body">
                    <p>Shinwoo Park</p>
                    <p>Yewon Seo</p>
                    <p>Nayeon Kim</p>
                </div>
            </div>

            <div class="intro-info">
                <p class="intro-info-title">Contact</p>
                <div class="intro-info-body">
                    <div class="intro-row">
                        <span class="intro-row-label">Instagram:</span>
                        <a href="https://www.instagram.com/soft_life_glide" target="_blank" rel="noopener noreferrer" class="intro-row-value">@soft_life_glide</a>
                    </div>
                    <div class="intro-row">
                        <span class="intro-row-label">Email:</span>
                        <a href="mailto:contact@softlifeglide.com" class="intro-row-value">contact@softlifeglide.com</a>
                    </div>
                </div>
            </div>

            <div class="intro-info">
                <p class="intro-info-title">Project List</p>
                <div class="intro-info-body">
                    <div class="intro-row">
                        <span class="intro-row-label">2025. 09</span>
                        <span>-</span>
                        <span>Soft . Life . Glide</span>
                    </div>
                    <div class="intro-row">
                        <span class="intro-row-label">2025. 09</span>
                        <span>-</span>
                        <span>Pull my Daisy</span>
                    </div>
                </div>
            </div>

            <div class="intro-info">
                <p class="intro-info-title">Selected Press</p>
                <div class="intro-info-body">
                    <div class="intro-row">
                        <span class="intro-row-label">2022. 08</span>
                        <span>-</span>
                        <span>Talk, ⌜Two Pages Lockdown⌟, Two Pages Project</span>
                    </div>
                    <div class="intro-row">
                        <span class="intro-row-label">2022. 08</span>
                        <span>-</span>
                        <span>Talk, Hongik Univ., &lt;Ieumdari&gt;, Online (Zoom)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>

<!-- =====================
     INFORMATION BAR
     (opened by "Information" footer link)
===================== -->
<div class="info-bar" id="info-bar">
    <div class="info-bar-text">
        <?php if ( $v = get_field( 'shop_name',            'option' ) ) : ?><p><?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'ceo_name',             'option' ) ) : ?><p>CEO | <?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'contact_number',       'option' ) ) : ?><p>Contact Number | <?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'e-mail',               'option' ) ) : ?><p>Email | <?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'business_number',      'option' ) ) : ?><p>Business Number | <?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'sales_number',         'option' ) ) : ?><p>Sales Number | <?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'chief_privacy_officer','option' ) ) : ?><p>Chief Privacy Officer | <?php echo esc_html( $v ); ?></p><?php endif; ?>
        <?php if ( $v = get_field( 'address',              'option' ) ) : ?><p>Address | <?php echo esc_html( $v ); ?></p><?php endif; ?>
    </div>
</div>

<!-- =====================
     PAGE CONTENT MODAL
     (Terms & Conditions — page 59, Privacy Policy — page 3)
===================== -->
<div class="intro-modal" id="page-content-modal">
    <div class="modal-inner">
        <button class="page-modal-close" aria-label="Close">
            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="1" y1="1" x2="19" y2="19" stroke="#000" stroke-width="1.2"/>
                <line x1="19" y1="1" x2="1" y2="19" stroke="#000" stroke-width="1.2"/>
            </svg>
        </button>
        <p class="page-modal-title"></p>
        <div class="page-modal-body"></div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
