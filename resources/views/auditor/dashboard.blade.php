<h1>Панель управления аудитора</h1>
<p>Добро пожаловать, {{ Auth::user()->name }}!</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Выйти</button>
</form> 