<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-5">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="ml-2">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="fillCustomers">
                    <i class="fas fa-users"></i>
                    <span class="ml-2">Customers</span>
                    <i class="fas fa-caret-down ml-2" id="customersCaret"></i>
                </a>
                <ul class="nav flex-column ml-3" id="manageCustOptions" style="display: none;">
                    <li class="nav-item">
                        <a class="nav-link" href="add_customers.php">
                            <i class="fas fa-user-plus"></i>
                            <span class="ml-2">Add Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_customers.php">
                            <i class="fas fa-eye"></i>
                            <span class="ml-2">View Customers</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="fillDetailsLink">
                    <i class="fas fa-store"></i>
                    <span class="ml-2">Shops</span>
                    <i class="fas fa-caret-down ml-2" id="detailsCaret"></i>
                </a>
                <ul class="nav flex-column ml-3" id="manageDetailOptions" style="display: none;">
                    <li class="nav-item">
                        <a class="nav-link" href="fill_details.php">
                            <i class="fas fa-industry"></i>
                            <span class="ml-2">Create Shops</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_details.php">
                            <i class="fas fa-eye"></i>
                            <span class="ml-2">View Shops</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="manageProductsLink">
                    <i class="fas fa-box-open"></i>
                    <span class="ml-2">Products</span>
                    <i class="fas fa-caret-down ml-2" id="productsCaret"></i>
                </a>
                <ul class="nav flex-column ml-3" id="manageProductsOptions" style="display: none;">
                    <li class="nav-item">
                        <a class="nav-link" href="add_products.php">
                            <i class="fas fa-cart-plus"></i>
                            <span class="ml-2">Add Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="viewproduct.php">
                            <i class="fas fa-eye"></i>
                            <span class="ml-2">View Products</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="manageBill">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="ml-2">Bills</span>
                    <i class="fas fa-caret-down ml-2" id="billCaret"></i>
                </a>
                <ul class="nav flex-column ml-3" id="manageBillOptions" style="display: none;">
                    <li class="nav-item">
                        <a class="nav-link" href="generatebill.php">
                            <i class="fas fa-file-invoice"></i>
                            <span class="ml-2">Generate New Bill</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="generatereport.php">
                    <i class="fas fa-chart-bar"></i>
                    <span class="ml-2">Generate Reports</span>
                </a>
            </li>

        </ul>
    </div>
</nav>


<script type="text/javascript">
    document.getElementById('manageProductsLink').addEventListener('click', function () {
        var options = document.getElementById('manageProductsOptions');
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
        var caret = document.getElementById('productsCaret');
        caret.classList.toggle('fa-caret-up');
    });

    document.getElementById('fillDetailsLink').addEventListener('click', function () {
        var options = document.getElementById('manageDetailOptions');
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
        var caret = document.getElementById('detailsCaret');
        caret.classList.toggle('fa-caret-up');
    });
    document.getElementById('fillCustomers').addEventListener('click', function () {
        var options = document.getElementById('manageCustOptions');
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
        var caret = document.getElementById('customersCaret');
        caret.classList.toggle('fa-caret-up');
    });

    document.getElementById('manageBill').addEventListener('click', function () {
        var options = document.getElementById('manageBillOptions');
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
        var caret = document.getElementById('billCaret');
        caret.classList.toggle('fa-caret-up');
    });
</script>