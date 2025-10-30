<?php
$company_info = getMainCompany(); 
$about_us = isset($company_info->about_us) && $company_info->about_us?json_decode($company_info->about_us):'';
$common_menu_page = isset($company_info->common_menu_page) && $company_info->common_menu_page?json_decode($company_info->common_menu_page):'';
?>
  <section class="aboutus-banner">
    <div class="aboutus-banner-content text-center">
      <h1 class="aboutus-title">About Us</h1>
    </div>
  </section>
  <!-- About us start -->
  <div class="section about-us-page">
    <div class="container">
      <div class="row align-items-center">

        <div class="col-lg-6 mb-lg-30 ct-single-img-wrapper">
          <div class="image-collage">
            <?php if(isset($about_us->about_us_image_1) && $about_us->about_us_image_1): ?>
            <div class="collage-image image-1">
              <img src="<?php echo base_url();?>uploads/about_us/<?php echo $about_us->about_us_image_1; ?>" alt="About Us Image 1">
            </div>
            <?php endif; ?>
            
            <div class="right-images">
              <?php if(isset($about_us->about_us_image_2) && $about_us->about_us_image_2): ?>
              <div class="collage-image image-2">
                <img src="<?php echo base_url();?>uploads/about_us/<?php echo $about_us->about_us_image_2; ?>" alt="About Us Image 2">
              </div>
              <?php endif; ?>
              
              <?php if(isset($about_us->about_us_image_3) && $about_us->about_us_image_3): ?>
              <div class="collage-image image-3">
                <img src="<?php echo base_url();?>uploads/about_us/<?php echo $about_us->about_us_image_3; ?>" alt="About Us Image 3">
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="section-title-wrap mr-lg-30 content-animate">
            <!-- <h5 class="custom-primary"><?php echo isset($about_us->about_us_title) && $about_us->about_us_title ? $about_us->about_us_title : '' ?></h5> -->
            <h2 class="title"><?php echo isset($about_us->abous_us_heading) && $about_us->abous_us_heading ? $about_us->abous_us_heading : '' ?></h2>
            <p class="subtitle">
              <?php echo isset($about_us->about_us_des) && $about_us->about_us_des ? $about_us->about_us_des : '' ?>
            </p>
            <a href="<?php echo base_url()?>online-order" class="btn-custom"><?php echo lang('check_our_menu'); ?> <i class="fa fa-caret-right"></i></a>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- About us End -->

  <style>
  @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap');
  
  .aboutus-banner {
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

  .aboutus-banner-content {
    position: relative;
    z-index: 2;
    text-align: center;
  }

  .aboutus-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 2.5rem;
    color: #333;
    margin: 0;
    text-align: center;
  }

  /* Image Collage Styles */
  .image-collage {
    position: relative;
    width: 100%;
    height: 600px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
  }

  .collage-image {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .collage-image:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  }

  .collage-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .collage-image:hover img {
    transform: scale(1.05);
  }

  /* Loading Animation Styles */
  .collage-image {
    opacity: 0;
    transform: translateY(50px) scale(0.8);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .collage-image.animate-in {
    opacity: 1;
    transform: translateY(0) scale(1);
  }

  .image-1 {
    transition-delay: 0.1s;
  }

  .image-2 {
    transition-delay: 0.3s;
  }

  .image-3 {
    transition-delay: 0.5s;
  }

  /* Content Animation */
  .content-animate {
    opacity: 0;
    transform: translateX(-30px);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  /* Left side - tall portrait image */
  .image-1 {
    width: 280px;
    height: 440px;
    order: 1;
  }

  /* Right side container for stacked images */
  .right-images {
    display: flex;
    flex-direction: column;
    gap: 20px;
    order: 2;
  }

  /* Top right - wide landscape image */
  .image-2 {
    width: 390px;
    height: 245px;
    order: 1;
  }

  /* Bottom right - medium portrait image */
  .image-3 {
    width: 280px;
    height: 320px;
    order: 2;
  }

  /* Responsive design */
  @media (max-width: 768px) {
    .image-collage {
      height: 500px;
      flex-direction: column;
      align-items: center;
    }
    
    .right-images {
      order: 2;
      width: 100%;
      align-items: center;
    }
    
    .image-1 {
      width: 250px;
      height: 200px;
      order: 1;
    }
    
    .image-2 {
      width: 250px;
      height: 150px;
    }
    
    .image-3 {
      width: 250px;
      height: 150px;
    }
  }

  @media (max-width: 576px) {
    .image-collage {
      height: 450px;
    }
    
    .image-1,
    .image-2,
    .image-3 {
      width: 200px;
      height: 120px;
    }
  }
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const collageImages = document.querySelectorAll('.collage-image');
    const contentAnimate = document.querySelector('.content-animate');
    
    // Function to trigger image animations
    function triggerImageAnimations() {
      collageImages.forEach((image, index) => {
        // Add the animate-in class with a slight delay for each image
        setTimeout(() => {
          image.classList.add('animate-in');
        }, index * 200);
      });
    }

    // Function to trigger content animation
    function triggerContentAnimation() {
      if (contentAnimate) {
        setTimeout(() => {
          contentAnimate.style.opacity = '1';
          contentAnimate.style.transform = 'translateX(0)';
        }, 100);
      }
    }

    // Use Intersection Observer for better performance
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          triggerImageAnimations();
          triggerContentAnimation();
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1
    });

    // Observe the about us section
    const aboutUsSection = document.querySelector('.about-us-page');
    if (aboutUsSection) {
      observer.observe(aboutUsSection);
    }

    // Fallback: trigger animations after 500ms if intersection observer doesn't work
    setTimeout(() => {
      if (collageImages[0] && !collageImages[0].classList.contains('animate-in')) {
        triggerImageAnimations();
        triggerContentAnimation();
      }
    }, 500);
  });
  </script>