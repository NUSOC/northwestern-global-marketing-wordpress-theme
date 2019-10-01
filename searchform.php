<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
    <div>
        <label for="s" class="screen-reader-text"><?php _e('Search for:','nu_gm'); ?></label>
        <input type="search" id="s" name="s" value="" aria-label="search this site" />

        <button type="submit" aria-label="submit search" id="searchsubmit" ><?php _e('Search','nu_gm'); ?></button>
    </div>
</form>