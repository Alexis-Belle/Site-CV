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
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile;
    endif;
    ?>
  </section>
    <div class="btn-container">
      <a href="/projets" class="contact-btn">Retour aux projets</a>
    </div>
</main>

<?php get_footer(); ?>
