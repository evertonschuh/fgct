"use strict";

$(function(){

    var $validator = $('.bs-stepper-content form').validate({
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
            exampleInputEmail1: {
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


    var e=document.getElementById("createApp");



    let r;
    e.addEventListener("show.bs.modal",
    function(e){
        var t=document.querySelector("#wizard-create-app");
        if(null!==t)
        {
            var n=[].slice.call(t.querySelectorAll(".btn-next")),
                c=[].slice.call(t.querySelectorAll(".btn-prev")),
                r=t.querySelector(".btn-submit");
                const a=new Stepper(t,{linear:!1});
                //a.steps("remove", 2);
                n&&n.forEach(e=>{e.addEventListener("click",e=>{if(l()) a.next()})}),
                c&&c.forEach(e=>{e.addEventListener("click",e=>{a.previous(),l()})}),
                r&&r.addEventListener("click",e=>{alert("Submitted..!!")})
        }
    });


    function l(){
        var $valid = $('.bs-stepper-content form').valid();
        alert($valid);
        if(!$valid) {
            $validator.focusInvalid();
            return false;
        }
        return true;
    }
});


        /*
    
        t&&(
            r=new Cleave(t,{creditCard:!0,onCreditCardTypeChanged:function(e){document.querySelector(".app-card-type").innerHTML=""!=e&&"unknown"!=e?'<img src="'+assetsPath+"img/icons/payments/"+e+'-cc.png" class="cc-icon-image" height="28"/>':""}}))}n&&new Cleave(n,{date:!0,delimiter:"/",datePattern:["m","y"]}),c&&new Cleave(c,{numeral:!0,numeralPositiveOnly:!0}),e.addEventListener("show.bs.modal",function(e){var t=document.querySelector("#wizard-create-app");





;*/
