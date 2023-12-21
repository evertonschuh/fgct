/**
 * @package Joomla.Plugin
 * @version 1.0
 * @author Gilles Migliori
 * @copyright (C) 2015- Gilles Migliori
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

/*jshint globalstrict: true, unused:false*/
/*global jQuery, jQuery, tinymce, window, document*/
'use strict';
tinymce.PluginManager.requireLangPack('bootstraplight');
tinymce.PluginManager.add('bootstraplight', function(editor, url) {

    var iFrameDefaultWidth = 885,
    bootstrapElementsLight,
    bootstrapDefaultCssPath,
    bootstrapLightCssPath,
    DefaultImagesPath,
    imagesPath;

if(typeof(editor.settings.bootstrapLightConfig) == 'undefined') {
    editor.settings.bootstrapLightConfig = [
    ];
}

/* Default Bootstrap Elements */

if(typeof(editor.settings.bootstrapLightConfig.bootstrapElementsLight) == 'undefined') {
    editor.settings.bootstrapLightConfig.bootstrapElementsLight = {
        'btn': true,
        'icon': true,
        'image': true,
        'table': true,
        'template': true,
        'breadcrumb': true,
        'pagination': true,
        'pager': true,
        'label': true,
        'badge': true,
        'alert': true,
        'panel': true,
        'snippet': true
    };
}

bootstrapElementsLight = editor.settings.bootstrapLightConfig.bootstrapElementsLight;

/* Set valid elements to prevent tinymce from removing glyphicon or other empty tags */

if ((editor.settings.verify_html !== false || editor.settings.valid_elements !== '*[*]') && editor.settings.bootstrapLightConfig.overwriteValidElements !== false) {
    editor.settings.valid_elements = getValidElements();
}

/* Default Bootstrap css path */

if(typeof(editor.settings.bootstrapLightConfig.bootstrapLightCssPath) == 'undefined') {
    bootstrapDefaultCssPath = url + '/css/bootstrap.min.css';
    editor.settings.bootstrapLightConfig.bootstrapLightCssPath = bootstrapDefaultCssPath;
}
bootstrapLightCssPath = editor.settings.bootstrapLightConfig.bootstrapLightCssPath;

/* Default Images path */

if(typeof(editor.settings.bootstrapLightConfig.imagesPath) == 'undefined') {
    DefaultImagesPath = url + '/';
    editor.settings.bootstrapLightConfig.imagesPath = {'imagesPath': DefaultImagesPath};
}
imagesPath = editor.settings.bootstrapLightConfig.imagesPath;

/* Snippet Management (Allow / Disallow) */

if(typeof(editor.settings.bootstrapLightConfig.bootstrapElementsLight.snippet === true && editor.settings.bootstrapLightConfig.allowSnippetManagement) == 'undefined') {
    editor.settings.bootstrapLightConfig.allowSnippetManagement = true;
}

/* Add Bootstrap css to editor */

var content_css = editor.settings.content_css;
if(typeof(content_css) == 'undefined') {
    content_css = bootstrapLightCssPath + ',' + url + '/css/editor-content.min.css';
} else {
    content_css = bootstrapLightCssPath + ',' + content_css + ',' + url + '/css/editor-content.min.css';
}
editor.settings.content_css = content_css;

/**
 * Open iframe dialog,
 * Get and transmit selected element attributes to iframe
 * @param  string iframeUrl
 * @param  string iframeTitle
 * @param  string iframeHeight
 * @param  string type      the dialog to display
 * (bsBtn|bsIcon|bsImage|bsTable|bsTemplate|bsBreadcrumb|bsPagination|bsPager|bsLabel|bsBadge|bsAlert|bsPanel|bsSnippet)
 * @return void
 */
function tblShowDialog(iframeUrl, iframeTitle, iframeHeight, type)
{

    /* get selected element values to transmit to iframe */

    var selectedNode = tblGetSelectedNode();
    var nodeAttributes = '';
    if(jQuery(selectedNode).hasClass('active')) {
        if(jQuery(selectedNode).hasClass('btn')) {
            nodeAttributes = tblGetNodeAttributes(selectedNode, 'bsBtn');
        } else if(jQuery(selectedNode).hasClass('label')) {
            nodeAttributes = tblGetNodeAttributes(selectedNode, 'bsLabel');
        } else if(jQuery(selectedNode).hasClass('badge')) {
            nodeAttributes = tblGetNodeAttributes(selectedNode, 'bsBadge');
        } else if(jQuery(selectedNode).hasClass('alert')) {
            nodeAttributes = tblGetNodeAttributes(selectedNode, 'bsAlert');
        }
    }
    var getTheHtml = function (){
        var html = '';
        var language = tinymce.activeEditor.getParam('language');
        if(!language) {
            language = 'en_EN';
        }
        html += '<input type="hidden" name="bs-code" id="bs-code" />';
        var qrySign = '?';
        if(iframeUrl.match(/\?/)) {
            qrySign = '&';
        }
        html += '<iframe src="'+ url + '/' + iframeUrl + qrySign + 'language=' + language+ '&bootstrap_css_path=' + bootstrapLightCssPath + '&' + nodeAttributes + '&' + new Date().getTime() + '"></iframe>';

        return html;
    };

    var iFrameWidth = iFrameDefaultWidth;

    if(jQuery(window).width() < 885) {
        iFrameWidth = jQuery(window).width()*0.9;
    }

    if(jQuery(window).height() > iframeHeight) {
        iframeHeight = (jQuery(window).height()*0.9) - 90;
    }

    var win = editor.windowManager.open({
        title: iframeTitle,
        width : iFrameWidth,
        height : iframeHeight,
        html: getTheHtml(),
        buttons: [
            {
                text: 'OK',
                subtype: 'primary',
                onclick: function(element) {
                    tblRenderContent(element, type);
                    this.parent().parent().close();
                }
            },
            {
                text: 'Cancel',
                onclick: function() {
                    this.parent().parent().close();
                }
            }
        ]
    },
    {
        jquery: jQuery // PASS JQUERY
    });

    /* OK / Cancel buttons position for responsive */

    jQuery('.mce-floatpanel').find('.mce-widget.mce-abs-layout-item.mce-first').css({'left':'auto', 'right':'82px'});
    jQuery('.mce-floatpanel').find('.mce-widget.mce-last.mce-abs-layout-item').css({'left':'auto', 'right':'10px'});

    jQuery(window).on('resize', function() {
        tblResizeDialog();
    });
}

function tblResizeDialog()
{
    var iFrameWidth = iFrameDefaultWidth;
    if(jQuery(window).width() > iFrameDefaultWidth) {
        iFrameWidth = iFrameDefaultWidth;
    } else {
        iFrameWidth = jQuery(window).width()*0.9;
    }
    jQuery('.mce-floatpanel').width(iFrameWidth).css('left', (jQuery(window).width() - iFrameWidth) / 2);
    jQuery('.mce-floatpanel').find('.mce-container-body, .mce-foot, .mce-abs-layout').width(iFrameWidth);
    if(iFrameWidth < 768) {
        jQuery('iframe').contents().find('.container').addClass('container-xs');
    } else {
        jQuery('iframe').contents().find('.container').removeClass('container-xs');
    }
}

/**
 * gets the selected node in editor, or the active parent matching a bootstrap element
 * @return matching bootstrap element
 */
function tblGetSelectedNode()
{
    var selectedNode = editor.selection.getNode();
    if(!jQuery(selectedNode).hasClass('active') || jQuery(selectedNode).closest('ol.breadcrumb').length > 0 || jQuery(selectedNode).closest('ul.pagination').length > 0 || jQuery(selectedNode).closest('ul.pager').length > 0 || jQuery(selectedNode).closest('div.alert').length > 0) { // li without link HAS class 'active' in bootstrap

        /* look for .table|.breadcrumb|.pagination|.pager|.alert|.panel in parents */

        var classes = ['.table', '.breadcrumb', '.pagination', '.pager', '.alert', '.panel'];
        var found = false;

        for (var i = 0; i < classes.length; i++) {
            if(jQuery(selectedNode).closest(classes[i]).length > 0 && found === false) {
                selectedNode = jQuery(selectedNode).closest(classes[i]);
                found = true;
            }
        }
    }

    return selectedNode;
}

/**
 * insert|update editor content
 * @param  string element the 'ok' button of iframe
 * @param  string type    type of content to insert|update
 *                        (bsBtn|bsIcon|bsImage|bsTable|bsTemplate|bsBreadcrumb|bsPagination|bsPager|bsLabel|bsBadge|bsAlert|bsPanel)
 * @return void
 */
function tblRenderContent(element, type)
{
    var markup = tblHtmlDecode(document.getElementById('bs-code').value);
    var selectedNode = tblGetSelectedNode();
    if(jQuery(selectedNode).hasClass('active')) {

        /* insert new content */

        jQuery(selectedNode).after(markup);

        /* remove old content */

        var typesClasses = {
            'bsBtn': 'btn',
            'bsLabel': 'label',
            'bsBadge': 'badge',
            'bsAlert': 'alert'
        };

        for(var key in typesClasses)
        {
          var value = typesClasses[key];
            if(type == key) {
                if(type == 'bsImage' && jQuery(selectedNode).is('img')) {
                    editor.dom.remove(selectedNode);
                } else if(jQuery(selectedNode).hasClass(value)) { // remove old element
                    editor.dom.remove(selectedNode);
                }
            }
        }
    } else {

        /* if none selected, insert new content */

        editor.insertContent(markup);
    }
    /* remove the '<br data-mce-bogus="1"> added by tinyMce in pagination with firefox */
    tinymce.activeEditor.dom.remove(tinymce.activeEditor.dom.select('br[data-mce-bogus="1"]'));
}

/**
 * get selected node attributes to transmit to iframe
 * @param  string selectedNode
 * @param  string type         type of the clicked btn
 *                             (bsBtn|bsIcon|bsImage|bsTable|bsTemplate|bsBreadcrumb|bsPagination|bsPager|bsLabel|bsBadge|bsAlert|bsPanel)
 * @return string              node attributes
 */
function tblGetNodeAttributes(selectedNode, type)
{
    var urlString = '';
    if(type == 'bsBtn') {
        var i;
        var btnCode = jQuery(selectedNode)[0].outerHTML.replace(' active', '');
        var btnIcon = '';
        if(jQuery(selectedNode).find('span')[0]) {
            btnIcon = jQuery(selectedNode).find('span').attr('class').split(' ')[1];
        }
        var styles = new Array('default', 'btn-primary', 'btn-success', 'btn-info', 'btn-warning', 'btn-danger');
        var btnStyle = '';
        for (i = styles.length - 1; i >= 0; i--) {
            if(jQuery(selectedNode).hasClass(styles[i])) {
                btnStyle = styles[i];
            }
        }
        var sizes = new Array('btn-xs', 'btn-sm', 'btn-lg');
        var btnSize = '';
        for (i = sizes.length - 1; i >= 0; i--) {
            if(jQuery(selectedNode).hasClass(sizes[i])) {
                btnSize = sizes[i];
            }
        }
        var btnTag = jQuery(selectedNode).prop('tagName').toLowerCase();
        var btnHref = '';
        if(btnTag == 'a') {
            btnHref = jQuery(selectedNode).attr('href');
        }
        var btnType = '';
        if(btnTag == 'button' || btnTag == 'input') {
            btnType = jQuery(selectedNode).attr('type');
        }
        var btnText;
        if(btnTag == 'button' || btnTag == 'a') {
            btnText = jQuery(selectedNode).remove('i').text();
        } else {
            btnText = jQuery(selectedNode).val();
        }
        var iconPos = 'prepend';
        if(jQuery(selectedNode).find('span')[0]) {
            var reg = new RegExp('/^' + btnText + '/');
            if(reg.test(jQuery(selectedNode).html()) === true) {
                iconPos = 'append';
            }
        }
        btnCode   = encodeURIComponent(btnCode);
        btnIcon   = encodeURIComponent(btnIcon);
        btnStyle  = encodeURIComponent(btnStyle);
        btnSize   = encodeURIComponent(btnSize);
        btnTag    = encodeURIComponent(btnTag);
        btnHref   = encodeURIComponent(btnHref);
        btnType   = encodeURIComponent(btnType);
        btnText   = encodeURIComponent(btnText);
        iconPos   = encodeURIComponent(iconPos);
        urlString =  'btnCode=' + btnCode + '&btnIcon=' + btnIcon + '&btnStyle=' + btnStyle + '&btnSize=' + btnSize + '&btnTag=' + btnTag + '&btnHref=' + btnHref + '&btnType=' + btnType + '&btnText=' + btnText + '&iconPos=' + iconPos;
    }
    else if(type == 'bsLabel' || type == 'bsBadge' || type == 'bsAlert') {
        urlString =  'edit=true';
    }

    return urlString;
}

function tblHtmlDecode(input){
    var e = document.createElement('div');
    e.innerHTML = input;
    return e.childNodes.length === 0 ? '' : e.childNodes[0].nodeValue;
}

// Add custom css for toolbar icons

editor.on('init', function()
{
    var cssURL = url + '/css/editor.min.css';
    if(document.createStyleSheet){
        document.createStyleSheet(cssURL);
    } else {
        var cssLink = editor.dom.create('link', {
                    rel: 'stylesheet',
                    href: cssURL
                  });
        document.getElementsByTagName('head')[0].
                  appendChild(cssLink);
    }

    /* get custom background color */

    var tinymceLightBackgroundColor = editor.settings.bootstrapLightConfig.tinymceLightBackgroundColor;
    if(tinymceLightBackgroundColor !== '') {
        editor.dom.addStyle('.mce-content-body {background-color: ' + tinymceLightBackgroundColor + ' !important}');
    }
    tblInitCallbackEvents();
});

/**
 * toggle visual aid for templates
 * @return void
 */
function tblToggleVisualAid()
{
    if(tinymce.activeEditor.hasVisual) {
        tinymce.activeEditor.dom.addClass(tinymce.activeEditor.dom.select('.mce-content-body '), 'hasVisual');
    } else {
        tinymce.activeEditor.dom.removeClass(tinymce.activeEditor.dom.select('.mce-content-body '), 'hasVisual');
    }
}

/* callback events to select bootstrap elements on click and allow updates */

/**
 * tblInitCallbackEvents
 * @return void
 */
function tblInitCallbackEvents()
{
    tblToggleVisualAid();
    tinymce.activeEditor.on('ExecCommand', function(e) {
        if(e.command == 'mceToggleVisualAid'); {
            tblToggleVisualAid();
        }
    });
    tblToggleVisualAid();
    tinymce.activeEditor.on('click keyup', function(e) {
        var elementSelector = '';
        var editorBtnName = '';
        if(jQuery(e.target).attr('class')) {
            if(jQuery(e.target).attr('class').match(/btn/)) {
                elementSelector = '.btn';
                editorBtnName = 'insertBtnBtn';
            } else if(jQuery(e.target).attr('class').match(/label/)) {
                elementSelector = '.label';
                editorBtnName = 'insertLabelBtn';
            } else if(jQuery(e.target).attr('class').match(/badge/)) {
                elementSelector = '.badge';
                editorBtnName = 'insertBadgeBtn';
            } else if(jQuery(e.target).attr('class').match(/alert/)) {
                elementSelector = '.alert';
                editorBtnName = 'insertAlertBtn';
            }
        }

        /* deactivate all previous activated */

        tblDeactivateAll();

        if(elementSelector === '') {
            return;
        }

        /* activate current */

        tblActivate(e.target, elementSelector, editorBtnName);
    });
    tblDeactivateAll(); // onLoad
}

function tblActivate(element, elementSelector, editorBtnName)
{
    if(elementSelector == '.btn' && jQuery(element).is('input') !== true) {
        editor.selection.setCursorLocation(element, true);
    }
    if(elementSelector == '.alert') {
        jQuery(element).closest('.alert').addClass('active');
        tblToggleEditorButton(editorBtnName, 'on');
    } else {
        jQuery(element).addClass('active');
        tblToggleEditorButton(editorBtnName, 'on');
    }
}

function tblDeactivateAll()
{
    var elements = new Array('.btn', '.label', '.badge', '.alert');
    for (var i = 0; i < elements.length; i++) {
        jQuery(editor.dom.select(elements[i])).removeClass('active');
    }
}

function tblToggleEditorButton(editorBtnName, onOff)
{
    var editorBtns = editor.buttons.bootstraplight.items;
    for (var i = editorBtns.length - 1; i >= 0; i--) {
        if(editorBtnName == 'allBtns' || editorBtns[i]._name == editorBtnName) {
            if(onOff == 'on') {
                jQuery(editorBtns[i]).focus();
            }
        }
    }
}

function tblProVersion()
{
	jQuery('.editor').css('overflow', 'visible');
jQuery('<div id="buy-pro-version" style="position:absolute;top:-80px;left:50%;margin-left:-290px;width:580px;z-index:1000000"><a href="http://codecanyon.net/item/tinymce-bootstrap-plugin-for-joomla/12525945"><p class="btn btn-primary btn-large" style="height:auto !important;padding:20px !important"><button type="button" class="btn btn-xs btn-warning" id="close-btn" style="float: right; margin-top: -15px; padding: 0 5px; margin-right: -15px;">×</button><strong>Get this feature on PRO version at</strong><br>http://codecanyon.net/item/tinymce-bootstrap-plugin-for-joomla/12525945</p></a></div>').appendTo('.editor'); jQuery('#close-btn').on('click', function(event) {
    event.stopPropagation();
    jQuery('#buy-pro-version').fadeOut(400, function() {
    	jQuery('.editor').css('overflow', 'hidden');
    });
    return false;
});
}

var tblBsItems = [];

// Create and render a buttongroup with buttons

var tblBs3Btn = tinymce.ui.Factory.create({
    type: 'button',
    text: '',
    classes: 'widget btn bs-icon-btn',
    icon: 'bootstrap-icon',
    tooltip: 'Bootstrap Elements Light'
});
tblBsItems.push(tblBs3Btn);
if(bootstrapElementsLight.btn) {
    var insertBtn = tinymce.ui.Factory.create({
        type: 'button',
        classes: 'widget btn bs-icon-btn',
        // text: 'btn',
        icon: 'icon-btn',
        name: 'insertBtnBtn',
        tooltip: 'Insert/Edit Bootstrap Button',
        onclick: function() {
            tblShowDialog('bootstrap-btn.php', 'Insert/Edit Bootstrap Button', 580, 'bsBtn');
        }
    });
    tblBsItems.push(insertBtn);
}
var insertImage = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-image',
    name: 'insertImageBtn',
    tooltip: 'Insert/Edit Bootstrap Image',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertImage);
var insertIcon = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-icon',
    name: 'insertIconBtn',
    tooltip: 'Insert/Edit Bootstrap Icon',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertIcon);
