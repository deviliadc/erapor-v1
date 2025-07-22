<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".datepickerTwo", {
            dateFormat: "Y-m-d",
            allowInput: true,
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
</script>

{{-- <script defer src="bundle.js"></script> --}}
<script defer src="{{ asset('tailadmin/build/bundle.js') }}"></script>
<!-- JS flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@stack('scripts')
