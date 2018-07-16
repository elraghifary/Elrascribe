$(document).ready(function () {
  $('.sidebar-menu').tree();
  $('[data-toggle="tooltip"]').tooltip();
  var table = $('#notulis').DataTable({
    initComplete: function () {
      this.api().columns([4]).every( function() {
        var column = this;
        var select = $("#statusFilter");
        column.data().unique().sort().each( function(d, j) {
          select.append('<option value="'+d+'">'+d+'</option>')
        });
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
    "lengthMenu": [ 10, 25 ],
    "dom": '<"top"Bf>tlp<"clear">i',
    "order": [[ 0, "desc" ]],
    "buttons": [
      'colvis',
      {
        extend: 'excelHtml5',
        title: 'Notulis',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
        }
      },
      {
        extend: 'pdfHtml5',
        title: 'Notulis',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5]
        }
      },
      {
        extend: 'print',
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
        "colvis": 'Filter'
      }
    },
    "ajax": {
      "url": "data.php",
      "type": "POST"
    },
    "columns": [
      { "data": "n" },
      { "data": "nama" },
      { "data": "email" },
      { "data": "telepon" },
      { "data": "status" },
      { "data": "button" }
    ],
    "columnDefs": [
      { "responsivePriority": 1, targets: 0 },
      { "responsivePriority": 2, targets: -1 },
      { "responsivePriority": 3, targets: 4 },
      {
        "render": function(data, type, row) {
          if (data == "1") {
            return "<button type='submit' id='"+row['id']+"' data-status='"+row['status']+"' data-name='"+row['nama']+"' data-email='"+row['email']+"' class='btn btn-xs btn-success btn-update-notulis' title='Klik untuk mengnonaktifkan'>Aktif</button>";
          } else if (data == "0") {
            return "<button type='submit' id='"+row['id']+"' data-status='"+row['status']+"' data-name='"+row['nama']+"' data-email='"+row['email']+"' class='btn btn-xs btn-info btn-update-notulis' title='Klik untuk mengaktifkan'>Menunggu Persetujuan</button>";
          }
        },
        "targets": 4
      }
    ]
  });

  $('#statusFilter').on('change', function() {
    var search = [];

    $.each($('#statusFilter option:selected'), function() {
        search.push($(this).val());
    });

    search = search.join('|');
    table.column(4).search(search, true, false).draw();
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
});

$(document).on( "click", ".btn-delete-notulis", function() {
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
            var table = $('#notulis').DataTable();
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

$(document).on( "click", ".btn-update-notulis", function() {
  var id = $(this).attr("id");
  var status = $(this).attr("data-status");
  var name = $(this).attr("data-name");
  var email = $(this).attr("data-email");

  var value = {
    id: id,
    status: status,
    name: name,
    email: email
  };
  $.ajax({
    url : "update.php",
    type: "POST",
    data : value,
    success: function(data, textStatus, jqXHR) {
      var data = jQuery.parseJSON(data);
      if (data.result == 1) {
        $( function() {
          PNotify.prototype.options.styling = "bootstrap3";
          new PNotify({
            title: 'Info',
            text: 'Data telah berhasil diubah.',
            type: 'success'
          });
        });
        var table = $('#notulis').DataTable();
        table.ajax.reload(null, false);
      } else {
        $( function() {
          PNotify.prototype.options.styling = "bootstrap3";
          new PNotify({
            title: 'Failed',
            text: 'Terjadi kesalahan. Data tidak dapat diubah. Pesan: '+data.error,
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