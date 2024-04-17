/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global $, window */

jQuery(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    jQuery('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        autoUpload : true,
        url: '/views/system/includes/UploadHandler.php?folder=' + jQuery('input[name="view"]').val() + '-' + jQuery('#cid').val()
    });

    // Load existing files:
    jQuery('#fileupload').addClass('fileupload-processing');

    jQuery.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: jQuery('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: jQuery('#fileupload')[0]
    }).always(function () {
        jQuery(this).removeClass('fileupload-processing');
    }).done(function (result) {
        jQuery(this).fileupload('option', 'done')
            .call(this, jQuery.Event('done'), {result: result});
    });
});
