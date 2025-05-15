<?php
/*
Template Name: Custom pages
*/
?>

<?php
get_header();
get_header('custom');
?>
<section class="bg-white py-10">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php
                the_content();
                ?>
            </div>
        </div>
    </div>
    <div class="svg-border-rounded text-light">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor">
            <path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0"></path>
        </svg>
    </div>
</section>
</main>
</div>
<?php get_footer() ?>