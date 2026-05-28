<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
<div>
    <form method="POST" action="{{ route('comments.store') }}">
        <h1>Enter Comment</h1>
    @csrf

    <label for="comment">Comment</label>
        <textbox lable="summery">

        </textbox>
    <textarea
        name="comment"
        id="comment"
    ></textarea>

    <button type="submit">
        Submit
    </button>
    </form>
</div>
