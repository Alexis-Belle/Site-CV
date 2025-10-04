<?php

get_header();
?>

<main class="page-projets">
  <h1>Mes projets</h1>

  <?php
  // Bloc filtres (catégorie + tri)
  get_template_part('template-parts/project/filters');

  // Récupération des filtres envoyés en GET
  $cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';
  $ord = isset($_GET['ord']) ? sanitize_text_field($_GET['ord']) : 'date_desc';

  // Paramètres initial de la requête
  $ppp   = 4; // Projets Par Page au chargement
  $paged = max(1, (int) get_query_var('paged'));

  $args = [
    'post_type'      => 'projet',
    'posts_per_page' => $ppp,
    'paged'          => $paged,
  ];

  // Filtre catégorie
  if ($cat !== '') {
    $args['tax_query'] = [[
      'taxonomy' => 'project_cat',
      'field'    => 'slug',
      'terms'    => [$cat],
    ]];
  }

  // Tri (date ou titre)
  switch ($ord) {
    case 'date_asc':   $args['orderby'] = 'date';  $args['order'] = 'ASC';  break;
    case 'title_asc':  $args['orderby'] = 'title'; $args['order'] = 'ASC';  break;
    case 'title_desc': $args['orderby'] = 'title'; $args['order'] = 'DESC'; break;
    default:           $args['orderby'] = 'date';  $args['order'] = 'DESC';
  }

  $query = new WP_Query($args);
  ?>

  <section class="project-listing">
    <div id="project-grid" class="projects-grid" data-ppp="<?php echo esc_attr($ppp); ?>">

      <?php if ($query->have_posts()) : ?>
        <?php $i = 0; while ($query->have_posts()) : $query->the_post(); $i++; ?>

          <?php
          // index absolu (prend en compte la pagination pour boucler 1..5)
          $absoluteIndex = ($paged - 1) * $ppp + $i;
          $toneClass = 'bg-tone-' . ((($absoluteIndex - 1) % 5) + 1);
          // alternance gauche/droite
          $sideClass = ($i % 2 === 0) ? 'right' : 'left';
          ?>

          <article class="project <?php echo esc_attr($sideClass . ' ' . $toneClass); ?>">
            <div class="project-image floating">
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large'); ?>
              </a>
            </div>

            <div class="project-content">
              <a href="<?php the_permalink(); ?>">
                <h2 class="title-projects"><?php the_title(); ?></h2>
              </a>

              <?php the_excerpt(); ?>

              <?php
              // Tags
              $tags = get_the_terms(get_the_ID(), 'project_tag');
              if ($tags && !is_wp_error($tags)) : ?>
                <ul class="project-tags">
                  <?php foreach ($tags as $tag) : ?>
                    <li class="tag-badge <?php echo 'tag--' . esc_attr($tag->slug); ?>">
                      <?php echo esc_html($tag->name); ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>

              <div class="container-buttons-project">
                <a href="<?php the_permalink(); ?>" class="btn">Voir le projet</a>
                <?php if ($github = get_post_meta(get_the_ID(), 'lien_github', true)) : ?>
                  <a href="<?php echo esc_url($github); ?>" class="github-btn" target="_blank" rel="noopener">
                    Code GitHub
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </article>

        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <p>Aucun projet trouvé.</p>
      <?php endif; ?>

    </div>

    <?php if ($query->max_num_pages > 1) : ?>
      <div class="projects-pagination">
        <button id="projects-load-more"
                class="btn load-more"
                data-current-page="1"
                data-max-pages="<?php echo (int) $query->max_num_pages; ?>">
          Voir plus
        </button>
      </div>
    <?php endif; ?>
  </section>

  <div class="btn-container">
    <a href="/contact" class="contact-btn">Me contacter</a>
  </div>
</main>

<?php get_footer(); ?>