var insertTable = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-table',
    name: 'insertTableBtn',
    tooltip: 'Insert/Edit Bootstrap Table',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertTable);
var insertTemplate = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-template',
    name: 'insertTemplateBtn',
    tooltip: 'Insert Bootstrap Template',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertTemplate);
var insertBreadcrumb = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-breadcrumb',
    name: 'insertBreadcrumbBtn',
    tooltip: 'Insert/Edit Bootstrap Breadcrumb',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertBreadcrumb);
var insertPagination = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-pagination',
    name: 'insertPaginationBtn',
    tooltip: 'Insert/Edit Bootstrap Pagination',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertPagination);
var insertPager = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-pager',
    name: 'insertPagerBtn',
    tooltip: 'Insert/Edit Bootstrap Pager',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertPager);
if(bootstrapElementsLight.label) {
    var insertLabel = tinymce.ui.Factory.create({
        type: 'button',
        classes: 'widget btn bs-icon-btn',
        icon: 'icon-label',
        name: 'insertLabelBtn',
        tooltip: 'Insert/Edit Bootstrap Label',
        onclick: function() {
            tblShowDialog('bootstrap-label.php', 'Insert/Edit Bootstrap Label', 350, 'bsLabel');
        }
    });
    tblBsItems.push(insertLabel);
}
if(bootstrapElementsLight.badge) {
    var insertBadge = tinymce.ui.Factory.create({
        type: 'button',
        classes: 'widget btn bs-icon-btn',
        icon: 'icon-badge',
        name: 'insertBadgeBtn',
        tooltip: 'Insert/Edit Bootstrap Badge',
        onclick: function() {
            tblShowDialog('bootstrap-badge.php', 'Insert/Edit Bootstrap Badge', 350, 'bsBadge');
        }
    });
    tblBsItems.push(insertBadge);
}
if(bootstrapElementsLight.alert) {
    var insertAlert = tinymce.ui.Factory.create({
        type: 'button',
        classes: 'widget btn bs-icon-btn',
        icon: 'icon-alert',
        name: 'insertAlertBtn',
        tooltip: 'Insert/Edit Bootstrap Alert',
        onclick: function() {
            tblShowDialog('bootstrap-alert.php', 'Insert/Edit Bootstrap Alert', 580, 'bsAlert');
        }
    });
    tblBsItems.push(insertAlert);
}
var insertPanel = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn bs-icon-btn-disabled',
    icon: 'icon-panel',
    name: 'insertPanelBtn',
    tooltip: 'Insert/Edit Bootstrap Panel',
    onclick: function() {
        tblProVersion();
    }
});
tblBsItems.push(insertPanel);
var insertSnippet = tinymce.ui.Factory.create({
    type: 'button',
    classes: 'widget btn bs-icon-btn',
    icon: 'icon-snippet',
    name: 'insertSnippetBtn',
    tooltip: 'Insert/Edit Snippet',
    onclick: function() {
        tblShowDialog('bootstrap-snippet.php?allowEdit=' + editor.settings.bootstrapLightConfig.allowSnippetManagement, 'Insert Snippet', 650, 'bsSnippet');
    }
});
tblBsItems.push(insertSnippet);
editor.addButton('bootstraplight', {
    type: 'buttongroup',
    classes: 'bs-btn',
    items: tblBsItems
});

