<title>Profile</title>
<?php
require_once('./components/header.php');
?>
<div class="container-fluid">
<div class="row flex-sm-row">
    <div class="col-12 col-lg-6 px-5 py-3 profile-height overflow-auto">
        <h1 class="fw-bold">Transaction History</h1>
        <?php
            $sql = "SELECT * FROM items INNER JOIN transaction_details ON transaction_details.item_id = items.item_id INNER JOIN transactions ON transactions.transaction_id = transaction_details.transaction_id WHERE user_id = '".$_SESSION['user_id']."'";
            $result = $con->query($sql);
            if($result->num_rows>0){
                while($fetch = $result->fetch_assoc()){
        ?>
        <div class="bg-primary rounded px-5 py-3 mb-3">
            <div class="d-flex justify-content-between">
                <p class="text-white">Transaction ID: <?php echo $fetch['transaction_id'];?></p>
                <p class="text-white"><?php echo $fetch['transaction_date'];?></p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="fw-bold text-white"><?php echo $fetch['item_title'];?></p>
                <p class="fw-bold text-warning">Total Price: <?php echo $fetch['price']*$fetch['qty'];?></p>
            </div>
        </div>
        <?php
                }
            }else{
        ?>
        <div class="bg-primary rounded px-5 py-3 mb-3">
            <h4 class="text-center text-white">No transactions yet.</h4>
        </div>
        <?php       
            }
        ?>
    </div>
    <div class="col-12 col-lg-6 px-5 py-3">
        <h1 class="fw-bold">Profile Settings</h1>
        <div class="container bg-warning p-4 rounded">
            <h3 class="fw-bold text-white">Account</h3>
            <form action="./process.php" method="POST">
            <?php
                $sql = "SELECT * FROM users WHERE user_id = '".$_SESSION['user_id']."'";
                $result = $con->query($sql);
                if($result->num_rows>0){
                    while($fetch = $result->fetch_assoc()){
                        $email = $fetch['user_email'];
                        $playerid = $fetch['player_id'];
                    }
                }
            ?>
            <input type="text" name="type" class="d-none" value="updateaccount">
            <input type="text" name="userid" class="d-none" value="<?php echo $_SESSION['user_id'];?>">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="player_id" id="player_id" placeholder="Player ID" value="<?php echo $playerid;?>">
                <label>Player ID</label>
                <span id="id-msg"></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo $email?>">
                <label>Email</label>
            </div>
            <button class="btn btn-primary">Update Account</button>
            </form>
            <hr>

            <form action="./process.php" method="POST">
            <input type="text" name="type" class="d-none" value="changepass">
            <input type="text" name="userid" class="d-none" value="<?php echo $_SESSION['user_id'];?>">
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="current_pass" placeholder="Current Password" required>
                <label>Current Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="new_pass" placeholder="Current Password" required>
                <label>New Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" placeholder="Confirm Password" required>
                <label>Confirm Password</label>
                <span id="pass-msg"></span>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
            </form>

            <hr>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Account</button>
        </div>
    </div>
</div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body d-flex justify-content-center align-items-center">
        <h4 class="text-center text-danger">Are you sure you want to delete your account?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <a href="./process.php?type=delaccount&user_id=<?php echo $_SESSION['user_id'];?>&email=<?php echo $_SESSION['email']?>"><button type="button" class="btn btn-danger">Yes</button></a>
      </div>
    </div>
  </div>
</div>
<?php
require_once('./components/footer.php')
?>