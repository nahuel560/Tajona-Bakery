<?php
$header_classes = array(
    'g5core-header-desktop-wrapper',
    'sticky-area'
);
?>
<div class="<?php echo join( ' ', $header_classes ) ?>">
    <div class="container">
        <div class="g5core-header-inner">
            <div class="width-50 d-flex">
            <?php G5CORE()->get_template( 'header/desktop/menu.php', array(
                'classes' => 'content-fill content-left',
                'menu_class' => 'main-menu menu-horizontal'
            ) ); ?>
            </div>
            <?php G5CORE()->get_template( 'header/desktop/logo.php', array('classes' => 'logo-center') ); ?>
            <div class="width-50 content-right d-flex">
            <?php G5CORE()->get_template( 'header/customize.php', array(
                'type'     => 'desktop',
                'location' => 'after_logo',
                'classes' => ''
            ) ); ?>
            </div>
        </div>
    </div>
</div>