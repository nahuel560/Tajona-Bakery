<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $archive_banner_type
 */
?>
<div class="g5shop__archive-banner">
    <?php G5SHOP()->get_template("banner/{$archive_banner_type}.php"); ?>
</div>
