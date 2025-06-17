 const inputs = document.querySelectorAll('.input-box');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) { // forward space if not the last field and a digit is inputted
                    inputs[index + 1].focus();
                }
                if (input.value === '' && index > 0) { //backspace
                    inputs[index - 1].focus();
                }
            });
        });

        document.getElementById('otpForm').addEventListener('submit', function(e) {
            let otp = '';
            inputs.forEach(input => otp += input.value); //combine into one string
            document.getElementById('otp').value = otp;
        });