/**
 * set tinymce valid_elements according to html5 shema
 * based on veprbl / html5_valid_elements.js
 * https://gist.github.com/veprbl/1136304
 * @return string
 */
function getValidElements()
{
    var valid_elements = '@[accesskey|draggable|style|class|hidden|tabindex|contenteditable|id|title|contextmenu|lang|dir<ltr?rtl|spellcheck|onabort|onerror|onmousewheel|onblur|onfocus|onpause|oncanplay|onformchange|onplay|oncanplaythrough|onforminput|onplaying|onchange|oninput|onprogress|onclick|oninvalid|onratechange|oncontextmenu|onkeydown|onreadystatechange|ondblclick|onkeypress|onscroll|ondrag|onkeyup|onseeked|ondragend|onload|onseeking|ondragenter|onloadeddata|onselect|ondragleave|onloadedmetadata|onshow|ondragover|onloadstart|onstalled|ondragstart|onmousedown|onsubmit|ondrop|onmousemove|onsuspend|ondurationmouseout|ontimeupdate|onemptied|onmouseover|onvolumechange|onended|onmouseup|onwaiting],a[target<_blank?_self?_top?_parent|ping|media|href|hreflang|type|rel<alternate?archives?author?bookmark?external?feed?first?help?index?last?license?next?nofollow?noreferrer?prev?search?sidebar?tag?up],abbr,address,area[alt|coords|shape|href|target<_blank?_self?_top?_parent|ping|media|hreflang|type|shape<circle?default?poly?rect|rel<alternate?archives?author?bookmark?external?feed?first?help?index?last?license?next?nofollow?noreferrer?prev?search?sidebar?tag?up],article,aside,audio[src|preload<none?metadata?auto|autoplay<autoplay|loop<loop|controls<controls|mediagroup],blockquote[cite],body,br,button[autofocus<autofocus|disabled<disabled|form|formaction|formenctype|formmethod<get?put?post?delete|formnovalidate?novalidate|formtarget<_blank?_self?_top?_parent|name|type<reset?submit?button|value],canvas[width,height],caption,cite,code,col[span],colgroup[span],command[type<command?checkbox?radio|label|icon|disabled<disabled|checked<checked|radiogroup|default<default],datalist[data],dd,del[cite|datetime],details[open<open],dfn,div,dl,dt,-em/i,embed[src|type|width|height],eventsource[src],fieldset[disabled<disabled|form|name],figcaption,figure,footer,form[accept-charset|action|enctype|method<get?post?put?delete|name|novalidate<novalidate|target<_blank?_self?_top?_parent],-h1,-h2,-h3,-h4,-h5,-h6,header,hgroup,hr,iframe[name|src|srcdoc|seamless<seamless|width|height|sandbox],img[alt=|src|ismap|usemap|width|height],input[accept|alt|autocomplete<on?off|autofocus<autofocus|checked<checked|disabled<disabled|form|formaction|formenctype|formmethod<get?put?post?delete|formnovalidate?novalidate|formtarget<_blank?_self?_top?_parent|height|list|max|maxlength|min|multiple<multiple|name|pattern|placeholder|readonly<readonly|required<required|size|src|step|type<hidden?text?search?tel?url?email?password?datetime?date?month?week?time?datetime-local?number?range?color?checkbox?radio?file?submit?image?reset?button|value|width],ins[cite|datetime],kbd,keygen[autofocus<autofocus|challenge|disabled<disabled|form|name],label[for|form],legend,li[value],mark,map[name],menu[type<context?toolbar?list|label],meter[value|min|low|high|max|optimum],nav,noscript,object[data|type|name|usemap|form|width|height],ol[reversed|start],optgroup[disabled<disabled|label],option[disabled<disabled|label|selected<selected|value],output[for|form|name],-p,param[name,value],-pre,progress[value,max],q[cite],ruby,rp,rt,samp,script[src|async<async|defer<defer|type|charset],section,select[autofocus<autofocus|disabled<disabled|form|multiple<multiple|name|size],small,source[src|type|media],span,-strong/b,-sub,summary,-sup,table,tbody,td[colspan|rowspan|headers],textarea[autofocus<autofocus|disabled<disabled|form|maxlength|name|placeholder|readonly<readonly|required<required|rows|cols|wrap<soft|hard],tfoot,th[colspan|rowspan|headers|scope],thead,time[datetime],tr,ul,var,video[preload<none?metadata?auto|src|crossorigin|poster|autoplay<autoplay|mediagroup|loop<loop|muted<muted|controls<controls|width|height],wbr';

    return valid_elements;
}
});