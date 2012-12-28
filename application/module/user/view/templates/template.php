
<?php include FRONT_VIEW_PATH . 'templates/header.php'; ?>
<div class='zx-front-main'>
    <div class="zx-front-left">
        <div class="zx-front-user-menu">
            <?php include 'menu.php'; ?>
        </div>
        <?php echo $content; ?>
    </div>
</div>
<div class="zx-front-clear-both"></div>
<?php include FRONT_VIEW_PATH . 'templates/footer.php'; ?>
