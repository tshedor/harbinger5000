<?php

$sponsor_meta_fields = array(
array(
  'name'  => 'Website',
  'desc'  => 'Sponsor\'s address on the big WWW (include http://)',
  'id'    => 'sponsor_url',
  'std'   => 'http://bankofprairievillage.com',
  'type'  => 'text',
),

);

$sponsor_meta_info = array(
  'title'     => 'Sponsor Info',
  'post_type' => 'sponsor'
);

new TractionMetaBoxes($sponsor_meta_fields, $sponsor_meta_info);
