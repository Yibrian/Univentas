 @push('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('alert', (event) => {
            Swal.fire({
                title: event.detail[0].title,
                text: event.detail[0].message,
                icon: event.detail[0].type,
                showConfirmButton: false,
                timer: 1500
            });
        });

        window.addEventListener('toast', (event) => {
            var Toast = Swal.mixin({
                 toast: true,
                 position: "top-end",
                 showConfirmButton: false,
                 timer: 4000,
                 timerProgressBar: false,
                 didOpen: (toast) => {
                     toast.onmouseenter = Swal.stopTimer;
                     toast.onmouseleave = Swal.resumeTimer;
                 }
             });
             Toast.fire({
                 icon: event.detail[0].type,
                 title: event.detail[0].title,
                 text: event.detail[0].message,
             });
        });
        
        window.addEventListener('reload', (event) => {
            setTimeout(function() {
                location.reload();
            }, 1000);

        });

    });
</script>
@endpush

