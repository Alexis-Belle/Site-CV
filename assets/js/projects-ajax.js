(function ($) {
  const $form = $('#project-filters');
  const $grid = $('#project-grid');
  const $loadMore = $('#projects-load-more');

  if (!$grid.length) return;

  // Lis l'état des filtres
  function getParams() {
    return {
      cat: $('.filter-list[data-filter="cat"]  li.active').data('value') || '',
      ord: $('.filter-list[data-filter="ord"] li.active').data('value') || 'date_desc'
    };
  }

  // Injecte le HTML + gérer "Voir plus"
  function render(payload, append) {
    const $items = $(payload.items_html);

    if (append) $grid.append($items);
    else $grid.html($items);

    $items.addClass('visible');

    if ($loadMore.length) {
      if (payload.current_page >= payload.max_pages) {
        $loadMore.hide();
      } else {
        $loadMore
          .show()
          .attr('data-current-page', payload.current_page)
          .attr('data-max-pages', payload.max_pages);
      }
    }
  }

  // Appel AJAX
  function fetchProjects(params, append) {
    $.post(ProjectsAjax.ajax_url, {
      action: 'ab_get_projects',
      nonce: ProjectsAjax.nonce,
      cat: params.cat,
      ord: params.ord,
      ppp: $grid.data('ppp') || 4,
      paged: params.paged || 1
    }).done(function (res) {
      if (res && res.success) {
        render(res.data, !!append);
      }
    });
  }

  // Empêche un submit classique
  $form.on('submit', function (e) { e.preventDefault(); });

  // Clic sur un filtre = recharge de page 1
  $form.on('click', '.filter-list li', function () {
    const $li = $(this);
    const $ul = $li.closest('.filter-list');

    $ul.find('li').removeClass('active');
    $li.addClass('active');

    const params = getParams();
    params.paged = 1;
    fetchProjects(params, false);
  });

  // 5) "Voir plus" = charge la page suivante
  $loadMore.on('click', function (e) {
    e.preventDefault();
    const current = parseInt($(this).attr('data-current-page') || '1', 10);
    const params = getParams();
    params.paged = current + 1;
    fetchProjects(params, true);
  });

})(jQuery);
