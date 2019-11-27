<!-- navigation bar stuff -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">J&C Concepts</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse pb-1" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="featured.php">Featured</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="new.php">New</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="popular.php">Popular</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Furniture
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="materials-of-furniture.php">View by Materials</a>
                    <a class="dropdown-item" href="styles-of-furniture.php">View by Styles</a>
                    <a class="dropdown-item" href="locations-of-furniture.php">View by Location</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Rooms
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="materials-of-rooms.php">View By Materials</a>
                    <a class="dropdown-item" href="styles-of-rooms.php">View by Style</a>
                    <a class="dropdown-item" href="locations-of-rooms.php">View by Location</a>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav mr-4">
        <?php if(isAdmin()){ ?>
            <a type="button" class="btn btn-outline-primary mr-2" href="aStuff.php">Admin Stuff</a><?php
        }
        if(isUser()){ ?>
            <a type="button" class="btn btn-outline-success mr-2" href="cart.php">Cart</a><?php
        }
            ?>
        <?php if(isAdmin() || isUser()){ ?>
            <li>
                <a type="button" class="btn btn-outline-light mr-2" href="CRS/logout-function.php">Logout</a>
            </li><?php
        }
        ?>
        <?php if(!isUser() && !isAdmin()){ ?>
            <li>
                <a type="button" class="btn btn-outline-light mr-2" href="forms/login.php">Login</a>
                <a type="button" class="btn btn-outline-primary" href="forms/register.php">Sign Up</a>
            </li><?php
        }
        ?>
        </ul>
    </div>
</nav>
