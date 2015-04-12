<?php
/*
Template Name: Style Guide
*/
get_header(); if(have_posts()) : while(have_posts()) : the_post(); ?>
<?php global $a; ?>
<div class="row clearfix">
	<div class="large-12 columns">
		<div class="lg-margin text-center">
			<h1 class="single-title"><?php the_title(); ?></h1>
		</div>
		<div <?php post_class('entry clearfix'); ?>>
			<?php the_content(); ?>
			<h2>Colors</h2>
			<div class="row">
				<div class="large-4 columns">
					<div class="color-block dom-color"></div>
				</div>
				<div class="large-4 columns">
					<div class="color-block sub-color"></div>
				</div>
				<div class="large-4 columns">
					<div class="color-block ter-color"></div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-12 columns">
					<h2>Forms</h2>
					<hr />
				</div>
			</div>
			<form class="row clearfix">
				<div class="large-6 columns">
					<h4>Text</h4>
						<input type="text" placeholder="Text" />
					<h4>Text Prepent</h4>
						<div class="row clearfix">
							<div class="prefix large-3 columns">Prefix</div>
								<input type="text" class="attached-input large-9 columns" />
						</div>
					<h4>Radio</h4>
					<div class="inline-radio">
						<label>Radio
							<input type="radio" name="radio" />
						</label>
						<label>Radio 2
							<input type="radio" name="radio" />
						</label>
						<label>Radio 3
							<input type="radio" name="radio" />
						</label>
					</div>
					<h4>Checkbox</h4>
					<div class="inline-radio">
						<label>Checkbox
							<input type="checkbox" name="checkbox" />
						</label>
						<label>Checkbox 2
							<input type="checkbox" name="checkbox" />
						</label>
						<label>Checkbox 3
							<input type="checkbox" name="checkbox" />
						</label>
					</div>
					<h4>Textarea</h4>
						<textarea placeholder="Textarea"></textarea>
					<h4>Select</h4>
						<select>
							<option>Option 1</option>
							<option>Option 1</option>
							<option>Option 1</option>
							<option>Option 1</option>
						</select>
					<h4>Speech Input</h4>
						<input type="text" x-webkit-speech />
					<h4>File Upload</h4>
						<input type="file" />

				</div>
				<div class="large-6 columns">
					<h4>Password</h4>
						<input type="password" />
					<h4>Range</h4>
						<input type="range" name="range" />
					<h4>Color</h4>
						<input type="color" />
					<h4>Number</h4>
						<input type="number" />
					<h4>Date</h4>
						<input type="date" />
					<h4>Submit</h4>
						<input type="submit" value="Button" />
				</div>
			</form>
			<div class="row clearfix">
				<div class="large-12 columns">
					<h2>Content and Type</h2>
					<hr />
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<h1>Heading 1</h1>
					<h2>Heading 2</h2>
					<h3>Heading 3</h3>
					<h4>Heading 4</h4>
					<h5>Heading 5</h5>
				</div>
				<div class="large-6 columns">
					<div class="font-block font-display">Display Font</div>
					<div class="font-block font-sub">Sub Font</div>
					<div class="font-block font-cond">Condensed Font</div>
					<div class="font-block font-body">Body Font</div>
					<div class="font-block font-light">Light Font</div>
					<hr />
					<h4>Paragraph</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem. Suspendisse est arcu, pretium a commodo nec, placerat ac metus. Praesent non ipsum lorem. Quisque dolor nulla, pulvinar eu orci ut, mollis malesuada ligula. Nam eu elit nec erat mollis mattis sed sit amet nunc. Cras tincidunt ligula id vestibulum vehicula. Vestibulum ornare nisl a enim mattis, in dignissim felis consectetur. Vestibulum sed feugiat diam.</p>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-4 columns">
					<h4>Bold</h4>
					<p>Lorem ipsum <strong>dolor sit amet</strong></p>
					<h4>Italic</h4>
					<p>Lorem ipsum <em>dolor sit amet</em></p>
					<h4>Underline</h4>
					<p>Lorem ipsum <span style="text-decoration:underline">dolor sit amet</span></p>
				</div>
				<div class="large-4 columns">
					<h4>Link</h4>
					<p>Lorem ipsum <a href="#" title="And a title">dolor sit amet</a></p>
					<h4>Strikethrough</h4>
					<p>Lorem ipsum <span style="text-decoration:line-through">dolor sit amet</span></p>
					<h4>Small</h4>
					<p>Lorem ipsum <small>dolor sit amet</small></p>
				</div>
				<div class="large-4 columns">
					<h4>Sup</h4>
					<p>Lorem ipsum <sup>dolor sit amet</sup></p>
					<h4>Sub</h4>
					<p>Lorem ipsum <sub>dolor sit amet</sub></p>
					<h4>Code</h4>
					<p>Lorem ipsum <code>dolor sit amet</code></p>
				</div>
			</div>

			<div class="row">
				<div class="large-6 columns">
					<h4>Quote (blockquote)</h4>
					<blockquote>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem. Suspendisse est arcu, pretium a commodo nec, placerat ac metus. Praesent non ipsum lorem. Quisque dolor nulla, pulvinar eu orci ut, mollis malesuada ligula. Nam eu elit nec erat mollis mattis.
						<cite>Quote Source</cite>
					</blockquote>
				</div>
				<div class="large-3 columns">
					<h4>Unordered List</h4>
					<ul>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
					</ul>
				</div>
				<div class="large-3 columns">
					<h4>Ordered List</h4>
					<ol>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
						<li>List Item</li>
					</ol>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<h4>Table</h4>
					<table>
						<tbody>
							<tr>
								<th>Heading</th>
								<th>Heading</th>
								<th>Heading</th>
							</tr>
							<tr>
								<td>Label</td>
								<td>Much longer and more descriptive attribute</td>
								<td>Secondary label</td>
							</tr>
							<tr>
								<td>Label</td>
								<td>Much longer and more descriptive attribute</td>
								<td>Secondary label</td>
							</tr>
							<tr>
								<td>Label</td>
								<td>Much longer and more descriptive attribute</td>
								<td>Secondary label</td>
							</tr>
							<tr>
								<td>Label</td>
								<td>Much longer and more descriptive attribute</td>
								<td>Secondary label</td>
							</tr>
							<tr>
								<td>Label</td>
								<td>Much longer and more descriptive attribute</td>
								<td>Secondary label</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="large-6 columns">
					<h4>Code</h4>
