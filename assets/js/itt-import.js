(function($){
    $(document).ready(function(){
        $(document).on('click', '#btn_import', function() {
            $('#import_file').click();
        })
        
        $(document).on('change', '#import_file', function() {

            $('#btn_import').addClass('loading');
            $('#btn_import').attr('disabled', true);

            var data = new FormData();
            data.append('file', this.files[0]);
            data.append('action', 'itt_import');

            $.ajax({
                url: adminURL,
                type: 'post',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                success: function(resp) {
                    alert(resp.message);
                    $('#btn_import').attr('disabled', false);
                    $('#btn_import').removeClass('loading');
                }
            })
        })
    
    })
})(jQuery)
