<div class="form-group">
    @if (!isset($reset))
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Role</label>
        </div>
        <div class="col-md-10">
            {!! Form::select('role',$roles,null,['class'=>'form-control role']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Name</label>
        </div>
        <div class="col-md-10">
            <input type="text"  name='name' class="form-control name" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Email</label>
        </div>
        <div class="col-md-10">
            <input type="text"  name='email' class="form-control email" required>
        </div>
    </div>
    @endif
    @if (!isset($edit))
        <div class="row">
            <div class="col-md-2 text-right mt-2">
                <label>Password</label>
            </div>
            <div class="col-md-10">
                <input type="password"  name='password' class="form-control password" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 text-right mt-2">
                <label>Confirm Password</label>
            </div>
            <div class="col-md-10">
                <input type="password" name='txtConfirmPassword'
                       class="form-control confirm-password" required>
            </div>
        </div>
    @endif

</div>
