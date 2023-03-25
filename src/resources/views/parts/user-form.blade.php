<h5 class="card-title">{{ $title }}</h5>
<form action="{{ $action }}" method="POST">
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Name</label>
        <input type="email" class="form-control" id="name" name="name">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password">
    </div>
    <div class="mb-3">
        <label for="password2" class="form-label">Repeat password</label>
        <input type="password2" class="form-control" name="password2" id="password2">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    @csrf
</form>
