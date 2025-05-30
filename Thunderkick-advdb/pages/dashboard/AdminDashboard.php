<?php
$successMsg = isset($_GET['success']) ? 'Shoe added successfully!' : null;
$errorMsg = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
$orderController = new OrdersController($conn);
$totalSales = $orderController->getTotalSales();
$recentOrders = $orderController->getRecentOrders();
$orderController = new OrdersController($conn);
$totalSales = $orderController->getTotalSales();
$totalOrders = $orderController->getTotalOrders(); // Add this line
$recentOrders = $orderController->getRecentOrders();
$userModel = new userModel($conn);
$totalUsers = $userModel->getTotalUsers();
$orderController = new OrdersController($conn);
$totalSales = $orderController->getTotalSales();
$totalOrders = $orderController->getTotalOrders();
$recentOrders = $orderController->getRecentOrders();
$adminNotifications = $orderController->getAdminNotifications();
?>s
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- My CSS -->
	<link rel="stylesheet" href="\thunderkicks1\Thunderkick-advdb\public\css\AdminDashboard.css">
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
				<a href="index.php?page=login" class="logout">
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
			<?php if ($successMsg): ?>
				<div id="flash-message" class="alert alert-success">
					<strong>Success!</strong> <?= $successMsg ?>
				</div>
			<?php endif; ?>

			<?php if ($errorMsg): ?>
				<div id="flash-message" class="alert alert-danger">
					<strong>Error!</strong> <?= $errorMsg ?>
				</div>
			<?php endif; ?>
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
						<h3><?= number_format($totalOrders) ?></h3>
						<p>Total Orders</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3><?= number_format($totalUsers) ?></h3>
						<p>Registered Users</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">
						<h3>₱<?= number_format($totalSales, 2) ?></h3>
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
					<!-- Update your orders table in AdminDashboard.php -->
					<table>
						<thead>
							<tr>
								<th>User</th>
								<th>Product</th>
								<th>Date Order</th>
								<th>Status</th>
								<th>Action</th> <!-- New column for the button -->
							</tr>
						</thead>
						<tbody>
							<?php foreach ($recentOrders as $order): ?>
								<tr>
									<td>
										<p><?= htmlspecialchars($order['username']) ?></p>
									</td>
									<td>
										<img src="<?= htmlspecialchars($order['shoe_img']) ?>" alt="shoe image">
										<p><?= htmlspecialchars($order['name']) ?></p>
									</td>
									<td><?= htmlspecialchars(date('Y-m-d', strtotime($order['created_at']))) ?></td>
									<td>
										<span class="status <?= $order['status'] === 'Completed' ? 'completed' : 'pending' ?>">
											<?= htmlspecialchars($order['status']) ?>
										</span>
									</td>
									<td>
										<?php if ($order['status'] !== 'Completed'): ?>
											<form method="POST" action="?page=complete-order">
												<input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
												<button type="submit" class="btn btn-success btn-sm">Complete</button>
											</form>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Notifications</h3>
						<i class='bx bx-bell'></i>
					</div>
					<ul class="notification-list">
						<?php if (!empty($adminNotifications)): ?>
							<?php foreach ($adminNotifications as $notification): ?>
								<li>
									<p><?= htmlspecialchars($notification['description']) ?></p>
									<span class="date"><?= htmlspecialchars(date('M d, Y H:i', strtotime($notification['created_at']))) ?></span>
								</li>
							<?php endforeach; ?>
						<?php else: ?>
							<li>
								<p>No new notifications.</p>
							</li>
						<?php endif; ?>
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


	<script>
		setTimeout(() => {
			const flash = document.getElementById('flash-message');
			if (flash) {
				flash.classList.add('opacity-0', 'transition-opacity', 'duration-700');
				setTimeout(() => flash.remove(), 700);
			}
		}, 3000); // 3 seconds
	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>



</body>

</html>