<div class="box">
  <?php if ($title_status) { ?>
  <div class="box-heading" style="<?php echo $title_style; ?>"><?php echo $heading_title; ?></div>
  <?php } ?>
  <div class="box-content">
    <ul class="<?php echo $style; ?>-category <?php echo $position; ?>">
      <?php foreach ($categories as $category) { ?>
      <?php if ($category['category_id'] == $category_id) { ?>
      <li class="category-<?php echo $category['category_id']; ?> active" style="border-left: 1px solid rgb(170, 170, 170);border-bottom: 2px solid rgb(210, 210, 210);"><a href="<?php echo $category['href']; ?>" class="category-<?php echo $toggle; ?> active"><span></span><?php echo $category['name']; ?></a>
        <?php } else { ?>
      <li class="category-<?php echo $category['category_id']; ?>" style="border-left: 1px solid rgb(170, 170, 170);border-bottom: 2px solid rgb(210, 210, 210);"><a href="<?php echo $category['href']; ?>" class="category-<?php echo $toggle; ?>"><span></span><?php echo $category['name']; ?></a>
        <?php } ?>
        <?php if ($category['children']) { ?>
        <a href="<?php echo $category['href']; ?>" class="toggle-<?php echo $toggle; ?>">&nbsp;</a>
        <ul>
          <?php if ($category['thumb'] && $image && $category['menu_image']) { ?>
          <li class="image"><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>"></a></li>
          <?php } ?>
          <?php foreach ($category['children'] as $child) { ?>
          <?php if ($child['category_id'] == $child_id) { ?>
          <li class="category-<?php echo $child['category_id']; ?> active"><a href="<?php echo $child['href']; ?>" class="category-<?php echo $toggle; ?> active"><span></span><?php echo $child['name']; ?></a>
            <?php } else { ?>
          <li class="category-<?php echo $child['category_id']; ?>"><a href="<?php echo $child['href']; ?>" class="category-<?php echo $toggle; ?>"><span></span><?php echo $child['name']; ?></a>
            <?php } ?>
            <?php if($child['child2_id']){ ?>
            <a href="<?php echo $child['href']; ?>" class="toggle-<?php echo $toggle; ?>">&nbsp;</a>
            <ul>
              <?php if ($child['thumb'] && $image && $child['menu_image']) { ?>
              <li class="image"><a href="<?php echo $child['href']; ?>" title="<?php echo $child['name']; ?>"><img src="<?php echo $child['thumb']; ?>" alt="<?php echo $child['name']; ?>"></a></li>
              <?php } ?>
              <?php foreach ($child['child2_id'] as $child2) { ?>
              <?php if ($child2['category_id'] == $child2_id) { ?>
              <li class="category-<?php echo $child2['category_id']; ?> active"><a href="<?php echo $child2['href']; ?>" class="category-<?php echo $toggle; ?> active"><span></span><?php echo $child2['name']; ?></a>
                <?php } else { ?>
              <li class="category-<?php echo $child2['category_id']; ?>"><a href="<?php echo $child2['href']; ?>" class="category-<?php echo $toggle; ?>"><span></span><?php echo $child2['name']; ?></a>
                <?php } ?>
                <?php if($child2['child3_id']){ ?>
                <a href="<?php echo $child2['href']; ?>" class="toggle-<?php echo $toggle; ?>">&nbsp;</a>
                <ul>
                  <?php if ($child2['thumb'] && $image && $child2['menu_image']) { ?>
                  <li class="image"><a href="<?php echo $child2['href']; ?>" title="<?php echo $child2['name']; ?>"><img src="<?php echo $child2['thumb']; ?>" alt="<?php echo $child2['name']; ?>"></a></li>
                  <?php } ?>
                  <?php foreach ($child2['child3_id'] as $child3) { ?>
                  <?php if ($child3['category_id'] == $child3_id) { ?>
                  <li class="category-<?php echo $child3['category_id']; ?> active"><a href="<?php echo $child3['href']; ?>" class="active"><span></span><?php echo $child3['name']; ?></a></li>
                  <?php } else { ?>
                  <li class="category-<?php echo $child3['category_id']; ?>"><a href="<?php echo $child3['href']; ?>"><span></span><?php echo $child3['name']; ?></a></li>
                  <?php } ?>
                  <?php } ?>
                </ul>
                <?php } ?>
              </li>
              <?php } ?>
            </ul>
            <?php } ?>
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
      </li>
      <?php } ?>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
      <li class="manufacturer-<?php echo $manufacturer['manufacturer_id']; ?> active"><a href="<?php echo $manufacturer['href']; ?>" class="active"><?php echo $manufacturer['name']; ?></a></li>
      <?php } else { ?>
      <li class="manufacturer-<?php echo $manufacturer['manufacturer_id']; ?>"><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
      <?php foreach ($informations as $information) { ?>
      <?php if ($information['information_id'] == $information_id) { ?>
      <li class="information-<?php echo $information['information_id']; ?> active"><a href="<?php echo $information['href']; ?>" class="active"><?php echo $information['title']; ?></a></li>
      <?php } else { ?>
      <li class="information-<?php echo $information['information_id']; ?>"><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
      <?php } ?>
      <?php foreach ($custom_links as $custom_link) { ?>
      <li><a href="<?php echo $custom_link['href']; ?>"><?php echo $custom_link['link_title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
</div>
