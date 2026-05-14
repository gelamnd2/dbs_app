<?php
require_once('../classes/database.php');
session_start();
$con = new database();
 
 
$authors = $con->getAuthors();
$genres = $con->getGenres();
 
if(isset($_POST['delete_author'])){
 
  $author_id = $_POST['author_id'] ;
  $author_firstname = $_POST['author_firstname'];
 
  if(!$author_id){
    $_SESSION['error_message'] = "Invalid author ID.";
    header('Location: authors-genres.php');
    exit();
  }
 
  try {
 
    $con->deleteAuthor($author_id);
 
   $_SESSION['success_message'] = $author_firstname . ' has been deleted successfully.';
  } catch(Exception $e){
 
    $_SESSION['error_message'] = "Failed to delete author: " . $e->getMessage();
  }
 
  header('Location: authors-genres.php');
  exit();
}
 
$updateAuthorStatus = null;
$updateAuthorMessage = '';
 
if(isset($_POST['update_author'])){
 
    $author_id = $_POST['author_id'];
    $firstname = $_POST['author_firstname'];
    $lastname = $_POST['author_lastname'];
    $birth = $_POST['author_birthyear'];
    $nationality = $_POST['author_nationality'];
 
    try {
 
        $con->updateAuthor($author_id, $firstname, $lastname, $birth, $nationality);
 
       $updateAuthorStatus = 'success';
        $updateAuthorMessage = 'Author update successfully.';
       
 
    } catch(Exception $e) {
 
         $updateAuthorStatus = 'error';
    $updateAuthorMessage = $e->getmessage();
    }
}
 
$updateGenreStatus = null;
$updateGenreMessage = '';
 
if(isset($_POST['update_genre'])){
 
    $genre_id = $_POST['genre_id'];
    $genre_name = $_POST['genre_name'];
 
    try {
 
        $con->updateGenre($genre_id, $genre_name);
 
        $updateGenreStatus = 'success';
        $updateGenreMessage = 'Genre updated successfully.';
 
    } catch(Exception $e) {
 
        $updateGenreStatus = 'error';
        $updateGenreMessage = $e->getMessage();
    }
}
 
 
if(isset($_POST['delete_genre'])){
  $genre_id= $_POST['genre_id'];
 $genre_name = $_POST['genre_name'] ?? 'This genre';
 
 
 try{
 
   $con->deleteGenre($genre_id);
   $_SESSION['success_message'] = $genre_name . ' has been deleted successfully.';
   header('Location: authors-genres.php');
   exit();
 
 
 } catch(Exception $e) {
   $error_message = "Cannot delete this genre. ";
 }
}
 
 
$addAuthorStatus = null;
$addAuthorMessage = '';
 
if(isset($_POST['add_author'])){
 
    $firstname = $_POST['author_firstname'];
    $lastname = $_POST['author_lastname'];
    $birth = $_POST['author_birthyear'];
    $nationality = $_POST['author_nationality'];
 
  try {
    $con->addAuthor($firstname, $lastname, $birth, $nationality);
 
   $addAuthorStatus = 'success';
   $addAuthorMessage = 'Author added successfully.';
 
  } catch(Exception $e) {
     $addAuthorStatus = 'error';
    $addAuthorMessage = $e->getmessage();
}
}
$addGenreStatus = null;
$addGenreMessage = '';
 
if (isset($_POST['add_genre'])) {
 
    $genre_name = $_POST['genre_name'];
 
    try {
        $con->addGenres($genre_name);
 
        $addGenreStatus = 'success';
        $addGenreMessage = 'Genre added successfully.';
 
    } catch (Exception $e) {
        $addGenreStatus = 'error';
        $addGenreMessage = $e->getMessage();
    }
}
 
 
 
 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Authors and Genres - Admin (Teaching Demo)</title>
 
   <!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"-->
  <link rel="stylesheet" href="../assets/css/style.css">
 
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdminStatic">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navAdminStatic" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
      <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.html">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.html">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.html">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
      </div>
    </div>
  </div>
</nav>
 
