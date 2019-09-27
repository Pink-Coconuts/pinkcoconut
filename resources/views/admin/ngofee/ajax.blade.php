              <div class="box-body" id='form_{{ $id }}'>
              <div class="box-header">
                <h3 class="box-title btn btn-danger" style="float: right;" onclick="return remove('{{ $id }}');">Remove</h3>
              </div>
                <!-- <span class="text-danger">(*)Fields are Mandatory</span> -->
                <div class="form-group">
                  <label for="input_ngo_name" class="col-sm-3 control-label">Ngo Name</label>
                  <div class="col-sm-6">
                    {!! Form::text('name[]', '', ['class' => 'form-control','placeholder' => 'Ngo Name']) !!}
                  </div>
                </div>
                <div class="form-group">
                <label for="input_long_name" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    {!! Form::text('description[]', '', ['class' => 'form-control', 'placeholder' => 'Description']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_iso3" class="col-sm-3 control-label">Amount</label>
                  <div class="col-sm-6">
                    {!! Form::text('amount[]', '', ['class' => 'form-control', 'placeholder' => 'Amount']) !!}                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_num_code" class="col-sm-3 control-label">Currency</label>
                  <div class="col-sm-6">
                    {!! Form::select('currency[]',$currency,'', ['class' => 'form-control']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_phone_code" class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-6">
                    {!! Form::select('status[]',['Active','Inactive'],'', ['class' => 'form-control']) !!}                    
                  </div>
                </div>
              </div>