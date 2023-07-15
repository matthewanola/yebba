<title>Shop</title>
<?php
require_once('./components/header.php');
?>
<div class="container pt-4 px-5 p-lg-5 shop-height">
    <div class="row flex-sm-row">
        <?php
            $sql = "SELECT * FROM items";
            $result = $con->query($sql);
            if($result->num_rows>0){
                while($fetch = $result->fetch_assoc()){
        ?>
        <div class="col-12 col-md-6 col-lg-3 mb-3">
            <div class="card rounded shadow overflow-hidden">
                <div class="container-fluid d-flex justify-content-center bg-warning overflow-hidden" style="height:25vh;">
                    <img class="img-fluid" src="./images/<?php echo $fetch['item_imgurl'];?>" class="card-img-top">
                </div>
                <div class="card-body d-flex justify-content-between">
                    <p class="fw-bold text-uppercase text-primary"><?php echo $fetch['item_title'];?></p>
                    <p class="fw-bold text-warning"><?php echo $fetch['item_price'];?></p>
                </div>
                <?php
                if(isset($_SESSION['isloggedin'])){
                    if($_SESSION['isloggedin'] == true){
                ?>
                <a href="./process.php?type=addcart&itemid=<?php echo $fetch['item_id'];?>&userid=<?php echo $_SESSION['user_id'];?>"><div class="card-footer bg-primary text-center text-white">
                    <p class="m-0">Add to Cart</p>
                </div></a>
                <?php
                    }}else{
                ?>
                <div class="card-footer bg-primary text-center text-white">
                    <button type="button" class="btn btn-primary m-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal"><p class="m-0">Add to Cart</p></button>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <?php
                }
            }
        ?>
        
    </div>
</div>


<?php
require_once('./components/footer.php')
?>
