<?php

get_header(); ?>

<main class="page-projets">
  <h1>Mes projets</h1>

    <section class="project-listing">
    <?php
        $args = array(
        'post_type' => 'post',
        'category_name' => 'projets',
        'orderby' => 'date',
        'order' => 'DESC'
        );
        $query = new WP_Query($args);
        $i = 0;
        while ($query->have_posts()) : $query->the_post();
        $i++;
    ?>
        <article class="project <?php echo ($i % 2 == 0) ? 'right' : 'left'; ?>">
        <div class="project-image">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('large'); ?>
            </a>
        </div>
        <div class="project-content">
           <a href="<?php the_permalink(); ?>"> <h2><?php the_title(); ?></h2> </a>
            <p><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn">Voir le projet</a>
        </div>
        </article>
    <?php endwhile; wp_reset_postdata(); ?>
    </section>


</main>

<?php get_footer(); ?>
