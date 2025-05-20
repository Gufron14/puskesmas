<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/moment.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/simplebar.min.js') }}"></script>
<script src='{{ asset('backend/assets/js/jquery.stickOnScroll.js') }}'></script>
<script src="{{ asset('backend/assets/js/tinycolor-min.js') }}"></script>
<script src="{{ asset('backend/assets/js/config.js') }}"></script>
<script src="{{ asset('backend/assets/js/d3.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/topojson.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.sparkline.min.js') }}"></script>
<script src='{{ asset('backend/assets/js/jquery.mask.min.js') }}'></script>
<script src='{{ asset('backend/assets/js/jquery.steps.min.js') }}'></script>
<script src='{{ asset('backend/assets/js/jquery.validate.min.js') }}'></script>
<script src='{{ asset('backend/assets/js/jquery.timepicker.js') }}'></script>
<script src="{{ asset('backend/assets/js/apps.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    function deleteActivity(id) {
        event.preventDefault();

        const formId = `Hapus${id}`;
        const form = document.getElementById(formId);

        Swal.fire({
            title: 'Apakah Anda Yakin ?',
            text: 'Data Akan Terhapus Secara Permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a746',
            cancelButtonColor: '#FF0000',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function Logout() {
        event.preventDefault();
        const Logout = document.getElementById('logout-form');

        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda akan keluar dari akun ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2E93fA',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#logout-form').submit();
            }
        });

    }
</script>
