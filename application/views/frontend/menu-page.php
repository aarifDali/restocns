<?php
$company_info     = getMainCompany();
$categories       = getFoodMenuCategory();
$get_food_menu    = getFoodMenuForMenuPage();
$common_menu_page = isset($company_info->common_menu_page) && $company_info->common_menu_page ? json_decode($company_info->common_menu_page) : '';
$online_selected_outlet = $this->session->userdata('online_selected_outlet');
$outlet_details = getOutletById($online_selected_outlet);

?>
<input type="hidden" id="item_add_success" value="<?php echo lang('item_add_success') ?>">
<!-- Menu Page Banner Start -->
<section class="menu-banner">
  <div class="menu-banner-content text-center">
    <h1 class="menu-title">Discover Our Signature Dishes</h1>
  </div>
</section>
<!-- Menu Page Banner End -->

<div class="menu_page">
  <!-- Menu Categories Start -->
  <div class="ct-menu-categories menu-filter">
    <div class="container">
      <div class="category-section-centered">
        <h2 class="category-title-centered">Menu of the Day</h2>
        <div class="category-list-centered">
          <!-- <a href="#" data-filter="*" class="category-item-centered active"> -->
            <!-- <div class="category-icon-centered">
              <img src="<?php echo base_url(); ?>assets/website/img/all.png" alt="">
            </div> -->
            <!-- <div class="category-info-centered">
              <h6><?php echo lang('all'); ?></h6>
              <p><?php echo count($get_food_menu); ?>&nbsp;<?php echo lang('foods'); ?></p>
            </div> -->
          </a>
          <?php if ($categories) {
            foreach ($categories as $cat) {
          ?>
            <a href="#" data-filter=".cat-id-<?php echo $cat->id ?>" class="category-item-centered">
              <!-- <div class="category-icon-centered">
                <img src="<?php echo base_url(); ?><?php echo $cat->category_image ? "uploads/category/" . $cat->category_image : 'assets/media/default_cat.jpg'; ?>" alt="">
              </div> -->
              <div class="category-info-centered">
                <h6><?php echo $cat->category_name ?></h6>
                <!-- <p><?php echo countFoodMenuCategory($cat->id); ?>&nbsp;<?php echo lang('foods'); ?></p> -->
              </div>
            </a>
          <?php
            }
          } ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Menu Categories End -->

  <!-- Menu Wrapper Start -->
  <div class="section section-padding">
    <div class="container">
      <!-- <div class="section-title-wrap d-flex justify-content-between align-items-center">
        <div>
          <h2 class="title"><?php echo lang('foods'); ?></h2>
        </div>
      </div> -->
      <div class="menu-container row">

        <!-- Product Start -->
        <?php
          $thumb_imgs = isset($outlet_details->thumb_imgs)?(Array)json_decode($outlet_details->thumb_imgs):'';
        foreach ($get_food_menu as $food) {
          $img  = '';
          $str = "thumb_".$food->id;
          if(isset($thumb_imgs[$str]) && $thumb_imgs[$str]){
            $img = base_url()."uploads/website/".$thumb_imgs[$str];
          }else{
            $img = base_url()."assets/media/no_image.png";
          }
        ?>
          <div class="col-xl-3 col-lg-4 col-md-6 mb-3 cat-id-<?php echo $food->category_id ?>">
            <div class="product">
              <a class="product-thumb" href="<?php echo base_url() . 'food-details/'; ?><?php echo d($food->id,1) ?>/<?php echo d($food->category_id,1) ?>"> <img src="<?php echo $img ?>" alt="" /> </a>
              <div class="product-body">
                <div class="product-desc">
                  <div class="rating-wrap">
                    <div class="rating">
                      <div class="full-stars-example-two">
                        <?php $rating = getRating($food->id); ?>
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
                  <p class="text-success m-0 d-none" id="success_rating_<?php echo $food->id; ?>">Thanks for rating.</p>
                  <h4><a href="<?php echo base_url() . 'food-details/'; ?><?php echo d($food->id,1) ?>/<?php echo d($food->category_id,1) ?>"><?php echo $food->name; ?></a> </h4>

                  <p class="product-price">
                    <span><?php echo getAmtCustom($food->sale_price); ?></span>
                  </p>

                </div>
                <?php  if($company_info->sos_enable_online_order=="Yes"):?>
                <div class="product-controls">
                  <a href="<?php echo base_url() . 'food-details/'; ?><?php echo d($food->id,1) ?>/<?php echo d($food->category_id,1) ?>" class="order-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19.4141 8.06641H16.1523L13.8086 4.94141C13.8997 4.79818 13.9714 4.64518 14.0234 4.48242C14.0755 4.31966 14.1016 4.14714 14.1016 3.96484C14.1016 3.48307 13.929 3.06966 13.584 2.72461C13.2389 2.37956 12.8255 2.20703 12.3438 2.20703C11.862 2.20703 11.4486 2.37956 11.1035 2.72461C10.7585 3.06966 10.5859 3.48307 10.5859 3.96484C10.5859 4.45964 10.7585 4.8763 11.1035 5.21484C11.4486 5.55339 11.862 5.72266 12.3438 5.72266C12.4349 5.72266 12.5228 5.71615 12.6074 5.70312C12.6921 5.6901 12.7799 5.67057 12.8711 5.64453L14.6875 8.06641H5.3125L7.12891 5.64453C7.22005 5.67057 7.30794 5.6901 7.39258 5.70312C7.47721 5.71615 7.5651 5.72266 7.65625 5.72266C8.13802 5.72266 8.55143 5.55339 8.89648 5.21484C9.24154 4.8763 9.41406 4.45964 9.41406 3.96484C9.41406 3.48307 9.24154 3.06966 8.89648 2.72461C8.55143 2.37956 8.13802 2.20703 7.65625 2.20703C7.17448 2.20703 6.76107 2.37956 6.41602 2.72461C6.07096 3.06966 5.89844 3.48307 5.89844 3.96484C5.89844 4.14714 5.92448 4.31966 5.97656 4.48242C6.02865 4.64518 6.10026 4.79818 6.19141 4.94141L3.84766 8.06641H0.585938C0.429688 8.06641 0.292969 8.125 0.175781 8.24219C0.0585938 8.35938 0 8.49609 0 8.65234C0 8.82161 0.0585938 8.96159 0.175781 9.07227C0.292969 9.18294 0.429688 9.23828 0.585938 9.23828H1.34766L1.64062 10.4102H18.3594L18.6523 9.23828H19.4141C19.5703 9.23828 19.707 9.18294 19.8242 9.07227C19.9414 8.96159 20 8.82161 20 8.65234C20 8.49609 19.9414 8.35938 19.8242 8.24219C19.707 8.125 19.5703 8.06641 19.4141 8.06641ZM11.7578 3.96484C11.7578 3.80859 11.8164 3.67188 11.9336 3.55469C12.0508 3.4375 12.1875 3.37891 12.3438 3.37891C12.5 3.37891 12.6367 3.4375 12.7539 3.55469C12.8711 3.67188 12.9297 3.80859 12.9297 3.96484C12.9297 4.13411 12.8711 4.27409 12.7539 4.38477C12.6367 4.49544 12.5 4.55078 12.3438 4.55078C12.1875 4.55078 12.0508 4.49544 11.9336 4.38477C11.8164 4.27409 11.7578 4.13411 11.7578 3.96484ZM7.65625 3.37891C7.8125 3.37891 7.94922 3.4375 8.06641 3.55469C8.18359 3.67188 8.24219 3.80859 8.24219 3.96484C8.24219 4.13411 8.18359 4.27409 8.06641 4.38477C7.94922 4.49544 7.8125 4.55078 7.65625 4.55078C7.5 4.55078 7.36328 4.49544 7.24609 4.38477C7.12891 4.27409 7.07031 4.13411 7.07031 3.96484C7.07031 3.80859 7.12891 3.67188 7.24609 3.55469C7.36328 3.4375 7.5 3.37891 7.65625 3.37891ZM3.35938 17.2852C3.45052 17.6758 3.6556 17.9948 3.97461 18.2422C4.29362 18.4896 4.65495 18.6133 5.05859 18.6133H14.9414C15.3451 18.6133 15.7064 18.4896 16.0254 18.2422C16.3444 17.9948 16.5495 17.6758 16.6406 17.2852L18.0664 11.582H1.93359L3.35938 17.2852ZM12.9297 13.3398C12.9297 13.1836 12.9883 13.0469 13.1055 12.9297C13.2227 12.8125 13.3594 12.7539 13.5156 12.7539C13.6719 12.7539 13.8086 12.8125 13.9258 12.9297C14.043 13.0469 14.1016 13.1836 14.1016 13.3398V16.8555C14.1016 17.0247 14.043 17.1647 13.9258 17.2754C13.8086 17.3861 13.6719 17.4414 13.5156 17.4414C13.3594 17.4414 13.2227 17.3861 13.1055 17.2754C12.9883 17.1647 12.9297 17.0247 12.9297 16.8555V13.3398ZM9.41406 13.3398C9.41406 13.1836 9.47266 13.0469 9.58984 12.9297C9.70703 12.8125 9.84375 12.7539 10 12.7539C10.1562 12.7539 10.293 12.8125 10.4102 12.9297C10.5273 13.0469 10.5859 13.1836 10.5859 13.3398V16.8555C10.5859 17.0247 10.5273 17.1647 10.4102 17.2754C10.293 17.3861 10.1562 17.4414 10 17.4414C9.84375 17.4414 9.70703 17.3861 9.58984 17.2754C9.47266 17.1647 9.41406 17.0247 9.41406 16.8555V13.3398ZM5.89844 13.3398C5.89844 13.1836 5.95703 13.0469 6.07422 12.9297C6.19141 12.8125 6.32812 12.7539 6.48438 12.7539C6.64062 12.7539 6.77734 12.8125 6.89453 12.9297C7.01172 13.0469 7.07031 13.1836 7.07031 13.3398V16.8555C7.07031 17.0247 7.01172 17.1647 6.89453 17.2754C6.77734 17.3861 6.64062 17.4414 6.48438 17.4414C6.32812 17.4414 6.19141 17.3861 6.07422 17.2754C5.95703 17.1647 5.89844 17.0247 5.89844 16.8555V13.3398Z" fill="black" />
                    </svg>
                  </a>
                </div>
                <?php endif?>
              </div>
            </div>
          </div>
        <?php
        } ?>
        <!-- Product End -->
      </div>

    </div>
  </div>
  <!-- Menu Wrapper End -->
