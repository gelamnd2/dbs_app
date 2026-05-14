 <?php
require_once('../classes/database.php');
$con = new database();

$allbooks = $con->viewBooks();
$allauthors = $con->viewAuthors();
$allgenres = $con->viewGenres();

$addUpdateBookStatus = null;
$addUpdateBookMessage = '';

if(isset($_POST['update_book'])){

  $book_id = $_POST['book_id'];
  $title = $_POST['book_title'];
  $isbn = $_POST['book_isbn'];
  $year = $_POST['book_publication_year'];
  $publisher = $_POST['book_publisher'];

  try {
    $con->updateBook($book_id, $title, $isbn, $year, $publisher);

   $addUpdateBookStatus = 'success';
    $addUpdateBookMessage = 'Update Books  successfully.';

  } catch(Exception $e) {
     $addUpdateBookStatus = 'error';
    $addUpdateBookMessage = $e->getmessage();
}
}

$addBookStatus = null;
$addBookMessage = '';
 
if(isset($_POST['add_book'])){
 
  $title = $_POST['book_title'];
  $isbn = $_POST['book_isbn'];
  $publication_year = $_POST['book_publication_year'];
  $edition = $_POST['book_edition'];
  $publisher = $_POST['book_publisher'];
 
 
  try {
    $book_id = $con->insertBook($title, $isbn, $publication_year, $edition, $publisher);

    $addBookStatus = 'success';
    $addBookMessage = 'Book added successfully.';

} catch (Exception $e) {
    $addBookStatus = 'error';
    $addBookMessage = $e->getmessage();
}
}
$copyStatus = null;
$copyMessage = '';

if(isset($_POST['book_copy'])){
 
  $book = $_POST['book_id'];
  $status = $_POST['bc_status'];

 
 
  try {
    $copy_id = $con->insertBookCopy($book, $status);

    $copyStatus = 'success';
    $copyMessage = 'Book added successfully.';

} catch (Exception $e) {
    $copyStatus = 'error';
    $copyMessage =  $e->getmessage();
}
}
$bookAuthorsStatus = null;
$bookAuthorsMessage = '';

if(isset($_POST['book_Author'])){

  
  $book_id = $_POST['book_id'];
  $author_id = $_POST['author_id'];

 
 
  try {
    $con->insertBookAuthors($book_id, $author_id);

    $bookAuthorsStatus = 'success';
    $bookAuthorsMessage = 'Book added successfully.';

} catch (Exception $e) {
    $bookAuthorsStatus = 'error';
    $bookAuthorsMessage = $e->getmessage();
}
}

  $bookgenreStatus = null;
  $bookgenreMessage = '';

  if(isset($_POST['add_Genre'])) {
    $book_id = $_POST['book_id'];
    $genre_id = $_POST['genre_id'];

    try {
      $con->addGenre($genre_id, $book_id);

      $bookgenreStatus = 'success';
      $bookgenreMessage = 'Genre assigned to book successfully.';
    }catch (Exception $e) {
      $bookgenreStatus = 'error';
      $bookgenreMessage = $e->getmessage();
    }
  }


?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Books — Admin</title>
  <!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"-->
  <link rel="stylesheet" href="../assets/css/style.css">

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">

