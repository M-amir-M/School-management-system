{{ csrf_field() }}

<section>
    <div class="form-group">
        <div class="row">
            <div class="col-md-4"><input type="radio" name="role" value="teacher"
                /> معلم
            </div>
            <div class="col-md-4"><input checked type="radio" name="role" value="student"
                /> دانش آموز
            </div>
            <div class="col-md-4"><input type="radio" name="role" value="parent"
                /> والدین
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('username') ? ' has-error' : ' has-warning' }}">
        <input type="text" value="{{ old('username') }}" name="username" class="form-control input-sm"
               placeholder="نام کاربری"/>
        @if ($errors->has('username'))
            <span class="help-block">
                <strong>{{ $errors->first('username') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('email') ? ' has-error' : ' has-warning' }}">
        <input type="text" value="{{ old('email') }}" name="email" class="form-control input-sm" placeholder="ایمیل"/>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password') ? ' has-error' : ' has-warning' }}">
        <input type="password" name="password" class="form-control input-sm"
               placeholder="رمز عبور"/>
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : ' has-warning' }}">
        <input id="password-confirm" type="password" class="form-control input-sm"
               name="password_confirmation" placeholder="تکرار رمز عبور">

        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('fname') ? ' has-error' : ' has-warning' }}">
        <input type="text" value="{{ old('fname') }}" name="fname" class="form-control input-sm"
               placeholder="نام"/>
        @if ($errors->has('fname'))
            <span class="help-block">
                <strong>{{ $errors->first('fname') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('lname') ? ' has-error' : ' has-warning' }}">
        <input type="text" value="{{ old('lname') }}" name="lname" class="form-control input-sm"
               placeholder="نام خانوادگی"/>
        @if ($errors->has('lname'))
            <span class="help-block">
                <strong>{{ $errors->first('lname') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <input type="text" value="{{ old('birthday') }}" id="birthdayDate" name="birthday" class="form-control input-sm pdate"
               placeholder="تاریخ تولد">
    </div>
    <div class="form-group">
        <select value="{{ old('clase') }}" class="form-control input-sm" name="clase">
            <option value="3">کلاس</option>
        </select>
    </div>

    <div class="form-group">
        <textarea name="address" placeholder="آدرس" class="form-control">{{ old('address') }}</textarea>
    </div>
    <div class="form-group">
        <input type="text" value="{{ old('phone') }}" name="phone" class="form-control input-sm"
               placeholder="شماره تلفن"/>
    </div>
    <div class="form-group">
        <input type="text" value="{{ old('mobile') }}" name="mobile" class="form-control input-sm"
               placeholder="شماره همراه"/>
    </div>
    <div class="form-group">
        <div class="">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa fa-btn fa-user"></i> ثبت نام
            </button>
        </div>
    </div>
    </div>
</section>