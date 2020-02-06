<div class="form-group">
    <div class="row">
        <div class="col-md-2 text-right mt-2">
            <label>Version</label>
        </div>
        <div class="col-md-10">
            {!! Form::select('version', $versions, null,['class'=>'form-control version select2','required','placeholder'=>'']) !!}
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-2 text-right mt-2">
            <label>Stability</label>
        </div>
        <div class="col-md-10">
            {!! Form::select('stability', $stability, null,['class'=>'form-control stability','required','placeholder'=>'']) !!}
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