<?php
// Display success/error messages
if (isset($_GET['success'])) {
	echo '<div class="alert alert-success">Shoe added successfully!</div>';
} elseif (isset($_GET['error'])) {
	echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- My CSS -->
	<link rel="stylesheet" href="AdminDashboard.css">
	<style>
		.sizes-container {
			max-height: 200px;
			overflow-y: auto;
			border: 1px solid #dee2e6;
			padding: 10px;
			border-radius: 5px;
		}
	</style>

	<title>Thunderkicks</title>
</head>

<body>
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="tanda.png" class="logo">
			<span class="text">ThunderKicks</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="AdminDashboard.php">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#" class="logout">
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>



	<!-- CONTENT -->
	<section id="content">

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
				</div>
				<button class="btn-download" data-bs-toggle="modal" data-bs-target="#addShoesModal">Add Shoes</button>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check'></i>
					<span class="text">
						<h3>1020</h3>
						<p>New Order</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3>2834</h3>
						<p>Visitors</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">
						<h3>$2543</h3>
						<p>Total Sales</p>
					</span>
				</li>
			</ul>


			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Orders</h3>
						<i class='bx bx-search'></i>
						<i class='bx bx-filter'></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>User</th>
								<th>Date Order</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="sapatos.webp">
									<p>Jordan</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status completed">Completed</span></td>
							</tr>
							<tr>
								<td>
									<img src="sapatos.webp">
									<p>Nike Air</p>
								</td>
								<td>01-10-2021</td>
								<td><span class="status pending">Pending</span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Todos</h3>
						<i class='bx bx-plus'></i>
						<i class='bx bx-filter'></i>
					</div>
					<ul class="todo-list">
						<li class="completed">
							<p>Restock</p>
							<i class='bx bx-dots-vertical-rounded'></i>
						</li>
						<li class="completed">
							<p>Social Media Engagement</p>
							<i class='bx bx-dots-vertical-rounded'></i>
						</li>
						<li class="not-completed">
							<p>Updates on Flatform</p>
							<i class='bx bx-dots-vertical-rounded'></i>
						</li>
					</ul>
				</div>
			</div>
		</main>

		<!-- Add Shoes Modal -->
		<div class="modal fade" id="addShoesModal" tabindex="-1" aria-labelledby="addShoesModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form action="/thunderkicks1/backend/controllers/addShoesHandler.php" method="POST" enctype="multipart/form-data">
						<div class="modal-header">
							<h5 class="modal-title" id="addShoesModalLabel">Add New Shoes</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>

						<div class="modal-body">
							<div class="row">
								<!-- Basic Info Column -->
								<div class="col-md-6">
									<div class="mb-3">
										<label for="shoeName" class="form-label">Shoe Name</label>
										<input type="text" class="form-control" id="shoeName" name="shoeName" required>
									</div>

									<div class="mb-3">
										<label for="shoeBrand" class="form-label">Brand</label>
										<select class="form-select" id="shoeBrand" name="shoeBrand" required>
											<option value="">Select Brand</option>
											<?php
											$brands = ['Adidas', 'Anta', 'Nike', 'New Balance'];
											foreach ($brands as $brand) {
												echo "<option value='$brand'>$brand</option>";
											}
											?>
										</select>
									</div>

									<div class="mb-3">
										<label for="shoeCategory" class="form-label">Category</label>
										<select class="form-select" id="shoeCategory" name="shoeCategory" required>
											<option value="">Select Category</option>
											<?php
											$categories = ['Men', 'Women', 'Kids'];
											foreach ($categories as $category) {
												echo "<option value='$category'>$category</option>";
											}
											?>
										</select>
									</div>

									<div class="mb-3">
										<label for="shoeType" class="form-label">Type</label>
										<select class="form-select" id="shoeType" name="shoeType" required>
											<option value="">Select Type</option>
											<option value="Basketball">Basketball</option>
											<option value="Running">Running</option>
											<option value="Casual">Casual</option>
											<option value="Training">Training</option>
										</select>
									</div>

									<div class="mb-3">
										<label for="shoePrice" class="form-label">Price</label>
										<div class="input-group">
											<span class="input-group-text">₱</span>
											<input type="number" step="0.01" class="form-control" id="shoePrice" name="shoePrice" required>
										</div>
									</div>
								</div>

								<!-- Image and Sizes Column -->
								<div class="col-md-6">
									<div class="mb-3">
										<label for="shoeImage" class="form-label">Product Image</label>
										<input type="file" class="form-control" id="shoeImage" name="shoeImage" accept="image/*" required>
										<div class="form-text">Recommended size: 500x500px</div>
									</div>

									<div class="mb-3">
										<label class="form-label">Sizes & Stock</label>
										<div class="sizes-container">
											<?php
											$commonSizes = [7, 8, 9, 10, 11, 12];
											foreach ($commonSizes as $size) {
												echo '
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">Size ' . $size . '</span>
                                            <input type="number" class="form-control" name="sizes[' . $size . ']" placeholder="Stock" min="0" value="10">
                                        </div>';
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Add Product</button>
						</div>
					</form>
				</div>
			</div>
		</div>

	</section>
	<!-- CONTENT -->


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>