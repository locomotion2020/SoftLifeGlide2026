<section id="pageTitle" class="page-title-section" >
    <div class="page-title-container container flex-col gap-1-2 --narrow " >
        <h1 class="page-title __txt-hidden" >
            <?php echo is_front_page() ? get_bloginfo( 'name' ) : get_the_title();?>
        </h1>
    </div>
</section>
<section id="pageContent" class="company-image">
    <div class="container content-container --narrow ">
        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
    </div>
</section>