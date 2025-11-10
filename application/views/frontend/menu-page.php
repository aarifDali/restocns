<?php
$company_info     = getMainCompany();
$categories       = getFoodMenuCategory();
$get_food_menu    = getFoodMenuForMenuPage();
$online_selected_outlet = $this->session->userdata('online_selected_outlet');
$outlet_details = getOutletById($online_selected_outlet);

// Organize products by category for subcategory views
$products_by_category = array();
$thumb_imgs = isset($outlet_details->thumb_imgs)?(Array)json_decode($outlet_details->thumb_imgs):'';
foreach ($get_food_menu as $food) {
    if (!isset($products_by_category[$food->category_id])) {
        $products_by_category[$food->category_id] = array();
    }
    $products_by_category[$food->category_id][] = $food;
}

// Build subcategory data structure for each parent category
$subcategory_data = array();
foreach ($categories as $cat) {
    $subcategories = $this->Common_model->getChildCategories($cat->id);
    if ($subcategories && count($subcategories) > 0) {
        $subcategory_data[$cat->id] = array(
            'parent' => $cat,
            'subcategories' => $subcategories,
            'has_parent_products' => isset($products_by_category[$cat->id]) && count($products_by_category[$cat->id]) > 0
        );
    }
}

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
        <h2 class="category-title-centered">Explore Menu</h2>
        <div class="category-nav-wrapper" style="position:relative;">
          <button type="button" class="cat-scroll-btn cat-prev" aria-label="Previous" style="position:absolute;left:-10px;top:50%;transform:translateY(-50%);background:#fff;border:2px solid #ffc107;border-radius:50%;width:36px;height:36px;display:none;align-items:center;justify-content:center;cursor:pointer;z-index:2;"><svg width="18" height="18" viewBox="0 0 20 20" fill="#ffc107" xmlns="http://www.w3.org/2000/svg"><path d="M1.61719 9.11719C1.12891 9.60547 1.12891 10.3984 1.61719 10.8867L7.86719 17.1367C8.35547 17.625 9.14844 17.625 9.63672 17.1367C10.125 16.6484 10.125 15.8555 9.63672 15.3672L5.51562 11.25H17.5C18.1914 11.25 18.75 10.6914 18.75 10C18.75 9.30859 18.1914 8.75 17.5 8.75H5.51953L9.63281 4.63281C10.1211 4.14453 10.1211 3.35156 9.63281 2.86328C9.14453 2.375 8.35156 2.375 7.86328 2.86328L1.61328 9.11328L1.61719 9.11719Z"/></svg></button>
          <div class="category-list-centered" id="category-items" style="display:flex;gap:15px;align-items:center;justify-content:flex-start;width:100%;height:210px;overflow:hidden;padding:0;">
            <a href="#" data-filter="*" class="category-item-centered active">
              <img class="category-thumb-180" src="<?php echo base_url(); ?>assets/website/img/all.png" alt="<?php echo lang('all'); ?>">
            </a>
            <?php if ($categories) {
              foreach ($categories as $index => $cat) {
            ?>
              <a href="#" data-filter=".cat-id-<?php echo $cat->id ?>" class="category-item-centered category-item-image-only category-page-item" data-index="<?php echo $index; ?>" title="<?php echo $cat->category_name ?>">
                <img class="category-thumb-180" src="<?php echo base_url(); ?><?php echo $cat->category_image ? "uploads/category/" . $cat->category_image : 'assets/media/default_cat.jpg'; ?>" alt="<?php echo $cat->category_name ?>">
              </a>
            <?php
              }
            } ?>
          </div>
          <button type="button" class="cat-scroll-btn cat-next" aria-label="Next" style="position:absolute;right:-10px;top:50%;transform:translateY(-50%);background:#fff;border:2px solid #ffc107;border-radius:50%;width:36px;height:36px;display:none;align-items:center;justify-content:center;cursor:pointer;z-index:2;"><svg width="18" height="18" viewBox="0 0 20 20" fill="#ffc107" xmlns="http://www.w3.org/2000/svg"><path d="M18.3828 10.8828C18.8711 10.3945 18.8711 9.60156 18.3828 9.11328L12.1328 2.86328C11.6445 2.375 10.8516 2.375 10.3633 2.86328C9.875 3.35156 9.875 4.14453 10.3633 4.63281L14.4844 8.75H2.5C1.80859 8.75 1.25 9.30859 1.25 10C1.25 10.6914 1.80859 11.25 2.5 11.25H14.4805L10.3672 15.3672C9.87891 15.8555 9.87891 16.6484 10.3672 17.1367C10.8555 17.625 11.6484 17.625 12.1367 17.1367L18.3867 10.8867L18.3828 10.8828Z"/></svg></button>
        </div>
      </div>
    </div>
  </div>
  <!-- Menu Categories End -->

  <!-- Menu Wrapper Start -->
  <div class="section section-padding">
    <div class="container">
      
      <!-- All Products View (Default) -->
      <div id="all-products-view" class="menu-container row">
        <!-- Product Start -->
        <?php
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

      <!-- Hierarchical Subcategory View (Hidden by default) -->
      <div id="subcategory-view" class="subcategory-sections" style="display: none;">
        <?php foreach ($subcategory_data as $parent_id => $subcat_info): ?>
          <div class="subcategory-parent-wrapper" data-parent-id="<?php echo $parent_id; ?>" style="display: none;">
            <?php 
            // Show parent category products if they exist
            if ($subcat_info['has_parent_products']): 
              $parent_products = $products_by_category[$parent_id];
            ?>
              <div class="subcategory-section">
                <h3 class="subcategory-section-title"><?php echo $subcat_info['parent']->category_name; ?></h3>
                <div class="subcategory-carousel-wrapper">
                  <div class="subcategory-carousel row" id="carousel-parent-<?php echo $parent_id; ?>">
                    <?php foreach ($parent_products as $food): 
                      $img = '';
                      $str = "thumb_".$food->id;
                      if(isset($thumb_imgs[$str]) && $thumb_imgs[$str]){
                        $img = base_url()."uploads/website/".$thumb_imgs[$str];
                      }else{
                        $img = base_url()."assets/media/no_image.png";
                      }
                    ?>
                      <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
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
                    <?php endforeach; ?>
                  </div>
                  <?php if (count($parent_products) > 4): ?>
                    <div class="subcategory-nav">
                      <button class="subcategory-nav-btn prev-btn" data-carousel="carousel-parent-<?php echo $parent_id; ?>">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M1.61719 9.11719C1.12891 9.60547 1.12891 10.3984 1.61719 10.8867L7.86719 17.1367C8.35547 17.625 9.14844 17.625 9.63672 17.1367C10.125 16.6484 10.125 15.8555 9.63672 15.3672L5.51562 11.25H17.5C18.1914 11.25 18.75 10.6914 18.75 10C18.75 9.30859 18.1914 8.75 17.5 8.75H5.51953L9.63281 4.63281C10.1211 4.14453 10.1211 3.35156 9.63281 2.86328C9.14453 2.375 8.35156 2.375 7.86328 2.86328L1.61328 9.11328L1.61719 9.11719Z" fill="#2D2C2B" />
                        </svg>
                      </button>
                      <button class="subcategory-nav-btn next-btn" data-carousel="carousel-parent-<?php echo $parent_id; ?>">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M18.3828 10.8828C18.8711 10.3945 18.8711 9.60156 18.3828 9.11328L12.1328 2.86328C11.6445 2.375 10.8516 2.375 10.3633 2.86328C9.875 3.35156 9.875 4.14453 10.3633 4.63281L14.4844 8.75H2.5C1.80859 8.75 1.25 9.30859 1.25 10C1.25 10.6914 1.80859 11.25 2.5 11.25H14.4805L10.3672 15.3672C9.87891 15.8555 9.87891 16.6484 10.3672 17.1367C10.8555 17.625 11.6484 17.625 12.1367 17.1367L18.3867 10.8867L18.3828 10.8828Z" fill="#2D2C2B" />
                        </svg>
                      </button>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>
            
            <?php 
            // Show subcategory sections
            foreach ($subcat_info['subcategories'] as $subcat): 
              if (isset($products_by_category[$subcat->id]) && count($products_by_category[$subcat->id]) > 0):
                $subcat_products = $products_by_category[$subcat->id];
            ?>
              <div class="subcategory-section">
                <h3 class="subcategory-section-title"><?php echo $subcat->category_name; ?></h3>
                <div class="subcategory-carousel-wrapper">
                  <div class="subcategory-carousel row" id="carousel-<?php echo $subcat->id; ?>">
                    <?php foreach ($subcat_products as $food): 
                      $img = '';
                      $str = "thumb_".$food->id;
                      if(isset($thumb_imgs[$str]) && $thumb_imgs[$str]){
                        $img = base_url()."uploads/website/".$thumb_imgs[$str];
                      }else{
                        $img = base_url()."assets/media/no_image.png";
                      }
                    ?>
                      <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
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
                    <?php endforeach; ?>
                  </div>
                  <?php if (count($subcat_products) > 4): ?>
                    <div class="subcategory-nav">
                      <button class="subcategory-nav-btn prev-btn" data-carousel="carousel-<?php echo $subcat->id; ?>">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M1.61719 9.11719C1.12891 9.60547 1.12891 10.3984 1.61719 10.8867L7.86719 17.1367C8.35547 17.625 9.14844 17.625 9.63672 17.1367C10.125 16.6484 10.125 15.8555 9.63672 15.3672L5.51562 11.25H17.5C18.1914 11.25 18.75 10.6914 18.75 10C18.75 9.30859 18.1914 8.75 17.5 8.75H5.51953L9.63281 4.63281C10.1211 4.14453 10.1211 3.35156 9.63281 2.86328C9.14453 2.375 8.35156 2.375 7.86328 2.86328L1.61328 9.11328L1.61719 9.11719Z" fill="#2D2C2B" />
                        </svg>
                      </button>
                      <button class="subcategory-nav-btn next-btn" data-carousel="carousel-<?php echo $subcat->id; ?>">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M18.3828 10.8828C18.8711 10.3945 18.8711 9.60156 18.3828 9.11328L12.1328 2.86328C11.6445 2.375 10.8516 2.375 10.3633 2.86328C9.875 3.35156 9.875 4.14453 10.3633 4.63281L14.4844 8.75H2.5C1.80859 8.75 1.25 9.30859 1.25 10C1.25 10.6914 1.80859 11.25 2.5 11.25H14.4805L10.3672 15.3672C9.87891 15.8555 9.87891 16.6484 10.3672 17.1367C10.8555 17.625 11.6484 17.625 12.1367 17.1367L18.3867 10.8867L18.3828 10.8828Z" fill="#2D2C2B" />
                        </svg>
                      </button>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php 
              endif;
            endforeach; ?>
          </div>
        <?php endforeach; ?>
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

  /* Mobile Product Card Redesign - Horizontal Layout */
  @media (max-width: 768px) {
    /* Override Bootstrap columns for product containers */
    .menu-container .col-xl-3,
    .menu-container .col-lg-4,
    .menu-container .col-md-6,
    .subcategory-carousel .col-xl-3,
    .subcategory-carousel .col-lg-4,
    .subcategory-carousel .col-md-6 {
      width: auto !important;
      max-width: none !important;
      flex: none !important;
      margin-bottom: 15px;
    }

    /* Product container - fixed dimensions, horizontal flex layout */
    .product {
      width: 430px;
      max-width: calc(100vw - 30px);
      height: 215px;
      display: flex;
      flex-direction: row;
      border-radius: 12px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin: 0 auto 15px auto;
      position: relative;
    }

    /* Ensure containers don't overflow */
    .menu-container,
    .subcategory-sections {
      overflow-x: hidden;
      width: 100%;
      max-width: 100vw;
    }

    /* Disable hover effects on mobile */
    .product:hover {
      transform: none;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .product:hover .product-thumb img {
      transform: none;
    }

    /* Product body - Left section (60%) - starts from left border of product */
    .product-body {
      width: 60%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 15px 15px 15px 15px;
      order: 1;
    }

    /* Product description adjustments - starts from left border of product div */
    .product-desc {
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1;
      margin-left: -15px;
      padding-left: 0;
    }

    /* Rating wrap - keep visible but smaller */
    .product-desc .rating-wrap {
      margin-bottom: 5px;
    }

    .product-desc .rating-wrap .rating {
      font-size: 12px;
    }

    .product-desc .rating-num {
      font-size: 11px;
    }

    /* Product name - adjust font size */
    .product-desc h4 {
      font-size: 16px;
      line-height: 1.3;
      margin: 0 0 8px 0;
    }

    .product-desc h4 a {
      color: #333;
      text-decoration: none;
    }

    /* Product price - prominent display with larger font in yellow */
    .product-price {
      font-size: 22px;
      font-weight: bold;
      color: #ffc107;
      margin: 8px 0 0 0;
    }

    .product-price span {
      font-size: 22px;
      font-weight: bold;
      color: #ffc107;
    }


    /* Product thumb - Right section (40%) with image */
    .product-thumb {
      width: 40%;
      height: 100%;
      display: flex;
      flex-direction: column;
      order: 2;
      border-radius: 0;
      overflow: hidden;
      position: relative;
    }

    .product-thumb img {
      width: 100%;
      height: calc(100% - 60px);
      object-fit: cover;
      border-radius: 0;
      display: block;
    }

    /* Product controls - position absolutely in right section, below image */
    .product-controls {
      position: absolute;
      bottom: 10px;
      left: 60%;
      width: 40%;
      margin: 0;
      padding: 8px;
      padding-right: 25px;
      z-index: 2;
      box-sizing: border-box;
    }

    /* Order item button - KFC style with padding and rounded corners */
    .product-controls .order-item {
      display: block;
      width: 100%;
      background-color: #e40000;
      color: #fff !important;
      text-align: center;
      padding: 12px 15px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      border: none;
      font-size: 14px;
      line-height: 1.2;
      box-sizing: border-box;
    }

    .product-controls .order-item svg {
      display: none !important;
    }

    .product-controls .order-item::after {
      content: "ADD TO CART";
      display: inline-block;
    }

    /* Subcategory carousel adjustments - prevent horizontal scroll */
    .subcategory-carousel-wrapper {
      overflow-x: hidden;
      width: 100%;
      max-width: 100vw;
    }

    .subcategory-carousel {
      padding: 15px 0;
      overflow-x: auto;
      overflow-y: visible;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: thin;
    }

    /* Products in carousel inherit all styles from .product selector above */
    /* No specific overrides needed - they will use the same 430px width, margins, and styling */

    /* Prevent body horizontal scroll */
    body {
      overflow-x: hidden;
    }
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
    text-align: left;
    position: relative;
  }

  .category-title-centered::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    border-radius: 2px;
  }

  .category-list-centered {
    display: flex;
    flex-direction: row;
    gap: 15px;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: nowrap;
    overflow-x: hidden;
    padding: 10px 0;
    scrollbar-width: none; /* Firefox hide */
    width: 100%;
  }
  #category-items .category-item-centered{flex:0 0 auto}
  #category-items .category-item-centered:first-child{margin-left:0}

  /* Hide scrollbar for the inner items container too */
  #category-items { scrollbar-width: none; }
  #category-items::-webkit-scrollbar { display: none; }

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
    justify-content: flex-start;
    gap: 10px;
  }

  /* Image-only category tiles */
  .category-item-image-only{
    padding: 0;
    border-radius: 12px;
    min-width: auto;
    width: 180px;
    height: 180px;
    justify-content: center;
  }
  .category-item-image-only .category-icon-centered{display:none}
  .category-thumb-180{width:180px;height:180px;object-fit:cover;border-radius:12px;display:block}

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
    width: 40px;
    height: 40px;
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
    text-align: left;
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
    
    .category-nav-wrapper {
      height: auto !important;
    }
    
    .category-list-centered {
      flex-direction: row;
      flex-wrap: wrap;
      gap: 10px;
      overflow-x: visible !important;
      overflow-y: visible !important;
      height: auto !important;
      max-height: none !important;
      justify-content: flex-start;
      align-items: flex-start;
    }
    
    #category-items {
      height: auto !important;
      overflow: visible !important;
      max-height: none !important;
    }
    
    .category-item-centered {
      padding: 12px 20px;
      min-width: auto;
    }
    
    /* Image-only category tiles - mobile size: 3 per row, 110x110 */
    .category-item-image-only {
      width: calc((100% - 20px) / 3);
      max-width: 110px;
      min-width: 110px;
      height: 110px;
      flex: 0 0 calc((100% - 20px) / 3);
      margin-bottom: 10px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff;
      padding: 0;
      box-sizing: border-box;
    }
    
    /* Category images fill the tile completely */
    .category-thumb-180 {
      width: 110px !important;
      height: 110px !important;
      object-fit: cover;
      display: block;
      border-radius: 12px;
    }
    
    /* Make the "All" category container match image-only items */
    .category-item-centered:first-child {
      width: calc((100% - 20px) / 3);
      max-width: 110px;
      min-width: 110px;
      height: 110px;
      padding: 0;
      flex: 0 0 calc((100% - 20px) / 3);
      margin-bottom: 10px;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      display: flex;
      background: #fff;
      box-sizing: border-box;
    }
    
    .category-item-centered:first-child .category-thumb-180 {
      width: 110px !important;
      height: 110px !important;
      object-fit: cover;
      border-radius: 12px;
    }
    
    .category-icon-centered {
      width: 50px;
      height: 50px;
    }
    
    /* Hide scroll buttons on mobile since items wrap */
    .cat-scroll-btn {
      display: none !important;
    }
  }
  
  /* Extra small mobile devices - ensure 3 per row */
  @media (max-width: 575px) {
    .category-item-image-only {
      width: calc((100% - 20px) / 3);
      min-width: 110px;
      max-width: 110px;
      height: 110px;
      flex: 0 0 calc((100% - 20px) / 3);
    }
    
    .category-item-centered:first-child {
      width: calc((100% - 20px) / 3);
      min-width: 110px;
      max-width: 110px;
      flex: 0 0 calc((100% - 20px) / 3);
    }
    
    .category-thumb-180 {
      width: 110px !important;
      height: 110px !important;
      object-fit: cover;
      border-radius: 12px;
    }
    
    .category-item-image-only {
      padding: 0;
      overflow: hidden;
    }
    
    .category-item-centered:first-child {
      padding: 0;
      overflow: hidden;
    }
  }

  /* Subcategory Sections Styling */
  .subcategory-section {
    margin-bottom: 60px;
    transition: all 0.4s ease;
  }

  .subcategory-section-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 2rem;
    color: #333;
    margin-bottom: 30px;
    text-align: left;
    position: relative;
  }

  .subcategory-section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    border-radius: 2px;
  }

  .subcategory-carousel-wrapper {
    position: relative;
  }

  .subcategory-carousel {
    overflow-x: auto;
    padding: 20px 0;
    scrollbar-width: thin;
    scrollbar-color: #ffc107 #f0f0f0;
  }

  .subcategory-carousel::-webkit-scrollbar {
    height: 6px;
  }

  .subcategory-carousel::-webkit-scrollbar-track {
    background: #f0f0f0;
    border-radius: 3px;
  }

  .subcategory-carousel::-webkit-scrollbar-thumb {
    background: #ffc107;
    border-radius: 3px;
  }

  .subcategory-carousel::-webkit-scrollbar-thumb:hover {
    background: #ff8c00;
  }

  .subcategory-nav {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
  }

  .subcategory-nav-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #ffc107;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .subcategory-nav-btn:hover {
    background: #ffc107;
    transform: scale(1.1);
  }

  .subcategory-nav-btn svg {
    width: 20px;
    height: 20px;
    fill: #ffc107;
    transition: fill 0.3s ease;
  }

  .subcategory-nav-btn:hover svg {
    fill: #fff;
  }

  .subcategory-nav-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .subcategory-nav-btn.disabled:hover {
    transform: none;
    background: #fff;
  }

  .subcategory-nav-btn.disabled svg {
    fill: #ccc;
  }

  /* Loading Spinner Styling */
  .spinner-border.text-warning {
    color: #ffc107 !important;
    width: 3rem;
    height: 3rem;
  }

  .loading-text {
    color: #666;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
  }

  /* Responsive Design for Subcategory Sections */
  @media (max-width: 768px) {
    .subcategory-section-title {
      font-size: 1.6rem;
      margin-bottom: 20px;
    }
    
    .subcategory-carousel {
      padding: 15px 0;
    }
  }