</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.html">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBooks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navBooks" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.html">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.html">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link active" href="books.html">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.html">Borrowers</a></li>
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
    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h5 class="mb-1">Add Book</h5>
        <p class="small-muted mb-3">Creates a row in <b>Books</b>.</p>

        <!-- Later in PHP: action="../php/books/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" name="book_title" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" name="book_isbn" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" name="book_publication_year" type="number" min="1500" max="2100" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Edition</label>
            <input class="form-control" name="book_edition" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" name="book_publisher" placeholder="optional">
          </div>
          <button name="add_book" class="btn btn-primary w-100" type="submit">Save Book</button>
        </form>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-2">Add Copy</h6>
        <p class="small-muted mb-3">Creates a row in <b>BookCopy</b>.</p>
        <!-- Later in PHP: action="../php/copies/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Book</label>
            <select class="form-select" name="book_id" required>
              <option value="">Select book</option>
             <?php
              foreach($allbooks as $book) {
                echo '<option value="' . $book['book_id'] . '">' . $book['book_title'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="bc_status" required>
              <option value="AVAILABLE">AVAILABLE</option>
              <option value="ON_LOAN">ON_LOAN</option>
              <option value="LOST">LOST</option>
              <option value="DAMAGED">DAMAGED</option>
              <option value="REPAIR">REPAIR</option>
            </select>
          </div>
          <button name ='book_copy' class="btn btn-outline-primary w-100" type="submit">Add Copy</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-3">
          <div>
            <h5 class="mb-1">Books List</h5>
            <div class="small-muted">Placeholder rows. Replace with PHP + MySQL output.</div>
          </div>
          <div class="d-flex gap-2">
            <input class="form-control" style="max-width: 260px;" placeholder="Search title / ISBN...">
            <button class="btn btn-outline-secondary">Search</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Publisher</th>
                <th>Copies</th>
                <th>Available</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
//for edit button
              $viewcopies = $con->viewCopies();
              foreach($viewcopies as $books){


              echo'<tr>';
               echo' <td>'.$books['book_id'].'</td>';
               echo' <td>'.$books['book_title'].'</td>';
               echo' <td>'.$books['book_isbn'].'</td>';
               echo' <td>'.$books['book_publication_year'].'</td>';
               echo' <td>'.$books['book_publisher'].'</td>';
               echo' <td>'.$books['Copies'].'</td>';
               echo' <td>'.$books['Available_Copies'].'</td>';
                echo '<td class="text-end">';
                echo '<div class =" btn-group" role="group">';

                 echo ' <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBookModal"

                 data-book-id="'.$books['book_id'] . '"
                 data-book-title="'.$books['book_title'] . '"
                 data-book-isbn="'.$books['book_isbn'] . '"
                 data-book-publication-year="'.$books['book_publication_year'] . '"
                 data-book-publisher="'.$books['book_publisher'] . '"

                 >Edit</button>';


                  echo '<button class="btn btn-sm btn-outline-danger">Delete</button>';
                echo '</td>';
                echo '</tr>';
              }
              ?>

            </tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Author to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookAuthors</b>.</p>
              <!-- Later in PHP: action="../php/bookauthors/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                   <?php
                    foreach($allbooks as $book) {
                      echo '<option value="' . $book['book_id'] . '">' . $book['book_title'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="author_id" required>
                    <option value="">Select author</option>
                   <?php
                    foreach($allauthors as $author) {
                      echo '<option value="' . $author['author_id'] . '">' . $author['author_firstname'] . ' ' . $author['author_lastname'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-12">
                  <button name= "book_Author"class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (book_id, author_id).</div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Genre to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookGenre</b>.</p>
              <!-- Later in PHP: action="../php/bookgenre/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                    <?php
                    foreach($allbooks as $book) {
                      echo '<option value="' . $book['book_id'] . '">' . $book['book_title'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="genre_id" required>
                    <option value="">Select genre</option>
                   <?php
                    foreach($allgenres as $genre) {
                      echo '<option value="' . $genre['genre_id'] . '">' . $genre['genre_name'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="col-12">
                  <button name= "add_Genre" class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (genre_id, book_id).</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<!-- Edit Book Modal (UI only) -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Book</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">

        <div class="mb-3">
            <label class="form-label">Book ID</label>
            <input class="form-control" name ="book_id" id="edit_book_id" readonly>

          </div>

          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" name ="book_title" id="edit_book_title">
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" name = "book_isbn" id ="edit_book_isbn">
          </div>

          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" name = "book_publication_year" id ="edit_book_year">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" name = "book_publisher" id ="edit_book_publisher">
          </div>
          <button name= "update_book"class="btn btn-primary w-100" type="sunmit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script>

  const editBookModal = document.getElementById('editBookModal');

  editBookModal.addEventListener('show.bs.modal', function 
  (event) {

  const btn = event.relatedTarget;

  if(!btn) return;

  document.getElementById('edit_book_id').value = btn.getAttribute('data-book-id');
  document.getElementById('edit_book_title').value = btn.getAttribute('data-book-title');
  document.getElementById('edit_book_isbn').value = btn.getAttribute('data-book-isbn');
  document.getElementById('edit_book_year').value = btn.getAttribute('data-book-publication-year');
  document.getElementById('edit_book_publisher').value = btn.getAttribute('data-book-publisher');
  
}
);
  </script>

<script>

  const addBookStatus = <?php echo json_encode($addBookStatus)?>;
  const addBookMessage = <?php echo json_encode($addBookMessage)?>;
 
  if(addBookStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: addBookMessage,
      confirmButtonText: 'OK'
    });
  }else if(addBookStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: addBookMessage,
      confirmButtonText: 'OK'
    });
  }

  const copyStatus = <?php echo json_encode($copyStatus)?>;
  const copyMessage = <?php echo json_encode($copyMessage)?>;
 
  if(copyStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: copyMessage,
      confirmButtonText: 'OK'
    });
  }else if(copyStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: copyMessage,
      confirmButtonText: 'OK'
    });
  }

  const bookAuthorsStatus = <?php echo json_encode($bookAuthorsStatus)?>;
  const bookAuthorsMessage = <?php echo json_encode($bookAuthorsMessage)?>;
 
  if(bookAuthorsStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: bookAuthorsMessage,
      confirmButtonText: 'OK'
    });
  }else if(bookAuthorsStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: bookAuthorsMessage,
      confirmButtonText: 'OK'
    });
  }
   const bookgenreStatus = <?php echo json_encode($bookgenreStatus); ?>;
  const bookgenreMessage = <?php echo json_encode($bookgenreMessage); ?>;

  if(bookgenreStatus == 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: bookgenreMessage,
    });
  } else if(bookgenreStatus == 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: bookgenreMessage,
    });
  } 

  const addUpdateBookStatus = <?php echo json_encode($addUpdateBookStatus)?>;
  const addUpdateBookMessage = <?php echo json_encode($addUpdateBookMessage)?>;
 
  if(addUpdateBookStatus == 'success'){
    Swal.fire({
    icon: 'success',
    title: 'Success',
      text: addUpdateBookMessage,
      confirmButtonText: 'OK'
    });
  }else if(addUpdateBookStatus == 'error'){
    Swal.fire({
    icon: 'error',
    title: 'Error',
      text: addUpdateBookMessage,
      confirmButtonText: 'OK'
    });
  }
  </script>
</body>
</html>