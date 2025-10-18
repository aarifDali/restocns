<?php if (isset($offer_banners) && !empty($offer_banners)): ?>
<!-- Offer Banners Carousel Start -->
<div class="offer-banners-carousel-wrapper">
  <div class="carousel-container">
    <!-- Carousel Header -->
    <div class="carousel-header">
      <h2 class="carousel-title">Special Offers</h2>
    </div>
    
    <!-- Carousel -->
    <div class="owl-carousel owl-theme" id="offer_banners_carousel">
      <?php foreach ($offer_banners as $banner): ?>
      <div class="offer-banner-item">
        <div class="offer-banner-card">
          <div class="offer-banner-image">
            <img src="<?php echo base_url('uploads/offer_banners/' . $banner->banner_image); ?>" 
                 alt="<?php echo escape_output($banner->heading); ?>" 
                 class="img-fluid">
          </div>
          <div class="offer-banner-content">
            <div class="offer-banner-text">
              <h3 class="offer-banner-heading"><?php echo escape_output($banner->heading); ?></h3>
              <a href="<?php echo escape_output($banner->button_link); ?>" 
                 class="offer-banner-btn">
                <?php echo escape_output($banner->button_text); ?> 
                <i class="fa fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    
    <!-- Carousel Navigation -->
    <div class="carousel-navigation">
      <button class="carousel-nav-btn prev-btn" type="button">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.61719 9.11719C1.12891 9.60547 1.12891 10.3984 1.61719 10.8867L7.86719 17.1367C8.35547 17.625 9.14844 17.625 9.63672 17.1367C10.125 16.6484 10.125 15.8555 9.63672 15.3672L5.51562 11.25H17.5C18.1914 11.25 18.75 10.6914 18.75 10C18.75 9.30859 18.1914 8.75 17.5 8.75H5.51953L9.63281 4.63281C10.1211 4.14453 10.1211 3.35156 9.63281 2.86328C9.14453 2.375 8.35156 2.375 7.86328 2.86328L1.61328 9.11328L1.61719 9.11719Z"/>
        </svg>
      </button>
      <button class="carousel-nav-btn next-btn" type="button">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M18.3828 10.8828C18.8711 10.3945 18.8711 9.60156 18.3828 9.11328L12.1328 2.86328C11.6445 2.375 10.8516 2.375 10.3633 2.86328C9.875 3.35156 9.875 4.14453 10.3633 4.63281L14.4844 8.75H2.5C1.80859 8.75 1.25 9.30859 1.25 10C1.25 10.6914 1.80859 11.25 2.5 11.25H14.4805L10.3672 15.3672C9.87891 15.8555 9.87891 16.6484 10.3672 17.1367C10.8555 17.625 11.6484 17.625 12.1367 17.1367L18.3867 10.8867L18.3828 10.8828Z"/>
        </svg>
      </button>
    </div>
    
  </div>
</div>
<!-- Offer Banners Carousel End -->

<style>
.offer-banners-carousel-wrapper {
  position: relative;
  padding: 60px 0;
  background: #f8f9fa;
}

.carousel-container {
  position: relative;
  width: 100%;
  margin: 0;
  padding: 0;
}

.carousel-header {
  text-align: left;
  margin-bottom: 40px;
  padding: 0 20px;
}

.carousel-title {
  font-family: "Gilroy", "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  font-size: 36px;
  font-weight: 700;
  color: #000000;
  margin-bottom: 0;
  line-height: 52px;
  letter-spacing: -1.5px;
  margin-bottom: -27px;
}

#offer_banners_carousel {
  position: relative;
  height: 500px;
}

#offer_banners_carousel .owl-stage-outer {
  height: 100%;
  overflow: hidden;
}

#offer_banners_carousel .owl-stage {
  height: 100%;
  display: flex;
  align-items: center;
}

#offer_banners_carousel .owl-item {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.offer-banner-item {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.offer-banner-card {
  position: relative;
  width: 100%;
  height: 500px;
  border-radius: 0;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  background: white;
}

.offer-banner-card:hover {
  transform: scale(1.02);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.offer-banner-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.offer-banner-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}


.offer-banner-content {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: none;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  /* justify-content: center; */
  color: white;
  text-align: left;
  padding: 30px;
}

.offer-banner-text {
  max-width: 60%;
  text-align: left;
}

.offer-banner-heading {
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 70px;
  font-weight: 800;
  margin-bottom: 0;
  line-height: 1.1;
  letter-spacing: -2px;
  color: white;
}

/* Responsive Special Offers Title */
@media only screen and (max-width: 991.98px) {
  .carousel-title {
    font-size: 42px;
  }
}

