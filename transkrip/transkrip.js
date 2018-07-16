try {
  var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  var recognition = new SpeechRecognition();
}
catch(e) {
  console.error(e);
  $('.no-browser-support').show();
  $('.app').hide();
}

$(document).ready(function () {
  $('#txtDate').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayBtn: 'linked',
    language: 'id'
  });
  $('#txtTime').inputmask("h:s",{ "placeholder": "hh/mm" });
  $('.sidebar-menu').tree();
  var table = $('#transcript').DataTable({
    initComplete: function () {
      this.api().columns([1]).every( function() {
        var column = this;
        var select = $("#projectNameFilter");
        column.data().unique().sort().each( function(d, j) {
          select.append('<option value="'+d+'">'+d+'</option>')
        });
      });
      this.api().columns([5]).every( function() {
        var column = this;
        var select = $("#moderatorFilter");
        column.data().unique().sort().each( function(d, j) {
          select.append('<option value="'+d+'">'+d+'</option>')
        });
      });
      this.api().columns([3]).every( function() {
        var column = this;
        var select = $("#monthFilter");
        moment.locale('id');
        var month = moment.months();
        for (var i = 0; i < month.length; i++) {
          select.append('<option value="'+month[i]+'">'+month[i]+'</option>')
        }
      });
    },
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "responsive": true,
    "autoWidth": true,
    "pageLength": 10,
    "dom": '<"top"Bf>tlp<"clear">i',
    "order": [[ 0, "desc" ]],
    "buttons": [
      'colvis',
      {
        extend: 'excelHtml5',
        title: 'Transkrip',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
        }
      },
      {
        extend: 'pdfHtml5',
        title: 'Transkrip',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
        }
      },
      {
        extend: 'print',
        title: 'Transkrip',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
        }
      }
    ],
    "language": {
      "sProcessing":   "Sedang memproses...",
      "sLengthMenu":   "Tampilkan _MENU_ entri",
      "sZeroRecords":  "Tidak ditemukan data yang sesuai",
      "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
      "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
      "sInfoPostFix":  "",
      "sSearch":       "Cari:",
      "sUrl":          "",
      "oPaginate": {
        "sFirst":    "Pertama",
        "sPrevious": "<<",
        "sNext":     ">>",
        "sLast":     "Terakhir"
      },
      "buttons": {
        "colvis": 'Kolom'
      }
    },
    "ajax": {
      "url": "data.php",
      "type": "POST"
    },
    "columns": [
      { "data": "n" },
      { "data": "proyek" },
      { "data": "idi" },
      { "data": "tanggal" },
      { "data": "hari" },
      { "data": "moderator" },
      { "data": "button" }
    ],
    "columnDefs": [
      { "responsivePriority": 1, targets: 0 },
      { "responsivePriority": 2, targets: -1 },
      {
        "render": function(data, type, row){
          moment.locale('id');
          return moment(data).format("DD MMMM YYYY");
        },
        "targets": 3
      },
      {
        "render": function(data, type, row) {
            return data + ' / ' + row.waktu;
        },
        "targets": 4
      }
    ]
  });

  $('#projectNameFilter').on('change', function() {
    var search = [];

    $.each($('#projectNameFilter option:selected'), function() {
        search.push($(this).val());
    });

    search = search.join('|');
    table.column(1).search(search, true, false).draw();
  });

  $('#moderatorFilter').on('change', function() {
    var search = [];

    $.each($('#moderatorFilter option:selected'), function() {
        search.push($(this).val());
    });

    search = search.join('|');
    table.column(5).search(search, true, false).draw();
  });

  $('#monthFilter').on('change', function() {
    var search = [];

    $.each($('#monthFilter option:selected'), function() {
        search.push($(this).val());
    });

    search = search.join('|');
    table.column(3).search(search, true, false).draw();
  });

  table.columns().every( function () {
    var that = this;
    $('input', this.footer()).on('keyup change', function () {
      if (that.search() !== this.value) {
        that
          .search( this.value )
          .draw();
      }
    });
  });

  $(document)
  .one('focus.autoExpand', 'textarea.autoExpand', function() {
    var savedValue = this.value;
    this.value = '';
    this.baseScrollHeight = this.scrollHeight;
    this.value = savedValue;
  })
  .on('input.autoExpand', 'textarea.autoExpand', function() {
    var minRows = this.getAttribute('data-min-rows')|0, rows;
    this.rows = minRows;
    rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
    this.rows = minRows + rows;
  });
});

