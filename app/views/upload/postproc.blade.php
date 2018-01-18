@extends('template.template')
@section('content')


<script type="text/javascript">
    $.ajax({
        url: "postproc",
        type: "GET",
        async: true,
        data: {
            "form": true
        },
        dataType: "html",
        success: function (data) {
            alert(JSON.stringify(data));
        }
    });

</script>

@stop