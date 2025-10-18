<?php if (isset($offer_banners) && !empty($offer_banners)): ?>
<!-- Offer Banners Carousel Start -->
<div class="offer-banners-carousel-wrapper">
  <div class="carousel-container">
    <!-- Carousel Header -->
    
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

<!-- Popular Dishes Section Start -->
<div class="popular-dishes-section">
  <div class="container">
    <div class="section-title-wrap d-flex justify-content-between align-items-center">
      <div>
        <h2 class="title">Popular Dishes</h2>
      </div>
      <div class="popular-dishes-see-all">
        <a href="<?php echo base_url('online-order'); ?>" class="see-all-btn">
          See All
        </a>
      </div>
    </div>
    
    <div class="popular-dishes-carousel-wrapper">
      <div class="popular-dishes-carousel" id="popular-dishes-carousel">
        <?php 
        // Fetch available online foods
        $popular_foods = getFoodMenuForMenuPage();
        $online_selected_outlet = $this->session->userdata('online_selected_outlet');
        $outlet_details = getOutletById($online_selected_outlet);
        $thumb_imgs = isset($outlet_details->thumb_imgs) ? (Array)json_decode($outlet_details->thumb_imgs) : '';
        
        if ($popular_foods && count($popular_foods) > 0) {
          foreach ($popular_foods as $food) {
            // Get food image
            $img = '';
            $str = "thumb_".$food->id;
            if(isset($thumb_imgs[$str]) && $thumb_imgs[$str]){
              $img = base_url()."uploads/website/".$thumb_imgs[$str];
            } else {
              $img = base_url()."assets/media/no_image.png";
            }
            
            // Get rating
            $rating = getRating($food->id);
        ?>
        <div class="popular-dish-item">
          <div class="product">
            <a class="product-thumb" href="<?php echo base_url() . 'food-details/'; ?><?php echo d($food->id,1) ?>/<?php echo d($food->category_id,1) ?>">
              <img src="<?php echo $img ?>" alt="<?php echo escape_output($food->name); ?>" />
            </a>
            <div class="product-body">
              <div class="product-desc">
                <div class="rating-wrap">
                  <div class="rating">
                    <div class="full-stars-example-two">
                      <div class="rating" id="rating-sing-<?php echo $food->id; ?>">
                        <?php for ($i = 5; $i >= 1; $i--) { ?>
                          <span class="rating-sing-<?php echo $food->id; ?>" data-rating="<?php echo $i; ?>">
                            <i class="<?php echo ($rating >= $i) ? 'fas' : 'far'; ?> fa-star"></i>
                          </span>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <span class="rating-num">(<?php echo $rating; ?>)</span>
                </div>
                <h4><a href="<?php echo base_url() . 'food-details/'; ?><?php echo d($food->id,1) ?>/<?php echo d($food->category_id,1) ?>"><?php echo escape_output($food->name); ?></a></h4>
                <p class="product-price">
                  <span><?php echo getAmtCustom($food->sale_price); ?></span>
                </p>
              </div>
              <div class="product-controls">
                <a href="<?php echo base_url() . 'food-details/'; ?><?php echo d($food->id,1) ?>/<?php echo d($food->category_id,1) ?>" class="order-item">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.4141 8.06641H16.1523L13.8086 4.94141C13.8997 4.79818 13.9714 4.64518 14.0234 4.48242C14.0755 4.31966 14.1016 4.14714 14.1016 3.96484C14.1016 3.48307 13.929 3.06966 13.584 2.72461C13.2389 2.37956 12.8255 2.20703 12.3438 2.20703C11.862 2.20703 11.4486 2.37956 11.1035 2.72461C10.7585 3.06966 10.5859 3.48307 10.5859 3.96484C10.5859 4.45964 10.7585 4.8763 11.1035 5.21484C11.4486 5.55339 11.862 5.72266 12.3438 5.72266C12.4349 5.72266 12.5228 5.71615 12.6074 5.70312C12.6921 5.6901 12.7799 5.67057 12.8711 5.64453L14.6875 8.06641H5.3125L7.12891 5.64453C7.22005 5.67057 7.30794 5.6901 7.39258 5.70312C7.47721 5.71615 7.5651 5.72266 7.65625 5.72266C8.13802 5.72266 8.55143 5.55339 8.89648 5.21484C9.24154 4.8763 9.41406 4.45964 9.41406 3.96484C9.41406 3.48307 9.24154 3.06966 8.89648 2.72461C8.55143 2.37956 8.13802 2.20703 7.65625 2.20703C7.17448 2.20703 6.76107 2.37956 6.41602 2.72461C6.07096 3.06966 5.89844 3.48307 5.89844 3.96484C5.89844 4.14714 5.92448 4.31966 5.97656 4.48242C6.02865 4.64518 6.10026 4.79818 6.19141 4.94141L3.84766 8.06641H0.585938C0.429688 8.06641 0.292969 8.125 0.175781 8.24219C0.0585938 8.35938 0 8.49609 0 8.65234C0 8.82161 0.0585938 8.96159 0.175781 9.07227C0.292969 9.18294 0.429688 9.23828 0.585938 9.23828H1.34766L1.64062 10.4102H18.3594L18.6523 9.23828H19.4141C19.5703 9.23828 19.707 9.18294 19.8242 9.07227C19.9414 8.96159 20 8.82161 20 8.65234C20 8.49609 19.9414 8.35938 19.8242 8.24219C19.707 8.125 19.5703 8.06641 19.4141 8.06641ZM11.7578 3.96484C11.7578 3.80859 11.8164 3.67188 11.9336 3.55469C12.0508 3.4375 12.1875 3.37891 12.3438 3.37891C12.5 3.37891 12.6367 3.4375 12.7539 3.55469C12.8711 3.67188 12.9297 3.80859 12.9297 3.96484C12.9297 4.13411 12.8711 4.27409 12.7539 4.38477C12.6367 4.49544 12.5 4.55078 12.3438 4.55078C12.1875 4.55078 12.0508 4.49544 11.9336 4.38477C11.8164 4.27409 11.7578 4.13411 11.7578 3.96484ZM7.65625 3.37891C7.8125 3.37891 7.94922 3.4375 8.06641 3.55469C8.18359 3.67188 8.24219 3.80859 8.24219 3.96484C8.24219 4.13411 8.18359 4.27409 8.06641 4.38477C7.94922 4.49544 7.8125 4.55078 7.65625 4.55078C7.5 4.55078 7.36328 4.49544 7.24609 4.38477C7.12891 4.27409 7.07031 4.13411 7.07031 3.96484C7.07031 3.80859 7.12891 3.67188 7.24609 3.55469C7.36328 3.4375 7.5 3.37891 7.65625 3.37891ZM3.35938 17.2852C3.45052 17.6758 3.6556 17.9948 3.97461 18.2422C4.29362 18.4896 4.65495 18.6133 5.05859 18.6133H14.9414C15.3451 18.6133 15.7064 18.4896 16.0254 18.2422C16.3444 17.9948 16.5495 17.6758 16.6406 17.2852L18.0664 11.582H1.93359L3.35938 17.2852ZM12.9297 13.3398C12.9297 13.1836 12.9883 13.0469 13.1055 12.9297C13.2227 12.8125 13.3594 12.7539 13.5156 12.7539C13.6719 12.7539 13.8086 12.8125 13.9258 12.9297C14.043 13.0469 14.1016 13.1836 14.1016 13.3398V16.8555C14.1016 17.0247 14.043 17.1647 13.9258 17.2754C13.8086 17.3861 13.6719 17.4414 13.5156 17.4414C13.3594 17.4414 13.2227 17.3861 13.1055 17.2754C12.9883 17.1647 12.9297 17.0247 12.9297 16.8555V13.3398ZM9.41406 13.3398C9.41406 13.1836 9.47266 13.0469 9.58984 12.9297C9.70703 12.8125 9.84375 12.7539 10 12.7539C10.1562 12.7539 10.293 12.8125 10.4102 12.9297C10.5273 13.0469 10.5859 13.1836 10.5859 13.3398V16.8555C10.5859 17.0247 10.5273 17.1647 10.4102 17.2754C10.293 17.3861 10.1562 17.4414 10 17.4414C9.84375 17.4414 9.70703 17.3861 9.58984 17.2754C9.47266 17.1647 9.41406 17.0247 9.41406 16.8555V13.3398ZM5.89844 13.3398C5.89844 13.1836 5.95703 13.0469 6.07422 12.9297C6.19141 12.8125 6.32812 12.7539 6.48438 12.7539C6.64062 12.7539 6.77734 12.8125 6.89453 12.9297C7.01172 13.0469 7.07031 13.1836 7.07031 13.3398V16.8555C7.07031 17.0247 7.01172 17.1647 6.89453 17.2754C6.77734 17.3861 6.64062 17.4414 6.48438 17.4414C6.32812 17.4414 6.19141 17.3861 6.07422 17.2754C5.95703 17.1647 5.89844 17.0247 5.89844 16.8555V13.3398Z" fill="black" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php 
          }
        } else {
          // Show message if no foods available
        ?>
        <div class="no-foods-message">
          <p>No popular dishes available at the moment.</p>
        </div>
        <?php } ?>
      </div>
      
      <!-- Navigation Arrows Under Cards -->
      <div class="popular-dishes-nav-bottom d-flex justify-content-end">
        <div class="popular-dishes-nav">
          <a href="javascript:void(0)" class="customPrevBtn popular-dishes-prev">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1.61719 9.11719C1.12891 9.60547 1.12891 10.3984 1.61719 10.8867L7.86719 17.1367C8.35547 17.625 9.14844 17.625 9.63672 17.1367C10.125 16.6484 10.125 15.8555 9.63672 15.3672L5.51562 11.25H17.5C18.1914 11.25 18.75 10.6914 18.75 10C18.75 9.30859 18.1914 8.75 17.5 8.75H5.51953L9.63281 4.63281C10.1211 4.14453 10.1211 3.35156 9.63281 2.86328C9.14453 2.375 8.35156 2.375 7.86328 2.86328L1.61328 9.11328L1.61719 9.11719Z" fill="#2D2C2B" />
            </svg>
          </a>
          <a href="javascript:void(0)" class="customNextBtn popular-dishes-next">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18.3828 10.8828C18.8711 10.3945 18.8711 9.60156 18.3828 9.11328L12.1328 2.86328C11.6445 2.375 10.8516 2.375 10.3633 2.86328C9.875 3.35156 9.875 4.14453 10.3633 4.63281L14.4844 8.75H2.5C1.80859 8.75 1.25 9.30859 1.25 10C1.25 10.6914 1.80859 11.25 2.5 11.25H14.4805L10.3672 15.3672C9.87891 15.8555 9.87891 16.6484 10.3672 17.1367C10.8555 17.625 11.6484 17.625 12.1367 17.1367L18.3867 10.8867L18.3828 10.8828Z" fill="#2D2C2B" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Popular Dishes Section End -->

<style>
.offer-banners-carousel-wrapper {
  position: relative;
  padding: 0;
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

/* =========================
Popular Dishes Section
========================= */

.popular-dishes-section {
  position: relative;
  padding: 80px 0;
  background: #fff;
}

.popular-dishes-carousel-wrapper {
  position: relative;
  overflow: hidden;
  margin-top: 0px;
}

.popular-dishes-see-all {
  display: flex;
  align-items: center;
}

.popular-dishes-nav-bottom {
  margin-top: 20px;
  padding: 0 10px;
}

.popular-dishes-nav {
  display: flex;
  gap: 10px;
}

.see-all-btn {
  display: inline-flex;
  align-items: center;
  padding: 8px 20px;
  background: var(--primary-color, #7367f0);
  color: white;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  border: 2px solid var(--primary-color, #7367f0);
}

.see-all-btn:hover {
  background: transparent;
  color: var(--primary-color, #7367f0);
  text-decoration: none;
}

.popular-dishes-carousel {
  display: flex;
  transition: transform 0.3s ease;
  gap: 20px;
}

.popular-dish-item {
  flex: 0 0 calc(25% - 15px);
  min-width: 280px;
}

/* Navigation Arrows */
.customPrevBtn,
.customNextBtn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: #f8f9fa;
  border: 1px solid #e5e7eb;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
}

.customPrevBtn:hover,
.customNextBtn:hover {
  background: var(--primary-color, #7367f0);
  border-color: var(--primary-color, #7367f0);
}

.customPrevBtn:hover svg path,
.customNextBtn:hover svg path {
  fill: white;
}

/* Responsive Popular Dishes */
@media only screen and (max-width: 1200px) {
  .popular-dish-item {
    flex: 0 0 calc(33.333% - 14px);
  }
}

@media only screen and (max-width: 991.98px) {
  .popular-dish-item {
    flex: 0 0 calc(50% - 10px);
  }
}

@media only screen and (max-width: 767.98px) {
  .popular-dish-item {
    flex: 0 0 calc(100% - 0px);
  }
  
  .popular-dishes-section {
    padding: 60px 0;
  }
  
  .section-title-wrap {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }
  
  .popular-dishes-see-all {
    align-self: flex-end;
  }
  
  .see-all-btn {
    padding: 6px 16px;
    font-size: 13px;
  }
  
  .popular-dishes-nav-bottom {
    margin-top: 15px;
  }
}

/* No Foods Message */
.no-foods-message {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 18px;
  font-style: italic;
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
document.addEventListener('DOMContentLoaded', function() {
  // Popular Dishes Carousel
  const popularCarousel = document.getElementById('popular-dishes-carousel');
  const prevBtn = document.querySelector('.popular-dishes-prev');
  const nextBtn = document.querySelector('.popular-dishes-next');
  
  if (popularCarousel && prevBtn && nextBtn) {
    let currentIndex = 0;
    const itemsPerView = 4; // Show 4 items at once
    const totalItems = popularCarousel.children.length;
    const maxIndex = Math.max(0, totalItems - itemsPerView);
    
    function updateCarousel() {
      const translateX = -currentIndex * (100 / itemsPerView);
      popularCarousel.style.transform = `translateX(${translateX}%)`;
      
      // Update button states
      prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
      nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
    }
    
    prevBtn.addEventListener('click', function(e) {
      e.preventDefault();
      stopAutoPlay(); // Stop auto-play when manually navigating
      if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
      }
      setTimeout(startAutoPlay, 2000); // Resume auto-play after 2 seconds
    });
    
    nextBtn.addEventListener('click', function(e) {
      e.preventDefault();
      stopAutoPlay(); // Stop auto-play when manually navigating
      if (currentIndex < maxIndex) {
        currentIndex++;
        updateCarousel();
      }
      setTimeout(startAutoPlay, 2000); // Resume auto-play after 2 seconds
    });
    
    // Touch/swipe support for mobile
    let startX = 0;
    let isDragging = false;
    
    popularCarousel.addEventListener('touchstart', function(e) {
      startX = e.touches[0].clientX;
      isDragging = true;
    });
    
    popularCarousel.addEventListener('touchmove', function(e) {
      if (!isDragging) return;
      e.preventDefault();
    });
    
    popularCarousel.addEventListener('touchend', function(e) {
      if (!isDragging) return;
      isDragging = false;
      
      const endX = e.changedTouches[0].clientX;
      const diffX = startX - endX;
      
      if (Math.abs(diffX) > 50) { // Minimum swipe distance
        if (diffX > 0 && currentIndex < maxIndex) {
          // Swipe left - next
          currentIndex++;
          updateCarousel();
        } else if (diffX < 0 && currentIndex > 0) {
          // Swipe right - previous
          currentIndex--;
          updateCarousel();
        }
      }
    });
    
    // Initialize
    updateCarousel();
    
    // Auto-play functionality
    let autoPlayInterval;
    const autoPlayDelay = 3000; // 3 seconds
    
    function startAutoPlay() {
      autoPlayInterval = setInterval(() => {
        if (currentIndex < maxIndex) {
          currentIndex++;
          updateCarousel();
        } else {
          currentIndex = 0; // Reset to beginning
          updateCarousel();
        }
      }, autoPlayDelay);
    }
    
    function stopAutoPlay() {
      if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null;
      }
    }
    
    // Start auto-play
    startAutoPlay();
    
    // Pause on hover
    popularCarousel.addEventListener('mouseenter', stopAutoPlay);
    popularCarousel.addEventListener('mouseleave', startAutoPlay);
    
    // Pause on touch
    popularCarousel.addEventListener('touchstart', stopAutoPlay);
    popularCarousel.addEventListener('touchend', () => {
      setTimeout(startAutoPlay, 1000); // Resume after 1 second
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      // Recalculate based on screen size
      const screenWidth = window.innerWidth;
      let newItemsPerView = 4;
      
      if (screenWidth <= 1200) newItemsPerView = 3;
      if (screenWidth <= 991) newItemsPerView = 2;
      if (screenWidth <= 767) newItemsPerView = 1;
      
      const newMaxIndex = Math.max(0, totalItems - newItemsPerView);
      if (currentIndex > newMaxIndex) {
        currentIndex = newMaxIndex;
      }
      
      updateCarousel();
    });
  }
});
</script>

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
