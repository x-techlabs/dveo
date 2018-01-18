
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Billing Information</h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
      <span class="form_error error_msg"></span>
      <form id = "updateBilling" method="post">
          <div class="form-group">
            <label for="firstName">First name:</label>
            <input type="text" class="form-control" id="firstName" name = 'firstName' value = "{{ $billingInfo->firstName }}">
          </div>
          <div class="form-group">
            <label for="lastName">Last name:</label>
            <input type="text" class="form-control" id="lastName" name = 'lastName' value = "{{ (isset($billingInfo->lastName) && !empty($billingInfo->lastName)) ? $billingInfo->lastName  : ''}}">
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control" id="email" name = 'email' value = "{{ (isset($billingInfo->email) && !empty($billingInfo->email)) ? $billingInfo->email  : ''}}">
            <span class="email_error error_msg"></span>
          </div>
          <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="number" class="form-control" id="phone" name = 'phone' value = "{{ (isset($billingInfo->phone) && !empty($billingInfo->phone)) ? $billingInfo->phone  : ''}}">
          </div>
          <div class="form-group">
            <label for="company">Company:</label>
            <input type="text" class="form-control" id="company" name = 'company' value = "{{ (isset($billingInfo->company) && !empty($billingInfo->company)) ? $billingInfo->company  : ''}}">
          </div>
          <div class="form-group">
            <label for="line1">Address Line 1:</label>
            <input type="text" class="form-control" id="line1" name = 'line1' value = "{{ (isset($billingInfo->line1) && !empty($billingInfo->line1)) ? $billingInfo->line1  : ''}}">
          </div>
          <div class="form-group">
            <label for="line2">Address Line 2:</label>
            <input type="text" class="form-control" id="line2" name = 'line2' value = "{{ (isset($billingInfo->line2) && !empty($billingInfo->line2)) ? $billingInfo->line2  : ''}}">
          </div>
          <div class="form-group">
            <label for="line3">Address Line 3:</label>
            <input type="text" class="form-control" id="line3" name = 'line3' value = "{{ (isset($billingInfo->line3) && !empty($billingInfo->line3)) ? $billingInfo->line3  : ''}}">
          </div>
          <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" id="city" name = 'city' value = "{{ (isset($billingInfo->city) && !empty($billingInfo->city)) ? $billingInfo->city  : ''}}">
          </div>
          <div class="form-group">
            <label for="zip">Zip/Postal Code:</label>
            <input type="text" class="form-control" id="zip" name = 'zip' value = "{{ (isset($billingInfo->zip) && !empty($billingInfo->zip)) ? $billingInfo->zip  : ''}}">
          </div>
          <div class="form-group">
            <label for="country">Country(2-letter country code):</label>
            <input type="text" class="form-control" id="country" name = 'country' value = "{{ (isset($billingInfo->country) && !empty($billingInfo->country)) ? $billingInfo->country  : ''}}">
          </div>
          <div class="form-group">
            <label for="state">State:</label>
            <input type="text" class="form-control" id="state" name = 'state' value = "{{ (isset($billingInfo->state) && !empty($billingInfo->state)) ? $billingInfo->state  : ''}}">
          </div>

          <!-- Submit -->
          <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>