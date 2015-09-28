<div class="row clearfix">

  <div class="large-8 columns">

    <header class="single-header">
      <h1><?php the_title(); ?></h1>
      <div class="byline">By <?php harbinger_authors(); ?></div>
      <div class="dateline">Posted <?php the_date(); ?></div>
    </header>

    <div <?php post_class('entry'); ?>>
      <?php the_content(); ?>
    </div>

    <div class="meta">
      <?php the_category(); ?>
    </div>

    <ul class="social-single">
      <?php Traction::social_single(false); ?>
    </ul>

    <?php comments_template(); ?>

  </div>

  <div class="large-4 columns">
    <?php get_sidebar('main-sidebar'); ?>
  </div>
</div>
