<style>
    .inline-button {
        margin-left: 10px;
    }
    .inline-button-top {
        margin-top: 1em;
    }
    .add-button {
        margin-top: 20px;
    }
    td.top {
        vertical-align: top;
    }
    td .group {
        margin-bottom: 10px;
    }
    .form__group {
        display: inline-block;
        vertical-align: top;
        margin-left: 10px;
    }
    .form__group span {
        width: 100%;
        margin-bottom: 5px;
    }
    .form__group input, .form__group select  {
        width: 100%;
    }
    .form__group input.small {
        width: 70%;
    }
    td select {
        width: 25%;
    }
    .user-list {
        padding-left: 10px;
        font-weight: bold;
    }

</style>
<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
        <h1><img src="view/image/module.png" alt=""> <b><?php echo $heading_title ?></b></h1>
        <div class="buttons">
            <a onclick="$('#form').submit();" class="button"><span>Сохранить</span></a>
            <a onclick="location = '/admin/index.php?route=extension/module&token=<?php echo $token ?>';" class="button"><span>Отменить</span></a>
        </div>
    </div>
    <div class="content">
        <form action="/admin/index.php?route=module/redirects&token=<?php echo $token ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
            <tbody>
                    <tr>
                        <td class="left top"><?php echo $entity_yandex_direct ?></td>
                        <td id="yandex_direct_col" class="left">
                            <?php foreach ($redirects_yandex_direct as $key => $data) { ?>
                            <div class="group yandex_direct_group">
                                <div class="form__group">
                                    <span><?php echo $entity_link ?></span>
                                    <input 
                                        type="text" 
                                        name="redirects_yandex_direct[<?php echo $key ?>][link]" 
                                        value="<?php echo htmlspecialchars($data['link'])?>"
                                    />
                                </div>
                                <div class="form__group">
                                    <span><?php echo $entity_users_groups?></span>
                                    <select 
                                        multiple name="redirects_yandex_direct[<?php echo $key ?>][groups][]"
                                    >
                                        <?php foreach ($user_groups as $group) { ?>
                                            <option <?php if (in_array($group, $data['groups'])) echo 'selected'; ?>><?php echo $group ?></option>
                                        <?php } ?>
                                    </select> 
                                </div>
                                <a onclick="removeYandexDirect(event)" class="button inline-button inline-button-top"><?php echo $entity_group_remove ?></a>
                            </div>
                            <?php } ?>
                            <a onclick="addYandexDirect(event)" id="yandex_direct_button_add" class="button add-button"><?php echo $entity_group_add ?></a>
                        </td>
                    </tr>
            </tbody>
            <tbody>
                    <tr>
                        <td class="left top"><?php echo $entity_redirect_users ?></td>
                        <td id="redirects_users_col" class="left">
                            <?php foreach ($redirects_users as $key => $data) { ?>
                            <div class="group redirects_users_group">
                                <div class="form__group">
                                    <span><?php echo $entity_link ?></span>
                                    <input 
                                        type="text" 
                                        name="redirects_users[<?php echo $key ?>][link]" 
                                        value="<?php echo htmlspecialchars($data['link'])?>"
                                    />
                                </div>
                                <div class="form__group">
                                    <span><?php echo $entity_users_ids ?></span>
                                    <?php
                                        $list = array();
                                        foreach ($data['users'] as $user) {
                                            $list[] = $user;
                                        }
                                    ?>
                                    <input type="text" value="<?php echo implode(', ', $list)?>" name="redirects_users[<?php echo $key ?>][users]"/>
                                </div>
                                <a onclick="removeRedirectsUsers(event)" class="button inline-button inline-button-top"><?php echo $entity_group_remove ?></a>
                            </div>
                            <?php } ?>
                            <a onclick="addRedirectsUsers(event)" id="redirects_users_button_add" class="button add-button"><?php echo $entity_group_add ?></a>
                        </td>
                    </tr>
            </tbody>
        </table>
        </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script>
    var user_groups = <?php echo json_encode($user_groups) ?>;
    // Yandex
    function addYandexDirect() {
        var count = $('#yandex_direct_col').find('.yandex_direct_group').length;
        var options = [];
        user_groups.forEach(function(group) {
            options.push('<option>' + group + '</option>');
        });
        var name = 'redirects_yandex_direct[' + count + ']';
        var html = [
            '<div class="group yandex_direct_group">',
            '<div class="form__group"><span><?php echo $entity_link?></span><input type="text" name="' + name + '[link]" value=""/></div>',
            '<div class="form__group"><span><?php echo $entity_users_groups?></span><select multiple name="' + name + '[groups][]">',
            options.join('\n'),
            '</select></div>',
            '<a onclick="removeYandexDirect(event)" class="button inline-button inline-button-top"><?php echo $entity_group_remove ?></a>',
            '</div>'
        ];
        $('#yandex_direct_button_add').before(html.join(''));
    }
    function removeYandexDirect(event) {
        $(event.target).parents('.yandex_direct_group').remove();
    }
    // Users
    function addRedirectsUsers() {
        var count = $('#redirects_users_col').find('.redirects_users_group').length;
        var name = 'redirects_users[' + count + ']';
        var html = [
            '<div class="group redirects_users_group">',
            '<div class="form__group"><span><?php echo $entity_link?></span><input type="text" name="' + name + '[link]" value=""/></div>',
            '<div class="form__group"><span><?php echo $entity_users_ids ?></span>',
            '<input type="text" name="' + name + '[users]"/>',
            '</div>',
            '<div class="form__group"><span class="user-list"></span></div>',
            '<a onclick="removeRedirectsUsers(event)" class="button inline-button inline-button-top"><?php echo $entity_group_remove ?></a>',
            '</div>'
        ];
        $('#redirects_users_button_add').before(html.join(''));
    }
    function removeRedirectsUsers(event) {
        $(event.target).parents('.redirects_users_group').remove();
    }
</script>