<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
<div>
    @csrf

    <label for="comment">Comment</label>

    <textarea
        name="comment"
        id="comment"
    ></textarea>

    <button type="submit">
        Submit
    </button>
</div>
