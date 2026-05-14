<?php
require_once('../classes/database.php');
$con = new database();

$overview = [
	'total_books' => 0,
	'total_copies' => 0,
	'open_loans' => 0,
	'overdue_items' => 0,
];

$bookcount = $con->countBook();
$loans = $con->viewLoans();

$recentLoans = [];

try {
	$overview = $con->viewDashboardOverview() ?: $overview;
	$recentLoans = $con->viewRecentLoans(5);
} catch (Exception $e) {
	$recentLoans = [];
}

$totalBooks = (int)($overview['total_books'] ?? 0);
$totalCopies = (int)($overview['total_copies'] ?? 0);
$openLoans = (int)($overview['open_loans'] ?? 0);
$overdueItems = (int)($overview['overdue_items'] ?? 0);
?>
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard — Library</title>

  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.html">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navAdmin" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link active" href="admin-dashboard.html">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.html">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="books.html">Books</a></li>
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
		<div class="col-12 col-lg-8">
			<div class="card p-4">
				<h5 class="mb-1">Quick Overview</h5>
				<p class="small-muted mb-4">These are NOT placeholder values—no need to connect to PHP later.</p>

				<div class="row g-3 mb-4">
					<div class="col-6 col-md-3">
						<div class="border rounded p-3 bg-white h-100">
							<div class="small-muted">Total Books</div>
							<div class="fs-4 fw-semibold"><?php echo $totalBooks; ?></div>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<div class="border rounded p-3 bg-white h-100">
							<div class="small-muted">Total Copies</div>
							<div class="fs-4 fw-semibold"><?php echo $totalCopies; ?></div>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<div class="border rounded p-3 bg-white h-100">
							<div class="small-muted">Open Loans</div>
							<div class="fs-4 fw-semibold"><?php echo $openLoans; ?></div>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<div class="border rounded p-3 bg-white h-100">
							<div class="small-muted">Overdue Items</div>
							<div class="fs-4 fw-semibold"><?php echo $overdueItems; ?></div>
						</div>
					</div>
				</div>

        <hr class="my-4">

        <h6 class="mb-2">Recent Loans</h6>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Loan ID</th>
                <th>Borrower</th>
                <th>Status</th>
                <th>Loan Date</th>
                <th>Processed By</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach($loans as $loan){ ?>
                <tr>
                  <td><?php echo $loan['loan_id']; ?></td>
                  <td><?php echo $loan['borrower_name']; ?></td>

                  <td>
                    <?php if($loan['loan_status'] == 'OPEN'){ ?>
                      <span class="badge text-bg-warning">OPEN</span>
                    <?php } else { ?>
                      <span class="badge text-bg-success">CLOSED</span>
                    <?php } ?>
                  </td>

                  <td><?php echo date("Y-m-d", strtotime($loan['loan_date'])); ?></td>
                  <td><?php echo $loan['processed_by']; ?></td>
                </tr>
              <?php } ?>
            </tbody>

          </table>
        </div>

      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h6 class="mb-3">Admin Shortcuts</h6>

        <div class="d-grid gap-2">
          <a class="btn btn-primary" href="checkout.html">Process Checkout</a>
          <a class="btn btn-outline-primary" href="return.html">Process Return</a>
          <a class="btn btn-outline-secondary" href="books.html">Manage Books</a>
          <a class="btn btn-outline-secondary" href="borrowers.html">Manage Borrowers</a>
        </div>

        <hr class="my-4">

        <div class="small-muted">
          Reminder: every checkout must record <b>processed_by_user_id</b> (ADMIN).
        </div>
      </div>
    </div>

  </div>
</main>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>