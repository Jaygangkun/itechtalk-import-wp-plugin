<?php get_header() ?>
<div class="itt-compare-page">
<?php
$comparedProductsSlug = get_query_var( 'products' );

$comparedProductsSlugTokens = explode('-vs-', $comparedProductsSlug);

$firstIttProduct = null;
$secondIttProduct = null;

if(count($comparedProductsSlugTokens) >= 2) {
    $productSlug1 = $comparedProductsSlugTokens[0];
    $productSlug2 = $comparedProductsSlugTokens[1];

    $productsBySlug1 = get_posts([
        'name' => $productSlug1,
        'post_type'   => ITT_POST_TYPE,
        'post_status' => 'publish',
        'numberposts' => 1
    ]);

    if(count($productsBySlug1) > 0) {
        $firstIttProduct = $productsBySlug1[0];
    }
    else {
        ?>
        <div>No Found First Product to compare</div>
        <?php
    }

    $productsBySlug2 = get_posts([
        'name' => $productSlug2,
        'post_type'   => ITT_POST_TYPE,
        'post_status' => 'publish',
        'numberposts' => 1
    ]);

    if(count($productsBySlug2) > 0) {
        $secondIttProduct = $productsBySlug2[0];
    }
    else {
        ?>
        <div>No Found Second Product to compare</div>
        <?php
    }
}
else {
    ?>
    <div class="">No Found Products to compare</div>
    <?php
}

if($firstIttProduct && $secondIttProduct) {
    $firstIttProductImageURL = '';
    $secondIttProductImageURL = '';
    if(has_post_thumbnail($firstIttProduct->ID)) {
        $ittProductImage = wp_get_attachment_image_src(get_post_thumbnail_id($firstIttProduct->ID));
        $firstIttProductImageURL = $ittProductImage[0];
    }
    
    if(has_post_thumbnail($secondIttProduct->ID)) {
        $secondProductImage = wp_get_attachment_image_src(get_post_thumbnail_id($secondIttProduct->ID));
        $secondIttProductImageURL = $secondProductImage[0];
    }

    ?>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
    <table>
        <tbody>
            <tr>
                <td><img class="" src="<?= $firstIttProductImageURL?>"></td>
                <td>vs</td>
                <td><img class="" src="<?= $secondIttProductImageURL?>"></td>
            </tr>
            <?php
            
            $ittProductMetaFields = [
                [
                    'name' => 'network',
                    'title' => 'Network'
                ],
                [
                    'name' => 'launch',
                    'title' => 'Launch'
                ],
                [
                    'name' => 'body',
                    'title' => 'Body'
                ],
                [
                    'name' => 'display',
                    'title' => 'Display'
                ],
                [
                    'name' => 'platform',
                    'title' => 'Platform'
                ],
                [
                    'name' => 'memory',
                    'title' => 'Memory'
                ],
                [
                    'name' => 'main_camera',
                    'title' => 'Main Camera'
                ],
                [
                    'name' => 'selfie_camera',
                    'title' => 'Selfie Camera'
                ],
                [
                    'name' => 'camera',
                    'title' => 'Camera'
                ],
                [
                    'name' => 'sound',
                    'title' => 'Sound'
                ],
                [
                    'name' => 'comms',
                    'title' => 'Comms'
                ],
                [
                    'name' => 'features',
                    'title' => 'Features'
                ],
                [
                    'name' => 'battery',
                    'title' => 'Battery'
                ],
                [
                    'name' => 'misc',
                    'title' => 'Misc'
                ],
                [
                    'name' => 'tests',
                    'title' => 'Tests'
                ],
                // [
                //     'name' => 'type',
                //     'title' => 'Type'
                // ]
                // [
                //     'name' => 'item_url',
                //     'title' => 'Item URL'
                // ]
            ];

            foreach($ittProductMetaFields as $metaField) {
                ?>
                <tr>
                    <td></td>
                    <td><b><?= $metaField['title']?></b></td>
                    <td></td>
                </tr>
                <?php
                $firstIttProductMetaData = get_field($metaField['name'], $firstIttProduct->ID);
                if($firstIttProductMetaData != '-') {
                    $firstIttProductNetwork = json_decode(str_replace("'", '"', str_replace('"', '\"', $firstIttProductMetaData)), true);
                    $firstIttProductNetworkKeys = array_keys($firstIttProductNetwork);
                }
                else {
                    $firstIttProductNetworkKeys = [];
                }
                

                $secondIttProductMetaData = get_field($metaField['name'], $secondIttProduct->ID);
                if($secondIttProductMetaData != '-') {
                    $secondIttProductNetwork = json_decode(str_replace("'", '"', str_replace('"', '\"', $secondIttProductMetaData)), true);
                    $secondIttProductNetworkKeys = array_keys($secondIttProductNetwork);    
                }
                else {
                    $secondIttProductNetworkKeys = [];
                }
                
                $ittProductNetworkKeys = array_merge($firstIttProductNetworkKeys, $secondIttProductNetworkKeys);

                foreach($ittProductNetworkKeys as $ittProductNetworkKey) {
                    ?>
                    <tr>
                        <td><?= empty($firstIttProductNetwork[$ittProductNetworkKey]) ? '' : $firstIttProductNetwork[$ittProductNetworkKey]?></td>
                        <td><?= $ittProductNetworkKey?></td>
                        <td><?= empty($secondIttProductNetwork[$ittProductNetworkKey]) ? '' : $secondIttProductNetwork[$ittProductNetworkKey]?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td><?= get_field('type', $secondIttProduct->ID) ?></td>
                <td><b>Type</b></td>
                <td><?= get_field('type', $secondIttProduct->ID) ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php
}
?>
</div>
<?php get_footer()?>