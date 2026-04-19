<?php
require '../includes/config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Manage Products';
$error = '';
$success = '';

// Handle add/update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    if (!verifyCsrfToken()) {
        $error = 'Invalid form submission.';
    } else {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = sanitizeInput($_POST['category'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? 'mens');
    $status = sanitizeInput($_POST['status'] ?? 'active');
    $image = $_FILES['image']['name'] ?? '';

    // Server-side image validation
    if (!empty($image)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $real_type = $finfo->file($_FILES['image']['tmp_name']);
        if (!in_array($real_type, $allowed_types) || !in_array($file_ext, $allowed_exts)) {
            $error = 'Invalid image file type! Only JPG, PNG, GIF, and WEBP are allowed.';
        }
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $error = 'Image file size must be less than 5MB.';
        }
    }

    $available_colors = isset($_POST['available_colors']) ? implode(',', array_map('sanitizeInput', $_POST['available_colors'])) : '';
    $available_sizes = ($category === 'accessories') ? '' : (isset($_POST['available_sizes']) ? implode(',', array_map('sanitizeInput', $_POST['available_sizes'])) : '');

    if (!empty($error)) {
        // Image validation already failed above
    } elseif (empty($name) || $price <= 0 || empty($category)) {
        $error = 'Please fill in all required fields with valid values!';
    } else {
        if ($product_id === 0) {
            // Add new product
            if (empty($image)) {
                $error = 'Please upload an image!';
            } else {
                $image = 'apparel_' . time() . '_' . basename($image);
                $target_path = '../images/products/' . $image;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, gender, available_colors, available_sizes, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssdssssss", $name, $description, $price, $category, $gender, $available_colors, $available_sizes, $image, $status);
                    
                    if ($stmt->execute()) {
                        logAudit('product_added', 'product', $conn->insert_id, "Product: $name");
                        $success = 'Product added successfully!';
                    } else {
                        $error = 'Failed to add product!';
                    }
                    $stmt->close();
                } else {
                    $error = 'Failed to upload image!';
                }
            }
        } else {
            // Update product
            if (!empty($image)) {
                $image = 'apparel_' . time() . '_' . basename($image);
                $target_path = '../images/products/' . $image;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, gender = ?, available_colors = ?, available_sizes = ?, image = ?, status = ? WHERE id = ?");
                    $stmt->bind_param("ssdssssssi", $name, $description, $price, $category, $gender, $available_colors, $available_sizes, $image, $status, $product_id);
                } else {
                    $error = 'Failed to upload image!';
                }
            } else {
                $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, gender = ?, available_colors = ?, available_sizes = ?, status = ? WHERE id = ?");
                $stmt->bind_param("ssdsssssi", $name, $description, $price, $category, $gender, $available_colors, $available_sizes, $status, $product_id);
            }
            
            if (isset($stmt) && $stmt->execute()) {
                logAudit('product_updated', 'product', $product_id, "Product: $name");
                $success = 'Product updated successfully!';
            } else {
                $error = 'Failed to update product!';
            }
            if (isset($stmt)) $stmt->close();
        }
    }
    } // end CSRF check
}

// Handle delete product (POST only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!verifyCsrfToken()) {
        $error = 'Invalid form submission.';
    } else {
        $delete_id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            logAudit('product_deleted', 'product', $delete_id, 'Product deleted');
            $success = 'Product deleted successfully!';
        } else {
            $error = 'Failed to delete product!';
        }
        $stmt->close();
    }
}

// Get all products
$products = $conn->query("SELECT * FROM products ORDER BY category, name");
?>

<?php include '../includes/header/header.php'; ?>
<?php include '../includes/admin-sidebar.php'; ?>

