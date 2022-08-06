<?php

/**
 * Template Name: Page Sizes
 * Description: Page template sizes.
 *
 */
require_once(__DIR__ . '/../../plugins/painting/helper.php');

$helper = new Helper;
$helper->getAllTranslations("en");
get_header();

?>
<!-- tabs -->

<div class="container pt-15">
    <h2 class="ima-size-chang "><?= $helper->getHebrewText('please_choose_a_size_in_which_you_want_the_image'); ?></h2>
    <div>
        <form action="/<?php echo $_GET['type']; ?>" method="POST">
            <div class="row mt-5">
                <div class="col-md-4"></div>
                <div class="col-md-1">
                    <div class="font-img1">
                        <p><?= $helper->getHebrewText('width'); ?></p>
                        <input type="number" name="width" value="50" onchange="setDimension(this, 'width');">
                        <p><?= $helper->getHebrewText('cm'); ?></p>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar" id="progress-width" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">
                            <span class="sr-only">
                                <span id="width-number"></span>% <?= $helper->getHebrewText('complete'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="close-sec">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="font-img">
                        <p><?= $helper->getHebrewText('height'); ?></p>
                        <input type="number" name="height" value="70" onchange="setDimension(this, 'height');">
                        <p><?= $helper->getHebrewText('cm'); ?></p>
                    </div>
                    <div class="progress mt-2">
                        <div class="progress-bar" id="progress-height" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                            <span class="sr-only">
                                <span id="height-number"></span>% <?= $helper->getHebrewText('complete'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="size-change-btn">
                <input type="submit" name="submit" value="<?= $helper->getHebrewText('submit'); ?>">
            </div>
        </form>
    </div>
    <hr />

    <div class="img-size-diff">
        <h1 class="ima-size-chang mb-5 "><?= $helper->getHebrewText('popular_sizes'); ?></h1>
        <div class="row d-flex justify-content-center">
            <div class="col-md-4 col-8">
                <div>
                    <img src="<?= plugins_url('/painting/images/tree-736885__480.jpeg'); ?>" class="img-fluid">
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="one-img">
                    <img src="<?= plugins_url('/painting/images/tree-736885__480.jpeg'); ?>" class="img-fluid">
                </div>
            </div>
            <div class="col-md-2 col-5">
                <div class="two-img">
                    <img src="<?= plugins_url('/painting/images/tree-736885__480.jpeg'); ?>" class="img-fluid">
                </div>
            </div>
            <div class="col-md-1 col-7">
                <div class="three-img">
                    <img src="<?= plugins_url('/painting/images/tree-736885__480.jpeg'); ?>" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function setDimension(elem, id) {
        var value = elem.value;
        document.getElementById('progress-' + id).setAttribute('aria-valuenow', value);
        document.getElementById('progress-' + id).setAttribute('style', 'width: ' + value + '%');
        document.getElementById(id + '-number').innnerHTML = value;
    }
</script>
<?php

get_footer();

?>