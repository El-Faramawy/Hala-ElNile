<script>
    /**
     * Toggle password visibility between text and password
     */
    function togglePassword(btn) {
        var input = btn.closest('.input-wrap-custom').querySelector('input');
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa fa-eye';
        }
    }
</script>