</style>

<script>
$(document).ready(function() {
     var originalIsotope = null;
    // Category smooth scroller with arrows only when overflow
    (function initCategoryScroll(){
        var $wrap = $('#category-items');
        var $prev = $('.cat-prev');
        var $next = $('.cat-next');
        // Enable smooth programmatic scrolling
        $wrap.css('scrollBehavior','smooth');
        function updateArrows(){
            var canScroll = $wrap[0].scrollWidth > $wrap[0].clientWidth + 2; // allow small epsilon
            $prev.css('display', canScroll ? 'flex' : 'none');
            $next.css('display', canScroll ? 'flex' : 'none');
            if (!canScroll) return;
            var maxLeft = $wrap[0].scrollWidth - $wrap[0].clientWidth;
            var left = $wrap[0].scrollLeft;
            $prev.prop('disabled', left <= 0).css('opacity', left<=0?0.5:1);
            $next.prop('disabled', left >= maxLeft-1).css('opacity', left>=maxLeft-1?0.5:1);
        }
        function scrollByAmount(dir){
            var amount = Math.max(200, Math.floor($wrap[0].clientWidth * 0.6));
            var target = $wrap[0].scrollLeft + (dir * amount);
            $wrap[0].scrollTo({left: target, behavior: 'smooth'});
        }
        $prev.off('click').on('click', function(){ if(!$(this).prop('disabled')) scrollByAmount(-1); });
        $next.off('click').on('click', function(){ if(!$(this).prop('disabled')) scrollByAmount(1); });
        // Update on scroll and resize
        $wrap.on('scroll', updateArrows);
        $(window).on('resize', updateArrows);
        // Initial
        $wrap.scrollLeft(0);
        setTimeout(updateArrows, 0);
    })();
    
    // Initialize Isotope on page load for "All" view
    if (typeof $.fn.isotope !== 'undefined') {
        originalIsotope = $('.menu-container').isotope({
            itemSelector: '.col-xl-3',
            layoutMode: 'fitRows',
            transitionDuration: '0.6s'
        });
    }
    
    // Category click handler
    $('.category-item-centered').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all categories
        $('.category-item-centered').removeClass('active');
        // Add active class to clicked category
        $(this).addClass('active');
        
        var filter = $(this).data('filter');
        var categoryId = filter !== '*' ? filter.replace('.cat-id-', '') : null;
        
        if (filter === '*') {
            // Show all products view
            $('#subcategory-view').fadeOut(300, function() {
                $('#all-products-view').fadeIn(400);
                // Reset isotope filter
                if (originalIsotope) {
                    originalIsotope.isotope({ filter: '*' });
                }
            });
        } else {
            // Check if this category has subcategories (pre-rendered)
            var hasSubcategories = $('.subcategory-parent-wrapper[data-parent-id="' + categoryId + '"]').length > 0;
            
            if (hasSubcategories) {
                // Show subcategory view
                $('#all-products-view').fadeOut(300, function() {
                    // Hide all subcategory wrappers
                    $('.subcategory-parent-wrapper').hide();
                    // Show the selected one
                    $('.subcategory-parent-wrapper[data-parent-id="' + categoryId + '"]').fadeIn(400);
                    $('#subcategory-view').fadeIn(400, function() {
                        initializeSubcategoryCarousels();
                    });
                });
            } else {
                // Show filtered products view
                $('#subcategory-view').fadeOut(300, function() {
                    $('#all-products-view').fadeIn(400);
                    // Filter products using isotope
                    if (originalIsotope) {
                        originalIsotope.isotope({ filter: filter });
                    } else {
                        // Fallback if isotope not available
                        $('.menu-container .col-xl-3').hide();
                        $(filter).show();
                    }
                });
            }
        }
    });
    
    // Initialize carousel navigation
    function initializeSubcategoryCarousels() {
        $('.subcategory-carousel').each(function() {
            var carousel = $(this);
            var carouselId = carousel.attr('id');
            var prevBtn = $('.prev-btn[data-carousel="' + carouselId + '"]');
            var nextBtn = $('.next-btn[data-carousel="' + carouselId + '"]');
            
            // Remove existing handlers to prevent duplicates
            prevBtn.off('click');
            nextBtn.off('click');
            
            // Calculate scroll amount
            var containerWidth = carousel.width();
            var itemWidth = carousel.find('.col-xl-3').outerWidth(true) || 300;
            var visibleItems = Math.floor(containerWidth / itemWidth);
            var scrollAmount = itemWidth * Math.max(1, Math.floor(visibleItems / 2));
            
            var currentScroll = 0;
            var maxScroll = carousel[0].scrollWidth - carousel[0].clientWidth;
            
            function updateButtons() {
                prevBtn.toggleClass('disabled', currentScroll <= 0);
                nextBtn.toggleClass('disabled', currentScroll >= maxScroll);
            }
            
            // Previous button
            prevBtn.on('click', function() {
                if (currentScroll > 0) {
                    currentScroll = Math.max(0, currentScroll - scrollAmount);
                    carousel.animate({scrollLeft: currentScroll}, 300);
                    setTimeout(updateButtons, 300);
                }
            });
            
            // Next button
            nextBtn.on('click', function() {
                if (currentScroll < maxScroll) {
                    currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
                    carousel.animate({scrollLeft: currentScroll}, 300);
                    setTimeout(updateButtons, 300);
                }
            });
            
            // Initial button state
            updateButtons();
        });
    }
    
    // Initialize carousels on page load if subcategory view is visible
    if ($('#subcategory-view').is(':visible')) {
        initializeSubcategoryCarousels();
    }
});
</script>