<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="rounded-lg bg-slate-400 text-red-500 "type="submit">Log Out</button>
</form>
<div>
    <form method="POST" action="{{ route('comment.store') }}">
        <h1>Enter Comment</h1>
        @csrf
            <div>
                    <label for="summery">Summery</label>

                    <input
                    type="text"
                    name="summery"
                    id="summery">
            </div>
            <div>
                <label for='discription'>Comment</label>
                <textarea
                    name="description"
                    id="description">
                </textarea>
            </div>
        <button type="submit">Submit</button>
    </form>
</div>
