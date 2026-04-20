<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Manage Property Type</title>
    <style>
    .btn-edit {
        background: #ffc107;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: black;
        font-size: 13px;
    }

    .btn-edit:hover {
        background: #e0a800;
    }
    </style>

</head>

<body>
    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>
    <!-- NAVBAR -->
    <section id="navbar">
        <?php include 'header.php'; ?>
        <!-- MAIN -->
        <main>
            <h1 class="title" style="color: #5F84A2;">Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Property Type</a></li>
            </ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['ptid'])){
                        $ptid=$_GET['ptid'];
                        $ptype_sql="SELECT * FROM Property_type WHERE pt_ID='$ptid'";
                        $ptype_qry=mysqli_query($dbconid,$ptype_sql); 
                        while($ptype_result= mysqli_fetch_assoc($ptype_qry))
                        {
                        ?>
                    <input type="hidden" name="ptid" value="<?php echo $ptype_result['pt_ID'];?>">
                    <div class="inputfield">
                        <label>Property Type</label>
                        <input type="text" name="ptype" value="<?php echo $ptype_result['ptype'];?>" class="input">
                    </div>
                    <div class="inputfield">
                        <input type="submit" name="ptypeUpdate" value="Update" class="btn">
                    </div>
                    <?php
                        } }
                        else{
							?>
                    <div class="inputfield">
                        <label>Property Type</label>
                        <input type="text" name="ptype" class="input">
                    </div>
                    <div class="inputfield">
                        <input type="submit" name="ptypeSubmit" value="Submit" class="btn">
                    </div>
                    <?php } ?>
                </form>
            </div>
            <div class="table-data">
                <h3 class="table=title">Property Type Table</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Property Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 1;
                        $ptype_sql="SELECT * FROM Property_type";               
                        $ptype_qry=mysqli_query($dbconid,$ptype_sql);                                        
                        while($ptype= mysqli_fetch_assoc($ptype_qry))
                        {
                        ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $ptype['ptype'];?></td>
                                <td>
                                    <a href="ptypes.php?ptid=<?php echo $ptype['pt_ID'];?>" class="btn-edit">
                                    <i class='bx bx-edit'></i>Edit</a>
                                </td>

                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>

</body>

</html>