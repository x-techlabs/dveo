@extends('template.template')
@section('content')

<!--<script src="https://sdk.amazonaws.com/js/aws-sdk-2.0.15.min.js"></script>-->
<!---->
<!--<form role="form">-->
<!--    <div class="form-group">-->
<!--        <label for="exampleInputName">Name</label>-->
<!--        <input type="text" class="form-control" id="exampleInputName" placeholder="Enter Name">-->
<!--    </div>-->
<!--    <div class="form-group">-->
<!--        <label for="exampleInputDescription">Description</label>-->
<!--        <input type="text" class="form-control" id="exampleInputDescription" placeholder="Description">-->
<!--    </div>-->
<!--    <div class="form-group">-->
<!--        <label for="exampleInputCustom">Custom information 1</label>-->
<!--        <input type="text" class="form-control" id="exampleInputCustom" placeholder="Enter info">-->
<!--    </div>-->
<!--    <div class="form-group">-->
<!--        <label for="exampleInputCustom1">Custom information 2</label>-->
<!--        <input type="text" class="form-control" id="exampleInputCustom1" placeholder="Enter info">-->
<!--    </div>-->
<!--    <div class="form-group">-->
<!--        <label for="exampleInputFile">File input</label>-->
<!--        <input type="file" id="exampleInputFile">-->
<!---->
<!--    </div>-->
<!---->
<!--    <button type="button" id="sendToServer" class="btn btn-default">Submit</button>-->
<!--</form>-->

<form action="https://prolivestream.s3-us-west-2.amazonaws.com/" method="post" enctype="multipart/form-data" id="amazon_form">
    Key to upload: <input type="input" name="key" value="user/eric/" /><br />
    <input type="hidden" name="acl" value="public-read" />
    <input type="hidden" name="success_action_redirect" value="{{ asset('/') }}/upload" />
    Content-Type: <input type="input" name="Content-Type" value="image/jpeg" /><br />
<!--    <input type="hidden" name="x-amz-meta-uuid" value="14365123651274" />-->
<!--    Tags for File: <input type="input" name="x-amz-meta-tag" value="" /><br />-->
    <input type="hidden" name="AWSAccessKeyId" value="AKIAIDGRDUJ7ZG5DNJEA" />
    <input type="hidden" name="Policy" value="POLICY" id="policy" />
    <input type="hidden" name="Signature" value="SIGNATURE" id="signature"/>
    File: <input type="file" name="file" id="file"/> <br />
    <!-- The elements after this will be ignored -->
    <input type="submit" value="Upload to Amazon S3" id="upload_amazon"/>
</form>

<div id="result">
    <p style="color:#FF0000;"></p>
</div>
<div class="modal fade" id="modal-submit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span></button>

            </div>
            <div class="modal-body" id="body-text">
                You cann't add more videos
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">




$(document).ready(function() {
        var form = $('#amazon_form');
        var ajax = false;

        form.submit(function(event){
            event.preventDefault();
            // Get the current url
            var url = window.location.protocol + '//' + window.location.host + '/send_amazon';
            var path = $('#file').val();
            var formAmazon = this;
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "path" : path
                },
                dataType: "json",
                success: function (data) {

                    $('#policy').val(data.policy_encoded);
                    $('#signature').val(data.signature);

                }
            });
            setTimeout( function () {
                formAmazon.submit();
            }, 500);
        })

    $('#amazon_form').each(function() {

        var form = $(this);

        $(this).fileupload({
            url: form.attr('action'),
            type: 'POST',
            autoUpload: true,
            dataType: 'xml', // This is really important as s3 gives us back the url of the file in a XML document
            add: function (event, data) {
                var url = window.location.protocol + '//' + window.location.host + '/send_amazon';
                var path = $('#file').val();
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "path" : path
                    },
                    dataType: "json",
                    success: function (data) {

                        $('#policy').val(data.policy_encoded);
                        $('#signature').val(data.signature);

                    }
                });
                })
                data.submit();
            },
            send: function(e, data) {
                $('.progress').fadeIn();
            },
            progress: function(e, data){
                // This is what makes everything really cool, thanks to that callback
                // you can now update the progress bar based on the upload progress
                var percent = Math.round((e.loaded / e.total) * 100)
                $('.bar').css('width', percent + '%')
            },
            fail: function(e, data) {
                console.log('fail')
            },
            success: function(data) {
                // Here we get the file url on s3 in an xml doc
                var url = $(data).find('Location').text()

                $('#real_file_url').val(url) // Update the real input in the other form
            },
            done: function (event, data) {
                $('.progress').fadeOut(300, function() {
                    $('.bar').css('width', 0)
                })
            }
        })
    })

    });
</script>
@stop