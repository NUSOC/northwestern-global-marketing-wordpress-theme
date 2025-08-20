      <?php nu_gm_after_main_content(); ?>
      <?php if (is_fullwidth()): ?></div><?php endif; ?>
      </div>
      <footer>
        <script type="application/ld+json">
          <?php
          $header_lockup_img_url = get_theme_mod('header_lockup_img_setting', '');
          $home_url = home_url();
          $logo = (!empty($header_lockup_img_url)) ? $header_lockup_img_url : get_template_directory_uri() . '/library/images/northwestern-university.png';
          $logo_name = (!empty($header_lockup_img_url)) ? 'Northwestern University Logo' : get_bloginfo('name') . ' Logo';
          $social_media_urls = array();
          $social_media_output = '';
          $social_media_options = get_supported_social_media();
          foreach ($social_media_options as $social_media_option) {
            $key = str_replace(' ', '-', strtolower($social_media_option));
            $social_media_setting_key = 'footer_social_media_links_' . $key . '_setting';
            $social_media_account_url = get_theme_mod($social_media_setting_key, '');
            if (!empty($social_media_account_url)) {
              $social_media_urls[] = $social_media_account_url;
              $social_media_output .= '<a class="social ' . $key . '" href="' . $social_media_account_url . '" itemprop="sameAs" title="' . $social_media_option . '">' . $social_media_option . '</a>';
            }
          }
          $organization = (object) [
            '@context' => 'http://schema.org',
            '@type' => 'Organization',
            '@id' => $home_url . '/#organization',
            'url' => $home_url . '/',
            'mainEntityOfPage' => $home_url . '/',
            'name' => get_bloginfo('name'),
            'logo' => (object) [
              '@context' => 'http://schema.org',
              '@type' => 'ImageObject',
              'url' => $logo,
              'width' => '170',
              'height' => '52',
              'name' => $logo_name
            ],
            'parentOrganization' => (object) [
              '@context' => 'http://schema.org',
              '@type' => 'CollegeOrUniversity',
              'name' => 'Northwestern University',
              'url' => 'http://www.northwestern.edu/',
              'mainEntityOfPage' => 'http://www.northwestern.edu/',
              'sameAs' => array(
                'https://en.wikipedia.org/wiki/Northwestern_University',
                'https://www.facebook.com/NorthwesternU',
                'http://www.twitter.com/northwesternu',
                'https://instagram.com/northwesternu',
                'https://www.youtube.com/user/NorthwesternU',
                'http://www.futurity.org/university/northwestern-university/'
              ),
              'address' => '633 Clark Street, Evanston, IL 60208',
              'logo' => get_template_directory_uri() . '/library/images/northwestern-university.svg',
              'telephone' => array(
                '(847) 491-3741',
                '(312) 503-8649'
              ),
              'naics' => '611310'
            ]
          ];
          if (!empty($social_media_urls))
            $organization->sameAs = $social_media_urls;

          echo json_encode($organization, JSON_UNESCAPED_SLASHES);
          ?>
        </script>

        <div id="footer-publisher-info" class="contain-970" itemprop="publisher" itemscope itemtype="http://schema.org/Organization" itemid="<?php echo home_url(); ?>#footer-publisher-info">
          <meta itemprop="name" content="<?php bloginfo('name'); ?>" hidden />
          <link itemprop="url mainEntityOfPage" href="<?php echo $home_url; ?>" hidden />
          <span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject" hidden>
            <meta itemprop="url" content="<?php echo $logo; ?>" />
            <meta itemprop="width" content="170" />
            <meta itemprop="height" content="52" />
            <meta itemprop="name" content="<?php echo $logo_name; ?>" />
          </span>
          <div class="footer-content" itemprop="parentOrganization" itemscope itemtype="http://schema.org/CollegeOrUniversity">
            <a href="<?php echo nu_gm_footer_northwestern_logo_link(); ?>" itemprop="url mainEntityOfPage">
              <img alt="Northwestern University logo" src="<?php echo nu_gm_footer_northwestern_logo_img(); ?>" itemprop="logo">
            </a>
            <ul>
              <li>&copy; <?php echo date("Y") ?> <span itemprop="name">Northwestern University</span></li>
              <li><a href="http://www.northwestern.edu/disclaimer.html">Disclaimer</a></li>
              <?php echo nu_gm_footer_publisher_links_markup(); ?>
            </ul>
          </div>
          <div class="footer-content contact">
            <?php
            $footer_contact_info_address_line_1 = get_theme_mod('footer_contact_info_address_line_1_setting', '633 Clark Street');
            $footer_contact_info_address_line_2 = get_theme_mod('footer_contact_info_address_line_2_setting', 'Evanston, IL 60208');
            $footer_contact_info_phone_1_label  = get_theme_mod('footer_contact_info_phone_1_label_setting', 'Evanston');
            $footer_contact_info_phone_1_number = get_theme_mod('footer_contact_info_phone_1_number_setting', '(847) 491-3741');
            $footer_contact_info_phone_2_label  = get_theme_mod('footer_contact_info_phone_2_label_setting', 'Chicago');
            $footer_contact_info_phone_2_number = get_theme_mod('footer_contact_info_phone_2_number_setting', '(312) 503-8649');
            $footer_contact_info_email = get_theme_mod('footer_contact_info_email_setting', '');
            $footer_contact_info_website = get_theme_mod('footer_contact_info_website_setting', '');
            ?>
            <?php if (!empty($footer_contact_info_address_line_1)): ?>
              <ul>
                <li class="footer-pin-icon"><span class="hide-label">Address</span></li>
                <li itemprop="address"><?php echo $footer_contact_info_address_line_1; ?>
                  <?php if (!empty($footer_contact_info_address_line_2)): ?><br><?php echo $footer_contact_info_address_line_2; ?><?php endif; ?></li>
              </ul>
            <?php endif; ?>
            <?php if (!empty($footer_contact_info_phone_1_number)): ?>
              <ul>
                <li class="footer-phone-icon"><span class="hide-label">Phone number</span></li>
                <?php if (!empty($footer_contact_info_phone_1_label)): ?><li><strong><?php echo $footer_contact_info_phone_1_label; ?></strong></li><?php endif; ?>
                <li itemprop="telephone"><?php echo $footer_contact_info_phone_1_number; ?></li>
              </ul>
            <?php endif; ?>
            <?php if (!empty($footer_contact_info_phone_2_number)): ?>
              <ul>
                <?php if (empty($footer_contact_info_phone_1_number)): ?><li class="footer-phone-icon"><span class="hide-label">Phone number</span></li><?php endif; ?>
                <?php if (!empty($footer_contact_info_phone_2_label)): ?><li><strong><?php echo $footer_contact_info_phone_2_label; ?></strong></li><?php endif; ?>
                <li itemprop="telephone"><?php echo $footer_contact_info_phone_2_number; ?></li>
              </ul>
            <?php endif; ?>
            <?php if (!empty($footer_contact_info_email)): ?>
              <ul>
                <li class="footer-email-icon"><span class="hide-label">Email</span></li>
                <li><a href="mailto:<?php echo $footer_contact_info_email; ?>"><?php echo $footer_contact_info_email; ?></a></li>
              </ul>
            <?php endif; ?>
            <?php if (!empty($footer_contact_info_website)): ?>
              <ul>
                <li class="footer-website-icon"><span class="hide-label">Website</span></li>
                <li><a href="<?php echo $footer_contact_info_website; ?>"><?php echo $footer_contact_info_website; ?></a></li>
              </ul>
            <?php endif; ?>
          </div>
          <div class="footer-content">
            <p><strong>Social Media</strong></p>

           <!-- hide css optional from nu_hide_rss option -->
            <?php if (!get_theme_mod('nu_hide_rss')): ?>
              <a title="rss feed" class="social rss" href="<?php bloginfo('rss2_url'); ?>">rss feed</a>
            <?php endif; ?> 
            
            
            <?php echo $social_media_output; ?>
          </div>
          <div class="footer-content">
            <?php wp_nav_menu(array(
              'theme_location' => 'footer-links',
              'depth' => 1,
              'container' => 'nav'
            )); ?>
          </div>
        </div>
        <?php echo nu_gm_footer_bottom() ?>
      </footer>

      <?php $show_on_front = get_option('show_on_front'); ?>
      <?php if ((is_home() && $show_on_front == 'posts') || (is_front_page() && $show_on_front == 'page')): ?>
        <script type="application/ld+json">
          {
            "@context": "http://schema.org",
            "@id": "<?php echo home_url(); ?>/#website",
            "@type": "WebSite",
            "url": "<?php echo home_url(); ?>/",
            "name": "<?php bloginfo('name'); ?>",
            "potentialAction": {
              "@type": "SearchAction",
              "target": "<?php echo home_url(); ?>/?s={search_term_string}",
              "query-input": "required name=search_term_string"
            }
          },
        </script>
      <?php endif; ?>

      <?php wp_footer(); ?>

      </body>

      </html> <!-- end of site. what a ride! -->