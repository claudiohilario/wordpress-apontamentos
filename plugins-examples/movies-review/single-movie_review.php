<?php
/**
 * Template Default of the Movies Review
 */

 get_header();

?>

<div id="primary" class="content-area">
    <main id="main" class="content site-main" role="main">
        <?php 

            while(hahe_posts()) : the_post();
                $field_perfix = Movies_reviews::$field_prefix_1;

                $image = get_the_post_thumbnail( get_the_ID(), 'medium' );
                $image_url = wp_get_attachment_image_src(get_the_post_thumbnail_id(get_the_ID()), 'medium'); /** RECUPERAR THUMBNAIL */

                $rating = (int) post_custom($field_perfix.'review_rating');
                $show_rating = fn_show_rating($rating); /** Show rating. Function to show dash icons */ 

            endwhile;
        
        ?>
    </main>
</div>

<?php
    get_footer();
?>

<?php 
    /** Heper function */
    function fn_show_rating($rating = null) {
        $rating = (int) $rating;

        if($rating > 0) {
            $stars_rating = array();
            $show_rating = "";

            for($i = 0; $i < floor($rating/2); $i++) {
                $stars_rating[] = '<span class="dashicons dashicons-star-filled"></span>';
            }

            if($rating % 2 === 1) {
                $stars_rating[] = '<span class="dashicons dashicons-star-half"></span>';
            }

            $stars_rating = array_pad($stars_rating, 5, '<span class="dashicons dashicons-star-empty"></span>');


            return implode("\n", $stars_rating);
        }

        return false;
    }
?>