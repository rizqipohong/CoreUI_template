$("#formInput").submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: getUrl,
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        success: function (response) {
            if (response.success === true) {
                toastr.success(response.message)
                window.setTimeout(function() {
                  window.location.reload(true);
                }, 1000);
            } else {
                toastr.error(response.message)
            }
        }
    });

});

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});