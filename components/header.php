<?php
require_once('config.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./node_modules/@fortawesome/fontawesome-free/css/fontawesome.css">
    <link rel="stylesheet" href="./node_modules/@fortawesome/fontawesome-free/css/solid.css">
    <script src="./node_modules/jquery/dist/jquery.js"></script>
    <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.css">
    <script src="./node_modules/sweetalert2/dist/sweetalert2.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-primary px-5 py-3 py-lg-4">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand text-warning fw-bold" href="#">Pokémon GO Shop</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./home.php"><button class="btn btn-primary">Home</button></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./shop.php"><button class="btn btn-primary">PokéShop</button></a>
                </li>
            </ul>
            <ul class="navbar-nav d-flex">
                <?php
                    if(isset($_SESSION['isloggedin'])){
                        if($_SESSION['isloggedin'] == true){
                ?>
                    <li class="nav-item">
                        <a class="nav-link"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">Cart</button></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./profile.php"><button class="btn btn-primary">Profile</button></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./process.php?type=logout"><button class="btn btn-primary">Logout</button></a>
                    </li>
                <?php
                        }
                    }else{
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Login/Register</button></a>
                    </li>
                <?php
                
                    }
                ?>
            </ul>
        </div>
    </div>
</nav> 

<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- LOGIN FORM -->
            <div class="" id="logindiv">
                <form action="./process.php" method="POST">
                <input type="text" class="d-none" name="type" id="type" value="login">                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login_email" placeholder="Email">
                    <label>Email</label>
                </div>
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input type="password" id="pass-in" class="form-control" name="login_password" placeholder="Password">
                        <label>Password</label>
                    </div>
                    <button class="btn btn-outline-secondary" style="min-width: 50px;" type="button" id="visibilitiy-btn1"><i class="fa-solid fa-eye"></i></button>
                </div>
            </div>
            <!-- REGISTRATION FORM -->
            <div class="d-none" id="registerdiv">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="player_id" id="player_id" placeholder="Player ID" onkeyup="checkId();">
                    <label>Player ID</label>
                    <span id="id-msg"></span>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="register_email" placeholder="Email">
                    <label>Email</label>
                </div>
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <input type="password" id="pass-in1" class="form-control" name="register_password" placeholder="Password" onkeyup="checkPass();">
                        <label>Password</label>
                    </div>
                    <button class="btn btn-outline-secondary" style="min-width: 50px;" type="button" id="visibilitiy-btn2"><i class="fa-solid fa-eye"></i></button>
                </div>
                <div class="form-floating">
                    <input type="password" id="pass-in2" class="form-control" name="cpassword" placeholder="Password" onkeyup="checkPass();">
                    <label>Confirm Password</label>
                    <span id="pass-msg"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center flex-column">
            <button type="submit" class="btn btn-primary" id="primary-btn" style="width:20%;">Login</button>
            </form>
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <hr class="border border-primary" style="width:30%;"><p class="px-1 text-primary m-0">or</p><hr class="border border-primary" style="width:30%;">
            </div>
            <button type="button" class="btn btn-outline-primary" id="secondary-btn" style="width:20%;">Register</button>
        </div>
        </div>
    </div>
</div>

<!-- CART MODAL -->
<div class="modal modal-xl fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn btn-primary d-none" id="back-btn" style="width:10%;">Back</button>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="./process.php" method="POST">
            <input type="text" name="type" class="d-none" value="checkout">
            <input type="text" name="userid" class="d-none" value="<?php echo $_SESSION['user_id']?>">
            <table class="table table-bordered">
                <thead class="d-none" id="player-info-div">
                    <tr>
                        <th colspan="5">Player Information</th>
                    </tr>
                    <tr>
                        <td colspan="2">Player ID</td>
                        <td colspan="3"><input type="text" name="playerid" class="form-control" value="<?php echo $_SESSION['player_id']?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Email Address</td>
                        <td colspan="3"><input type="text" name="email" class="form-control" value="<?php echo $_SESSION['email']?>"></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        $sql = "SELECT * FROM items INNER JOIN carts on items.item_id = carts.item_id INNER JOIN users on users.user_id = carts.user_id WHERE carts.user_id = '".$_SESSION['user_id']."'";
                        $result = $con->query($sql);
                            if($result->num_rows>0){
                                while($fetch = $result->fetch_assoc()){
                    ?>
                                    <tr>
                                    <td class="d-none"><input type="text" name="cartid[]" value="<?php echo $fetch['cart_id'];?>"></td>
                                        <td class="d-none"><input type="text" name="itemid[]" value="<?php echo $fetch['item_id'];?>"></td>
                                        <td class="d-none"><input type="text" name="price[]" value="<?php echo $fetch['item_price'];?>"></td>
                                        <td><?php echo $fetch['item_title']?></td>
                                        <td class="price"><?php echo $fetch['item_price']?></td>
                                        <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
                                        <td><span id="total"></span></td>
                                        <td class="d-flex justify-content-center"><a href="./process.php?type=delcartitem&cartid=<?php echo $fetch['cart_id'];?>"><button type="button" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button></a></td>
                                    </tr>
                    <?php
                                }
                            }else{
                    ?>
                            <tr>
                                <td colspan="5">Cart is empty</td>
                            </tr>
                    <?php
                            }
                    ?>
                    <tr>
                        <td colspan="3">Overall Total</td>
                        <td colspan="2"><input type="number" id="overall-total" name="overalltotal" class="form-control" readonly></td>
                    </tr>
                    <tr class="d-none" id="gcash-ref">
                        <td colspan="3">Gcash Reference Number</td>
                        <td colspan="2"><input type="text" name="gcashref" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="proceed-btn">Proceed</button>
                <button type="submit" class="btn btn-primary d-none" id="confirm-btn">Confirm</button>
        </form>
            </div>  
        
        </div>
        </div>
    </div>
</div>

<!-- PROCEED TO CHECKOUT -->
<script>
$(document).ready(function() {
    const playerinfoDiv = $("#player-info-div");
    const proceedBtn = $("#proceed-btn");
    const confirmBtn = $("#confirm-btn");
    const backBtn = $("#back-btn");
    const gcashRef = $("#gcash-ref");
    const qtyInput = $(".qty");

    proceedBtn.click(function() {
        proceedBtn.addClass("d-none");
        playerinfoDiv.removeClass("d-none");
        confirmBtn.removeClass("d-none");
        backBtn.removeClass("d-none");
        gcashRef.removeClass("d-none");
        qtyInput.prop("readonly", true);
    });

    backBtn.click(function() {
        proceedBtn.removeClass("d-none");
        playerinfoDiv.addClass("d-none");
        confirmBtn.addClass("d-none");
        backBtn.addClass("d-none");
        gcashRef.addClass("d-none");
        qtyInput.prop("readonly", false);
    });
});
</script>

<!-- TOTAL AND OVERALL TOTAL CALCULATION -->
<script>
$(document).ready(function() {
    const qtyInput = $(".qty");
    const priceInput = $(".price");
    const total = $("#total");
    const overallTotal = $("#overall-total");

    qtyInput.on("input", function() {
        calculateTotal();
    });

    calculateTotal();

    function calculateTotal() {
        let sum = 0;
        qtyInput.each(function() {
            const qty = $(this).val();
            const price = $(this).closest("tr").find(".price").text();
            const subtotal = parseFloat(qty) * parseFloat(price);
            $(this).closest("tr").find("#total").text(subtotal.toFixed(2));
            sum += subtotal;
        });

        overallTotal.val(sum.toFixed(2));
    }
});

</script>

<!-- PASSWORD VISIBILITY LOGIN -->
<script>
$(document).ready(function() {
    const passwordField = $("#pass-in");
    const visibilityButton1 = $("#visibilitiy-btn1");

    visibilityButton1.click(function() {
        if (passwordField.attr("type") === "password") {
            passwordField.attr("type", "text");
            visibilityButton1.html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            passwordField.attr("type", "password");
            visibilityButton1.html('<i class="fa-solid fa-eye"></i>');
        }
    });
});
</script>

<!-- PASSWORD VISIBILITY REGISTRATION -->
<script>
$(document).ready(function() {
    const passwordField1 = $("#pass-in1");
    const passwordField2 = $("#pass-in2");
    const visibilityButton2 = $("#visibilitiy-btn2");

    visibilityButton2.click(function() {
        if (passwordField1.attr("type") === "password") {
            passwordField1.attr("type", "text");
            passwordField2.attr("type", "text");
            visibilityButton2.html('<i class="fa-solid fa-eye-slash"></i>');
        } else {
            passwordField1.attr("type", "password");
            passwordField2.attr("type", "password");
            visibilityButton2.html('<i class="fa-solid fa-eye"></i>');
        }
    });
});   
</script>

<!-- CHECK IF PALYER ID IS 12 CHARACTERS AND NUMERIC -->
<script>
    const playerId = $("#player_id");
    const idMessage = $("#id-msg");

    const checkId = function(){
        const idValue = playerId.val();

        if (idValue.length === 12 && /^\d+$/.test(idValue)) {
            idMessage.css('color', 'green');
            idMessage.html('Player ID is valid.');
        }else if(playerId.val() === ''){
            idMessage.css('color', '');
            idMessage.html('');
        }else{
            idMessage.css('color', 'red');
            idMessage.html('Player ID is invalid. Should be 12 numeric numbers.');
        }
    }
</script>

<!-- CHECK IF PASSWORD AND CPASSWORD MATCHED -->
<script>
    const password = $("#pass-in1");
    const cpassword = $("#pass-in2");
    const message = $("#pass-msg");
    const primarybtn = $("#primary-btn");

    const checkPass = function() {
        if (password.val() == cpassword.val() && password.val() !== '' && cpassword.val() !== '') {
            message.css('color', 'green');
            message.html('Password matched');
            primarybtn.prop('disabled', false);
        } else if (password.val() === '' || cpassword.val() === '') {
            message.css('color', '');
            message.html('');
            primarybtn.prop('disabled', '');
        } else if (password.val() != cpassword.val()) {
            message.css('color', 'red');
            message.html('Password do not match');
            primarybtn.prop('disabled', true);
        } 
    }

</script>

<!-- LOGIN/REGISTER TOGGLE -->
<script>
$(document).ready(function() {
  $("#secondary-btn").click(function() {
    const secondaryBtn = $("#secondary-btn");
    const primaryBtn = $("#primary-btn");
    const loginDiv = $("#logindiv");
    const registerDiv = $("#registerdiv");
    const typeInput = $("#type");

    if (typeInput.val() === "login") {
      typeInput.val("register");
    } else {
      typeInput.val("login");
    }

    // INITIAL VALUES
    const secondaryBtnText = secondaryBtn.text();
    const primaryBtnText = primaryBtn.text();

    secondaryBtn.text(primaryBtnText);
    primaryBtn.text(secondaryBtnText);

    loginDiv.toggleClass("d-none");
    registerDiv.toggleClass("d-none");
  });
});
</script>

<!-- SWEET ALERTS -->
<?php
if(isset($_GET['event'])){
    if($_GET['event'] == 'invalid-login'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Wrong Email or Password.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'input-login'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please fill all fields.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'invalid-email'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please use a valid email address.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'invalid-player-id'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please use a valid player id.',
            showConfirmButton: false,
            timer: 2000,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'email-already-in-use'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Email is already in use. Please login.',
            showConfirmButton: false,
            timer: 2000,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'registration-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Your account has been registered successfully.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'input-register'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Please fill all fields.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'purchase-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Purchase successful. Check your email for a receipt.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'pass-change-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Password changed successfully.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'wrong-password'){
        echo"
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Password change failed. Please input current password.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'update-acc-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Account updated successfully.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'add-cart-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Item has been added to cart.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'del-item-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Item was removed from the cart.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }elseif($_GET['event'] == 'account-del-success'){
        echo"
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Account deleted successfully.',
            showConfirmButton: false,
            timer: 2500,
            })
        </script>
        ";
    }
}

?>