</div>

<style>
  .product {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .product:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .product-thumb {
    display: block;
    overflow: hidden;
    border-radius: 12px;
  }

  .product-thumb img {
    width: 100%;
    height: auto;
    object-fit: cover;
    transition: transform 0.4s ease;
  }

  .product:hover .product-thumb img {
    transform: scale(1.08);
  }


  .menu-banner {
    background: #f8f5f0;
    padding: 100px 0; /* Increased from 60px to 100px for more height */
    margin-top: 80px; /* Add margin to push below fixed header */
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    width: 100%; /* Full width - you can change this */
    max-width: none; /* Remove any width restrictions */
  }

  .menu-banner-content {
    position: relative;
    z-index: 2;
    text-align: center;
  }

  .menu-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 2.5rem;
    color: #333;
    margin: 0;
    text-align: center;
  }

  /* Centered Category Section */
  .category-section-centered {
    text-align: center;
    width: 100%;
    margin: 0 auto;
    padding: 1px 20px; /* Reduced top padding to 1px, added side padding */
  }

  .category-title-centered {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 2rem;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
  }

  .category-list-centered {
    display: flex;
    flex-direction: row;
    gap: 15px;
    align-items: center;
    justify-content: center;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 10px 0;
    scrollbar-width: thin;
    scrollbar-color: #ffc107 #f0f0f0;
    width: 100%;
  }

  .category-list-centered::-webkit-scrollbar {
    height: 6px;
  }

  .category-list-centered::-webkit-scrollbar-track {
    background: #f0f0f0;
    border-radius: 3px;
  }

  .category-list-centered::-webkit-scrollbar-thumb {
    background: #ffc107;
    border-radius: 3px;
  }

  .category-list-centered::-webkit-scrollbar-thumb:hover {
    background: #ff8c00;
  }

  .category-item-centered {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    background: #fff;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    min-width: 120px;
    flex-shrink: 0;
    justify-content: center;
  }

  .category-item-centered:hover {
    background: #fff3cd;
    border-color: #ffc107;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
  }

  .category-item-centered.active {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    color: white;
    border-color: #ffc107;
    box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
  }

  .category-icon-centered {
    width: 35px;
    height: 35px;
    margin-right: 10px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .category-icon-centered img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .category-info-centered {
    flex: 1;
    text-align: center;
  }

  .category-info-centered h6 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    color: inherit;
    margin: 0 0 2px 0;
  }

  .category-info-centered p {
    font-size: 0.75rem;
    opacity: 0.8;
    color: inherit;
    margin: 0;
  }

  /* Responsive Design for Categories */
  @media (max-width: 768px) {
    .category-section-centered {
      padding: 30px 15px;
    }
    
    .category-title-centered {
      font-size: 1.8rem;
      margin-bottom: 25px;
    }
    
    .category-list-centered {
      flex-direction: column;
      gap: 10px;
    }
    
    .category-item-centered {
      padding: 12px 20px;
      min-width: 200px;
    }
    
    .category-icon-centered {
      width: 40px;
      height: 40px;
      margin-right: 15px;
    }
  }

</style>