<pre>
<code class="language-css">.dollar-bill {
  color:#3d3d3d;
  background:green;
}</code>
</pre>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-12 columns">
					<h2>UI</h2>
					<hr />
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<h4>Buttons</h4>
					<a href="#" class="button">Regular button</a>
					<h4>Labels</h4>
					<span class="label">Regular label</span> <span class="label info">Info label</span> <span class="label success">Success label</span> <span class="label error">Warning label</span>
					<h4>Breadcrumbs</h4>
					<div class="breadcrumbs">
						<a href="#">Breadcrumb one</a>
						<a href="#">Breadcrumb two</a>
						<a href="#">Breadcrumb three</a>
						<a href="#" class="current-item">Current page</a>
					</div>
				</div>
				<div class="large-6 columns">
					<h4>Alerts</h4>
					<div class="alert sm-margin">Regular alert with text</div>
					<div class="alert sm-margin info">Info alert with text</div>
					<div class="alert sm-margin success">Success alert with text</div>
					<div class="alert sm-margin error">Error alert with text</div>
					<h4>Pagination</h4 >
					<div class="page-navigation">
						<span class="page-numbers current">1</span>
						<a href="#" class="page-numbers">2</a>
						<a href="#" class="page-numbers">3</a>
						<a href="#" class="page-numbers">4</a>
						<a href="#" class="next page-numbers">&nbsp;Next&nbsp;&raquo;</a>
					</div>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-12 columns">
					<h2>Shortcodes</h2>
					<hr />
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-12 columns">
					<h3>Content Supplements</h3>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-4 columns">
					<pre><code class="language-shortcodes">[inline_text title="More" align="(left,right,center,none)"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem....[/inline_text]</code></pre>
					<?php echo do_shortcode('[inline_text title="More" align="left"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem. Suspendisse est arcu, pretium a commodo nec, placerat ac metus. Praesent non ipsum lorem. Quisque dolor nulla, pulvinar eu orci ut, mollis malesuada ligula. Nam eu elit nec erat mollis mattis.[/inline_text]') ?>
				</div>
				<div class="large-4 columns">
					<pre><code class="language-shortcodes">[sibling post="1" description="Your first Wordpress post" media="(video,audio,gallery)" align="(left,right,center,none)" /]</code></pre>
					<?php echo do_shortcode('[sibling post="1" description="Your first Wordpress post" media="(video,audio,gallery)" align="(left,right,center,none)" /]') ?>
				</div>

				<div class="large-4 columns">
					<pre><code class="language-shortcodes">[contact email="youremail@example.com" label="Get in Touch" /]</code></pre>
					<?php echo do_shortcode('[contact email="youremail@example.com" label="Get in Touch" /]'); ?>
				</div>
			</div>
			<div class="row md-margin clearfix">
				<div class="large-12 columns">
					<h3>Tabs</h3>
				</div>
			</div>
			<div class="row clearfix sm-margin">
				<div class="large-7 large-centered columns">
<pre><code class="language-shortcodes">[tabbed type="(vertical, horizontal)"]
  [tab name="Tab 1"]Tab one content would appear here[/tab]
  [tab name="Tab 2"]Tab two content would appear here[/tab]
  [tab name="Tab 3"]Tab three content would appear here[/tab]
[/tabbed]
</code></pre>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<h5>Vertical</h5>
					<?php echo do_shortcode('[tabbed type="vertical"][tab name="Tab 1" ]Tab one content would appear here[/tab][tab name="Tab 2"]Tab two content would appear here[/tab][tab name="Tab 3" ]Tab three content would appear here[/tab][/tabbed]') ?>
				</div>
				<div class="large-6 columns">
					<h5>Horizontal</h5>
					<?php echo do_shortcode('[tabbed type="horizontal"][tab name="Tab 1" ]Tab one content would appear here[/tab][tab name="Tab 2"]Tab two content would appear here[/tab][tab name="Tab 3" ]Tab three content would appear here[/tab][/tabbed]') ?>
				</div>
			</div>
			<div class="row clearfix md-margin">
				<div class="large-12 columns">
					<h2>Code</h2>
				</div>
			</div>
			<div class="row clearfix sm-margin">
				<div class="large-8 large-centered columns">
<pre><code class="language-shortcodes">[example just_code="(false, true)" lang="(markup, css, javascript, php)"]
  &lt;div>
    &lt;p style="color:green">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem. Suspendisse est arcu, pretium a commodo nec, placerat ac metus. Praesent non ipsum lorem.&lt;/p>
  &lt;/div>
[/example]
</code></pre>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<h4>Just_code="false"</h4>
<?php echo do_shortcode('[example just_code="false" lang="markup"]<div>
    <p style="color:green">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem. Suspendisse est arcu, pretium a commodo nec, placerat ac metus. Praesent non ipsum lorem.</p>
</div>[/example]') ?>
				</div>
				<div class="large-6 columns">
					<h4>Just_code="true"</h4>
<?php echo do_shortcode('[example just_code="true" lang="markup"]<div>
    <p style="color:green">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar tellus lorem. Suspendisse est arcu, pretium a commodo nec, placerat ac metus. Praesent non ipsum lorem.</p>
</div>[/example]') ?>
				</div>
			</div>
			<div class="row clearfix md-margin">
				<div class="large-12 columns">
					<h3>Alerts and Labels</h3>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<pre class="language-shortcodes"><code>[label name="Heads Up" type="(bonus/success, tip/info, error/danger)"]Label paragraph (opt.)[/label]</code></pre>
				</div>
				<div class="large-6 columns">
					<pre class="language-shortcodes"><code>[alert type="(bonus/success, tip/info, error/danger)"]Alert content[/alert]</code></pre>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-6 columns">
					<div class="sm-margin">
						<h4>Default</h4>
						<?php echo do_shortcode('[label name="Heads Up"]Custom label[/label]'); ?>
					</div>
					<div class="sm-margin">
						<h4>Info/Tip</h4>
						<?php echo do_shortcode('[label name="Tip" type="tip"]Custom label (tip)[/label]'); ?>
					</div>
					<div class="sm-margin">
						<h4>Bonus/Success</h4>
						<?php echo do_shortcode('[label name="Bonus" type="bonus"]Custom label (bonus)[/label]'); ?>
					</div>
					<div class="sm-margin">
						<h4>Error/Danger</h4>
						<?php echo do_shortcode('[label name="Danger" type="danger"]Custom label (danger)[/label]'); ?>
					</div>
				</div>
				<div class="large-6 columns">
					<div class="sm-margin">
						<h4>Default</h4>
						<?php echo do_shortcode('[alert]Custom alert[/alert]'); ?>
					</div>
					<div class="sm-margin">
						<h4>Info/Tip</h4>
						<?php echo do_shortcode('[alert type="info"]Custom alert (info)[/alert]'); ?>
					</div>
					<div class="sm-margin">
						<h4>Bonus/Success</h4>
						<?php echo do_shortcode('[alert type="success"]Custom alert (success)[/alert]'); ?>
					</div>
					<div class="sm-margin">
						<h4>Error/Danger</h4>
						<?php echo do_shortcode('[alert type="error"]Custom alert (error)[/alert]'); ?>
					</div>
				</div>
			</div>
			<div class="row clearfix md-margin">
				<div class="large-12 columns">
					<h3>Sitemap</h3>
				</div>
			</div>
			<div class="row clearfix">
				<div class="large-4 columns">
					<pre><code class="language-shortcodes">[sitemap pages="true" categories="true" tags="true" /]</code></pre>
				</div>
				<div class="large-8 columns">
					<?php echo do_shortcode('[sitemap pages="true" categories="true" tags="true" /]') ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php endwhile; else :
	get_template_part('inc/ts-stds/loop', 'error');
endif;
get_footer();