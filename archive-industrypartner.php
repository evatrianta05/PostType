<?php
/*
* Template Name: Industry Partner
* Xtreme Name: Industry Partner
*/
get_header();

$die_laender = array( 'OEM', 'SOLUTION-PROVIDERS', 'DISTRIBUTORS');
$die_laender_boxen = '';

//check ob auswahl vorhanden
$das_uebergebene_land = false;
if ( get_query_var( 'country' ) && is_string( get_query_var( 'country' ) ) ) {
	$das_uebergebene_land = sanitize_text_field( get_query_var( 'country' ) );
}

$ist_aktiv = '';
foreach ( $die_laender as $das_land) {
	$ist_aktiv = ( $das_uebergebene_land === $das_land ? 'Active' : ' ' ); //css style für aktuelles land
	$die_laender_boxen .= '
			<div class="filter-btn-white' . $ist_aktiv .  ' " data-filter=".' . $das_land . '" data-filter=".' . $das_land . '"><a href="#">' . $das_land . '</a></div>

	';
}

?>

<script>
// external js: isotope.pkgd.js
jQuery(document).ready(function( $ ) {
  // init Isotope
  $(window).load(function() {
	  var $grid = $('.grid').isotope({
		itemSelector: '.element-item',
		layoutMode: 'fitRows'
	  });
	  // filter functions
	  var filterFns = {
		// show if number is greater than 50
		numberGreaterThan50: function() {
		  var number = $(this).find('.number').text();
		  return parseInt( number, 10 ) > 50;
		},
		// show if name ends with -ium
		ium: function() {
		  var name = $(this).find('.name').text();
		  return name.match( /ium$/ );
		}
	  };
	  // bind filter button click
	  $('.filters-button-group').on( 'click', 'a', function() {
		var filterValue = $( this ).parent().attr('data-filter');
		// use filterFn if matches value
		filterValue = filterFns[ filterValue ] || filterValue;
		$grid.isotope({ filter: filterValue });
	  });
	  // change is-checked class on buttons
	  $('.button-group').each( function( i, buttonGroup ) {
		var $buttonGroup = $( buttonGroup );
		$buttonGroup.on( 'click', 'a', function() {
		  $buttonGroup.find('.filter-btn-white').removeClass('filter-btn-white-active');
		  $( this ).parent().addClass('filter-btn-white-active');
		});
	  });
  });
});
</script>

<div class="slim-hero-container industry-partner">
  <div class="container">
    <div class="row">
      <div class="6u fn-736">
        <h1 class="white-font">Industry Partners</h1>
        <p class="intro white-font">Wikitude has a track record of establishing partnerships with some of the largest names in the industry. Explore the logos below and follow the external links to learn more about some of these selected partnerships and projects.</p>
      </div>
    </div>
  </div>
</div>
<div class="" style="background: #01122c; top: 19px; position: relative;">
  <div class="container">
    <div class="row pb25">
    	<div class="button-group filters-button-group oh">
    		<div class="filter-btn-white" data-filter="*"><a href="#">ALL</a></div>
			<?php echo $die_laender_boxen; ?>
    	</div>
    </div>
    <div>
		<?php
		if ( isset( $das_uebergebene_land ) && $das_uebergebene_land ) {
			query_posts( array( 
				'post_type' => 'industrypartner', 
				'showposts' => 51,
				'meta_key' => 'ecpt_ipcompanycountry',
				'meta_value' => $das_uebergebene_land,
			    'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1)
				) 
			);
		} else {
			query_posts( array( 
				'post_type' => 'industrypartner', 
				'showposts' => 51,
			    'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1)
				) 
			);
		}	
			 
		if ( have_posts() ) :
		    do_action('xtreme_before_loop');
			?>
		    <div class="grid">
		    <?php
		    while ( have_posts() ) : the_post();
			//print_r(get_post_custom_values('ecpt_ipcompanycountry'));
		        do_action('xtreme_before_post');
		        if ( !xtreme_is_html5() ) : ?>
				<div class="post page" id="post-<?php the_ID() ?>">
					<?php xtreme_post_subtitle('h3') ?>
					<?php xtreme_post_headline( 'h4', false ) ?>
				        <?php else : ?>
					<article class="post page card-3u element-item <?php echo do_shortcode('[custom_field field="ecpt_ipcompanycountry" this_post="1" /]'); ?>" id="post-<?php the_ID() ?>" data-category="<?php echo do_shortcode('[custom_field field="ecpt_ipcompanycountry" this_post="1" /]'); ?>">
						<?php if ( current_theme_supports('xtreme-subtitles') ) : ?>
						<hgroup>
						<?php xtreme_post_subtitle('h3') ?>
						<?php endif; ?>
				        <div class="card-thumbnail" style="background-color: white; max-height:181px">
						<?php
							if(has_post_thumbnail()) {                    
				    			$image_src = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
				    			echo '<img src="' . $image_src[0]  . '" width="100%"  />';
							}	 
						?>
						<div class="card-overlay">
							<?php xtreme_post_headline( 'h3', false ) ?>
					        <div class="company-country"><?php echo do_shortcode('[custom_field field="ecpt_ipcompanycountry" this_post="1" /]'); ?></div>
							<?php if ( current_theme_supports('xtreme-subtitles') ) : ?>
							</hgroup>
							<?php endif; ?>
					        <?php endif ?>
				         
						    <div class="entry-content">
						    <?php xtreme_excerpt() ; ?> 
						    <?php wp_link_pages( array( 'before' => '<p><strong>' . __( 'Pages:', XF_TEXTDOMAIN ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'next' ) ) ?>
						    <?php //edit_post_link( __( 'Edit', XF_TEXTDOMAIN ), '<p>', '</p>' ) ?>
						    </div>
						</div>
						<p class="card-link"><a href="<?php echo do_shortcode('[custom_field field="ecpt_ipcompanyurl" this_post="1" /]'); ?>" rel="bookmark"></a></p>
				        </div>
					<?php if ( !xtreme_is_html5() ) echo '</div>'; else echo '</article>';
				        do_action('xtreme_after_post');
				    endwhile;
					?>
				</div>
	    <?php
	    do_action('xtreme_after_loop');
		if (  $wp_query->max_num_pages > 1 ) {
			xtreme_post_pagination();
		}
		endif;
		?>
	</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>
<?php
get_footer();
?>
