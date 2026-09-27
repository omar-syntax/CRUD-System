<?php

include "includes/config.php";
include "includes/header.php";

$sql = "SELECT * FROM products";
$res = mysqli_query($conn, $sql);

?>
<div class="container">
    <h2 class="text-primary mt-4">Products</h2>
    <button type="button" class="btn btn-primary mb-4 mt-2" data-bs-toggle="modal" data-bs-target="#modalId">
        Add product
    </button>
</div>

<table class="table container">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Price</th>
            <th scope="col">Created_at</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
        ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= $row["name"] ?></td>
                    <td><?= $row["description"] ?></td>
                    <td><?= $row["price"] ?></td>
                    <td><?= $row["created_at"] ?></td>
                    <td>
                        <button type="button" class="btn btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#editmodal"
                            data-id="<?= $row['id'] ?>"
                            data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
                            data-description="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>"
                            data-price="<?= $row['price'] ?>">Edit</button>
                        <a type="button" class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modal2Id"
                            data-id="<?= $row['id'] ?>">Delete</a>
                        <a href="#" class="btn btn-primary">Show</a>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>



<div class="modal fade" id="modalId" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="addModalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalTitleId">Add a new product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">product details</div>
            <form action="create.php" method="post" class="p-3">
                <div class="mb-3">
                    <input type="text" class="form-control m-1" name="name" id="addName"
                        aria-describedby="helpId" placeholder="Product name" />
                    <input type="text" class="form-control m-1" name="describtion" id="addDescribtion"
                        aria-describedby="helpId" placeholder="Product describtion" />
                    <input type="text" class="form-control m-1" name="price" id="addPrice"
                        aria-describedby="helpId" placeholder="Product price" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal2Id" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="deleteModalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalTitleId">Danger Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete this product?</div>
            <form action="delet.php" method="post" class="p-3">
                <div class="mb-3">
                    <input type="hidden" name="id" id="deleteId" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editmodal" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="editModalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitleId">Update product details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Change the data you want to edit it.</div>
            <form action="edit.php" method="post" class="p-3">
                <div class="mb-3">
                    <input class="form-control m-1" name="name" id="editName" aria-describedby="helpId" value="">
                    <input class="form-control m-1" name="description" id="editDescription" aria-describedby="helpId" value="">
                    <input class="form-control m-1" name="price" id="editPrice" aria-describedby="helpId" value="">
                    <input type="hidden" name="id" id="editId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editmodal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('editId').value          = button.getAttribute('data-id');
        document.getElementById('editName').value        = button.getAttribute('data-name');
        document.getElementById('editDescription').value = button.getAttribute('data-description');
        document.getElementById('editPrice').value       = button.getAttribute('data-price');
    });

    document.getElementById('modal2Id').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('deleteId').value = button.getAttribute('data-id');
    });
</script>

<?php
include "includes/footer.php";
?>