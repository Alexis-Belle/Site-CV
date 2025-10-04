<?php

$selected_cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';
$selected_ord = isset($_GET['ord']) ? sanitize_text_field($_GET['ord']) : 'date_desc';
?>

<section class="projects-filters">
  <form id="project-filters" class="project-filters" aria-label="Filtrer les projets" method="get">
    
    <!-- Filtre Catégorie -->
    <div class="filter-block">
      <span class="filter-label">Technologie :</span>
      <ul class="filter-list" data-filter="cat">
        <?php
          // Élément "Toutes"
          $all_active = ($selected_cat === '') ? 'active' : '';
          echo '<li data-value="" class="'.esc_attr($all_active).'">Toutes</li>';

          // Termes de taxonomie project_cat
          $cats = get_terms(['taxonomy' => 'project_cat', 'hide_empty' => true]);
          if (!is_wp_error($cats)) {
            foreach ($cats as $cat) {
              $is_active = ($selected_cat === $cat->slug) ? 'active' : '';
              echo '<li data-value="'.esc_attr($cat->slug).'" class="'.esc_attr($is_active).'">'.esc_html($cat->name).'</li>';
            }
          }
        ?>
      </ul>
    </div>

    <!-- Filtre Tri -->
    <div class="filter-block">
      <span class="filter-label">Trier par :</span>
      <ul class="filter-list" data-filter="ord">
        <?php
          // Fonction pour éviter de répéter la logique d'active
          $tri_options = [
            'date_desc'  => 'Plus récents',
            'date_asc'   => 'Plus anciens',
            'title_asc'  => 'Titre A→Z',
            'title_desc' => 'Titre Z→A',
          ];
          foreach ($tri_options as $value => $label) {
            $is_active = ($selected_ord === $value) ? 'active' : '';
            echo '<li data-value="'.esc_attr($value).'" class="'.esc_attr($is_active).'">'.esc_html($label).'</li>';
          }
        ?>
      </ul>
    </div>

  </form>
</section>
