function load_table(page_number = 1) {
  var limit_data = $('select[name="limit"] option:selected').val();
  var total_number = (page_number - 1) * limit_data;
  page_number = page_number == 1 ? 1 : total_number;
  $('#loading_table').addClass('d-none')
  // $('#loading_table').removeClass('d-none')
  $("#tbody").html("");
  $("#pagination").children().remove();
  $("#row").html("");
  Pace.track(function() {
    $.ajax({
      type: "POST",
      url: $('form[name="form-table"]').attr("data-url") + page_number,
      data: $('form[name="form-table"]').serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status == 1) {
            // $('#ID_divtable').addClass('double-scroll');
            $('#ID_divtable').doubleScroll({
                resetOnWindowResize: true,
                timeToWaitForResize: 50,
                onlyIfScroll: true
            });
            // $('#loading_table').addClass('d-none')
            $("#btnFormat").attr("data-url", res.format_link);
            var html = showData(res.data, res.offset);
            $("#tbody").empty();
            $("#pagination").empty();
            $("#row").empty();
            $("#tbody").append(html);
            $("#pagination").append(res.paginate);
            $("#row").append(res.row);
          }else{
            $('#loading_table').addClass('d-none')
            $("#tbody").append('<tr> <td colspan="30" class="text-center" >Data Tidak Ditemukan</td> </tr>');
          }
        },
        error: function (res) {
          console.log($('form[name="form-table"]').serialize());
        },
      });
  });
}

function load_table_mt(page_number = 1) {
  var limit_data = $('select[name="limit"] option:selected').val();
  var total_number = (page_number - 1) * limit_data;
  page_number = page_number == 1 ? 1 : total_number;
  $('#loading_table').addClass('d-none')
  // $('#loading_table').removeClass('d-none')
  $("#tbody").html("");
  $("#pagination").children().remove();
  $("#row").html("");
  $(".card-mt").text("")
  Pace.track(function() {
    $.ajax({
      type: "POST",
      url: $('form[name="form-table"]').attr("data-url") + page_number,
      data: $('form[name="form-table"]').serialize(),
      dataType: "json",
      success: function (res) {
        if (res.td_sme_status == 1) {
          card_mt(res.td_sme)
        }

        if (res.status == 1) {
            $("#btnFormat").attr("data-url", res.format_link);
            var html = showData(res.data, res.offset);
            var foot = showFoot(res.plan_potency, res.td_sme.t_plan_potency);
            $("#tfoot").empty();
            $("#tfoot").append(foot);
            $("#tbody").empty();
            $("#pagination").empty();
            $("#row").empty();
            $("#tbody").append(html);
            $("#pagination").append(res.paginate);
            $("#row").append(res.row);
          }else{
            $('#loading_table').addClass('d-none')
            $("#tbody").append('<tr> <td colspan="30" class="text-center" >Data Tidak Ditemukan</td> </tr>');
            $("#tfoot").empty();
          }
        },
        error: function (res) {
          console.log($('form[name="form-table"]').serialize());
        },
      });
  });
}

function disable_pagination() {
  $("#pagination").on("click", "a", function (e) {
    e.preventDefault();
    var page = $(this).attr("data-ci-pagination-page");
    load_table(page);
  });
}


  function get_modal(data, url, element) {
    var l = Ladda.create(element);
    l.start();
      $.ajax({
          url: url,
          method: "POST",
          data: data,
          success: function(data){
            l.stop()
            $('#dataModalForm').html(data);
            $('#ModalForm').modal('show');
          }
      });
  }

  // function get_modal(data, url) {
  //     $.ajax({
  //         url: url,
  //         method: "POST",
  //         data: data,
  //         success: function(data){
  //             $('#dataModalForm').html(data);
  //             $('#ModalForm').modal('show');
  //         }
  //     });
  // }

  $(document).on('click', '.clear-filter', function(){
    var url = base_url + 'SME_Master_Data/Data_Lender/clear_filter'
    var session = $(this).data('session');
    $.ajax({
      type: "POST",
      url: url,
      data: {session : session},
      dataType: "json",
      success: function (res) {
        location.reload(true);
      }
    });
  })
