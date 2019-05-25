<?php 

include '../debug.php';
include '../config/dep.php';

session_start();
if (!isset($_SESSION['User']) || $_SESSION['User']===null) {
  header('Location: /account/login.php');
  exit();
}

$user = $_SESSION['User'];

$db = new Database();
$conn = $db->getConnection();

$error = "";

if (isset($_POST['delete']))
{
    $id_ssh = (int)$_POST['delete'];
    $query = "DELETE FROM SSHKeys WHERE IdUser=".$user->id." AND Id=".$id_ssh;
    $data=mysqli_query($conn,$query);
    header('Location: /account/sshkeys.php');
    exit();
}
if (isset($_POST['ssh_key']))
{
    $ssh_key = $_POST['ssh_key'];
    $ssh_key = trim($ssh_key);
    $ssh_key = str_replace("\n","", $ssh_key);

    if (substr($ssh_key, 0, 8) === "ssh-rsa ") {
        $query = "INSERT INTO SSHKeys(IdUser, SSHKey) VALUES(".$user->id.",'".$ssh_key."')";
        $data=mysqli_query($conn,$query);
        header('Location: /account/sshkeys.php');
        exit();
    }
    else {
        $error = "Not a valid ssh-rsa key";
    }
}

$query = "SELECT Id, SSHKey FROM SSHKeys WHERE IdUser=".$user->id;
$data=mysqli_query($conn,$query);
while($row=mysqli_fetch_array($data)){
?>
    <form action="/account/sshkeys.php" method="POST">
    <input type="hidden" name="delete" value="<?php echo $row['Id']; ?>" />
    <textarea><?php echo htmlspecialchars($row['SSHKey']); ?></textarea>
    <button type="submit" class="btn btn-primary ">Delete ssh key</button>
    </form>
    <br>
<?php
}
?>
<form action="/account/sshkeys.php" method="POST" id="addForm">
Add a new ssh key!
<br>
<textarea form="addForm" name="ssh_key"></textarea>
<button type="submit" class="btn btn-primary ">Add ssh key</button>
</form>
<?php
if ($error !== '')
{
    ?>
    <div class="alert alert-danger" role="alert">
    <?php echo htmlspecialchars($error); ?>
    </div>
    <?php
}
?>

<script>
let stateObj = {
    foo: "dashboard",
};

history.replaceState(stateObj, "", "/dashboard/");
</script>

