
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Account Information</h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
      <form id = "updateAccount" method="post">
          <div class="form-group">
            <label for="firstName">First name:</label>
            <input type="text" class="form-control" id="firstName" name = 'firstName' value = "{{ $customer->firstName }}">
          </div>
          <div class="form-group">
            <label for="lastName">Last name:</label>
            <input type="text" class="form-control" id="lastName" name = 'lastName' value = "{{ $customer->lastName }}">
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control" id="email" name = 'email' value = "{{ $customer->email }}">
            <span class="email_error error_msg"></span>
          </div>
          <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="number" class="form-control" id="phone" name = 'phone' value = "{{ $customer->phone }}">
          </div>
          <div class="form-group">
            <label for="company">Company:</label>
            <input type="text" class="form-control" id="company" name = 'company' value = "{{ $customer->company }}">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>