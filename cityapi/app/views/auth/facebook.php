<?php
/*
    facebook.php

    Copyright Stefan Fisk 2012.
*/
?>
<script>
    <?php if (isset($returnUrl)): ?>
        window.opener.location = "<?php echo $returnUrl; ?>";
    <?php endif; ?>

    if (window.opener) {
        window.close();
    }
</script>
