<?php 
	//Name: Thumbnail Gallery;
	//Description: Displays a gallery of up to 12 items;
	//Author: Jeremy Boggs; 
?>

<div class="gallery-thumbnails-text-bottom">
<div class="primary">
	<?php echo display_exhibit_thumbnail_gallery(1, 12, array('class'=>'permalink')); ?>
	
	<div class="secondary">
	<div class="exhibit-text">
	<?php echo page_text(1); ?>
	</div>
	</div>

</div>
</div>
