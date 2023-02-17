$(document).on('keyup', 'div.editable-input input[type=text], input.number-rupiah', function(e){
  if (!$(this).hasClass('hasDatepicker')) {
    var value = formatRupiah($(this).val(), 'keyup');
    $(this).val(value);
  }
})

function formatRupiah(angka, key, prefix){
  var number_string = angka.replace(/[^,\d]/g, '').toString(),
  split           = number_string.split(','),
  sisa             = split[0].length % 3,
  rupiah             = split[0].substr(0, sisa),
  ribuan             = split[0].substr(sisa).match(/\d{3}/gi);

  if(ribuan){
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
  }

  if (key == 'keyup') {
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }else{
    return rupiah;
  }
}

function rupiah(bilangan){
  var minus = 0;
  var result = '';
  var	number_string = bilangan.toString();
  var  sign = number_string.charAt(0);
  result = number_string
  if (sign == '-') {
    result = number_string.replace('-', '')
    minus = 1;
  }
  var  sisa 	= result.length % 3,
    rupiah 	= result.substr(0, sisa),
    ribuan 	= result.substr(sisa).match(/\d{3}/g);

  if (ribuan) {
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
    if (sign == '-') {
      rupiah = '-' + rupiah;
    }
  }

  return rupiah
}

function number_test(n){
  var result = (n - Math.floor(n)) !== 0;
  if (result)
    return true;
  else
    return false;
}
