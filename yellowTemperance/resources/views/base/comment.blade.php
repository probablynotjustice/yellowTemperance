<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
<div>
    <form method="POST" action="{{ route('comment.store') }}">
        <h1>Enter Comment</h1>
        @csrf

        <label for="comment">Comment</label>
        <input type="text" name="summery">
        <textarea name="detail" id="comment"> </textarea>

        <button type="submit">Submit</button>
    </form>
</div>
