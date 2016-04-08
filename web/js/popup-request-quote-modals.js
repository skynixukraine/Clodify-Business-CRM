/**
 * Created by Vitaliy on 01.04.2016.
 */

var requestQuoteModals = (function(){

    var bgForPopup,
        popup,
        bodyPopap,
        progressBar,
        factor = 100/ 5,
        step,
        formStep,
        elem,
        checkedElemStep,
        back,
        next,
        quotes,
        dropdown,
        formData,
        files= {},
        arrayFiles =[];



    function progress(step){

        progressBar.css('width' , factor * step + '%');

    }



return{

    init: function(){

        bgForPopup      = $('#request-quote-modals');
        popup           = bgForPopup.find(".popup");
        progressBar     = $(".progress-bar");
        bodyPopap       = popup.find('.body-popap');
        formStep        = bodyPopap.find('> div');
        dropdown        = formStep.find("[data-toggle=dropdown]");
        back            = $(".back");
        next            = $(".next");
        quotes          = $(".quotes");



        formStep.hide().attr("aria-hidden", true);
        formStep.eq(0).show().attr("aria-hidden", false);

        for(var i=0; i < formStep.length; i++){

            formStep.eq(i).data("data-step", i);
        }


        $(".box-evaluation .en-btn").click(function () {//button open REQUEST A QUOTE

            event.preventDefault();
            bgForPopup.fadeIn(500);
            popup.fadeIn(1000);
            popup.slideDown(500);

            return false;

        });
        bgForPopup.find(".close").click(function () {// button close popup

            bgForPopup.fadeOut(1000);
            popup.fadeOut(1000);
            popup.slideUp(500);

        });
        next.click(function(event){//button next formStep
            event.preventDefault();
            elem = bodyPopap.find("[aria-hidden=false]");
            step = elem.data('data-step') + 1;


            if(step == 1){//skip step 2

                checkedElemStep = elem.find("input:checked");

                if(checkedElemStep.val().indexOf("Active site application") != 0 &&
                    checkedElemStep.val().indexOf("In development") != 0){

                    step += 1;
                }

            }


            formStep.eq(step).show().attr("aria-hidden", false);
            elem.hide() .attr("aria-hidden", true);
            back.css('display' , 'block');

            if(step == 5){

                next.css('display' , 'none');
                quotes.css('display' , 'block');
            }


            progress(step);

            return false;

        });
        back.click(function(event){//button back formStep
            event.preventDefault();

            elem = bodyPopap.find("[aria-hidden=false]");
            step = elem.data('data-step') -1;

            if(step == 1){//skip step 2

                checkedElemStep = formStep.eq(0).find(":checked");

                if(checkedElemStep.val().indexOf("Active site application") != 0 &&
                    checkedElemStep.val().indexOf("In development") != 0){

                    step -= 1;
                }

            }
            formStep.eq(step).show().attr("aria-hidden", false);
            elem.hide() .attr("aria-hidden", true);

            if(step == 0){

                back.css('display' , 'none');
            }
            console.log(step);
            if(step == 4) {
                next.css('display', 'block');
                quotes.css('display', 'none');
            }
            progress(step);

            return false;

        });



        quotes.click(function(event){//button 'GET MY QUOTES'
            event.preventDefault();


            formData = popup.find('form').serializeArray();

            for(var i = 0; i < dropdown.length; i++) {

                formData.push({
                    name: dropdown.eq(i).attr('name'),
                    value: dropdown.eq(i).val()
                });

            }
            if(files.value){

                formData.push(files);
            }else{

                formData.push({
                    name: "file",
                    value: ""
                });
            }



            console.log(formData);


            $.ajax({
                url : popup.find('form').attr("action"),
                type : 'POST',
                data : formData,
                processData: false,
                contentType: false,
                success : function(data) {
                    console.log(data);
                    alert(data);
                }
            });

            return false;
        });

        formStep.find("#file").change(function(e){//create an object with attached files

            arrayFiles.push(e.target.value);
            files.value = arrayFiles;
            files.name = $(this).attr('name');


            return false;
        });

        $(".dropdown-menu li").click(function(event) {//dropdown selected

            event.preventDefault();
            var el = $(this),
                value = el.text();

            elem = el.closest(".input-group-btn.select").find(".dropdown-toggle");

            elem.html(value + '<span class="caret1">&or;</span>');
            elem.attr('value', value);


        });



    }
}



})();



addEventListener("load", requestQuoteModals.init);