@if ($errors->any())
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>

        @endforeach

    </ul>
@endif

<form method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $user[0]['id'] }}">
    <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="password" name="confirm_password" placeholder="Enter confirm password"><br><br>
    <input type="submit">
</form>
