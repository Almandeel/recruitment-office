<link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/select2-bootstrap4.min.css') }}">

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-weight: bold;
        margin: 0 5px;
    }
</style>


<script src="{{ asset('dashboard/plugins/select2/select2.full.min.js') }}"></script>

<script>
    $('.select2').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
</script>