<main class="container py-4">
  <div class="row g-3">
    <?php if(isset($_SESSION['success_message'])){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> <?php echo $_SESSION['success_message']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success_message']); } ?>
 
    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Author</h5>
        <p class="small-muted mb-3">Sample form for the Authors table.</p>
 
        <form action="#" method="POST" class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label">First Name</label>
            <input class="form-control" name="author_firstname" placeholder="e.g., Jose" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="author_lastname" placeholder="e.g., Rizal" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Birth Year</label>
            <input class="form-control" name="author_birthyear" type="number" min="1" max="2100" placeholder="optional" />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Nationality</label>
            <input class="form-control" name="author_nationality" placeholder="optional" />
          </div>
          <div class="col-12">
            <button name= "add_author" class="btn btn-primary w-100" type="submit">Save Author</button>
          </div>
        </form>
      </div>
    </div>
 
    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Genre</h5>
        <p class="small-muted mb-3">Sample form for the Genres table.</p>
 
        <form action="#" method="POST" class="row g-2">
          <div class="col-12">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name="genre_name" placeholder="e.g., Classic" required />
          </div>
          <div class="col-12">
            <button name="add_genre" class="btn btn-outline-primary w-100" type="submit">Save Genre</button>
          </div>
        </form>
      </div>
    </div>
 
    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Authors List</h5>
          <span class="small-muted">Static sample data</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Author ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Year</th>
                <th>Nationality</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($authors as $a){
             
                echo'<tr>';
                   echo' <td>'. $a['author_id'].'</td>';
                   echo' <td>'. $a['author_firstname'].'</td>';
                   echo '<td>'. $a['author_lastname'].'</td>';
                   echo '<td>'. $a['author_birthyear'].'</td>';
                    echo'<td>'.$a['author_nationality'].'</td>';
                     echo '<td class="text-end">';
                echo '<div class =" btn-group" role="group">';
                     echo ' <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAuthorModal"
 
                 data-author-id="'.$a['author_id'] . '"
                 data-author-firstname="'.$a['author_firstname'] . '"
                 data-author-lastname="'.$a['author_lastname'] . '"
                 data-author-birthyear="'.$a['author_birthyear'] . '"
                 data-author-nationality="'.$a['author_nationality'] . '"
 
                 >Edit</button>';
 
                 echo ' <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAuthorModal"
 
                  data-author-id="'.$a['author_id'].'"
                  data-author-firstname="'.$a['author_firstname'].'"
                  data-author-lastname="'.$a['author_lastname'].'"
 
                >Delete</button>';
                echo '</div>';
                  echo '</td>';
                echo'</tr>';
                 ?>
             
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
 
    <div class="col-12 col-lg-4">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Genres List</h5>
          <span class="small-muted">Static sample data</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($genres as $g){
                echo'<tr>';
                    echo'<td>'. $g['genre_id'].'</td>';
                    echo'<td>'. $g['genre_name'].'</td>';
                      echo '<td class="text-end">';
                      echo '<div class =" btn-group" role="group">';
 
                      echo ' <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editGenreModal"
 
                    data-genre-id="'. $g['genre_id'].'"
                      data-genre-name="'.$g['genre_name'].'"
 
                >Edit</button>';
 
 
 
                echo ' <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGenreModal"
 
                    data-genre-id="'. $g['genre_id'].'"
                      data-genre-name="'.$g['genre_name'].'"
 
                >Delete</button>';
 
              echo'</div>';
                echo '</td>';
               
                echo '</tr>';
              }
               
                ?>
                 
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- Edit Book Modal (UI only) -->
<div class="modal fade" id="editAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">
 
        <div class="mb-3">
            <label class="form-label">Author ID</label>
            <input class="form-control" name ="author_id" id="edit_author_id" readonly>
 
          </div>
 
          <div class="mb-3">
            <label class="form-label">Author Firstname</label>
            <input class="form-control" name ="author_firstname" id="edit_author_firstname">
          </div>
          <div class="mb-3">
            <label class="form-label">Author Lastname</label>
            <input class="form-control" name = "author_lastname" id ="edit_author_lastname">
          </div>
 
          <div class="mb-3">
            <label class="form-label">Author Birthyear</label>
            <input class="form-control" name = "author_birthyear" id ="edit_author_birthyear">
          </div>
          <div class="mb-3">
            <label class="form-label">Author Nationality</label>
            <input class="form-control" name = "author_nationality" id ="edit_author_nationality">
          </div>
          <button name= "update_author"class="btn btn-primary w-100" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
 
<div class="modal fade" id="deleteAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete_author_name"></strong>?</p>
        <p class="text-danger small">This action cannot be undone.</p>
 
        <form action="#" method="POST">
          <input type="hidden" name="author_id" id="delete_author_id">
          <input type="hidden" name="author_firstname" id="delete_author_firstname">
         
         
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="delete_author" >Delete</button>
           
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
 
<div class="modal fade" id="editGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">
 
        <div class="mb-3">
            <label class="form-label">Genre ID</label>
            <input class="form-control" name ="genre_id" id="edit_genre_id" readonly>
          </div>
            <div class="mb-3">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name = "genre_name" id ="edit_genre_name">
          </div>
          <button name= "update_genre"class="btn btn-primary w-100" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
 
<div class="modal fade" id="deleteGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete_genre_name"></strong>?</p>
        <p class="text-danger small">This action cannot be undone.</p>
 
        <form action="#" method="POST">
          <input type="hidden" name="genre_id" id="delete_genre_id">
          <input type="hidden" name="genre_name" id="delete_genre_name_input">
         
         
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="delete_genre" >Delete</button>
           
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->
 
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>
 
<script>
const deleteAuthorModal = document.getElementById('deleteAuthorModal');
 
deleteAuthorModal.addEventListener('show.bs.modal', function(event){
  const btn = event.relatedTarget;
 
  const authorId = btn.getAttribute('data-author-id').value = btn.getAttribute('data-author-id') || '';
  const firstname = btn.getAttribute('data-author-firstname').value = btn.getAttribute('data-author-firstname') || '';
  const lastname = btn.getAttribute('data-author-lastname').value = btn.getAttribute('data-author-lastname') || '';
 
  document.getElementById('delete_author_id').value = btn.getAttribute('data-author-id');
  document.getElementById('delete_author_firstname').value = btn.getAttribute('data-author-firstname');
  document.getElementById('delete_author_name').value = btn.getAttribute('data-author-name');
});
 
const editAuthorModal = document.getElementById('editAuthorModal');
 
editAuthorModal.addEventListener('show.bs.modal', function (event) {
 
  const btn = event.relatedTarget;
 
  document.getElementById('edit_author_id').value = btn.getAttribute('data-author-id');
  document.getElementById('edit_author_firstname').value = btn.getAttribute('data-author-firstname');
  document.getElementById('edit_author_lastname').value = btn.getAttribute('data-author-lastname');
  document.getElementById('edit_author_birthyear').value = btn.getAttribute('data-author-birthyear');
  document.getElementById('edit_author_nationality').value = btn.getAttribute('data-author-nationality');
 
});
 
const editGenreModal = document.getElementById('editGenreModal');
 
editGenreModal.addEventListener('show.bs.modal', function (event) {
 
  const btn = event.relatedTarget;
 
  document.getElementById('edit_genre_id').value = btn.getAttribute('data-genre-id');
  document.getElementById('edit_genre_name').value = btn.getAttribute('data-genre-name');
 
 
});
 
 const deleteGenreModal = document.getElementById('deleteGenreModal');
 
deleteGenreModal.addEventListener('show.bs.modal', function(event){
  const btn = event.relatedTarget;
 
  const genreId = btn.getAttribute('data-genre-id').value = btn.getAttribute('data-genre-id');
  const genreName = btn.getAttribute('data-genre-name').value = btn.getAttribute('data-genre-name');
 
 
  document.getElementById('delete_genre_id').value = btn.getAttribute('data-genre-id');
  document.getElementById('delete_genre_name').textContent = btn.getAttribute('data-genre-name');
 
 
  // IMPORTANT
  document.getElementById('delete_genre_name_input').value = btn.getAttribute('data-genre-name');
 
});
</script>
<script>
  const addAuthorStatus = <?php echo json_encode($addAuthorStatus)?>;
  const addAuthorMessage = <?php echo json_encode($addAuthorMessage)?>;
 
  if(addAuthorStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: addAuthorMessage,
      confirmButtonText: 'OK'
    });
  }else if(addAuthorStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: addAuthorMessage,
      confirmButtonText: 'OK'
    });
  }
 
 
  const addGenreStatus = <?php echo json_encode($addGenreStatus)?>;
  const addGenreMessage = <?php echo json_encode($addGenreMessage)?>;
 
  if(addGenreStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: addGenreMessage,
      confirmButtonText: 'OK'
    });
  }else if(addGenreStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: addGenreMessage,
      confirmButtonText: 'OK'
    });
  }
 
   const updateAuthorStatus = <?php echo json_encode($updateAuthorStatus)?>;
  const updateAuthorMessage = <?php echo json_encode($updateAuthorMessage)?>;
 
  if(updateAuthorStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: updateAuthorMessage,
      confirmButtonText: 'OK'
    });
  }else if(updateAuthorStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: updateAuthorMessage,
      confirmButtonText: 'OK'
    });
  }
     const updateGenreStatus = <?php echo json_encode($updateGenreStatus)?>;
  const updateGenreMessage = <?php echo json_encode($updateGenreMessage)?>;
 
  if(updateGenreStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: updateGenreMessage,
      confirmButtonText: 'OK'
    });
  }else if(updateGenreStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: updateGenreMessage,
      confirmButtonText: 'OK'
    });
  }
  </script>
</body>
</html>
 
 