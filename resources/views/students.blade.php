<div class="container mt-4">
  <div class="row mb-5">
    <div class="col-md-12 col-xl-2">
      <h4 class="heading main-heading">{{ request()->get('type') === 'Left' ? request()->get('type') : 'All' }} Students</h4>
    </div>

    <div class="col-md-12 col-xl-10 mt-md-3 mt-xl-0">
      @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('regional_admin'))
        @php
          $url = request()->routeIs('student_listing') ? route('students.list') : route('waiting.list');
        @endphp
        @include('shared.filters', ['url' => $url])
      @elseif(auth()->user()->hasRole('head_teacher'))
        <form action="{{ route('students.list') }}" method="GET">
          <div class="row d-flex justify-content-end">
            <div class="form-group col-md-5 col-xl-3 col-12 mt-md-0 mt-3">
              <select name="class_id" class="form-control form-control-md classes-dropdown">
                <option value="">-- Select Class --</option>
                @foreach(auth()->user()->madrassa->classes->sortBy('name') as $class)
                  <option value="{{ $class->id }}" {{ request()->get('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-5 col-xl-3 col-12 mt-md-0 mt-3">
              <select name="type" class="form-control form-control-md">
                <option value="">-- Select Type --</option>
                <option value="Approved" {{ request()->get('type') === 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Left" {{ request()->get('type') === 'Left' ? 'selected' : '' }}>Left</option>
              </select>
            </div>

            <div class="col-md-2 col-xl-1 d-md-flex align-items-md-center mt-md-0 mt-3 d-flex justify-content-end">
              <button type="submit" class="btn btn-success btn-warning">Filter</button>
            </div>
          </div>
        </form>
      @endif

      @if(auth()->user()->hasRole('teacher'))
        <form action="{{ route('students.list') }}" method="GET">
          <div class="row d-flex justify-content-end">
            <div class="form-group col-md-5 col-xl-3 col-12 mt-md-0 mt-3">
              <select name="subject_id" class="form-control form-control-md subjects-dropdown">
                <option value="">-- Select Subject --</option>
                @foreach(\App\Models\Subject::orderBy('name')->get() as $subject)
                  <option value="{{ $subject->id }}" {{ request()->get('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-5 col-xl-3 col-12 mt-md-0 mt-3">
              <select name="standard_id" class="form-control form-control-md">
                <option value="">-- Select Class --</option>
                @foreach(classOptionsForAttendance() as $classOption)
                  <option value="{{ $classOption['id'] }}" {{ request()->get('standard_id') == $classOption['id'] ? 'selected' : '' }}>{{ $classOption['name'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2 col-xl-1 d-md-flex align-items-md-center mt-md-0 mt-3 d-flex justify-content-end">
              <button type="submit" class="btn btn-warning">Filter</button>
            </div>
          </div>
        </form>
      @endif
    </div>
  </div>

  @include('student_listing')
</div>
