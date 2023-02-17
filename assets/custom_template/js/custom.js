//Initialize Select2 Elements
$(".select2").select2();

//Initialize Select2 Elements
$(".select2bs4").select2({
  theme: "bootstrap4",
});

//Datemask dd/mm/yyyy
$("#datemask").inputmask("dd/mm/yyyy", { placeholder: "dd/mm/yyyy" });
//Datemask2 mm/dd/yyyy
$("#datemask2").inputmask("mm/dd/yyyy", { placeholder: "mm/dd/yyyy" });
//Money Euro
$("[data-mask]").inputmask();

//Date picker
$(".date_picker").datetimepicker({
  format: "L",
});
//Timepicker
$(".time_picker").datetimepicker({
  format: "LT",
});

//Date range picker
$(".date_time_range").daterangepicker({
  timePicker: true,
  timePickerIncrement: 30,
  locale: {
    format: "DD/MM/YYYY hh:mm A",
  },
});
//Date range picker with time picker
$(".date_range").daterangepicker({
  timePicker: false,
  timePickerIncrement: 30,
  locale: {
    format: "DD/MM/YYYY",
  },
});

//Colorpicker
$(".my-colorpicker1").colorpicker();
//color picker with addon
$(".my-colorpicker2").colorpicker();

$(".my-colorpicker2").on("colorpickerChange", function (event) {
  $(".my-colorpicker2 .fa-square").css("color", event.color.toString());
});

$("input[data-bootstrap-switch]").each(function () {
  $(this).bootstrapSwitch("state", $(this).prop("checked"));
});

$("#summernote").summernote();

bsCustomFileInput.init();

toastr.options = {
  closeButton: false,
  debug: false,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-top-right", //toast-top-center
  preventDuplicates: false,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

$(document).on('click', '#open_information', function(){
    let informasi_url = $(this).data('url');
    $.ajax({
        type: 'post',
        cache: false,
        url: informasi_url,
        success: function (data) {
            $('#preview-file .modal-body').html(data);
            $('#preview-file').modal('show');
        }
    });
})