@media only screen and (max-width: 767.98px) {
  .carousel-title {
    font-size: 36px;
  }
}

@media only screen and (max-width: 575.98px) {
  .carousel-title {
    font-size: 30px;
  }
}

.offer-banner-btn {
  background: linear-gradient(135deg, #ff6b35, #f7931e);
  border: none;
  padding: 12px 25px;
  border-radius: 25px;
  color: white;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  position: absolute;
  bottom: 30px;
  left: 30px;
}

.offer-banner-btn:hover {
  background: linear-gradient(135deg, #e55a2b, #e8821a);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 107, 53, 0.5);
  color: white;
  text-decoration: none;
}

.carousel-navigation {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 100%;
  display: flex;
  justify-content: space-between;
  pointer-events: none;
  z-index: 10;
  padding: 0 10px;
}

.carousel-nav-btn {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
  cursor: pointer;
  transition: all 0.3s ease;
  pointer-events: all;
  border: 2px solid rgba(255, 107, 53, 0.2);
}

.carousel-nav-btn:hover {
  background: #ff6b35;
  transform: scale(1.1);
  box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
  border-color: rgba(255, 107, 53, 0.5);
}

.carousel-nav-btn:hover svg path {
  fill: white;
}

.carousel-nav-btn svg path {
  fill: #ff6b35;
  transition: fill 0.3s ease;
}

.carousel-dots {
  text-align: center;
  margin-top: 30px;
}

.carousel-dots .owl-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: rgba(255, 107, 53, 0.3);
  margin: 0 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-block;
}

.carousel-dots .owl-dot.active {
  background: #ff6b35;
  transform: scale(1.2);
}

.carousel-dots .owl-dot:hover {
  background: #ff6b35;
  transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 1500px) {
  .carousel-container {
    width: 100%;
    padding: 0;
  }
}

@media (max-width: 1200px) {
  .carousel-title {
    font-size: 32px;
    letter-spacing: 0.3px;
  }
  
  .offer-banner-content {
    padding: 25px;
  }
  
  .offer-banner-btn {
    bottom: 25px;
    left: 25px;
  }
  
  .offer-banner-heading {
    font-size: 60px;
    letter-spacing: -1px;
  }
}

@media (max-width: 768px) {
  .carousel-container {
    padding: 0;
  }
  
  .carousel-title {
    font-size: 28px;
    letter-spacing: 0.2px;
  }
  
  #offer_banners_carousel {
    height: 400px;
  }
  
  .offer-banner-card {
    height: 400px;
  }
  
  .offer-banner-content {
    padding: 20px;
  }
  
  .offer-banner-btn {
    bottom: 20px;
    left: 20px;
  }
  
  .offer-banner-heading {
    font-size: 45px;
    letter-spacing: -0.5px;
  }
  
  .offer-banner-btn {
    padding: 10px 20px;
    font-size: 13px;
  }
  
  .carousel-nav-btn {
    width: 45px;
    height: 45px;
  }
}

@media (max-width: 480px) {
  .carousel-title {
    font-size: 24px;
    letter-spacing: 0.1px;
  }
  
  #offer_banners_carousel {
    height: 350px;
  }
  
  .offer-banner-card {
    height: 350px;
  }
  
  .offer-banner-content {
    padding: 15px;
  }
  
  .offer-banner-btn {
    bottom: 15px;
    left: 15px;
  }
  
  .offer-banner-heading {
    font-size: 35px;
    letter-spacing: 0px;
  }
  
  .offer-banner-btn {
    padding: 8px 16px;
    font-size: 12px;
  }
  
  .carousel-nav-btn {
    width: 40px;
    height: 40px;
  }
}
</style>

<script>
$(document).ready(function() {
    // Initialize Owl Carousel for offer banners
    $('#offer_banners_carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        center: false,
        items: 3,
        slideBy: 1,
        smartSpeed: 600,
        responsive: {
            0: {
                items: 1,
                margin: 0,
                dots: false
            },
            768: {
                items: 2,
                margin: 0,
                dots: false
            },
            1024: {
                items: 3,
                margin: 0,
                dots: false
            }
        }
    });
    
    // Custom navigation
    $('.prev-btn').click(function() {
        $('#offer_banners_carousel').trigger('prev.owl.carousel');
    });
    
    $('.next-btn').click(function() {
        $('#offer_banners_carousel').trigger('next.owl.carousel');
    });
    
    // Pause autoplay on hover
    $('#offer_banners_carousel').hover(
        function() {
            $(this).trigger('stop.owl.autoplay');
        },
        function() {
            $(this).trigger('play.owl.autoplay');
        }
    );
    
});
</script>
<?php endif; ?>
