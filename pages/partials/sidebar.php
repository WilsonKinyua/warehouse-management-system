<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index.php" class="brand-link">
        <img src="../../dist/img/favicon.ico" alt="Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Premiersoft Warehouse</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Gemad Agencies Ltd</a>
            </div>
        </div>
        <?php include __DIR__ . '/../utils/conn.php';
        // $conn = mysqli_connect("localhost",'root','','premierdb')or die($conn->error);
        $rights = $conn->query("SELECT * FROM security_groups WHERE id='$group' ")or die($conn->error);
        if (mysqli_num_rows($rights) == 1) {
        $row = $rights->fetch_array();
        $group = $row["group_name"];
        ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if(!($row["view_dashboard"] == 0)) { ?>
                    <li class="nav-item has-treeview">
                        <a href="../../index.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                <?php } if (!($row["manage_users"] == 0 && $row["manage_user_groups"] == 0)) {?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                User Management
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (!($row["manage_users"] == 0)) { ?>
                                <li class="nav-item">
                                    <a href="../users/users.php" class="nav-link">
                                        <i class="fas fa-user-plus nav-icon"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                            <?php } if (!($row["manage_user_groups"] == 0)) { ?>
                                <li class="nav-item">
                                    <a href="../users/groups.php" class="nav-link">
                                        <i class="fas fa-briefcase nav-icon"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } if (!($row["manage_suppliers"] == 0 && $row["manage_customers"] == 0 && $row["manage_customer_groups"] == 0)) {?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Contacts
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (!($row["manage_suppliers"] == 0)){ ?>
                                <li class="nav-item">
                                    <a href="../contacts/suppliers.php" class="nav-link">
                                        <i class="fas fa-truck-loading nav-icon"></i>
                                        <p>Suppliers</p>
                                    </a>
                                </li>
                            <?php } if (!($row["manage_customers"] == 0)){ ?>
                                <li class="nav-item">
                                    <a href="../contacts/customers.php" class="nav-link">
                                        <i class="far fa-user nav-icon"></i>
                                        <p>Customers</p>
                                    </a>
                                </li>
                            <?php } if (!($row["manage_customer_groups"] == 0)){ ?>
                                <li class="nav-item">
                                    <a href="../contacts/groups.php" class="nav-link">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>Customer Groups</p>
                                    </a>
                                </li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }if (!($row["manage_products"] == 0 && $row["manage_categories"] == 0)){?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                                Products
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (!($row["manage_products"] == 0)){?>
                                <li class="nav-item">
                                    <a href="../products/list.php" class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Products List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../products/products_create.php" class="nav-link">
                                        <i class="fas fa-plus-circle nav-icon"></i>
                                        <p>Add Products</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../products/price_list.php" class="nav-link">
                                        <i class="fas fa-barcode nav-icon"></i>
                                        <p>Price Lists</p>
                                    </a>
                                </li>
                            <?php } if (!($row["manage_categories"] == 0)){ ?>
                                <li class="nav-item">
                                    <a href="../products/categories.php" class="nav-link">
                                        <i class="fas fa-circle nav-icon"></i>
                                        <p>Product Categories</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }if (!($row["receive_stock"] == 0)){?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Purchases
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../purchases/list.php" class="nav-link">
                                    <i class="fas fa-list nav-icon"></i>
                                    <p>List Purchases</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../purchases/purchases_create.php" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon"></i>
                                    <p>Add Purchase</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../purchases/returns.php" class="nav-link">
                                    <i class="fas fa-undo nav-icon"></i>
                                    <p>List Purchases Returns</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php }if (!($row["sale_from_main"] == 0 )){?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-store"></i>
                            <p>
                                Sales
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (!($row["make_sales"] == 0)) { ?>
                                <li class="nav-item">
                                    <a href="../sales/sales_create.php" class="nav-link">
                                        <i class="fas fa-plus-circle nav-icon"></i>
                                        <p>Add Sales</p>
                                    </a>
                                </li>
                        
						<?php } ?>
						<!-- 
                            <li class="nav-item">
                                <a href="../sales/view_stock.php" class="nav-link">
                                    <i class="fa fas fa-hourglass-half nav-icon"></i>
                                    <p>View Stock</p>
                                </a>
                            </li>  -->
                            <?php if (!($row["issue_stock"] == 0)){ ?>
                                <li class="nav-item">
                                    <a href="../sales/request.php" class="nav-link">
                                        <i class="fas fa-shipping-fast nav-icon"></i>
                                        <p>Assign Stock</p>
                                    </a>
                                </li>
                            <?php } if (!($row["clear_sales"] == 0)){ ?>
                                <li class="nav-item">
                                    <a href="../sales/clear.php" class="nav-link">
                                        <i class="fas fa-circle nav-icon"></i>
                                        <p>Clear Sales</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
					 <?php if (!($row["adjust_stock"] == 0)){ ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Inventory
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../adjustments/list.php" class="nav-link">
                                   <i class="fas fa-plus-circle nav-icon"></i>
                                    <p>Adjust Inventory</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } }?>
				<?php if (!($row["manage_accounts"] == 0)){?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>
                            Accounts
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
					<?php } if (!($row["manage_accounts"] == 0)){?>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="../accounts/account_create.php" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Add Account</p>
                            </a>
                        </li>
					<?php } if (!($row["manage_account_categories"] == 0)){?>
                            <li class="nav-item">
                                <a href="../accounts/account_groups.php" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon"></i>
                                    <p>Account Groups</p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <!-- AR -->
				<?php if (!($row["credit_sales"] == 0)){?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Account Receivables
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../accounts_receivable/invoice_create.php" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Add Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../accounts_receivable/payment_create.php" class="nav-link">
                                <i class="fas fa-money-check nav-icon"></i>
                                <p>Add Payment</p>
                            </a>
                        </li>
                    </ul>
                </li>
				<?php } ?>
				
				<!-- AP -->
				<?php if (!($row["credit_sales"] == 0)){?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Account Payables
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../accounts_payable/bill_create.php" class="nav-link">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Add Bill</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../accounts_payable/bill_payment_create.php" class="nav-link">
                                <i class="fas fa-money-check nav-icon"></i>
                                <p>Add Bill Payment</p>
                            </a>
                        </li>
                    </ul>
                </li>
				<?php } ?>
				
				
                <?php  if (!($row["settings"] == 0)){?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../settings/discounts.php" class="nav-link">
                                    <i class="fas fa-cogs nav-icon"></i>
                                    <p>Discounts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../settings/tax_rates.php" class="nav-link">
                                    <i class="fas fa-bolt nav-icon"></i>
                                    <p>Taxes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../settings/pay_terms.php" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                    <p>Pay Terms</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../settings/stores.php" class="nav-link">
                                    <i class="fas fa-map-marker nav-icon"></i>
                                    <p>Stores</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } }?>
                <!--*******************REPORTS Side Nav *****************************-->

                <?php if (!($row["reports"] == 0 )){?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fas fa-chart-bar"></i>
                        <p>
                            Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <?php if (!($row["sales_reports"] == 0 )) { ?>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                             <a href="../reports/profit_loss.php" class="nav-link">
                                <i class="fa fas fa-money-check-alt nav-icon"></i>
                                <p>Profit & Loss</p>
                            </a>
                        </li>
						<?php } if (!($row["sales_reports"] == 0 )) { ?>
                        <li class="nav-item">
                            <a href="../reports/salessummary.php" class="nav-link">
                                <i class="fa fas fa-chart-line nav-icon"></i>
                                <p>Sales Summary</p>
                            </a>
                        </li>
						  <?php } if (!($row["sales_reports"] == 0 )) { ?>
                            <li class="nav-item">
                                <a href="../reports/dailysalessummary.php" class="nav-link">
                                    <i class="fa fas fa-search-dollar nav-icon "></i>
                                    <p>Daily Sales Summary</p>
                                </a>
                            </li>
                        <?php } if (!($row["sales_reports"] == 0 )) { ?>
                            <li class="nav-item">
                                <a href="../reports/salesdetail.php" class="nav-link">
                                    <i class="fas fa-file nav-icon"></i>
                                    <p>Sales Detail</p>
                                </a>
                            </li>
                        <?php } if (!($row["reports"] == 0 )) { ?>
                            </li>
                        <?php } if (!($row["sales_reports"] == 0 )) { ?>
                            <li class="nav-item">
                                <a href="../reports/itemsale.php" class="nav-link">
                                    <i class="fa fas fa-tags nav-icon "></i>
                                    <p>Item Sales</p>
                                </a>
                            </li>
                            
                        <?php } if (!($row["transfer_reports"] == 0 )) { ?>
                            <li class="nav-item">
                                <a href="../reports/inventory.php" class="nav-link">
                                    <i class="fa fas fa-hourglass-half nav-icon "></i>
                                    <p>Current Stocks</p>
                                </a>
                            </li>

                        <?php } if (!($row["transfer_reports"] == 0 )) { ?>
                        <li class="nav-item">
                            <a href="../reports/purchasedet.php" class="nav-link">
                                <i class="fa fas fa-truck nav-icon "></i>
                                <p>Purchases Detail</p>
                            </a>
                        </li>

                        <?php } if (!($row["transfer_reports"] == 0 )) { ?>
                        <li class="nav-item">
                            <a href="../reports/stocks_movement.php" class="nav-link">
                                <i class="fa fas fa-truck nav-icon "></i>
                                <p> Stocks Movement </p>
                            </a>
                        </li>

                    </ul>
                <?php }
                }?>

                    <!--**************************************************************** -->
                <li class="nav-item">
                    <a href="../utils/logout.php" class="nav-link">
                        <i class="nav-icon fas fa-undo-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>