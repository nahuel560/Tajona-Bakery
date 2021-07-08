<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<h4 class="product_title entry-title">
    <a title="<?php the_title_attribute() ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h4>
