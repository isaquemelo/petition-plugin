<div class="petition-widget--share">
    <?php $permalink = rawurlencode(get_permalink()); ?>
    <a class="post-share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?= $permalink ?>" target="_blank">
        <i class="fa fa-facebook-official"></i>
    </a>
    <a class="post-share-link" href="mailto:?subject=<?= the_title() ?>&body=<?= $permalink ?>" target="_blank">
        <i class="fa fa-envelope"></i>
    </a>
    <a class="post-share-link" href="https://twitter.com/intent/tweet?text=<?= urlencode(get_the_title().':') . '&url='.$permalink ?>" target="_blank">
        <i class="fa fa-twitter"></i>
    </a>
    <a class="post-share-link show-for-small-only" href="whatsapp://send?text=<?= $permalink ?>" target="_blank">
        <i class="fa fa-whatsapp"></i>
    </a>
</div>