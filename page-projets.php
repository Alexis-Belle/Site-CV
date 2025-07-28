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
    <?php
      $toneClass = 'bg-tone-' . (($i - 1) % 5 + 1); // boucle entre 1 et 5
    ?>
    <article class="project <?php echo ($i % 2 == 0) ? 'right' : 'left'; ?> <?php echo $toneClass; ?>">
        <div class="project-image">
            <a href="<?php the_permalink(); ?>">
              <div class="project-image floating">
                <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail('large'); ?>
                </a>
              </div>
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
    <div class="btn-container">
      <a href="/contact" class="contact-btn">Me contacter</a>
    </div>
</main>

<?php get_footer(); ?>
