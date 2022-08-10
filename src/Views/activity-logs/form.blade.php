<div class="form-group">
    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Date Range</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='dateRange' class="form-control date_input validate_group" id="dateRange">
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Performed On</label>
        </div>
        <div class="col-md-9">
            {!! Form::select('performed_on',$performed_on,null,['class'=>'form-control validate_group','placeholder'=>'','id'=>'performed_on']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Caused By</label>
        </div>
        <div class="col-md-9">
            {!! Form::select('caused_by',$causers,null,['class'=>'form-control validate_group','placeholder'=>'','id'=>'caused_by']) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Activity</label>
        </div>
        <div class="col-md-9">
            {!! Form::select('activity',['all'=>'All','created'=>'Created','updated'=>'Updated','deleted'=>'Deleted','restored'=>'Restored'],null,['id'=>'activity','class'=>'form-control validate_group','placeholder'=>'']) !!}
        </div>
    </div>
</div>
