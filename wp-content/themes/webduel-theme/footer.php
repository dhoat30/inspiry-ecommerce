<section class="subscribe-section">
  <div class="content">
    <div class="section-font-size">
      Sign up For Inspiry Emails.
    </div>
    <div class="poppins-font paragraph-font-size">
      Plus hear about the latest and greatest from our family of brands!
    </div>
  </div>

  <div class="form">
    <?php echo do_shortcode('[mc4wp_form id="88533"]');?>
  </div>
</section>
<footer class="off-white-bc footer">

  <div class="footer-menu-row row-container light-grey">
    <div class="trade-nav">
      <h6 class="footer-menu-title">
        Trade
      </h6>
      <?php
                  wp_nav_menu( array( 
                      'theme_location' => 'footer-trade-menu'
                    )); 
            ?>
    </div>

    <div class="help-info-nav">
      <h6 class="footer-menu-title">
        Help & Info
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-help-info'
              ) )
            ?>

    </div>

    <div class="Store">
      <h6 class="footer-menu-title">
        Store
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-store'
              ) )
            ?>

    </div>

    <div class="ways-to-shop">
      <h6 class="footer-menu-title">
        Ways to Shop
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-ways-to-shop'
              ) )
            ?>

    </div>

    <div class="ideas-insipiration">
      <h6 class="footer-menu-title">
        IDEAS & INSPIRATION
      </h6>
      <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-ideas-inspiration'
              ) )
            ?>
      <div class="social-media-footer">
        <h6 class="column-font-size regular">Get social with us</h6>
        <div class="underline-dg"></div>
        <div class="social-media-container">
          <?php 
           $argsContact = array(
            'pagename' => 'contact'
          );
          $queryContact = new WP_Query( $argsContact );
          while($queryContact->have_posts()){
            $queryContact->the_post(); 
          ?>
          <a class="social-icon" href="<?php echo get_field("facebook");?>" target="_blank"><i
              class="fab fa-facebook-square"></i></a>
          <a class="social-icon" href="<?php echo get_field("instagram");?>" target="_blank"><i class="fab fa-instagram-square"></i></a>
          <a class="social-icon" href="<?php echo get_field("pintrest_");?>" target="_blank"><i class="fab fa-pinterest-square"></i></a>
          <a class="social-icon" href="<?php echo get_field("youtube");?>" target="_blank"><i class="fab fa-youtube"></i></a>
          <?php 
          
          }
          wp_reset_postdata(  );
          ?>
        </div>
      </div>

    </div>

  </div>

  <div class="footer-img">
    <!-- <img src="<?php //echo get_site_url();?>/wp-content/uploads/2020/11/Inspiry_Slogan-transparent.png" alt="Slogan"> -->
  </div>
  <div class="copyright-container row-container light-grey">
    <div>© Copyright 2019 Inspiry NZ. All rights reserved. <a href="https://webduel.co.nz" rel="nofollow"
        target="_blank" class="dark-green rm-txt-dec"> Built By WebDuel</a></div>
  </div>

</footer>


<div class="go-to-header hide" id='go-to-header'>
  <a href="#header"><i class="fal fa-angle-up"></i></a>
</div>



<div class="overlay"></div>
<?php wp_footer();?>

</body>

</html>