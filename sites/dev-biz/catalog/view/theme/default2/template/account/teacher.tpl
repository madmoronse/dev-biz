<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <h1 style="overflow: auto;">
        <?php echo "Мои ученики"; ?>
    </h1>
    <?php if ($students) { ?>
    <div class="wrapper">
        <table class="list students_table">
            <thead>
            <tr>
                <td class="left">Ф.И.О.</td>
                <td class="left">Email</td>
                <td class="left">Телефон</td>
                <td class="left">Заказов</td>
                <td class="left">Сумма заказов</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $student) { ?>
            <tr>
                <td class="left"><?php echo $student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']; ?></td>
                <td class="left"><?php echo $student['email']; ?></td>
                <td class="left"><?php echo $student['phone']; ?></td>
                <td class="left"><?php echo $student['totalOrder']; ?></td>
                <td class="left"><?php echo $student['totalSum']; ?></td>
            </tr>


            <?php } ?>
            </tbody></table> </div>
    <div class="pagination"><?php echo $pagination; ?></div>

    <?php } else { ?>
    <div class="content"><?php echo $text_tracks_empty; ?></div>
    <?php } ?>
    <div class="buttons">
        <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
    </div>
    <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>