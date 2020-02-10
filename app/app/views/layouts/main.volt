<nav class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="#">WBH Media Database</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#wbhNavbar" aria-controls="wbhNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="wbhNavbar">
        {{ elements.getMenu() }}
    </div>
</nav>
<div class="container">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    <footer>
        <p>&copy; Konstantin Jakobi 2019</p>
    </footer>
</div>
