<?php
get_header();
?>

<main class="single-project">
  <section class="container">
    <?php
    if (have_posts()) :
      while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>

		<section class="intro-container">
            <div class="texte-intro">
              	<p><em><?php echo the_excerpt(get_the_ID(), 'intro_article', true); ?></em></p>
            </div>
            <div class="image-intro">
			      	<?php the_post_thumbnail('medium', ['class' => 'floating']); ?>
            </div>
        </section>

          <div class="content">
            <?php
              $tags = get_the_terms(get_the_ID(), 'project_tag');
              if ($tags && !is_wp_error($tags)) : ?>
              <ul class="single project-tags">
                  <?php foreach ($tags as $tag) : ?>
                  <li class="tag-badge <?php echo 'tag--' . esc_attr($tag->slug); ?>"><?php echo esc_html($tag->name); ?></li>
                  <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile;
    endif;
    ?>
  </section>
          <div class="single container-buttons-project">
            <a href="/projets" class="single contact-btn">Retour aux projets</a>
            <?php
            $github = get_post_meta(get_the_ID(), 'lien_github', true);
            if ($github) : ?>
            <a href="<?php echo esc_url($github); ?>" class="single github-btn" target="_blank" rel="noopener">Code GitHub</a>
            <?php endif; ?>
          </div>



</main>

<?php get_footer(); ?>