$(document).on( "click", ".btn-delete-transcript", function() {
  var id = $(this).attr("id");
  $( function() {
    PNotify.prototype.options.styling = "bootstrap3";
    new PNotify({
      text: 'Apakah Anda yakin ingin menghapus data ini?',
      icon: 'glyphicon glyphicon-question-sign',
      hide: false,
      confirm: {
        confirm: true
      },
      buttons: {
        closer: false,
        sticker: false
      },
      history: {
        history: false
      }
    }).get().on('pnotify.confirm', function() {
      var value = {
        id: id
      };
      $.ajax({
        url : "delete.php",
        type: "POST",
        data : value,
        success: function(data, textStatus, jqXHR) {
          var data = jQuery.parseJSON(data);
          if (data.result == 1) {
            $( function() {
              PNotify.prototype.options.styling = "bootstrap3";
              new PNotify({
                title: 'Info',
                text: 'Data telah berhasil dihapus.',
                type: 'success'
              });
            });
            var table = $('#transcript').DataTable();
            table.ajax.reload(null, false);
          } else {
            $( function() {
              PNotify.prototype.options.styling = "bootstrap3";
              new PNotify({
                title: 'Failed',
                text: 'Terjadi kesalahan. Data tidak dapat dihapus. Pesan: '+data.error,
                type: 'error'
              });
            });
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $( function() {
            PNotify.prototype.options.styling = "bootstrap3";
            new PNotify({
              title: 'Error',
              text: textStatus,
              type: 'error'
            });
          });
        }
      });
    });
  });
});

$(document).on("click", "#btn-save", function() {
  var id = $("#txtid").val();
  var notulisID = $("#txtUserID").val();
  var name = $("#txtName").val();
  var idi = $("#txtIDI").val();
  var date = $("#txtDate").val();
  var day = $("#txtDay").val();
  var time = $("#txtTime").val();
  var moderator = $("#txtModerator").val();
  var criteria = $("#txtCriteria").val();
  var body = CKEDITOR.instances.txtBody.getData();
  var crud = $("#crudmethod").val();

  $('#ftranscript').formValidation({
    framework: 'bootstrap',
    live: 'enabled',
    message: 'Undefined Value',
    icon: {
      valid: null,
      invalid: null,
      validating: null
    },
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          }
        }
      },
      idi: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          },
          numeric: {
            message: 'Silahkan masukkan angka saja.',
            thousandsSeparator: '',
            decimalSeparator: '.'
          }
        }
      },
      date: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          }
        }
      },
      day: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          }
        }
      },
      time: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          }
        }
      },
      moderator: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          },
          regexp: {
            regexp: /^[a-z\s]+$/i,
            message: 'Silahkan masukkan huruf dan spasi saja.'
          }
        }
      },
      criteria: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          }
        }
      },
      body: {
        validators: {
          notEmpty: {
            message: "Kolom ini tidak boleh kosong."
          }
        }
      },
    }
  })
  .off('success.form.fv')
  .on('err.field.fv', function(e, data) {
    if (data.fv.getSubmitButton()) {
      data.fv.disableSubmitButtons(false);
    }
  })
  .on('success.form.fv', function(e, data) {
    e.preventDefault();
    var value = {
      id: id,
      name: name,
      idi: idi,
      date: date,
      day: day,
      time: time,
      moderator: moderator,
      criteria: criteria,
      body: body,
      crud: crud
    };
    $.ajax({
      url : "save.php",
      type: "POST",
      data : value,
      success: function(data, textStatus, jqXHR) {
        var data = jQuery.parseJSON(data);
        if (data.crud == 'N') {
          if (data.result == 1) {
            $(function() {
              PNotify.prototype.options.styling = "bootstrap3";
              new PNotify({
                title: 'Info',
                text: 'Data telah berhasil ditambah.',
                type: 'success'
              });
            });
            var table = $('#transcript').DataTable();
            table.ajax.reload(null, false);
          } else {
            $( function() {
              PNotify.prototype.options.styling = "bootstrap3";
              new PNotify({
                title: 'Failed',
                text: 'Terjadi kesalahan. Data tidak dapat ditambah. Pesan: '+data.error,
                type: 'error'
              });
            });
          }
        } else if (data.crud == 'E') {
          if (data.result == 1) {
            $( function() {
              PNotify.prototype.options.styling = "bootstrap3";
              new PNotify({
                title: 'Info',
                text: 'Data telah berhasil diubah.',
                type: 'success'
              });
            });
            var table = $('#transcript').DataTable();
            table.ajax.reload(null, false);
          } else{
            $( function() {
              PNotify.prototype.options.styling = "bootstrap3";
              new PNotify({
                title: 'Failed',
                text: 'Terjadi kesalahan. Data tidak dapat diubah. Pesan: '+data.error,
                type: 'error'
              });
            });
          }
        } else{
          $( function() {
            PNotify.prototype.options.styling = "bootstrap3";
            new PNotify({
              title: 'Error',
              text: 'Undefined.',
              type: 'error'
            });
          });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $( function() {
          PNotify.prototype.options.styling = "bootstrap3";
          new PNotify({
            title: 'Error',
            text: textStatus,
            type: 'error'
          });
        });
      }
    });
  });
});

$(document).on("click", ".btn-view", function() {
  var id = $(this).attr("id");
  window.location.assign("lihat.php?id="+id);
});

$(document).on("click", ".btn-edit", function() {
  var id = $(this).attr("id");
  window.location.assign("ubah.php?id="+id);
});
