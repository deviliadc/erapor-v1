<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            toggle: false
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Init flatpickr
        flatpickr(".datepicker", {
            altInput: true,
            altFormat: "d-m-Y", // yang dilihat user
            dateFormat: "Y-m-d", // yang dikirim ke server
            allowInput: true,
            maxDate: new Date(), // Tidak bisa pilih tanggal setelah hari ini
        });

        // flatpickr(".datepicker-lahir", {
        //     altInput: true,
        //     altFormat: "d-m-Y",
        //     dateFormat: "Y-m-d",
        //     allowInput: true,
        //     maxDate: new Date(), // Tidak bisa pilih tanggal setelah hari ini
        // });

        // Untuk presensi harian (tidak boleh Sabtu/Minggu & tidak boleh setelah hari ini)
        flatpickr(".datepicker-presensi", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            allowInput: true,
            maxDate: new Date(),
            disable: [
                function(date) {
                    // Disable Sabtu (6) dan Minggu (0)
                    return date.getDay() === 6 || date.getDay() === 0;
                }
            ],
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
@stack('scripts')