<div class="admin-container">
    <div class="mb-4">
        <h1 class="text-coffee-dark mb-2" style="font-size: 2rem; font-weight: 800;">
            <i class="fas fa-box"></i> Manage Products
        </h1>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Add Product Form -->
    <div class="admin-card mb-4">
        <h5 class="text-coffee-dark mb-4" style="font-weight: 700;">
            <i class="fas fa-plus"></i> Add New Product
        </h5>
        <form method="POST" enctype="multipart/form-data" id="addProductForm">
            <?php echo csrfTokenField(); ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price *</label>
                    <input type="number" class="form-control" name="price" step="0.01" min="0.01" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gender *</label>
                    <select class="form-control" name="gender" id="addGender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="mens">Men</option>
                        <option value="womens">Women</option>
                        <option value="kids">Kids</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Category *</label>
                    <select class="form-control" name="category" id="addCategory" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="t-shirts">T-Shirts</option>
                        <option value="hoodies">Hoodies</option>
                        <option value="pants">Pants</option>
                        <option value="dresses">Dresses</option>
                        <option value="accessories">Accessories</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Available Colors *</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $colorOptions = ['Black','White','Navy','Red','Blue','Gray','Pink','Yellow','Green','Maroon','Brown','Orange','Purple','Beige'];
                        foreach ($colorOptions as $color): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_colors[]" value="<?php echo $color; ?>" id="addColor<?php echo $color; ?>">
                                <label class="form-check-label" for="addColor<?php echo $color; ?>"><?php echo $color; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-6 mb-3" id="addSizesGroup">
                    <label class="form-label">Available Sizes *</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $sizeOptions = ['XS','S','M','L','XL','XXL'];
                        foreach ($sizeOptions as $size): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_sizes[]" value="<?php echo $size; ?>" id="addSize<?php echo $size; ?>">
                                <label class="form-check-label" for="addSize<?php echo $size; ?>"><?php echo $size; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image *</label>
                <input type="file" class="form-control" name="image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Add Product
            </button>
        </form>
    </div>

    <!-- Products List -->
    <div class="admin-card">
        <h5 class="text-coffee-dark mb-4" style="font-weight: 700;">
            <i class="fas fa-list"></i> Products List
        </h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Category</th>
                        <th>Colors</th>
                        <th>Sizes</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td>
                            <img src="../images/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        </td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo ucfirst($product['gender'] ?? ''); ?></td>
                        <td><?php echo ucfirst(str_replace('-', ' ', $product['category'])); ?></td>
                        <td><small><?php echo htmlspecialchars($product['available_colors'] ?? ''); ?></small></td>
                        <td><small><?php echo htmlspecialchars($product['available_sizes'] ?? '-'); ?></small></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $product['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($product['status']); ?>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning me-1" onclick='openEditModal(<?php echo json_encode($product, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                                <?php echo csrfTokenField(); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div><!-- /admin-main-content -->
</div><!-- /admin-layout -->

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #3d2b1f; color: #fff;">
                <h5 class="modal-title" id="editProductModalLabel"><i class="fas fa-edit"></i> Edit Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="editProductForm">
                <div class="modal-body">
                    <?php echo csrfTokenField(); ?>
                    <input type="hidden" name="product_id" id="editProductId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price *</label>
                            <input type="number" class="form-control" name="price" id="editPrice" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender *</label>
                            <select class="form-control" name="gender" id="editGender" required>
                                <option value="mens">Men</option>
                                <option value="womens">Women</option>
                                <option value="kids">Kids</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Category *</label>
                            <select class="form-control" name="category" id="editCategory" required>
                                <option value="t-shirts">T-Shirts</option>
                                <option value="hoodies">Hoodies</option>
                                <option value="pants">Pants</option>
                                <option value="dresses">Dresses</option>
                                <option value="accessories">Accessories</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="editStatus">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Colors *</label>
                            <div class="d-flex flex-wrap gap-2" id="editColorsGroup">
                                <?php foreach ($colorOptions as $color): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="available_colors[]" value="<?php echo $color; ?>" id="editColor<?php echo $color; ?>">
                                        <label class="form-check-label" for="editColor<?php echo $color; ?>"><?php echo $color; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="editSizesGroup">
                            <label class="form-label">Available Sizes *</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($sizeOptions as $size): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="available_sizes[]" value="<?php echo $size; ?>" id="editSize<?php echo $size; ?>">
                                        <label class="form-check-label" for="editSize<?php echo $size; ?>"><?php echo $size; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div><img id="editCurrentImage" src="" alt="Current" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Change Image <small class="text-muted">(leave empty to keep current)</small></label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../js/admin-sidebar.js"></script>
<script>
// Toggle sizes visibility based on category selection
function toggleSizes(categorySelect, sizesGroupId) {
    const sizesGroup = document.getElementById(sizesGroupId);
    if (!sizesGroup) return;
    if (categorySelect.value === 'accessories') {
        sizesGroup.style.display = 'none';
        sizesGroup.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
    } else {
        sizesGroup.style.display = '';
    }
}

const addCategory = document.getElementById('addCategory');
if (addCategory) {
    addCategory.addEventListener('change', function() {
        toggleSizes(this, 'addSizesGroup');
    });
}

const editCategory = document.getElementById('editCategory');
if (editCategory) {
    editCategory.addEventListener('change', function() {
        toggleSizes(this, 'editSizesGroup');
    });
}

function openEditModal(product) {
    document.getElementById('editProductId').value = product.id;
    document.getElementById('editName').value = product.name;
    document.getElementById('editPrice').value = product.price;
    document.getElementById('editGender').value = product.gender || 'mens';
    document.getElementById('editCategory').value = product.category;
    document.getElementById('editStatus').value = product.status;
    document.getElementById('editDescription').value = product.description || '';
    document.getElementById('editCurrentImage').src = '../images/products/' + product.image;

    // Reset and set colors
    const colors = (product.available_colors || '').split(',').map(c => c.trim());
    document.querySelectorAll('#editColorsGroup input[type=checkbox]').forEach(cb => {
        cb.checked = colors.includes(cb.value);
    });

    // Reset and set sizes
    const sizes = (product.available_sizes || '').split(',').map(s => s.trim());
    document.querySelectorAll('#editSizesGroup input[type=checkbox]').forEach(cb => {
        cb.checked = sizes.includes(cb.value);
    });

    // Toggle sizes visibility
    toggleSizes(document.getElementById('editCategory'), 'editSizesGroup');

    const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
    modal.show();
}
</script>

<?php include '../includes/footer/footer.php'; ?>