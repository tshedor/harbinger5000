<div class="row clearfix">
  <div class="large-12 columns">
    <?php Traction::get_image('full'); ?>
  </div>
</div>

<div class="row clearfix">

  <div class="large-4 columns">
    <?php get_sidebar('main-sidebar'); ?>
  </div>

  <div class="large-8 columns">

    <header class="single-header">
      <h1><?php the_title(); ?></h1>
      <div class="byline">By <?php the_author_posts_link(); ?></div>
      <div class="dateline">Posted <?php the_date(); ?></div>
    </header>

    <div <?php post_class('entry'); ?>>
      <?php the_content(); ?>
    </div>

    <div class="meta">
      <?php the_category(); ?>
    </div>

    <?php comments_template(); ?>

  </div>

</div>