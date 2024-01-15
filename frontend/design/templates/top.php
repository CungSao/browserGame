<?php
    if(isset($_SESSION['loggedIn'])) {
?>
<div id="top">
    <h1 class="large noMargin floatLeft">My browser game</h1>
    <div id="accountOptions" class="floatRight">
        <a href="?bPage=accountOptions&action=logout&nonUI"><button>Logout</button></a><br>
        <a href="?page=town"><button>My Town</button></a>
        <a href="?page=army"><button>My Army</button></a>
        <a href="?page=otherWorlds"><button>Other Worlds</button></a>
        <a href="?page=create-char"><button>Create character</button></a>

    </div>
</div>

<?php
}
else {
    ?>
<div id="top">
    <h1 class="large noMargin floatLeft">My browser game</h1>
    <div id="accountOptions" class="floatRight">
        <a href="?page=register"><button>Register</button></a><br>
        <a href="?page=login"><button>Login</button></a>
    </div>
</div>
<?php
    }
?>