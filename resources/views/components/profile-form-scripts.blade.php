<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-yes-no-field]').forEach(function (field) {
            const radios = field.querySelectorAll('.yes-no-radio');
            const detail = field.querySelector('.yes-no-detail');

            function toggleDetail() {
                if (!detail) return;
                const isYes = field.querySelector('.yes-no-radio[value="1"]')?.checked;
                detail.classList.toggle('d-none', !isYes);
            }

            radios.forEach(function (radio) {
                radio.addEventListener('change', toggleDetail);
            });

            toggleDetail();
        });

        const birthDateInput = document.getElementById('birth_date');
        const ageInput = document.getElementById('age');

        if (birthDateInput && ageInput) {
            birthDateInput.addEventListener('change', function () {
                if (!this.value) return;
                const birth = new Date(this.value + 'T00:00:00');
                const today = new Date();
                let age = today.getFullYear() - birth.getFullYear();
                const monthDiff = today.getMonth() - birth.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                    age--;
                }
                if (age >= 0 && age <= 99) {
                    ageInput.value = age;
                }
            });
        }
    });
</script>
