    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('frontend/assets/js/scripts.js') }}"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script> -->
    <script>
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
