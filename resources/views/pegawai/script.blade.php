<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverside: true,
            ajax: "{{ url('pegawaiAjax') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            }, {
                data: 'nama',
                name: 'Nama'
            }, {
                data: 'email',
                name: 'Email'
            }, {
                data: 'aksi',
                name: 'Aksi'
            }]
        });
    });
    // GLOBAL SETUP
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 02_PROSES SIMPAN
    $('body').on('click', '.btnTambah', function(e) {
        e.preventDefault();
        $('#exampleModal').modal('show');

        $('.btnSimpan').click(function() {
            // e.preventDefault();
            // var nama = $('#nama').val();
            // var email = $('#email').val();
            // console.log(nama + ' - ' + email);
            simpan();
        });
    });
    // END 02_PROSES SIMPAN

    // 03_PROSES EDIT
    $('body').on('click', '.btnEdit', function(e) {
        let id = $(this).data('id');
        // alert('id isi ' + id);
        $.ajax({
            type: "GET",
            url: "pegawaiAjax/" + id + "/edit",
            success: function(response) {
                $('#exampleModal').modal('show');
                $('#nama').val(response.result.nama);
                $('#email').val(response.result.email);
                console.log(response.result);
                $('.btnSimpan').click(function() {
                    simpan(id);
                });
            }
        });
    });
    // END 03_PROSES EDIT

    // 04_PROSES DELETE
    $('body').on('click', '.btnDel', function(e) {
        // alert('DELETE');
        if (confirm('Yakin mau hapus data ini ?') == true) {
            var id = $(this).data('id');
            $.ajax({
                type: "DELETE",
                url: "pegawaiAjax/" + id,
            });
            $('#myTable').DataTable().ajax.reload();
        }
    });
    // END 04_PROSES DELETE

    // Function SIMPAN dan UPDATE
    function simpan(id = '') {
        if (id == '') {
            var var_url = 'pegawaiAjax';
            var var_type = 'POST';
        } else {
            var var_url = 'pegawaiAjax/' + id;
            var var_type = 'PUT';
        }
        $.ajax({
            type: var_type,
            url: var_url,
            data: {
                nama: $('#nama').val(),
                email: $('#email').val()
            },
            success: function(response) {
                if (response.errors) {
                    console.log(response.errors);
                    $('.alert-danger').removeClass('d-none');
                    $('.alert-danger').append("<ul>");
                    $.each(response.errors, function(key, value) {
                        $('.alert-danger').find('ul').append("<li>" + value +
                            "</li>");
                    });
                    $('.alert-danger').append("</ul>");
                } else {
                    $('.alert-success').removeClass('d-none');
                    $('.alert-success').html(response.success);
                }
                $('#myTable').DataTable().ajax.reload();
            }

        });
    }
    // END Function SIMPAN dan UPDATE

    // Hidden Modal
    $('#exampleModal').on('hidden.bs.modal', function() {
        // alert('Close');
        $('#nama').val('');
        $('#email').val('');

        $('.alert-danger').addClass('d-none');
        $('.alert-danger').html('');

        $('.alert-success').addClass('d-none');
        $('.alert-success').html('');
    });
    // End Hidden Modal
</script>
