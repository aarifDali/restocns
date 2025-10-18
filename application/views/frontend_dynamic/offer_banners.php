<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/setting.js'); ?>"></script>
<!-- Main content -->
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {
        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));unset($_SESSION['exception']);
        echo '</p></div></div></section>';
    }
    ?>

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('offer_banners'); ?>
        </h3>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>userHome"><i class="fa fa-dashboard"></i> <?php echo lang('home'); ?></a></li>
            <li class="active"><?php echo lang('offer_banners'); ?></li>
        </ol>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
            <?php
            $attributes = array('id' => 'offerBannerForm');
            echo form_open_multipart(base_url('Frontend/offerBanners'), $attributes); ?>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('heading'); ?> <span class="required_star">*</span></label>
                            <input tabindex="1" autocomplete="off" type="text" id="heading" name="heading" class="form-control" placeholder="<?php echo lang('heading'); ?>" value="">
                        </div>
                        <?php if (form_error('heading')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('heading'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('button_text'); ?> <span class="required_star">*</span></label>
                            <input tabindex="2" autocomplete="off" type="text" id="button_text" name="button_text" class="form-control" placeholder="<?php echo lang('button_text'); ?>" value="">
                        </div>
                        <?php if (form_error('button_text')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('button_text'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('button_link'); ?> <span class="required_star">*</span></label>
                            <input tabindex="3" autocomplete="off" type="text" id="button_link" name="button_link" class="form-control" placeholder="<?php echo lang('button_link'); ?>" value="">
                        </div>
                        <?php if (form_error('button_link')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('button_link'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-6">
                        <div class="form-group">
                            <label><?php echo lang('sort_order'); ?> <span class="required_star">*</span></label>
                            <input tabindex="4" autocomplete="off" type="number" id="sort_order" name="sort_order" class="form-control" placeholder="<?php echo lang('sort_order'); ?>" value="1" min="1">
                        </div>
                        <?php if (form_error('sort_order')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('sort_order'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-12">
                        <div class="form-group">
                            <div class="form-label-action">
                                <label><?php echo lang('banner_image'); ?> (Recommended: 1200x400px) <span class="required_star">*</span></label>
                            </div>
                            <input type="file" id="banner_image" accept="image/*" name="banner_image" class="form-control">
                        </div>
                        <?php if (form_error('banner_image')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('banner_image'); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn me-2">
                    <i data-feather="plus"></i>
                    <?php echo lang('add_offer_banner'); ?>
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- Offer Banners List -->
    <div class="box-wrapper">
        <div class="table-box">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('image'); ?></th>
                                <th><?php echo lang('heading'); ?></th>
                                <th><?php echo lang('button_text'); ?></th>
                                <th><?php echo lang('button_link'); ?></th>
                                <th><?php echo lang('sort_order'); ?></th>
                                <th><?php echo lang('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($offer_banners)) {
                                foreach ($offer_banners as $banner) {
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo base_url() ?>uploads/offer_banners/<?php echo $banner->banner_image; ?>" 
                                         alt="<?php echo $banner->heading; ?>" 
                                         style="width: 100px; height: 60px; object-fit: cover;">
                                </td>
                                <td><?php echo escape_output($banner->heading); ?></td>
                                <td><?php echo escape_output($banner->button_text); ?></td>
                                <td>
                                    <a href="<?php echo escape_output($banner->button_link); ?>" target="_blank">
                                        <?php echo escape_output($banner->button_link); ?>
                                    </a>
                                </td>
                                <td><?php echo escape_output($banner->sort_order); ?></td>
                                <td>
                                    <a href="<?php echo base_url() ?>Authentication/deleteOfferBanner/<?php echo $this->custom->encrypt_decrypt($banner->id, 'encrypt'); ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('<?php echo lang('alert_delete_offer_banner'); ?>')">
                                        <i class="fa fa-trash"></i> <?php echo lang('delete'); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center"><?php echo lang('no_offer_banners_found'); ?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</section>
