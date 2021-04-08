<div class="box">
  <div class="box-heading category-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <ul class="box-category">
      <?php foreach ($categories as $category) { ?>
      <li>
        <?php if ($category['children']) { ?>
					<?php if ($category['category_id'] == $category_id) { ?>
						<a class="active with-child" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
					<?php } else { ?>
						<a class="with-child" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
					<?php } ?>
					<ul>
						<?php foreach ($category['children'] as $child) { ?>
						<li>
							<?php if ($child['category_id'] == $child_id) { ?>
								<a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>
							<?php } else { ?>
								<a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
							<?php } ?>
						</li>
						<?php } ?>
					</ul>
        <?php } else {?>
					<?php if ($category['category_id'] == $category_id) { ?>
						<a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
					<?php } else { ?>
						<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
					<?php } ?>
				<?php } ?>
      </li>
      <?php } ?>
    </ul>
  </div>
</div>
