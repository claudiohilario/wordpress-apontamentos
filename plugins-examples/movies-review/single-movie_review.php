<?php
/**
 * Template Default of the Movies Review
 */

 get_header();

?>

<div id="primary" class="content-area">
    <main id="main" class="content site-main" role="main">
        <?php 

            while(have_posts()) : the_post();
                $field_perfix = Movies_reviews::$field_prefix_1;

                $image = get_the_post_thumbnail( get_the_ID(), 'medium' );
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium'); /** RECUPERAR THUMBNAIL */

                $rating = (int) post_custom($field_perfix.'review_rating');
                $show_rating = fn_show_rating($rating); /** Show rating. Function to show dash icons */ 

                $director = wp_strip_all_tags(post_custom($field_perfix.'movie_director'));
                $link_site = esc_url( post_custom($field_perfix.'movie_site'));

                $year = (int) post_custom($field_perfix.'movie_year');

                $movies_category = get_the_terms(get_the_id(), 'movie_types');

                $movie_type = '';

                if($movies_category && !is_wp_error( $movies_category )) {
                    $movie_type = array();

                    foreach ($movies_category as $cat) {
                        $movie_type[] = $cat->name;               
                    }
                }

                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('hentry') ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>') ?>
                    </header>

                </article>

                <div class="entry-content">
                    <div class="left">
                        <?php
                            if(isset($image)) {
                        ?>
                            <div class="poster">
                                <?php  
                                    if(isset($link_site)) {

                                ?>
                                    <a href="<?php print $link_site ?>" target="_blank">
                                        <?=$image; ?>
                                    </a>
                            
                                <?php

                                    }
                                    else {
                                        echo $image;
                                    }
                            }
                        ?>  
                            </div>                  
                    </div>
                
                </div>

        <?php

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