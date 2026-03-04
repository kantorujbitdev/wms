<script>
    $(document).on('click', '.btn-edit', function () {
        let row = $(this).closest('.item-row');
        row.find('.view-mode').addClass('d-none');
        row.find('.edit-mode').removeClass('d-none');
    });

    $(document).on('click', '.btn-save', function () {
        let row = $(this).closest('.item-row');

        let productText = row.find('.product-select option:selected').text();
        let qty = row.find('.qty-input').val();
        let note = row.find('.note-input').val();

        if (!qty || qty <= 0) {
            alert('Qty harus lebih dari 0');
            return;
        }

        row.find('.product-text').text(productText);
        row.find('.qty-text').text(qty);
        row.find('.note-text').text(note);

        row.find('.view-mode').removeClass('d-none');
        row.find('.edit-mode').addClass('d-none');
    });

    $(document).on('click', '.btn-remove', function () {
        if ($('.item-row').length <= 1) {
            alert('Minimal 1 barang');
            return;
        }
        $(this).closest('.item-row').remove();
    });
</script>