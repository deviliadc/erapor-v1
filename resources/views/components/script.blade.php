<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            toggle: false
        });
    });
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Init flatpickr
    //     flatpickr(".datepicker", {
    //         altInput: true,
    //         altFormat: "d-m-Y", // yang dilihat user
    //         dateFormat: "Y-m-d", // yang dikirim ke server
    //         allowInput: true,
    //         maxDate: new Date(), // Tidak bisa pilih tanggal setelah hari ini
    //         defaultDate: document.querySelector('.datepicker')?.value ||
    //     });

    //     // Untuk presensi harian (tidak boleh Sabtu/Minggu & tidak boleh setelah hari ini)
    //     flatpickr(".datepicker-presensi", {
    //         altInput: true,
    //         altFormat: "d-m-Y",
    //         dateFormat: "Y-m-d",
    //         allowInput: true,
    //         maxDate: new Date(),
    //         // defaultDate: document.querySelector('.datepicker').value
    //         disable: [
    //             function(date) {
    //                 // Disable Sabtu (6) dan Minggu (0)
    //                 return date.getDay() === 6 || date.getDay() === 0;
    //             }
    //         ],
    //     });
    // });
    document.addEventListener('DOMContentLoaded', function() {
        // Datepicker umum
        // document.querySelectorAll('.datepicker').forEach(function(el) {
        //     flatpickr(el, {
        //         altInput: true,
        //         altFormat: "d-m-Y",
        //         dateFormat: "Y-m-d",
        //         allowInput: true,
        //         maxDate: new Date(),
        //         defaultDate: (el.value && /^\d{4}-\d{2}-\d{2}$/.test(el.value.trim())) ? el
        //             .value.trim() : new Date()
        //     });
        // });

        // Untuk tanggal umum (contoh: tanggal lahir) -> biarkan kosong kalau tidak ada
        // flatpickr(".datepicker", {
        //     altInput: true,
        //     altFormat: "d-m-Y",
        //     dateFormat: "Y-m-d",
        //     defaultDate: function(selectedDates, dateStr, instance) {
        //         return dateStr || null; // kalau ada di input, pakai; kalau tidak, biarkan kosong
        //     }
        // });
        // document.querySelectorAll('.datepicker').forEach(function(el) {
        //     flatpickr(el, {
        //         altInput: true,
        //         altFormat: "d-m-Y", // Tampil di form: hari-bulan-tahun
        //         dateFormat: "Y-m-d", // Value yang dikirim ke server: tahun-bulan-hari
        //         allowInput: true,
        //         maxDate: new Date(),
        //         defaultDate: (el.value && /^\d{4}-\d{2}-\d{2}$/.test(el.value.trim())) ? el
        //             .value.trim() : null
        //     });
        // });

        document.querySelectorAll('.datepicker').forEach(function(el) {
            flatpickr(el, {
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                allowInput: true,
                defaultDate: (el.dataset.defaultDate && /^\d{4}-\d{2}-\d{2}$/.test(el.dataset.defaultDate)) ? el.dataset.defaultDate : null,
                maxDate: null
            });
        });


        // Datepicker presensi (tidak boleh Sabtu/Minggu)
        document.querySelectorAll('.datepicker-presensi').forEach(function(el) {
            flatpickr(el, {
                altInput: true,
                altFormat: "d-m-Y",
                dateFormat: "Y-m-d",
                allowInput: true,
                maxDate: new Date(),
                defaultDate: (el.dataset.defaultDate && /^\d{4}-\d{2}-\d{2}$/.test(el.dataset.defaultDate)) ? el.dataset.defaultDate : null,
                disable: [
                    function(date) {
                        return date.getDay() === 6 || date.getDay() === 0;
                    }
                ],
            });
        });
    });




    function dropdown() {
        return {
            options: [],
            selected: [],
            show: false,
            open() {
                this.show = true;
            },
            close() {
                this.show = false;
            },
            isOpen() {
                return this.show === true;
            },
            select(index, event) {
                if (!this.options[index].selected) {
                    this.options[index].selected = true;
                    this.options[index].element = event.target;
                    this.selected.push(index);
                } else {
                    this.selected.splice(this.selected.lastIndexOf(index), 1);
                    this.options[index].selected = false;
                }
            },
            remove(index, option) {
                this.options[option].selected = false;
                this.selected.splice(index, 1);
            },
            loadOptions() {
                const options = document.getElementById("select").options;
                for (let i = 0; i < options.length; i++) {
                    this.options.push({
                        value: options[i].value,
                        text: options[i].innerText,
                        selected: options[i].getAttribute("selected") !== null,
                    });
                }
            },
            selectedValues() {
                return this.selected.map(option => this.options[option].value);
            },
        };
    }
    @if (session('open_modal_id'))
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: '{{ session('open_modal_id') }}'
        }))
    @endif
</script>

<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script defer src="{{ asset('tailadmin/build/bundle.js') }}"></script>
<!-- JS flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
@stack('scripts')
