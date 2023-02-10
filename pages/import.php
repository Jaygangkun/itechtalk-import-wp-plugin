<?php
$settings = get_option('ft_settings', null);
if ($settings !==  null) { 
    $settings = unserialize($settings); 
}
?>
<script>
    const adminURL = '<?= admin_url('admin-ajax.php'); ?>';
</script>
<div class="itt-page-container">
    <div class="itt-wrap">
        <div class="itt-field">
            <div class="itt-input">
                <p>
                    <input type="file" name="import_file" id="import_file" style="display:none;" accept=".csv"/>
                    <button type="button" id="btn_import" class="button button-primary button-has-loader"><span class="button-text">Import</span> <span class="button-loader"></span></button>
                </p>
            </div>
        </div>
    </div>
</div>