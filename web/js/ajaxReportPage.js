var ajaxReportPageModule = (function() {

    return {
        init: function() {

            // var fieldTask = $('.field-task');
            // var submitDiv = $('.submit-div');
            // var submitButton = $('.submit-div button[type=submit]'),
            //     editButton = $('.edit');
            // submitButton.css("visibility", "hidden");
            // submitDiv.hide();
            // fieldTask.removeClass("col-lg-6").addClass("col-lg-7");

            $('form').submit(function(event) {
                event.preventDefault();
            });

            var projectId = $(".form-add-report #report-project_id"),
                reportDate = $('#date_report'),
                reportText = $('#report-task'),
                reportHours = $('#report-hours'),
                tableLoad = $('.load'),
                formInput = [projectId, reportDate, reportText, reportHours];
            var dataArr = {
                'projectId': '',
                'reportDate': '',
                'reportText': '',
                'reportHours': '',
            };


            ////////////////////////////////////////////////////////////////////////////
            ///Function changes load-table elements and do their input or select elements
            function changeTableRow() {
                var tableLoad = $('.load'),
                    tableLoadRow = tableLoad.find('tr:not(.changed-row)');
                tableLoadRow.each(function() {
                    var thisRow = $(this);
                    thisRow.addClass("changed-row");
                    var thisRowTd = thisRow.find('td');
                    var tableArr = [];
                    thisRowTd.each(function(i) {
                        var thisTd = $(this);
                        var thisValue = thisTd.text();
                        switch (i) {
                            case 1:
                                thisTd.empty();
                                var clonedSelect = projectId.clone();
                                clonedSelect.addClass('report-project-id');
                                thisTd.append(clonedSelect);

                                if (thisTd.hasClass('created-project-id')) {
                                    thisTd.find("option[value = '" + thisValue + "']").prop('selected', true)
                                } else {
                                    thisTd.find("option:contains('" + thisValue + "')").prop('selected', true)
                                }
                                break;
                            case 2:
                                thisTd.empty();
                                thisTd.append('<input class="form-control report-text" type = "text" value = "' + thisValue + '">')
                                break
                            case 3:
                                thisTd.empty();
                                thisTd.append('<input class="form-control report-hour" type = "text" value = "' + thisValue + '">')
                                break
                            case 4:
                                thisTd.empty();
                                thisTd.append('<div class="input-group date"><input class="form-control created-date" data-date-format="dd/mm/yyyy" data-provide="datepicker" type = "text" ><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>');
                                var input = thisTd.find('div');
                                if (!input.find(input).hasClass('created-date')) {
                                    thisValue = thisValue.split('-');
                                    thisValue = thisValue.reverse();
                                    thisValue = thisValue.join('/');
                                }
                                $(input).datepicker({
                                    format: 'dd/mm/yyyy'
                                }).datepicker("setDate", thisValue);
                                break
                        }
                    })
                })
            }

            ////////////////////////////////////////////////////////////////////////////
            ///Function for adding report in load-table and sending data trough ajax/////////

            function addReport() {
                $.each(formInput, function(num) {
                    thisInput = $(this);
                    thisInput.change(function() {
                        var count = 0,
                            thisChange = $(this);
                        saveDataInObject();
                        $.each(dataArr, function(i) {
                            if (dataArr[i].length > 0) {
                                count++;
                            }
                            if (count == 4) {
                                jsonData = JSON.stringify(dataArr);
                                $.ajax({
                                    type: "POST",
                                    url: "index",
                                    data: jsonData,
                                    dataType: 'json',
                                    success: function(data) {},
                                    error: function(data) {
                                        console.log(data);
                                        console.log('error');
                                        tableLoad.append("<tbody><tr><td></td><td class='created-project-id'>" + dataArr.projectId + "</td><td>" + dataArr.reportText + "</td><td>" + dataArr.reportHours + "</td><td>" + dataArr.reportDate + "</td><td><i class='fa fa-times delete' style='cursor: pointer' data-toggle='tooltip' data-placement='top' title='' data-original-title='Delete'></i></td></tr></tbody>");
                                        var form = $('.form-add-report');
                                        form.find('#report-task, #report-hours, .form-add-report #report-project_id').val('');
                                        $.each(dataArr, function(i) {
                                            console.log(dataArr[i]);
                                            delete dataArr[i];
                                        })
                                        changeTableRow();
                                        editReport();
                                        removeReport();
                                        countHours();
                                    }
                                })
                            }
                        })
                    })
                })
            }


            //////////////////////////////////////////////////////////////////////////////
            ///Function saves data from "load-table", when it changing and from /////////
            ///"add-report" table, when report adding //////////////////////////////////
            function saveDataInObject(el) {
                if (el != undefined) {
                    var thisSelect = el.closest('tr').find('select option:selected').val(),
                        dateReport = el.closest('tr').find('.created-date').val(),
                        reportTask = el.closest('tr').find('.report-text').val(),
                        reportHours = el.closest('tr').find('.report-hour').val();
                } else {
                    var thisSelect = $('.form-add-report #report-project_id :selected').val(),
                        dateReport = $('#date_report').val(),
                        reportTask = $('#report-task').val(),
                        reportHours = $('#report-hours').val();
                }

                ///checking entered data, and saving their////////////////////////////////
                if (thisSelect != "") {
                    dataArr.projectId = thisSelect;
                } else {
                    dataArr.projectId = "";
                }

                if (dateReport != "") {
                    dataArr.reportDate = dateReport;

                } else {
                    dataArr.reportDate = "";
                }

                if (reportTask.length >= 20) {
                    dataArr.reportText = reportTask;
                } else {
                    dataArr.reportText = "";
                }
                if (reportHours != "" && reportHours < 10 && reportHours != 0) {
                    dataArr.reportHours = reportHours;
                } else {
                    dataArr.reportHours = "";
                }
                return dataArr;
            }



            function deleteHelpBlock(thisHelp, all) {
                if (all == "all") {
                    var helpSpan = thisHelp.closest('td').find('.help-block');
                    var hasErrorTd = thisHelp.closest('td');
                    helpSpan.remove();
                    hasErrorTd.removeClass('has-error');
                }
                var helpSpan = thisHelp.next('.help-block');
                var hasErrorTd = thisHelp.closest('td');
                helpSpan.remove();
                console.log();
                hasErrorTd.removeClass('has-error');

            }

            ///////////////////////////////////////////////////////////////////////////////////////
            ///Function for editing information in load-table, saves its and sends data trough ajax 
            function editReport() {
                var tableLoadRow = tableLoad.find('tr');
                tableLoadRow.each(function() {
                    var thisChange = $(this).find('td input,td select');
                    thisChange.change(function() {
                        var thisInput = $(this);
                        if (thisInput.hasClass('report-text') && thisInput.val().length < 20 && thisInput.val().length > 0) {
                            if (thisInput.closest('td').hasClass("has-error")) {
                                deleteHelpBlock(thisInput);
                            }
                            thisInput.closest('td').addClass("has-error");
                            thisInput.after('<span class = "help-block" id= "helpblockEr">Task should contain at least 20 characters.</span>');
                        } else if (thisInput.hasClass('report-text') && thisInput.val().length == 0) {
                            if (thisInput.closest('td').hasClass("has-error")) {
                                deleteHelpBlock(thisInput);
                            }
                            thisInput.closest('td').addClass("has-error");
                            thisInput.after('<span class = "help-block" id= "helpblockEr">Task cannot be blank.</span>');
                        } else if (thisInput.hasClass('report-hour') && thisInput.val() > 10) {

                            if (thisInput.closest('td').hasClass("has-error")) {
                                deleteHelpBlock(thisInput);
                            }
                            thisInput.closest('td').addClass("has-error");
                            thisInput.after('<span class = "help-block" id= "helpblockEr">Hours must be no greater than 10.</span>');
                        } else if (thisInput.hasClass('report-hour') && thisInput.val() <= 0) {
                            if (thisInput.closest('td').hasClass("has-error")) {
                                deleteHelpBlock(thisInput);
                            }
                            thisInput.closest('td').addClass("has-error");
                            thisInput.after('<span class = "help-block" id= "helpblockEr">Hours must be no less than 0.1.</span>');
                        } else if (thisInput.hasClass('report-hour') && thisInput.val().length == 0) {
                            if (thisInput.closest('td').hasClass("has-error")) {
                                deleteHelpBlock(thisInput);
                            }
                            thisInput.closest('td').addClass("has-error");
                            thisInput.after('<span class = "help-block" id= "helpblockEr">Task cannot be blank.</span>');
                        } else if (thisInput.hasClass('report-project-id') && thisInput.val() == "") {
                            if (thisInput.closest('td').hasClass("has-error")) {
                                deleteHelpBlock(thisInput);
                            }
                            thisInput.closest('td').addClass("has-error");
                            thisInput.after('<span class = "help-block" id= "helpblockEr">Project ID cannot be blank.</span>');
                        } else {
                            deleteHelpBlock(thisInput, "all");
                            var count = 0;
                            saveDataInObject(thisInput);
                            $.each(dataArr, function(i) {
                                if (dataArr[i] != "") {
                                    count++;
                                }
                            })
                            if (count == 4) {
                                jsonData = JSON.stringify(dataArr);
                                $.ajax({
                                    type: "POST",
                                    url: "index",
                                    data: jsonData,
                                    dataType: 'json',
                                    success: function(data) {
                                        console.log('success');
                                        console.log(data);
                                    },
                                    error: function(data) {
                                        console.log('Data were send:');
                                        $.each(dataArr, function(i) {
                                            console.log(dataArr[i]);
                                            delete dataArr[i];
                                        });
                                        countHours();
                                    }
                                })
                            }
                        }
                    })
                })
            }


            //////////////////////////////////////////////////////////////////////////////////////
            ///Function for removing reports from load-table, saves its and sends data trough ajax 
            function removeReport() {
                var deleteButton = $('.load .delete');
                deleteButton.each(function() {
                    var thisButton = $(this);
                    thisButton.unbind();
                    thisButton.click(function() {
                        var clickedButton = $(this);
                        saveDataInObject(clickedButton);
                        jsonData = JSON.stringify(dataArr);
                        $.ajax({
                            type: "POST",
                            url: "index",
                            data: jsonData,
                            dataType: 'json',
                            success: function(data) {
                                console.log('success');
                                console.log(data);
                            },
                            error: function(data) {
                                console.log('error');
                                console.log('Data were send:');
                                $.each(dataArr, function(i) {
                                    console.log(dataArr[i]);
                                    delete dataArr[i];
                                });
                                clickedButton.parent().parent('tr').remove();
                                countHours();
                            }
                        })
                    })
                })
            }

            function countHours() {
                var totalHours = 0;
                var dateInp = $('.load .date input');
                // var eachReportHours = $('.load tr>td:nth-child(4) input');
                // eachReportHours.each(function() {
                //     var thisHours = $(this);
                //     totalHours += +thisHours.val();
                // })
                var day = new Date();
                var date = (day.getDate()).toString();
                var month = (day.getMonth() + 1).toString();
                var today = (day.getDay()).toString();

                console.log(today);

                var dateFilterVal = $("#dateFilter").val();
                //for today reports
                if (dateFilterVal == 1) {
                    dateInp.each(function() {
                        var thisDate = $(this);
                        var thisDateVal = thisDate.val();
                        var splitDate = thisDateVal.split('/');
                        if (date == parseInt(splitDate[0], 10) && month == parseInt(splitDate[1], 10)) {
                            var hour = thisDate.closest('tr').find('.report-hour').val();
                            totalHours += +hour;
                        }

                    })

                }
                //for this month reports
                else if (dateFilterVal == 3) {
                    dateInp.each(function() {
                        var thisDate = $(this);
                        var thisDateVal = thisDate.val();
                        var splitDate = thisDateVal.split('/');
                        if (month == parseInt(splitDate[1], 10)) {
                            var hour = thisDate.closest('tr').find('.report-hour').val();
                            totalHours += +hour;
                        }

                    })
                }
                //for last manth reports
                else if (dateFilterVal == 4) {
                    dateInp.each(function() {
                        var thisDate = $(this);
                        var thisDateVal = thisDate.val();
                        var splitDate = thisDateVal.split('/');
                        if (month - 1 == parseInt(splitDate[1], 10)) {
                            var hour = thisDate.closest('tr').find('.report-hour').val();
                            totalHours += +hour;
                        }

                    })
                }
                // //for this week reports
                // else if(dateFilterVal == 2){
                //     var week = 7 - today;
                //     dateInp.each(function() {
                //         var thisDate = $(this);
                //         var thisDateVal = thisDate.val();
                //         var splitDate = thisDateVal.split('/');
                //         if (month == parseInt(splitDate[1], 10)) {
                //             var hour = thisDate.closest('tr').find('.report-hour').val();
                //             totalHours += +hour;
                //         }

                //     })
                // }
                var showTotalHours = $('#totalHours');
                showTotalHours.text("Total: " + totalHours + " hours");
            }



            removeReport();
            addReport();
            changeTableRow();
            editReport();
            countHours();

        }
    }

})();
