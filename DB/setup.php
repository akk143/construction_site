<?php 
$dbconid=mysqli_connect("localhost","root","");
if ($dbconid) {
    echo "db connection successful";
} else {
    die("db connection error: ".mysqli_connect_error());
}

// create database
$database="CREATE DATABASE IF NOT EXISTS construction_db";
$qry_sql=mysqli_query($dbconid, $database);
if ($qry_sql) {
    echo "<br>construction db is created successfully.";
} else {
    echo "db creating error : ".mysqli_error($dbconid);
}

mysqli_select_db($dbconid,"construction_db");


$client_tbl="CREATE TABLE IF NOT EXISTS Client (
    client_ID int AUTO_INCREMENT NOT NULL,
    client_name varchar(80),
    client_email varchar(50),   
    client_phone varchar(30),
    client_address varchar(80),
    client_profile varchar(100),
    client_pwd varchar(100),
    PRIMARY KEY (client_ID)
)";
$qry_sql=mysqli_query($dbconid,$client_tbl);
if($qry_sql==true)
{
    echo "<br>Client tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$admin_tbl="CREATE TABLE IF NOT EXISTS Administrator_tbl (
    admin_ID int AUTO_INCREMENT NOT NULL,
    admin_name varchar(80),
    admin_email varchar(50),   
    admin_phone varchar(30),
    admin_address varchar(80),
    admin_profile varchar(100),
    admin_pwd varchar(100),
    PRIMARY KEY (admin_ID)
)";
$qry_sql=mysqli_query($dbconid,$admin_tbl);
if($qry_sql==true)
{
    echo "<br>Admin tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$contact_tbl="CREATE TABLE IF NOT EXISTS ContactMessage (
    msg_ID int AUTO_INCREMENT NOT NULL,
    sender_name varchar(50),
    sender_email varchar(50),   
    msg_subject text,
    msg_detail text,
    PRIMARY KEY (msg_ID)
)";
$qry_sql=mysqli_query($dbconid,$contact_tbl);
if($qry_sql==true)
{
    echo "<br>Contact_Message tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$FAQ_tbl="CREATE TABLE IF NOT EXISTS FAQ (
    FAQ_ID int AUTO_INCREMENT NOT NULL,
    FAQ_question text,
    FAQ_answer text,
    PRIMARY KEY (FAQ_ID)
)";
$qry_sql=mysqli_query($dbconid,$FAQ_tbl);
if($qry_sql==true)
{
    echo "<br>FAQ tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$serviceCate_tbl="CREATE TABLE IF NOT EXISTS Service_Category(
    sc_ID int AUTO_INCREMENT NOT NULL,
    category_name varchar(80),
    category_img varchar(100), 
    category_description text,
    PRIMARY KEY (sc_ID)
)";
$qry_sql=mysqli_query($dbconid,$serviceCate_tbl);
if($qry_sql==true)
{
    echo "<br>Service category tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$subcate_tbl="CREATE TABLE IF NOT EXISTS Service_SubCategory(
    subCate_ID int AUTO_INCREMENT NOT NULL,
    subCate_name varchar(80),
    subCate_img varchar(100), 
    subCate_description text,
    sc_ID int NOT NULL,
    PRIMARY KEY (subCate_ID),
	FOREIGN KEY (sc_ID) REFERENCES Service_Category(sc_ID)
)";
$qry_sql=mysqli_query($dbconid,$subcate_tbl);
if($qry_sql==true)
{
    echo "<br>Service sub-category tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$service_tbl="CREATE TABLE IF NOT EXISTS Service_tbl(
    service_ID int AUTO_INCREMENT NOT NULL,
    servicename varchar(80),
    service_description text,
    service_detail varchar(100),
    service_content varchar(100),
    service_price int,
    service_img varchar(100), 
    subCate_ID int NOT NULL,
    PRIMARY KEY (service_ID),
	FOREIGN KEY (subCate_ID) REFERENCES Service_SubCategory(subCate_ID)
)";
$qry_sql=mysqli_query($dbconid,$service_tbl);
if($qry_sql==true)
{
    echo "<br>Service tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$blog_tbl="CREATE TABLE IF NOT EXISTS Blog(
    blog_ID int AUTO_INCREMENT NOT NULL,
    blog_title varchar(100),
    blog_content text,
    blog_img varchar(100),
    blog_date date,
    service_ID int NOT NULL,
    PRIMARY KEY (blog_ID),
	FOREIGN KEY (service_ID) REFERENCES Service_tbl(service_ID)
)";
$qry_sql=mysqli_query($dbconid,$blog_tbl);
if($qry_sql==true)
{
    echo "<br>Blog tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$pMethod_tbl="CREATE TABLE IF NOT EXISTS Payment_Method(
    pm_ID int AUTO_INCREMENT NOT NULL,
    pay_name varchar(25),
    pay_number varchar(55),
    PRIMARY KEY (pm_ID)
)";
$qry_sql=mysqli_query($dbconid,$pMethod_tbl);
if($qry_sql==true)
{
    echo "<br>Payment_method tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$payment_tbl="CREATE TABLE IF NOT EXISTS Payment(
    payment_ID int AUTO_INCREMENT NOT NULL,
    amount_paid int,
    payment_status varchar(25), 
    pm_ID int NOT NULL,
    PRIMARY KEY (payment_ID),
	FOREIGN KEY (pm_ID) REFERENCES Payment_Method(pm_ID)
)";
$qry_sql=mysqli_query($dbconid,$payment_tbl);
if($qry_sql==true)
{
    echo "<br>Payment tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$booking_tbl="CREATE TABLE IF NOT EXISTS Booking_service(
    bk_ID int AUTO_INCREMENT NOT NULL,
    preferred_startDate date,
    preferred_endDate date,
    location_detail text,
    area_size varchar(100),
    bk_note text,
    bk_status varchar(50),
    service_ID int NOT NULL,
    client_ID int NOT NULL,
    payment_ID int NOT NULL,
    PRIMARY KEY (bk_ID),
	FOREIGN KEY (service_ID) REFERENCES Service_tbl(service_ID),
	FOREIGN KEY (client_ID) REFERENCES Client(client_ID),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID)
)";
$qry_sql=mysqli_query($dbconid,$booking_tbl);
if($qry_sql==true)
{
    echo "<br>Service_booking tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$pType_tbl="CREATE TABLE IF NOT EXISTS Property_type(
    pt_ID int AUTO_INCREMENT NOT NULL,
    ptype varchar(55),
    PRIMARY KEY (pt_ID)
)";
$qry_sql=mysqli_query($dbconid,$pType_tbl);
if($qry_sql==true)
{
    echo "<br>Property_type tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$property_tbl="CREATE TABLE IF NOT EXISTS Property(
    property_ID int AUTO_INCREMENT NOT NULL,
    property_name varchar(100),
    property_profile varchar(100),
    property_description text,
    property_location varchar(100),
    property_price int,
    property_area varchar(50),
    no_of_bedroom int,
    no_of_bathroom int,
    built_year int,
    land_size varchar(50),
    property_status varchar(25),
    pt_ID int NOT NULL,
    PRIMARY KEY (property_ID),
    FOREIGN KEY (pt_ID) REFERENCES Property_type(pt_ID)
)";
$qry_sql=mysqli_query($dbconid,$property_tbl);
if($qry_sql==true)
{
    echo "<br>Property tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$purchase_tbl="CREATE TABLE IF NOT EXISTS Purchase_Property(
    pp_ID int AUTO_INCREMENT NOT NULL,
    purchase_note text,
    remaining_amount int,
    purchase_status varchar(25),
    property_ID int NOT NULL,
    client_ID int NOT NULL,
    payment_ID int NOT NULL,
    PRIMARY KEY (pp_ID),
	FOREIGN KEY (property_ID) REFERENCES Property(property_ID),
	FOREIGN KEY (client_ID) REFERENCES Client(client_ID),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID)
)";
$qry_sql=mysqli_query($dbconid,$purchase_tbl);
if($qry_sql==true)
{
    echo "<br>Purchase_property tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

// $consult_tbl="CREATE TABLE IF NOT EXISTS Consultation(
//     consult_ID int AUTO_INCREMENT NOT NULL,
//     consult_title varchar(50),
//     consult_description text,
//     consult_content text,
//     consult_detail text,
//     consult_img varchar(100),
//     PRIMARY KEY (consult_ID)
// )";
// $qry_sql=mysqli_query($dbconid,$consult_tbl);
// if($qry_sql==true)
// {
//     echo "<br>Consultation tbl created successfully";
// }
// else {
//     echo mysqli_error($dbconid);
// }

// $appointment_tbl="CREATE TABLE IF NOT EXISTS Appointment(
//     ap_ID int AUTO_INCREMENT NOT NULL,
//     ap_type varchar(50),
//     ap_note text,
//     preferred_date date,
//     ap_status varchar(30),
//     client_ID int NOT NULL,
//     consult_ID int NOT NULL,
//     PRIMARY KEY (ap_ID),
//     FOREIGN KEY (client_ID) REFERENCES Client(client_ID),
//     FOREIGN KEY (consult_ID) REFERENCES Consultation(consult_ID)
// )";
// $qry_sql=mysqli_query($dbconid,$appointment_tbl);
// if($qry_sql==true)
// {
//     echo "<br>Appointment tbl created successfully";
// }
// else {
//     echo mysqli_error($dbconid);
// }

$gallery_tbl="CREATE TABLE IF NOT EXISTS Gallery(
    GID int AUTO_INCREMENT NOT NULL,
    GImage varchar(50),
    Gdescription text,
    PRIMARY KEY (GID)
)";
$qry_sql=mysqli_query($dbconid,$gallery_tbl);
if($qry_sql==true)
{
    echo "<br>Gallery tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$propertyGallery_tbl="CREATE TABLE IF NOT EXISTS Property_gallery(
    pg_ID int AUTO_INCREMENT NOT NULL,
    property_ID int NOT NULL,
    GID int NOT NULL,
    PRIMARY KEY (pg_ID),
    FOREIGN KEY (property_ID) REFERENCES Property(property_ID),
	FOREIGN KEY (GID) REFERENCES Gallery(GID)
)";
$qry_sql=mysqli_query($dbconid,$propertyGallery_tbl);
if($qry_sql==true)
{
    echo "<br>Property_gallery tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$pjType_tbl="CREATE TABLE IF NOT EXISTS Project_Type (
    pjtype_ID int AUTO_INCREMENT NOT NULL,
    pj_type varchar(20),
    PRIMARY KEY (pjtype_ID)
)";
$qry_sql=mysqli_query($dbconid,$pjType_tbl);
if($qry_sql==true)
{
    echo "<br>Project_type tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$project_tbl="CREATE TABLE IF NOT EXISTS Project(
    pj_ID int AUTO_INCREMENT NOT NULL,
    pj_title varchar(100),
    pj_description text,
    pj_detail text,
    pjtype_ID int NOT NULL,
    PRIMARY KEY (pj_ID),
    FOREIGN KEY (pjtype_ID) REFERENCES Project_Type(pjtype_ID)
)";
$qry_sql=mysqli_query($dbconid,$project_tbl);
if($qry_sql==true)
{
    echo "<br>Project tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$projGallery_tbl="CREATE TABLE IF NOT EXISTS Project_gallery (
    projg_ID int AUTO_INCREMENT NOT NULL,
    pj_ID int NOT NULL,
    GID int NOT NULL,
    PRIMARY KEY (projg_ID),
    FOREIGN KEY (pj_ID) REFERENCES Project(pj_ID),
	FOREIGN KEY (GID) REFERENCES Gallery(GID)
)";
$qry_sql=mysqli_query($dbconid,$projGallery_tbl);
if($qry_sql==true)
{
    echo "<br>Project_gallery tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$saveProject_tbl="CREATE TABLE IF NOT EXISTS Save_project(
    sp_ID int AUTO_INCREMENT NOT NULL,
    client_ID int NOT NULL,
    pj_ID int NOT NULL,
    PRIMARY KEY (sp_ID),
    FOREIGN KEY (client_ID) REFERENCES Client(client_ID),
    FOREIGN KEY (pj_ID) REFERENCES Project(pj_ID)
)";
$qry_sql=mysqli_query($dbconid,$saveProject_tbl);
if($qry_sql==true)
{
    echo "<br>Save_Project tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

$feedback_tbl="CREATE TABLE IF NOT EXISTS Feedback(
    feedback_ID int AUTO_INCREMENT NOT NULL,
    rating int,
    review text,
    client_ID int NOT NULL,
    pj_ID int NOT NULL,
    PRIMARY KEY (feedback_ID),
    FOREIGN KEY (client_ID) REFERENCES Client(client_ID),
    FOREIGN KEY (pj_ID) REFERENCES Project(pj_ID)
)";
$qry_sql=mysqli_query($dbconid,$feedback_tbl);
if($qry_sql==true)
{
    echo "<br>Feedback tbl created successfully";
}
else {
    echo mysqli_error($dbconid);
}

?>