<?php

get_header(); ?>

<main class="page-projets">
  <h1>Mes projets</h1>

    <section class="project-listing">
    <?php
      $args = array(
        'post_type'      => 'projet',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
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
          <div class="project-image floating">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('large'); ?>
            </a>
          </div>
        <div class="project-content">
           <a href="<?php the_permalink(); ?>"> <h2 class="title-projects"><?php the_title(); ?></h2> </a>
            <?php the_excerpt(); ?>

            <?php
            $tags = get_the_terms(get_the_ID(), 'project_tag');
            if ($tags && !is_wp_error($tags)) : ?>
            <ul class="project-tags">
                <?php foreach ($tags as $tag) : ?>
                <li class="tag-badge <?php echo 'tag--' . esc_attr($tag->slug); ?>"><?php echo esc_html($tag->name); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <div class="container-buttons-project">
                <a href="<?php the_permalink(); ?>" class="btn">Voir le projet</a> 
                <?php
                $github = get_post_meta(get_the_ID(), 'lien_github', true);
                if ($github) : ?>
                <a href="<?php echo esc_url($github); ?>" class="github-btn" target="_blank" rel="noopener">Code GitHub</a>
                <?php endif; ?>
            </div>
        </div>
        </article>
    <?php endwhile; wp_reset_postdata(); ?>
    </section>
    <div class="btn-container">
      <a href="/contact" class="contact-btn">Me contacter</a>
    </div>
</main>

<?php get_footer(); ?>
