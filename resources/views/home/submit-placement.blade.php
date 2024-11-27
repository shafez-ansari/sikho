@extends('home.home-master')

@section('content')

@if($yes == true && ($entityName == "AAFT Noida" || $entityName == "AAFT University"))

    <form action="" id="entityFormId" method="post">
        @csrf
        <div class="row form-group">
            <div class="col-md-6">
                <label for="">Your last completed academic qualification.</label>
            </div> 
            <div class="col-md-6">
                <select class="selectpicker" multiple data-live-search="true" name="academicQual[]" id="academicQualId">
                    @foreach($academicQual as $qual)
                        <option value="$qual->qualification_id">$qual->qualification_name</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

@endif
<script type="text/javascript">
     $(document).ready(function() {
        $('#academicQualId').selectpicker();
    });
</script>
@endsection
