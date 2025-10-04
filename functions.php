<?php

add_action('wp_enqueue_scripts', function () {

    // Style thème parent
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Style thème enfant
    wp_enqueue_style(
        'alexis-main-style',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['parent-style'],
        wp_get_theme()->get('Version')
    );

    // Script Animation JS
    wp_enqueue_script(
        'alexis-animation-init',
        get_stylesheet_directory_uri() . '/assets/js/animation-init.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );

    // Script JS AJAX
    if (is_post_type_archive('projet')) {
        wp_enqueue_script(
            'projects-ajax',
            get_stylesheet_directory_uri() . '/assets/js/projects-ajax.js',
            ['jquery'],
            '1.0',
            true
        );
        // Données envoyées au JS
        wp_localize_script('projects-ajax', 'ProjectsAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('projects_nonce'),
        ]);
    }
});


// Handler AJAX pour les projets
add_action('wp_ajax_ab_get_projects', 'ab_get_projects');
add_action('wp_ajax_nopriv_ab_get_projects', 'ab_get_projects');

function ab_get_projects() {
    check_ajax_referer('projects_nonce', 'nonce');

    // Récupération des paramètres envoyés en POST
    $cat   = isset($_POST['cat'])   ? sanitize_text_field($_POST['cat'])   : '';
    $ord   = isset($_POST['ord'])   ? sanitize_text_field($_POST['ord'])   : 'date_desc';
    $ppp   = isset($_POST['ppp'])   ? max(1, (int)$_POST['ppp'])          : 4;
    $paged = isset($_POST['paged']) ? max(1, (int)$_POST['paged'])        : 1;

    // Filtre catégorie
    $tax_query = [];
    if ($cat) {
        $tax_query[] = [
            'taxonomy' => 'project_cat',
            'field'    => 'slug',
            'terms'    => [$cat],
        ];
    }

    // Tri choisi
    $orderby = 'date'; $order = 'DESC';
    if ($ord === 'date_asc')   { $orderby = 'date';  $order = 'ASC'; }
    if ($ord === 'title_asc')  { $orderby = 'title'; $order = 'ASC'; }
    if ($ord === 'title_desc') { $orderby = 'title'; $order = 'DESC'; }

    // Requête des projets
    $args = [
        'post_type'      => 'projet',
        'posts_per_page' => $ppp,
        'paged'          => $paged,
        'orderby'        => $orderby,
        'order'          => $order,
    ];
    if ($tax_query) $args['tax_query'] = $tax_query;

    $q = new WP_Query($args);

    // Boucle d’affichage (HTML renvoyé à JS)
    ob_start();
    if ($q->have_posts()) {
        $i = 0;
        while ($q->have_posts()) { 
          $q->the_post(); 
          $i++;

          // Index absolu (inclut pagination)
          $absoluteIndex = ($paged - 1) * $ppp + $i;

          $toneClass = 'bg-tone-' . ((($absoluteIndex - 1) % 5) + 1);
          $sideClass = ($absoluteIndex % 2 === 0) ? 'right' : 'left';?>
            <article class="project <?php echo ($i % 2 == 0) ? 'right' : 'left'; ?> <?php echo esc_attr($toneClass); ?>">
              <div class="project-image floating">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
              </div>
              <div class="project-content">
                <a href="<?php the_permalink(); ?>"><h2 class="title-projects"><?php the_title(); ?></h2></a>
                <?php the_excerpt(); ?>

                <?php // Tags du projet
                $tags_terms = get_the_terms(get_the_ID(), 'project_tag');
                if ($tags_terms && !is_wp_error($tags_terms)) : ?>
                  <ul class="project-tags">
                    <?php foreach ($tags_terms as $tag) : ?>
                      <li class="tag-badge <?php echo 'tag--' . esc_attr($tag->slug); ?>">
                        <?php echo esc_html($tag->name); ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>

                <div class="container-buttons-project">
                  <a href="<?php the_permalink(); ?>" class="btn">Voir le projet</a>
                  <?php if ($github = get_post_meta(get_the_ID(), 'lien_github', true)) : ?>
                    <a href="<?php echo esc_url($github); ?>" class="github-btn" target="_blank" rel="noopener">Code GitHub</a>
                  <?php endif; ?>
                </div>
              </div>
            </article>
        <?php }
        wp_reset_postdata();
    } else {
        echo '<p>Aucun projet trouvé.</p>';
    }
    $items_html = ob_get_clean();

    // Réponse envoyée au JS
    wp_send_json_success([
        'items_html'   => $items_html,
        'current_page' => (int)$paged,
        'max_pages'    => (int)$q->max_num_pages,
    ]);
}
