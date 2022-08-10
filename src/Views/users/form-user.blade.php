<div class="form-group">
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Role</label>
        </div>
        <div class="col-md-10  mt-2">
           <label>{{$user->roles[0]->name}}</label>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Name</label>
        </div>
        <div class="col-md-10">
            <input type="text" value="{{$user->name}}" name='name' class="form-control name" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Email</label>
        </div>
        <div class="col-md-10">
            <input type="text"  name='email' value="{{$user->email}}" class="form-control email" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Profile Picture</label>
        </div>
        <div class="col-md-10">
            <input type="file" class="mt-2"  name='image'  class="">
        </div>
    </div>
</div>
