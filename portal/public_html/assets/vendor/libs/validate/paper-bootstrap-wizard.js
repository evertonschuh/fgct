/*! =========================================================
 *
 Paper Bootstrap Wizard - V1.0.1
*
* =========================================================
*
* Copyright 2016 Creative Tim (http://www.creative-tim.com/product/paper-bootstrap-wizard)
 *
 *                       _oo0oo_
 *                      o8888888o
 *                      88" . "88
 *                      (| -_- |)
 *                      0\  =  /0
 *                    ___/`---'\___
 *                  .' \|     |// '.
 *                 / \|||  :  |||// \
 *                / _||||| -:- |||||- \
 *               |   | \\  -  /// |   |
 *               | \_|  ''\---/''  |_/ |
 *               \  .-\__  '-'  ___/-. /
 *             ___'. .'  /--.--\  `. .'___
 *          ."" '<  `.___\_<|>_/___.' >' "".
 *         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *         \  \ `_.   \_ __\ /__ _/   .-` /  /
 *     =====`-.____`.___ \_____/___.-`___.-'=====
 *                       `=---='
 *
 *     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 *               Buddha Bless:  "No Bugs"
 *
 * ========================================================= */

// Paper Bootstrap Wizard Functions

searchVisible = 0;
transparent = true;

$(document).ready(function(){

    /*  Activate the tooltips      */
    $('[rel="tooltip"]').tooltip();

    // Code for the Validator
    var $validator = $('.wizard-card form').validate({
        rules: {
            firstname: {
                required: true,
                minlength: 3
            },
            fullname_survey: {
                required: true,
                fullname: true,
            },
            email_survey: {
                required: true
            },
            termcheck: {
                required: true
            },
            age_survey: {
                required: true
            },
            company_survey: {
                required: true
            },

        },
    });

    // Wizard Initialization
    $('.wizard-card').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        'nextSelector': '.btn-next',
        'previousSelector': '.btn-previous',

        onNext: function(tab, navigation, index) {
            var $valid = $('.wizard-card form').valid();
            if(!$valid) {
                $validator.focusInvalid();
                return false;
            }
        },

        onPrevious: function(tab, navigation, index) {
            //alert('asdasd');

            if(tab.hasClass('li-hidden') && tab.prev('li:not(.li-hidden)').hasClass('active'))
                tab.prev('li:not(.li-hidden)').removeClass('active');

        },

        onInit : function(tab, navigation, index){

            //check number of tabs and fill the entire row
            var $total = navigation.find('li:not(.li-hidden)').length;
            $width = 100/$total;

            navigation.find('li:not(.li-hidden)').css('width',$width + '%');

        },

        onTabClick : function(tab, navigation, index){

            var $valid = $('.wizard-card form').valid();

            if(!$valid){
                return false;
            } else{
                return true;
            }

        },

        onTabShow: function(tab, navigation, index) {
            var $total = navigation.find('li:not(.li-hidden)').length;
            var $totalFinal = navigation.find('li').length;

            var $questions = navigation.find('li.questions-progress').length;
            var $current = index+1;

            var $wizard = navigation.closest('.wizard-card');

            // If it's the last tab then hide the last button and show the finish instead
            if($current >= $totalFinal) {
                $($wizard).find('.btn-next').hide();
                $($wizard).find('.btn-finish').show();
            } else {
                $($wizard).find('.btn-next').show();
                $($wizard).find('.btn-finish').hide();
            }
          // alert(navigation.attr('class'));
          // tab.remove();
            var prev = 0;
            if(tab.prevAll('li.li-hidden').length)
                prev = (tab.prevAll('li.li-hidden').length);

            var prevQ = 0;
            if(tab.prevAll('li.questions-progress').length)
                prevQ = (tab.prevAll('li.questions-progress').length);

            if(tab.hasClass('questions-progress') || tab.hasClass('intro-tab')){                
                $('#questions-progress').show();
                $('#question-name').html(tab.data('name'));
                $('#question-value').html(tab.data('value'));
                $('#question-total').html(tab.data('total'));

            }
            else
                $('#questions-progress').hide();
                
            var move_question_progress = 100 / ($questions);
            move_question_progress = move_question_progress * (prevQ) ;

            $wizard.find($('.progress-questions-test ')).css({width: move_question_progress + '%'});

              //  tab.prevUntil('li.li-hidden').show()
           // alert(tab.siblings('li:not(.li-hidden)').html());
            //update progress
           

            if(!tab.hasClass('li-hidden')){
                var move_distance = 100 / $total;
                move_distance = move_distance * ((index)-prev) + move_distance / 2;
                $wizard.find($('.progress-circle')).css({width: move_distance + '%'});
                $wizard.find($('.wizard-card .nav-pills li.active a .icon-circle')).addClass('checked');
            }
            else
                tab.prevAll('li.pesquisa').addClass('active');
        }
    });

/*
        // Prepare the preview for profile picture
        $("#wizard-picture").change(function(){
            readURL(this);
        });

        $('[data-toggle="wizard-radio"]').click(function(){
            wizard = $(this).closest('.wizard-card');
            wizard.find('[data-toggle="wizard-radio"]').removeClass('active');
            $(this).addClass('active');
            $(wizard).find('[type="radio"]').removeAttr('checked');
            $(this).find('[type="radio"]').attr('checked','true');
        });

        $('[data-toggle="wizard-checkbox"]').click(function(){
            if( $(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).find('[type="checkbox"]').removeAttr('checked');
            } else {
                $(this).addClass('active');
                $(this).find('[type="checkbox"]').attr('checked','true');
            }
        });
*/
        $('.set-full-height').css('height', 'auto');

    });



    //Function to show image before upload

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}


function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        }, wait);
        if (immediate && !timeout) func.apply(context, args);
    };
};

