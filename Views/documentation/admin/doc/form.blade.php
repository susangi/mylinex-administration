<div class="form-group">
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Title</label>
        </div>
        <div class="col-md-10">
            <input type="text" name='title' class="form-control title" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Is parent</label>
        </div>
        <div class="col-md-10 mt-2">
            <input type="checkbox" name='is_parent' class="is_parent" onclick="getPatents('{{($editable)?'#docEditForm':'#docCreateForm'}}')">
        </div>
    </div>
    <div class="row parent_row">
        <div class="col-md-2 text-right mt-2">
            <label>Parent</label>
        </div>
        <div class="col-md-10">
            {!! Form::select('parent', $parents, null,['class'=>'form-control parent','required','placeholder'=>'']) !!}
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-2 text-right mt-2">
            <label>Permission</label>
        </div>
        <div class="col-md-10">
            {!! Form::select('permission', $permissions, null,['class'=>'form-control permission','placeholder'=>'None']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Order</label>
        </div>
        <div class="col-md-10">
            <input type="number" name='order' class="form-control order" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Description</label>
        </div>
        <div class="col-md-10">
            <textarea name="description" class="description" required></textarea>
        </div>
    </div>
</div>