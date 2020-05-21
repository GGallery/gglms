_scaricaattesati = (function ($, my) {
        var diff_days = 0;
        var diff_days_thresold = 31;

        function _init() {


            console.log('init scarica attestati ok');
            $("#btn-download").click(_downloadAttestati);

            $("#btn-download").click(_downloadAttestati);

            $("#id_corso").change(function () {

                var id_corso = $('#id_corso').val();
                if (id_corso != -1 && diff_days > 0 && diff_days <= diff_days_thresold) {

                    $("#btn-download").removeClass('disabled');
                } else {
                    $("#btn-download").addClass('disabled');

                }

            });

            $("#startdate").change(getDays);
            $("#finishdate").change(getDays)
        }

        function _downloadAttestati() {


            var id_corso = $('#id_corso').val();

            var url = "index.php?option=com_gglms&task=attestatibulk.dwnl_attestati_by_corso&id_corso= " + id_corso;


            url = $("#startdate").val() ? url + "&startdate=" + $("#startdate").val() : url;
            url = $("#finishdate").val() ? url + "&enddate=" + $("#finishdate").val() : url;


            $("#btn-download").removeClass('disabled');
            $("#btn-download").attr("href", url);


        }

        function _getDays() {

            // alert('getDays');

            var start = $("#startdate").val();
            var end = $("#finishdate").val();


            // To set two dates to two variables
            var date1 = new Date(start);
            var date2 = end != "" ? new Date(end) : new Date();

            // To calculate the time difference of two dates
            var Difference_In_Time = date2.getTime() - date1.getTime();

            // To calculate the no. of days between two dates
            diff_days = Difference_In_Time / (1000 * 3600 * 24);

            console.log('Difference_In_Days', diff_days);


            if (diff_days > diff_days_thresold) {

                 _showMsg( Joomla.JText._('COM_GGLMS_ATTESTATI_BULK_MAX_LIMIT').replace('{gg}',diff_days_thresold )  );
                $("#btn-download").addClass('disabled');
            }

            if (diff_days < 0) {
                // _showMsg('Seleziona una data di fine maggiore della data di inizio');
                _showMsg(Joomla.JText._('COM_GGLMS_ATTESTATI_BULK_MAX_LIMIT'));
                $("#btn-download").addClass('disabled');
            }


        }

        function _showMsg(text){
            $("#msg").text('');
            $("#msg").text(text);
            $("#msg").show();

        }


        // public methods
        my.init = _init;
        my.getDays = _getDays;
        return my;

    }

)(jQuery, this);

