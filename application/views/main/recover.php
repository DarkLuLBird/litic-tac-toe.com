<form action="recover?hash=<?= $_GET['hash'] ?>" method="POST">
    <div class="form-group">
        <label>Password</label>
        <input class="form-control" type="password" name="password">
    </div>
    <div class="form-group">
        <label>Confirm password</label>
        <input class="form-control" type="password" name="password_repeat">
    </div>
    <button type="submit" class="btn btn-primary btn-block">Register</button>
</form>