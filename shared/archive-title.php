<section class="row clearfix">
  <div class="large-12 columns archive-head">
    <?php if(is_category()) { ?>
      <h1><?php single_cat_title(); Traction::if_paged(); ?></h1>
    <?php } elseif(is_tag()) { ?>
      <h1>Tagged <?php single_tag_title(); Traction::if_paged(); ?></h1>
    <?php } elseif(is_tax()){ $tax =  get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) ?>
      <h1><?php echo $tax->name; Traction::if_paged(); ?></h1>
    <?php } elseif(is_day() || is_month() || is_year()) { ?>
      <h1>From <?php the_time('F j, Y'); Traction::if_paged(); ?></h1>
    <?php } elseif(is_author()) { ?>
      <h1>By <?php the_author_meta('display_name', get_query_var('author')); Traction::if_paged(); ?></h1>
    <?php } ?>
  </div>
</section>
