
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Payment Method</h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
      <span class="error_msg form_error"></span>
      <form id = "payment_method" method="post">
        <input type="hidden" name="" id = "card_action" value = "{{ (!empty($card)) ? 'edit' : 'add' }}">
          <div class="form-group">
            <label for="firstName">First name:</label>
            <input type="text" class="form-control" id="firstName" name = 'firstName' value = "{{ (!empty($card->firstName)) ? $card->firstName : '' }}">
          </div>
          <div class="form-group">
            <label for="lastName">Last name:</label>
            <input type="text" class="form-control" id="lastName" name = 'lastName' value = "{{ (!empty($card->lastName)) ? $card->lastName : '' }}">
          </div>
          <div class="form-group">
            <label for="card_number">Number:</label>
            <input type="text" class="form-control" id="card_number" name = 'card_number' value = "">
            <span class="card_error error_msg"></span>
          </div>
          <div class="form-group">
            <label for="cvv">CVV:</label>
            <input type="text" class="form-control" id="cvv" name = 'cvv' value = "">
            <span class="cvv_error error_msg"></span>
          </div>
          <div class="form-group">
            <label for="">Expiry:</label>
            <select name="month" id = "month">
              @for($i = 1; $i < 13; $i++)
                <option value="{{ ($i < 10) ? '0'.$i : $i }}" {{ (!empty($card->expiryMonth) && $card->expiryMonth == $i ) ? 'selected' : '' }}>{{ ($i < 10) ? '0'.$i : $i }}</option>
              @endfor
            </select>
            <select name="year" id = "month">
              @for($i = 2016; $i < 2037; $i++)
                <option value="{{ $i }}" {{ (!empty($card->expiryYear) && $card->expiryYear == $i) ? 'selected' : '' }}>{{ $i }}</option>
              @endfor
            </select>
          </div>
          <h4>Card Billing Info</h4>
          <hr>
          <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="address" name = 'address' value = "{{ (!empty($card->billingAddr1)) ? $card->billingAddr1 : '' }}">
          </div>
          <div class="form-group">
            <label for="ext_address">Extended Address:</label>
            <input type="text" class="form-control" id="ext_address" name = 'ext_address' value = "{{ (!empty($card->billingAddr2)) ? $card->billingAddr2 : '' }}">
          </div>
          <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" id="city" name = 'city' value = "{{ (!empty($card->billingCity)) ? $card->billingCity : '' }}">
          </div>
          <div class="form-group">
            <label for="zip">Zip/Postal Code:</label>
            <input type="text" class="form-control" id="zip" name = 'zip' value = "{{ (!empty($card->billingZip)) ? $card->billingZip : '' }}">
          </div>
          <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" class="form-control" id="country" name = 'country' value = "{{ (!empty($card->billingCountry)) ? $card->billingCountry : '' }}">
          </div>
          <div class="form-group">
            <label for="state">State / County / Province:</label>
            <input type="text" class="form-control" id="state" name = 'state' value = "{{ (!empty($card->billingState)) ? $card->billingState : '' }}">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>