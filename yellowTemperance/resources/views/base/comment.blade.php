<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
<div>
    <form method="POST" action="{{ route('comment.store') }}">
        <h1>Enter Comment</h1>
        @csrf
            <div>
                    <label for="summary">summary</label>

                    <input
                    type="text"
                    name="summary"
                    id="summary">
            </div>
            <div>
                <label for='detail'>Comment</label>
                <textarea
                    name="detail"
                    id="detail">
                </textarea>
            </div>
        <button type="submit">Submit</button>
    </form>
</div>

<h2>All Comments</h2>

@foreach ($comment as $comment)

    <div class="border p-4 rounded mb-4">

        <h3>{{ $comment->summary }}</h3>

        <p>{{ $comment->detail }}</p>

    </div>

@endforeach
