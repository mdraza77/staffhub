{{-- SweetAlert2 Index --}}
@include('sweetalert2::index')

{{-- jQuery Script --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

{{-- DataTable JS Files --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.tailwindcss.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

{{-- All tables datatable js is here --}}
<script src="{{ asset('js/datatable-config.js') }}"></script>

<script>
    function setupPhoneValidation(inputElement) {
        if (!inputElement) return;

        // Create warning span
        const warnSpan = document.createElement('span');
        warnSpan.className = 'text-xs text-red-500 mt-1 hidden block';
        warnSpan.textContent = 'Phone number must be exactly 10 digits.';
        inputElement.parentNode.appendChild(warnSpan);

        // Block non-numeric keypresses
        inputElement.addEventListener('keypress', function (e) {
            const char = String.fromCharCode(e.which || e.keyCode);

            // Allow only digits (0-9)
            if (!/^[0-9]$/.test(char)) {
                e.preventDefault();
                return;
            }

            // Limit maximum character length to 10
            if (this.value.length >= 10) {
                e.preventDefault();
            }
        });

        // Handle paste and other input changes (sanitize non-numeric)
        const validate = function (input) {
            let val = input.value.replace(/[^0-9]/g, '');

            // Enforce max length limit
            if (val.length > 10) {
                val = val.slice(0, 10);
            }

            input.value = val;

            // Perform validation
            if (val === '') {
                input.classList.remove('border-red-500');
                warnSpan.classList.add('hidden');
                input.setCustomValidity('');
                return;
            }

            if (val.length !== 10) {
                input.classList.add('border-red-500');
                warnSpan.classList.remove('hidden');
                input.setCustomValidity('Must be exactly 10 digits.');
            } else {
                input.classList.remove('border-red-500');
                warnSpan.classList.add('hidden');
                input.setCustomValidity('');
            }
        };

        inputElement.addEventListener('input', function () {
            validate(this);
        });

        // Validate on load if has initial value
        if (inputElement.value.trim() !== '') {
            validate(inputElement);
        }
    }
</script>

@stack('scripts')
</body>

</html>